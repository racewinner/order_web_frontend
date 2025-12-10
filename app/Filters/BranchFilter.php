<?php
namespace App\Filters;

use App\Controllers\MyAccount;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DateInterval;
use DateTime;
use App\Helpers\JwtHelper;
use App\Models\Employee;

class BranchFilter implements FilterInterface {
    public function __construct() {

    }

    public function before(RequestInterface $request, $arguments = null) {

        if (!$request->isAJAX()) {

            $branch = session()->get('branch');
            if (empty($branch)) {
                // Get URL parameters: account, md5, api_order_id
                $account = $request->getGet('account');
                $md5 = $request->getGet('md5');
                $api_order_id = $request->getGet('api_order_id');
                
                // Check if account or md5 is empty
                if (empty($account) || empty($md5)) {
                    return redirect()->to(base_url('login'));
                }
                
                // Get user from epos_employee table matching username = account and password = md5
                $employeeModel = new Employee();
                $user = $employeeModel->where([
                    'username' => $account,
                    'password' => $md5,
                    'deleted' => 0
                ])->first();
                
                // If user doesn't exist, redirect to login
                if (!$user) {
                    return redirect()->to(base_url('login'));
                }
                
                // set session data
                $person_id = $user['person_id'];
                session()->set('person_id', $person_id);
                $organization_id = $user['organization_id'] ?? null;
                session()->set('organization_id', $organization_id);
                
                $branch = "";
                $branch_list = $employeeModel->get_info($person_id)->branches;
                if (count($branch_list) == 0) {
                    $macc = new MyAccount();
                    $nearest_branch_id = $macc->getBranch();
                    if ($nearest_branch_id > 0) {
                        $branch = $nearest_branch_id;
                    }
                } else if (count($branch_list) == 1) {
                    $my_branch = $branch_list[0];
                    if ($my_branch > 0) {
                        $branch = $my_branch;
                    }
                } else {
                    $macc = new MyAccount();
                    $nearest_branch_id = $macc->getAllocatedBranch();

                    $last_kiss_branch_id = $macc->getLastKissBranch($person_id);
                    if (!empty($last_kiss_branch_id)) {
                        $nearest_branch_id = $last_kiss_branch_id;
                    }

                    if ($nearest_branch_id > 0) {
                        $branch = $nearest_branch_id;
                    }
                }
                session()->set('branch', $branch);
                
                // Set expired_datetime for session management
                $now = new DateTime();
                $interval = new DateInterval('PT1H');
                $now->add($interval);
                session()->set('expired_datetime', $now);
                
                // Set api_order_id to session if not empty
                if (!empty($api_order_id)) {
                    session()->set('api_order_id', $api_order_id);
                }
                
                // Continue with the request (don't redirect)
                // return null;
            } else {
                // Get URL parameters: account, md5, api_order_id
                $account = $request->getGet('account');
                $md5 = $request->getGet('md5');
                $api_order_id = $request->getGet('api_order_id');
                
                // Check if account or md5 is empty
                if (!empty($account) && !empty($md5)) {
                    // Get user from epos_employee table matching username = account and password = md5
                    $employeeModel = new Employee();
                    $user = $employeeModel->where([
                        'username' => $account,
                        'password' => $md5,
                        'deleted' => 0
                    ])->first();
                    
                    // If user doesn't exist, redirect to login
                    if ($user) {
                        // set session data
                        $person_id = $user['person_id'];
                        
                        // Compare $person_id with session's person_id
                        $session_person_id = session()->get('person_id');
                        if ($person_id == $session_person_id && !empty($api_order_id)) {
                            session()->set('api_order_id', $api_order_id);
                        }
                    }
                }

                $now = new DateTime();
                $expired_datetime = session()->get('expired_datetime');

                if ($request->getPath() == "home/guest" || $now < $expired_datetime) {
                    $interval = new DateInterval('PT1H'); // 5 minutes
                    $now->add($interval);

                    session()->set('expired_datetime', $now);
                } else {
                    session()->destroy();
                    return redirect()->to(base_url('login'));
                }
            }
        } else {
			$person_id = session()->get('person_id');
            if ($person_id) {
                $now = new DateTime();
                $expired_datetime = session()->get('expired_datetime');

                if ($now < $expired_datetime) {
                    $interval = new DateInterval('PT1H'); // 5 minutes
                    $now->add($interval);

                    session()->set('expired_datetime', $now);
                } else {
                    session()->destroy();

                    return \Config\Services::response()
                        ->setStatusCode(401)
                        ->setContentType('application/json')
                        ->setJSON([
                            'error' => 'unauthenticated',
                            'message' => 'Login required.'
                    ]);
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    { 

    }
}
