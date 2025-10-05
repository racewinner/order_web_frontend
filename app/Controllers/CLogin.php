<?php

namespace App\Controllers;

use App\Models\Manager;

class CLogin extends BaseController 
{
	// function __construct()
	// {
	// 	parent::__construct();
	// }
	
	function index()
	{
		$Manager = new Manager();
		if($Manager->is_logged_in()) // Manager is model
		{
			return redirect()->to(base_url('cpanel'));
		}
		echo view('clogin');
	}
	
	function login_check()
	{
		$Manager = new Manager();
		$rules = [
			'username' => [
				'label' => lang('login_undername'),
				'rules' => 'required',
			],
		];

		$validation = \Config\Services::validation();

		$validation->setRules($rules);
			
		if(!$validation->withRequest($this->request)->run())
		{
			echo view('clogin', [
				'validation' => $validation,
			]);
		}

		$username = request()->getPost("username");	
		$password = request()->getPost("password");

		if($Manager->login($username,$password))
		{
			return redirect()->to(base_url('cpanel'));
		}
		else
		{
			$session = session();
			$session->setFlashdata('error', 'Invalid username or password.');
			
			return redirect()->to(base_url('clogin'));
		}	

				
	}
}
