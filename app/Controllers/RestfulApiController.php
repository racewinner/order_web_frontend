<?php

namespace App\Controllers;

use App\Models\Product;
use CodeIgniter\API\ResponseTrait;
use App\Models\Employee;
use App\Models\Order;
use App\Helpers\JwtHelper;

class RestfulApiController extends BaseController
{
    use ResponseTrait;

    /**
     * Add products to cart endpoint - validates user and adds products to cart
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function add_to_cart()
    {
        try {
            $request = request();
            $db = \Config\Database::connect();
            
            // Get username and password from request
            $username = $request->getPost('username');
            $password = $request->getPost('password');
            $branch = $request->getPost('branch');
            $organization_id = $request->getPost('organization_id');

            // Validate input
            if (empty($username) || empty($password)) {
                return $this->fail([
                    'success' => false,
                    'message' => 'Username and password are required'
                ], 400);
            }
            if (empty($branch) || empty($organization_id)) {
                return $this->fail([
                    'success' => false,
                    'message' => 'Branch and organization_id are required'
                ], 400);
            }

            // Check if user exists and credentials are valid
            $user = $db->table('epos_employees')
                ->where('username', $username)
                ->where('password', md5($password))
                ->where('deleted', 0)
                ->get()
                ->getRowArray();

            if (!$user) {
                return $this->fail([
                    'success' => false,
                    'message' => 'Invalid username or password'
                ], 401);
            }

            $person_id = $user['person_id'];

            // Get product list from request
            $prod_list = $request->getPost('prod_list');

            // Convert JSON string to array if needed
            if (is_string($prod_list)) {
                $decoded = json_decode($prod_list, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $prod_list = $decoded;
                } else {
                    return $this->fail([
                        'success' => false,
                        'message' => 'prod_list is not a valid JSON string: ' . json_last_error_msg()
                    ], 400);
                }
            }

            // Validate product list
            if (!empty($prod_list) && !is_array($prod_list)) {
                return $this->fail([
                    'success' => false,
                    'message' => 'prod_list must be an array or valid JSON string'
                ], 400);
            }

            // Get full user info for JWT token generation
            $Employee = new Employee();
            $userInfo = $Employee->get_info($person_id);

            // Prepare JWT payload
            $payload = [
                'person_id' => $user['person_id'],
                'username' => $user['username'],
                'email' => $user['email'] ?? '',
                'branch' => $branch,
                'organization_id' => $organization_id,
            ];

            $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-this-in-production';
            $token = JwtHelper::encode($payload, $secret, 3600); // 1 hour

            // If prod_list is empty, save token and return success with zero counts and JWT
            if (empty($prod_list)) {
                // Save JWT token to epos_employees table
                $db->table('epos_employees')
                    ->where('person_id', $person_id)
                    ->update(['api_access_token' => $token]);

                return $this->respond([
                    'success' => true,
                    'message' => 'No products to add to cart',
                    'jwt' => $token,
                    'data' => [
                        'total_products' => 0,
                        'valid_count' => 0,
                        'invalid_count' => 0,
                        'success_count' => 0,
                        'failed_count' => 0
                    ]
                ], 200);
            }

            // Validate each product in the list
            $validated_products = [];
            foreach ($prod_list as $index => $product) {
                if (!isset($product['prod_code']) || empty($product['prod_code'])) {
                    return $this->fail([
                        'success' => false,
                        'message' => "Product at index {$index} is missing prod_code"
                    ], 400);
                }
                if (!isset($product['quantity']) || !is_numeric($product['quantity']) || $product['quantity'] <= 0) {
                    return $this->fail([
                        'success' => false,
                        'message' => "Product at index {$index} has invalid quantity"
                    ], 400);
                }
                if (!isset($product['group_type']) || empty($product['group_type'])) {
                    return $this->fail([
                        'success' => false,
                        'message' => "Product at index {$index} is missing group_type"
                    ], 400);
                }
                if (!isset($product['branch']) || empty($product['branch'])) {
                    return $this->fail([
                        'success' => false,
                        'message' => "Product at index {$index} is missing branch"
                    ], 400);
                }
                if (!isset($product['organization_id']) || empty($product['organization_id'])) {
                    return $this->fail([
                        'success' => false,
                        'message' => "Product at index {$index} is missing organization_id"
                    ], 400);
                }

                $validated_products[] = [
                    'prod_code' => $product['prod_code'],
                    'quantity' => (int)$product['quantity'],
                    'group_type' => $product['group_type'],
                    'branch' => $product['branch'],
                    'organization_id' => $product['organization_id']
                ];
            }

            // Separate products into existed and unexisted lists
            $existed_prod_list = [];
            $unexisted_prod_list = [];
            
            $Order = new Order();
            
            foreach ($validated_products as $product) {
                // Check if product exists in epos_product table
                $isAvailable = $Order->available_product(
                    $db,
                    $product['prod_code'],
                    $product['branch'],
                    $product['organization_id']
                );

                if ($isAvailable) {
                    $existed_prod_list[] = $product;
                } else {
                    $unexisted_prod_list[] = $product;
                }
            }

            // Initialize counters
            $valid_count = count($existed_prod_list);
            $invalid_count = count($unexisted_prod_list);
            $success_count = 0;
            $failed_count = 0;

            // Add products to cart
            $db->transStart();
            $Product = new Product();
            // Add existed products to epos_cart
            foreach ($existed_prod_list as $product) {
                $result = $Product->to_cart_by_api(
                    $product['prod_code'],
                    "1",
                    $person_id,
                    $product['quantity'],
                    0,
                    $product['group_type'],
                    $product['branch'],
                    $product['organization_id'],
                );

                if ($result) {
                    $success_count++;
                } else {
                    $failed_count++;
                }
            }

            // Add unexisted products to epos_cart_suspense
            foreach ($unexisted_prod_list as $product) {
                // Check if item already exists in suspense
                $exist_in_suspense = $db->table('epos_cart_suspense')
                    ->where('prod_code', $product['prod_code'])
                    ->where('person_id', $person_id)
                    ->where('group_type', $product['group_type'])
                    ->where('branch', $product['branch'])
                    ->where('organization_id', $product['organization_id'])
                    ->get()
                    ->getNumRows() > 0;

                if ($exist_in_suspense) {
                    // Update quantity if exists
                    $db->table('epos_cart_suspense')
                        ->where('prod_code', $product['prod_code'])
                        ->where('person_id', $person_id)
                        ->where('group_type', $product['group_type'])
                        ->where('branch', $product['branch'])
                        ->where('organization_id', $product['organization_id'])
                        ->update(['quantity' => $product['quantity']]);
                } else {
                    // Get line_position for sort of product in suspense
                    $line_position = 0;
                    $query = $db->table('epos_cart_suspense')
                        ->select('line_position')
                        ->orderBy('line_position', 'desc')
                        ->get();
                    if ($query->getNumRows() > 0) {
                        $line_position = (int)$query->getResult()[0]->line_position;
                    }
                    $line_position = $line_position + 1;

                    // Insert new record
                    $suspense_data = [
                        'prod_code' => $product['prod_code'],
                        'quantity' => $product['quantity'],
                        'group_type' => $product['group_type'],
                        'line_position' => $line_position,
                        'person_id' => $person_id,
                        'branch' => $product['branch'],
                        'organization_id' => $product['organization_id']
                    ];
                    $db->table('epos_cart_suspense')->insert($suspense_data);
                }
            }
            
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return $this->fail([
                    'success' => false,
                    'message' => 'Database transaction failed'
                ], 500);
            }

            // Save JWT token to epos_employees table
            $db->table('epos_employees')
                ->where('person_id', $person_id)
                ->update(['api_access_token' => $token]);

            // Return response (JWT token already generated above)
            $response = [
                'success' => true,
                'message' => "Processed {$valid_count} valid and {$invalid_count} invalid product(s)",
                'jwt' => $token,
                'data' => [
                    'total_products' => count($validated_products),
                    'valid_count' => $valid_count,
                    'invalid_count' => $invalid_count,
                    'success_count' => $success_count,
                    'failed_count' => $failed_count,
                ]
            ];

            return $this->respond($response, 200);
            
        } catch (\Exception $e) {
            return $this->fail([
                'success' => false,
                'message' => 'Failed to add products to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function add_to_cart_by_custom_api()
    {
        $request = request();
        $db = \Config\Database::connect();

        ///////////////////////////////////////////////////////
        // Custom API for Trolley Merge; ePOS to Web Ordering - Ver 2.0
        ///////////////////////////////////////////////////////
        // Mode: "Merge"; push incoming products data to trolley  
        ///////////////////////////////////////////////////////
        // AUTHOR: YASIR
        ///////////////////////////////////////////////////////

        // RESET GLOBALS
        $result       = new \stdClass();
        $info         = "";
        $code         = "";
        $data         = "";
        $redirect     = "";
        $missing      = [];
        $abort        = false;
        $misc         = "";
        $item         = "";
        $mode         = "Merge";
        $method       = $_SERVER['REQUEST_METHOD'];
        $verification = false;

        // Connect to Ordering database
        /*
        $link = mysqli_connect("localhost", "", "", "") or die("Cannot connect to database!");
        */

        ///////////////////////////////////
        // JSON DECODE INTO $_POST
        ///////////////////////////////////
        // Decode API Incoming Json Data into $_POST
        /*
        $raw = file_get_contents('php://input');
        $_POST = json_decode($raw, true);
        */
        $_POST = $request->getJSON();
        $raw = json_encode($_POST);
        // Convert $_POST and all nested objects to arrays recursively
        $_POST = json_decode($raw, true);
        
        // Store Incoming Data into Variables
        $epos_order    = $_POST['epos_order'];
        $token         = $_POST['token'];
        $account       = $_POST['account'];
        $items         = $_POST['items'];

        ///////////////////////////////////
        // Json Data Validation
        ///////////////////////////////////

        // Convert Raw data to Lowercase
        $raw = strtolower($raw);

        // Validate Json Data - Sql Injections
        if( strpos($raw, 'select *') || strpos($raw, 'insert into') || strpos($raw, 'delete from') ){
            $abort = true;
            $misc = " - [-----Json Data-----] = Sql keys found ";
        }

        // Validate Json Data - Main
        if($epos_order == "" || $account == "" || !$items || strlen($epos_order) >= 16 || strlen($token) != 80 || strlen($account) >= 31 || count($items) >=501){
            // Allow up to 500 items
            $abort = true;
            $misc = " - [-----Json Main-----] = Invalid character length ";
        }

        // Validate Json Data - Items Array 
        foreach($items as $i){
            if($i["epos_code"]=="" || $i["prod_code"]=="" || $i["prod_desc"]=="" || $i["quantity"]==""
                || !is_numeric($i["prod_code"]) || !is_numeric($i["prod_uos"]) || !is_numeric($i["quantity"])
                || strlen($i["epos_code"]) >= 16 || strlen($i["prod_code"]) >= 16 || strlen($i["prod_desc"]) >= 200 || strlen($i["prod_pack_desc"]) >= 16 || strlen($i["prod_uos"]) >= 6
                || strlen($i["wholesale"]) >= 15 || strlen($i["retail"]) >= 15 || strlen($i["brand"]) >= 26 || strlen($i["group_desc"]) >= 51 || strlen($i["quantity"]) >= 4){
                    $abort = true; 
                    $misc = " - [-----Json Items Array-----] = Invalid character length or format";
            }
        }

        if($abort == true){
            // Invalid Json Request
            $info  = "Failure";
            $code  = "400";
            $data  = "Invalid Json Request";
        }
        else{
            // Extract User Credentials from Encoded Token - OLD Logic
            //$tok   = base64_decode($token);
            //$md5   = strtok( $tok, '+' );
            //$email = strtok( '' ); 
        }

        // REQUEST METHOD VERIFICATION
        if($method != "POST"){
            // Invalid Method
            $info = "Failure";
            $code = "405";
            $data = "[".$method."] Method Not Acceptable";
        }
        else if(isset($_POST) && $abort == false){
            
            ///////////////////////////////////
            // Logic for User Verification - START
            //$query = 'SELECT * FROM epos_employees WHERE username = "'.$account.'" && password  = "'.$md5.'" && email  = "'.$email.'" && deleted = "0"';
            $query = 'SELECT * FROM epos_employees WHERE username = "'.$account.'" && api_key  = "'.$token.'" && deleted = "0"';
            $r = $db->query($query);
            if($r->getNumRows() > 0){
                if ($row = $r->getRow()){ 
                    $row = (array) $row;
                    $row = json_decode(json_encode($row), true);
                    $person_id = $row['person_id'];
                    $md5 = $row['password'];
                    $email = $row['email'];
                    $username = $row['username'];
                    $last_kiss_branch = $row['last_kiss_branch'];
                    $organization_id = $row['organization_id'];
                    if($row['price_list001'] == 1){ $price_list[] = '01'; }
                    if($row['price_list005'] == 1){ $price_list[] = '05'; }
                    if($row['price_list007'] == 1){ $price_list[] = '07'; }
                    if($row['price_list008'] == 1){ $price_list[] = '08'; }
                    if($row['price_list009'] == 1){ $price_list[] = '09'; }
                    if($row['price_list010'] == 1){ $price_list[] = '10'; }
                    if($row['price_list011'] == 1){ $price_list[] = '11'; }
                    if($row['price_list012'] == 1){ $price_list[] = '12'; }
                    if($row['price_list999'] == 1){ $price_list[] = '999'; }
                }
                $verification = true;
                //echo implode(',', $price_list)." ========== ";
            }
            // Logic for User Verification - END
            
            ///////////////////////////////////
            // Push ePOS Order into epos_api_orders Table and get api_order_id
            $api_order_id = $this->push_api_order($person_id, $account, $epos_order);


            ///////////////////////////////////
            // Encryption Token Check Success
            if($verification == true){
            
                $info = "Success";	
                $code = "200";
                $data = "Verified And Processed";
            
                ///////////////////////////////////
                // MODE : Merge
                ///////////////////////////////////
                // Push Products Data to Trolley
                if($mode == "Merge"){
                    
                    // Dump All incoming API Data to epos_api_product and epos_api_orders
                
                    //////////////////////////////////////////
                    // Loop through Items to get values - START
                    foreach($items as $item){
                        $c = 0;
                        $misc .= " [-----".$item["prod_code"]."-----] ";
                        
                        //////////////////////////////////////////
                        // Check Products Table for Matching Codes - START
                        $query1 = 'SELECT * FROM epos_product WHERE prod_code = "'.$item["prod_code"].'" && is_disabled = "N" && price_list IN ("99999",'. implode(',', $price_list) .') Limit 1';
                        $r1 = $db->query($query1);
                        if ($row1 = $r1->getRowArray()) {  
                            
                            $c = $c + 1;
                            
                            //echo $item['prod_code']." = ";
                            
                            //////////////////////////////////////////
                            // Check Trolley to Update Existing Products - START
                            $query2 = 'SELECT * FROM epos_cart WHERE prod_code = "'.$row1['prod_code'].'" and person_id = "'.$person_id.'" and presell=0';
                            $r2 = $db->query($query2);
                            if ($row2 = $r2->getRowArray()) { 
                                $misc .=  "[UPDATE] ".$row2['prod_code']." Found QTY: ".$row2['quantity']." + ".$item['quantity']." | ";
                                // Update Cart Quantity
                                $query3 = 'UPDATE epos_cart SET quantity="'.($row2['quantity']+$item['quantity']).'" WHERE prod_code="'.$row2['prod_code'].'" && person_id="'.$person_id.'" && presell=0';
                                $db->query($query3);
                                //echo " [UPDATE] - ".$query3;
                                
                                // Push Product into epos_api_product Table - Status: Update
                                $this->push_api_product($api_order_id, $epos_order, $account, $person_id, $row1['prod_id'], $item['epos_code'], $item['prod_code'], $item['prod_desc'], $item['prod_pack_desc'], $item['wholesale'], $item['retail'], $item['prod_uos'], $item['brand'], $item['group_desc'], $item['quantity'], "Updated");
                                
                            }
                            // Check Trolley to Update Existing Products - END
                            
                            
                            //////////////////////////////////////////
                            // Add Qualified Products To Trolley - START
                            // - first: get line_position
                            // - second: get group_type
                            // - third: add product to trolley
                            $query_line_pos = 'SELECT MAX(line_position) as max_line_position FROM epos_cart WHERE person_id = "'.$person_id.'" && presell=0';
                            $r_line_pos = $db->query($query_line_pos);
                            $row_line_pos = $r_line_pos->getRowArray();
                            $line_position = ($row_line_pos['max_line_position'] !== null) ? $row_line_pos['max_line_position'] + 1 : 1;
                            
                            // Get group_type from epos_categories
                            $query_group_type = 'SELECT type FROM epos_categories WHERE filter_desc = "'.$row1['group_desc'].'" LIMIT 1';
                            $r_group_type = $db->query($query_group_type);
                            $row_group_type = $r_group_type->getRowArray();
                            $group_type = ($row_group_type && isset($row_group_type['type'])) ? $row_group_type['type'] : '';
                            
                            if($r2->getNumRows() == 0){ 
                                $misc .=  "[INSERT] ".$row1['prod_id']." Not Found - Added Item to Cart | ";
                                // Prepare Product Data for Trolley 
                                $cart_data = array( 
                                                    // 'prod_id'       =>$row1['prod_id'],
                                                    'quantity'      =>$item['quantity'],
                                                    'person_id'     =>$person_id,
                                                    'group_type'    =>$group_type,
                                                    'prod_code'     =>$row1['prod_code'],
                                                    // 'prod_uos'      =>$row1['prod_uos'],
                                                    // 'start_date'    =>$row1['start_date'],
                                                    // 'prod_desc'     =>$row1['prod_desc'],
                                                    // 'prod_pack_desc'=>$row1['prod_pack_desc'],
                                                    // 'vat_code'      =>$row1['vat_code'],
                                                    // 'prod_price'    =>$row1['prod_price'],
                                                    // 'group_desc'    =>$row1['group_desc'],
                                                    // 'prod_code1'    =>$row1['prod_code1'],
                                                    // 'price_list'    =>$row1['price_list'],
                                                    // 'prod_level1'   =>$row1['prod_level1'],
                                                    // 'prod_level2'   =>$row1['prod_level2'],
                                                    // 'prod_level3'   =>$row1['prod_level3'],
                                                    // 'prod_rrp'      =>$row1['prod_rrp'],
                                                    // 'wholesale'     =>$row1['wholesale'],
                                                    // 'retail'        =>$row1['retail'],
                                                    // 'p_size'        =>$row1['p_size'],
                                                    // 'van'           =>$row1['van'],
                                                    // 'shelf_life'    =>$row1['shelf_life'],
                                                    // 'price_start'   =>$row1['price_start'],
                                                    // 'price_end'     =>$row1['price_end'],
                                                    // 'brand'         =>$row1['brand'],
                                                    // 'epoints'       =>$row1['epoints'],
                                                    'branch'           =>$row1['branch'],
                                                    'organization_id'  =>$row1['organization_id'],
                                                    'line_position'    =>$line_position,
                                                    'api_order_id'     =>$api_order_id
                                            );
                                // Insert Product to Trolley 
                                $query4 = "INSERT INTO epos_cart (".implode(", ", array_keys($cart_data)).") VALUES ('".implode("', '", array_values($cart_data))."')";
                                $db->query($query4);
                                //echo " [INSERT] - ".$query4;
                                
                                // Push Product into epos_api_product Table - Status: Added
                                $this->push_api_product($api_order_id, $epos_order, $account, $person_id, $row1['prod_id'], $item['epos_code'], $item['prod_code'], $item['prod_desc'], $item['prod_pack_desc'], $item['wholesale'], $item['retail'], $item['prod_uos'], $item['brand'], $item['group_desc'], $item['quantity'], "Added");
                            }
                            // Add Qualified Products To Trolley - END
                        }
                        // Check Products Table for Matching Codes - END
                        
                        //////////////////////////////////////////
                        // Missing Items Logic - START
                        if($c == 0){ 
                            $missing[] = $item["epos_code"]; 
                            $misc .=  " [MISSING] ".$item["prod_code"];
                            
                            // Push Product into epos_api_product Table - Status: Missing
                            $this->push_api_product($api_order_id, $epos_order, $account, $person_id, "", $item['epos_code'], $item['prod_code'], substr($item['prod_desc'],0,40), $item['prod_pack_desc'], $item['wholesale'], $item['retail'], $item['prod_uos'], $item['brand'], $item['group_desc'], $item['quantity'], "Missing");
                        
                        }
                        // Missing Items Logic - END
                    }
                    // Loop through Items to get values - END
                    
                    //////////////////////////////////////////
                    // Update Json Response for Missing Products
                    if($missing){
                        $info = "Warning";
                        $code = "202";
                        $data = "One Or More Products Are Missing";	
                    }
                }
            }
            else{	
                // Encryption Token Check Failed
                $info  = "Verification Failed";
                $code  = "401";
                $data  = "Incorrect Token";
            }
        
        }

        //////////////////////////////////////////
        // Prepare result for json response
        
        $result->info = $info;
        $result->code = $code;
        $result->data = $data;
        if($missing){  $result->missing = implode(',', $missing); }
        if($info == "Success" || $info == "Warning"){ 
            $result->redirect = "https://order.uniteduk.co.uk/index.php/login/auto/".$account."/".$md5; 
            $result->redirect2ordering = 'https://orderingtest.uniteduk.co.uk/orders/checkout?account='.$account.'&md5='.$md5.'&api_order_id='.$api_order_id;
        }

        $response = json_encode([$result]);

        // $decode = json_decode($response, true);
        // foreach($decode as $d){
        // 	echo "Redirect: ".$d["redirect"];
        // }

        // Push Json Data into epos_api_log Table
        $api_log_id = $this->push_api_log(json_encode($_POST,JSON_HEX_APOS), $response);
        // echo $response;

        //////////////////////////////////////////
        // Final Actions
        //////////////////////////////////////////

        //////////////////////////////////////////
        // Update Response & log ID in API Orders Table
        if($api_order_id){
            $query5 = "UPDATE epos_api_orders SET response='".$response."', api_log_id='".$api_log_id."' WHERE api_order_id=".$api_order_id;
            $db->query($query5);
            //echo " [UPDATE] - ".$query5;
        }
        //////////////////////////////////////////
        // Update Misc Data in API Log Table
        if($api_log_id){
            $query6 = "UPDATE epos_api_log SET misc='".$misc."' WHERE api_log_id='".$api_log_id."'";
            $db->query($query6);
            //echo " [UPDATE] - ".$query6;
        }

        //////////////////////////////////////////
        // Prepare JWT payload
        // $payload = [
        //     'person_id' => $person_id,
        //     'username' => $username,
        //     'email' => $email,
        //     'branch' => $last_kiss_branch,
        //     'organization_id' => $organization_id,
        //     'api_order_id' => $api_order_id,
        // ];

        // $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-this-in-production';
        // $auto_access_token = JwtHelper::encode($payload, $secret, 3600); // 1 hour
        // $result->redirect = 'https://orderingtest.uniteduk.co.uk/orders/checkout?auto_access_token='.$auto_access_token;
        // Return response
        $result = json_decode(json_encode($result), true);
        return $this->respond($result, 200);
    }

    //////////////////////////////////////////
    // Custom Functions: Push Data to API Tables
    //////////////////////////////////////////

    // Push Json Data to API Log Table
    function push_api_log($request, $response){
        
        // global $link;
        $db = \Config\Database::connect();
        ///////////////////////////////////
        // Dump Json Request and Response
        $q = "INSERT into epos_api_log (request, response) VALUES(
        '".$request."', 
        '".$response."'
        )";
        $db->query($q) or die("Error Dumping Log");
        return $db->insertID();
    }

    // Push ePOS Order to API Orders Table
    function push_api_order($person_id, $account, $epos_order){
        
        // global $link;
        $db = \Config\Database::connect();
        ///////////////////////////////////
        // Dump ePOS_order
        $data = [
            'person_id' => $person_id,
            'account' => $account,
            'epos_order' => $epos_order
        ];
        
        if (!$db->table('epos_api_orders')->insert($data)) {
            die("Error Inserting API Order");
        }
        return $db->insertID();
    }

    // Push Product Data to API Product Table
    function push_api_product($api_order_id, $epos_order, $account, $person_id, $prod_id, $epos_code, $prod_code, $prod_desc, $prod_pack_desc, $wholesale, $retail, $prod_uos, $brand, $group_desc, $quantity, $status){
        
        // global $link;
        $db = \Config\Database::connect();
        ///////////////////////////////////
        // Dump product data to api_product table
        $data = [
            'api_order_id' => $api_order_id,
            'epos_order' => $epos_order,
            'account' => $account,
            'person_id' => $person_id,
            'prod_id' => $prod_id,
            'epos_code' => $epos_code,
            'prod_code' => $prod_code,
            'prod_desc' => substr($prod_desc, 0, 40),
            'prod_pack_desc' => $prod_pack_desc,
            'wholesale' => $wholesale,
            'retail' => $retail,
            'prod_uos' => $prod_uos,
            'brand' => $brand,
            'group_desc' => $group_desc,
            'quantity' => $quantity,
            'status' => $status
        ];
        
        if (!$db->table('epos_api_products')->insert($data)) {
            die("Error Inserting API Product");
        }
    }
}

