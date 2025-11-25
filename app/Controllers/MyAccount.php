<?php
namespace App\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use CodeIgniter\Email\Email;
use App\Services\MyAccountService;
use App\Services\GeoLocationService;

class MyAccount extends Secure_area /* implements iData_controller*/
{
	function __construct()
	{
		parent::__construct('myaccount');
	}

    function credit_account($mode = 'view') {
        $Employee = new Employee();
		$Admin = new Admin();
		$Branch = new Branch();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		$this->data['mode'] = $mode;

		// To get controller name
        $this->data['controller_name'] = $this->request->getUri()->getSegment(1);
		
		// To get logged_in_user
		$user_info = $Employee->get_logged_in_employee_info();
		$this->data['user_info'] = $user_info;

		// To get branches from db.
		$branches = $Branch->get_all_branches();
		$this->data['branches'] = $branches;

		// To get credit account information
		$response = MyAccountService::get_credit_account($user_info->username);
		if(isset($response['error'])) $this->data['error'] = $response['error']; 
		if(isset($response['data'])) {
			$this->data['credit_account'] = $response['data'];
		} else {
			return redirect()->back();
		}

		// To get the last payment
		$lp = MyAccountService::get_last_payment($user_info->username);
		if(isset($lp['error'])) $this->data['error'] = $lp['error'];
		if(isset($lp['data'])) {
			foreach($branches as $branch) {
				if($branch->id == $lp['data']['payment']['branch']) {
					$lp['data']['payment']['branch_name'] = $branch->name;
					break;
				}
			}
			$this->data['last_payment'] = $lp['data'];
		}

        // return view("myaccount/credit_account", $this->data);
		return view("v2/pages/myaccount/credit_account", $this->data);
    }

	function send_payment() 
	{
		$Employee = new Employee();
		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}
		
		$user_info = $Employee->get_logged_in_employee_info();

		$branch = request()->getPost('payment_branch');
		$amount = request()->getPost('pay_amount');

		$response = MyAccountService::send_payment($user_info->username, $branch, $amount);
		if(isset($response['error'])) {
			return response()->setJSON([
				'success' => false,
				'error' => $response['error']
			]);
		} else {
			return response()->setJSON([
				'success' => true,
				'data' => $response['data']
			]);
		}
	}

	function invoice_history() 
	{
		$Employee = new Employee();
		$Branch = new Branch();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();

		if(request()->isAJAX()) {
			$branches = $Branch->get_all_branches();
			$response = MyAccountService::get_invoice_history($user_info->username);
			if(isset($response['data']) && isset($response['data']['transaction_headers'])) {
				for($i=0; $i<count($response['data']['transaction_headers']); $i++) {
					$invoice = &$response['data']['transaction_headers'][$i];
					foreach($branches as $branch) {
						if($invoice['branch'] == $branch['id']) {
							$invoice['branch_name'] = $branch['site_name'];
							break;
						}
					}
				}
			}
			return response()->setJSON($response);
		}

		// return view("myaccount/invoice_history", $this->data);
		return view("v2/pages/myaccount/invoice_history", $this->data);
	}

	function invoice_detail()
	{
		$Employee = new Employee();
		$Branch = new Branch();
		$Product = new Product();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();

		// To get invoice detail
		$tn = request()->getGet('tn');
		$branch = request()->getGet('branch');
		$dt = request()->getGet('dt');
		$this->data['invoice_header'] = [
			'tn' => $tn,
			'branch' => $branch,
			'dt' => (new \DateTime($dt))->format('d/m/y h:i')
		];

		$response = MyAccountService::get_invoice_detail($user_info->username, $tn, $branch, $dt);
		if(isset($response['data']) && $response['data']['status'] == 'success') {
			$transaction_details = $response['data']['transaction_details'];
			for($i=0; $i<count($transaction_details); $i++) {
				$t = &$transaction_details[$i];
				$product = $Product->getLowestPriceProductByCode($user_info, $t['item']);
				if(isset($product)) $t['product'] = $product;
			}
			$this->data['transaction_details'] = $transaction_details;
		}
		
		// return view("myaccount/invoice_detail", $this->data);
		return view("v2/pages/myaccount/invoice_detail", $this->data);
	}

	function loyalty() {
		$Employee = new Employee();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();		

		$response = MyAccountService::get_loyalty($user_info->username);
		return response()->setJSON($response);
	}

	function order_history() {
		$Employee = new Employee();
		$Branch = new Branch();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();

		if(request()->isAJAX()) {
			$branches = $Branch->get_all_branches();
			$response = MyAccountService::get_order_history($user_info->username);
			if(isset($response['data']) && isset($response['data']['order_headers'])) {
				for($i=0; $i<count($response['data']['order_headers']); $i++) {
					$invoice = $response['data']['order_headers'][$i];
					foreach($branches as $branch) {
						if($invoice['branch'] == $branch['id']) {
							$response['data']['order_headers'][$i]['branch_name'] = $branch['site_name'];
							break;
						}
					}
				}
			}
			return response()->setJSON($response);
		}

		return view("v2/pages/myaccount/order_history", $this->data);
		// return view("myaccount/order_history", $this->data);
	}

	function order_detail()
	{
		$Employee = new Employee();
		$Branch = new Branch();
		$Product = new Product();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();

		// To get order detail
		$on = request()->getGet('on');
		$branch = request()->getGet('branch');
		$dt = request()->getGet('dt');
		$this->data['order_header'] = [
			'on' => $on,
			'branch' => $branch,
			'dt' => (new \DateTime($dt))->format('d/m/y h:i')
		];

		$response = MyAccountService::get_order_detail($user_info->username, $on, $branch, $dt);
		if(isset($response['data']) && $response['data']['status'] == 'success') {
			$order_details = $response['data']['order_details'];
			for($i=0; $i<count($order_details); $i++) {
				$o = &$order_details[$i];
				$product = $Product->getLowestPriceProductByCode($user_info, $o['item']);
				if(isset($product)) $o['product'] = $product;
			}
			$this->data['order_details'] = $order_details;
		}
		
		return view("v2/pages/myaccount/order_detail", $this->data);
	}

	function ledger() 
	{
		$Employee = new Employee();
		$Branch = new Branch();
		$Product = new Product();

		if(!$Employee->is_logged_in()) return redirect()->to('/');
		$user_info = $Employee->get_logged_in_employee_info();

		if(request()->isAJAX()) {
			$response = MyAccountService::get_ledger_detail($user_info->username);
			return response()->setJSON($response);
		}

		return view("v2/pages/myaccount/ledger", $this->data);
	}

  public function getBranch() {
    $nearest_branch_id = 0;
		$client_ip = request()->getIPAddress();
		$client_ip = "8.8.8.8";
		$client_location = GeoLocationService::getLocationFromIp($client_ip);
		if(!empty($client_location)) {
			$min_distance = 0;
			foreach($this->data['all_branches'] as $branch) {
				$lat_diff = abs($branch['geo_latitude'] - $client_location['latitude']);
				$lon_diff = abs($branch['geo_longitude'] - $client_location['longitude']);
				$l = sqrt($lat_diff * $lat_diff + $lon_diff * $lon_diff);
				if($min_distance == 0 || $min_distance > $l) {
					$min_distance = $l;
					$nearest_branch_id = $branch['id'];
				}
			}
		}
    return $nearest_branch_id;
  }

  public function getAllocatedBranch() {
    $Branch = new Branch();
    $allocated_branches = $Branch->get_allocated_branches();

    $nearest_branch_id = 0;
		$client_ip = request()->getIPAddress();
		$client_ip = "8.8.8.8";
		$client_location = GeoLocationService::getLocationFromIp($client_ip);
		if(!empty($client_location)) {
			$min_distance = 0;
			foreach($allocated_branches as $branch) {
				$lat_diff = abs(floatval($branch->geo_latitude) - floatval($client_location['latitude']));
				$lon_diff = abs(floatval($branch->geo_longitude) - floatval($client_location['longitude']));
				$l = sqrt($lat_diff * $lat_diff + $lon_diff * $lon_diff);
				if($min_distance == 0 || $min_distance > $l) {
					$min_distance = $l;
					$nearest_branch_id = $branch->id;
				}
			}
		}
    return $nearest_branch_id;
  }

	public function getSelectBranch() {
		$nearest_branch_id = 0;

    $branch = session()->get('branch');
    if (empty($branch)) {
        $client_ip = request()->getIPAddress();
        $client_ip = "8.8.8.8";
        $client_location = GeoLocationService::getLocationFromIp($client_ip);
        if(!empty($client_location)) {
            $min_distance = 0;
            foreach($this->data['all_branches'] as $branch) {
                $lat_diff = abs($branch['geo_latitude'] - $client_location['latitude']);
                $lon_diff = abs($branch['geo_longitude'] - $client_location['longitude']);
                $l = sqrt($lat_diff * $lat_diff + $lon_diff * $lon_diff);
                if($min_distance == 0 || $min_distance > $l) {
                    $min_distance = $l;
                    $nearest_branch_id = $branch['id'];
                }
            }
        }
        $this->data['nearest_branch_id'] = $nearest_branch_id;
    } else {
        $this->data['nearest_branch_id'] = $branch;
    }

		echo view('v2/pages/myaccount/sel_branch', $this->data);
	}

  public function getAllocatedSelectBranch() {
    $Branch = new Branch();
    $allocated_branches = $Branch->get_allocated_branches();
    $this->data['allocated_branches'] = $allocated_branches;// here, allocated_branches has >=2 branches

		$nearest_branch_id = 0;

    $branch = session()->get('branch');
    if (empty($branch)) {
        $client_ip = request()->getIPAddress();
        $client_ip = "8.8.8.8";
        $client_location = GeoLocationService::getLocationFromIp($client_ip);
        if(!empty($client_location)) {
            $min_distance = 0;
            foreach($allocated_branches as $branch) {
                $lat_diff = abs(floatval($branch->geo_latitude) - floatval($client_location['latitude']));
                $lon_diff = abs(floatval($branch->geo_longitude) - floatval($client_location['longitude']));
                $l = sqrt($lat_diff * $lat_diff + $lon_diff * $lon_diff);
                if($min_distance == 0 || $min_distance > $l) {
                    $min_distance = $l;
                    $nearest_branch_id = $branch->id;
                }
            }
        }
        $this->data['nearest_branch_id'] = $nearest_branch_id;
    } else {
        $this->data['nearest_branch_id'] = $branch;
    }

		echo view('v2/pages/myaccount/sel_allocated_branch', $this->data);
	}

	public function postSelectBranch() {
		$branch = request()->getPost('branch');
		session()->set('branch', $branch);

    $person_id = session()->get('person_id');
    if (!empty($person_id)) {
      $this->updtLastKissBranch($person_id, $branch);
    }
		return redirect()->to(base_url('home'));
	}

	public function postMyBranches() {
		$Employee = new Employee();

		try {
			if($Employee->is_logged_in()) {			
				$branches = request()->getVar('branches');
				$logon_personId = session()->get('person_id');
				$Employee->set('branches', $branches)->where('person_id', $logon_personId)->update();
	
				return response()->setJSON([
					'message' => 'Branch was updated successfully'
				]);
			}
		} catch(\Exception $e) {
			return response()->setJSON([
				'message' => $e->getMessage()
			])->setStatusCode(400);
		}
	}

  public function getLastKissBranch($person_id)
  {
    $Employee = new Employee();
    return $Employee->get_last_kiss_branch_id($person_id);
  }

  public function updtLastKissBranch($person_id, $last_kiss_branch_id)
  {
    $Employee = new Employee();
    return $Employee->save_last_kiss_branch_id($person_id, $last_kiss_branch_id);
  }
}