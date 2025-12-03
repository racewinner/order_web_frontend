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
}

