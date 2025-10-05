<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class BranchFilter implements FilterInterface {
    public function __construct() {

    }

    public function before(RequestInterface $request, $arguments = null) {
        $branch = session()->get('branch');
        if(empty($branch)) {
            return redirect()->to(base_url('myaccount/sel_branch'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    { 

    }
}
