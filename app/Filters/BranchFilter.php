<?php
namespace App\Filters;

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
                // Get api_access_token from URL parameters
                $api_access_token = $request->getGet('api_access_token');
                if (empty($api_access_token)) {
                    return redirect()->to(base_url('login'));
                }

                // Decode JWT token
                $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-this-in-production';
                $payload = JwtHelper::decode($api_access_token, $secret);

                if ($payload === false) {
                    return redirect()->to(base_url('login'));
                }

                // Check token expiration from payload
                $now = time();
                $expired_datetime = null;
                
                if (isset($payload['exp'])) {
                    $expired_datetime = $payload['exp'];
                }

                // Check if token is expired
                if ($expired_datetime && $now >= $expired_datetime) {
                    return redirect()->to(base_url('login'));
                }

                // Extract person_id and organization_id from token
                $person_id = $payload['person_id'] ?? null;
                $organization_id = $payload['organization_id'] ?? null;

                if (empty($person_id)) {
                    return redirect()->to(base_url('login'));
                }

                // Get branch from employee info (branch might not be in token, so get from employee record)
                $Employee = new Employee();
                $userInfo = $Employee->get_info($person_id);
                
                // Get branch - try from token first, then from user info
                $branch = null;
                if (isset($payload['branch'])) {
                    $branch = $payload['branch'];
                } 
                // elseif (isset($userInfo->branches) && is_array($userInfo->branches) && !empty($userInfo->branches)) {
                //     // Use first branch if multiple branches available
                //     $branch = $userInfo->branches[0];
                // } 
                // elseif (isset($userInfo->last_kiss_branch)) {
                //     $branch = $userInfo->last_kiss_branch;
                // }

                if (empty($branch)) {
                    return redirect()->to(base_url('login'));
                }

                // Set session values
                session()->set('person_id', $person_id);
                session()->set('branch', $branch);
                if (!empty($organization_id)) {
                    session()->set('organization_id', $organization_id);
                }

                // Set expired_datetime (1 hour from now, matching token expiration)
                $now = new DateTime();
                $interval = new DateInterval('PT1H');
                $now->add($interval);
                session()->set('expired_datetime', $now);
                
                // return redirect()->to(base_url('myaccount/sel_branch'));
                // return redirect()->to(base_url('login'));
                // Token validated and session set, continue with request
            } else {
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
