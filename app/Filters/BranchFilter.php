<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DateInterval;
use DateTime;

class BranchFilter implements FilterInterface {
    public function __construct() {

    }

    public function before(RequestInterface $request, $arguments = null) {

        if (!$request->isAJAX()) {

            $branch = session()->get('branch');
            if (empty($branch)) {
                // return redirect()->to(base_url('myaccount/sel_branch'));
                // return redirect()->to(base_url('login'));
                return redirect()->to(base_url('login/guest_login'));
            } else {
                $now = new DateTime();
                $expired_datetime = session()->get('expired_datetime');

                if (empty($expired_datetime) || $now < $expired_datetime) {
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

                    \Config\Services::response()
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
