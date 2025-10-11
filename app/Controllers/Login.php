<?php
namespace App\Controllers;
use App\Models\Employee;
use App\Models\Manager;

class Login extends BaseController 
{
	// function __construct()
	// {
	// 	parent::__construct();
	// }
	
	function index()
	{
		$Employee = new Employee();
    // $branch = session()->get('branch');
		if ($Employee->is_logged_in()/* && !empty($branch)*/)
		{			
      return redirect()->to(base_url('home'));
		}
		// echo view('login');
		echo view("v2/pages/login");
	}
	
	function login_check(){
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
			echo view('login', [
				'validation' => $validation,
			]);
		}

		$username = request()->getPost("username");	
		$password = request()->getPost("password");	
		$is_mobile = request()->getPost("is_mobile");

		$Employee = new Employee();

		
		if($Employee->login($username,$password))
		{
			session()->set('is_mobile', $is_mobile);

      $person_id = session()->get('person_id');
      $branch_list = $Employee->get_info($person_id)->branches;

      if (count($branch_list) == 0) {
          $macc = new MyAccount();
          $nearest_branch_id = $macc->getBranch();
          if ($nearest_branch_id > 0) {
            session()->set('branch', $nearest_branch_id);
            return redirect()->to(base_url('home'));
          }

      } else if (count($branch_list) == 1) {
          $my_branch = $branch_list[0];
          if ($my_branch > 0) {
            session()->set('branch', $my_branch);
            return redirect()->to(base_url('home'));
          }

      } else {
        $macc = new MyAccount();
        $nearest_branch_id = $macc->getAllocatedBranch();
        if ($nearest_branch_id > 0) {
          session()->set('branch', $nearest_branch_id);
          return redirect()->to(base_url('home'));
          // return redirect()->to(base_url('myaccount/sel_allocated_branch'));
        }
      }

      return redirect()->to(base_url('login'));
		}	
		else
		{
			$session = session();
			$session->setFlashdata('error', 'Invalid username or password.');
			
			return redirect()->to(base_url('login'));
		}	
		
    // ?
		$Manager = new Manager();
		if($Manager->login($username,$password)){
			return redirect()->to(base_url('cpanel'));
		}
	}
	
	function guest_login()
	{	
		// $Employee = new Employee();
		// if($Employee->login("guest","guest"))
		// {						
		// 	return redirect()->to(base_url('home'));
		// }
    $branch = session()->get('branch');
    if (!empty($branch)) {
      return redirect()->to(base_url('home'));
    }

    $macc = new MyAccount();
    $nearest_branch_id = $macc->getBranch();

    if ($nearest_branch_id > 0) {
      session()->set('branch', $nearest_branch_id);

      return redirect()->to(base_url('home'));
    }

    return redirect()->to(base_url('login'));
	}
	
	
	// Cron Job - Promos ///////////////////////////////////
	function cron_promos(){
		$this->load->model('admin');
		$t = date("M d, Y");
		$p = realpath(APPPATH . '../images/promotion');
		$s = realpath(APPPATH . '../images/banner');
		$d["link1"]      = $this->admin->get_plink('link newsletter');
		$d["link1_1"]    = $this->admin->get_plink('link newsletter2');
		$d["period1"]    = $this->admin->get_plink('link newsletter period');
		$d["period1_1"]  = $this->admin->get_plink('link newsletter2 period');
		$d["p1_date"]    = $this->admin->get_plink('link newsletter date');	
		$d["link2"]      = $this->admin->get_plink('link cash & carry');
		$d["link2_1"]    = $this->admin->get_plink('link cash & carry2');
		$d["period2"]    = $this->admin->get_plink('link cash & carry period');
		$d["period2_1"]  = $this->admin->get_plink('link cash & carry2 period');
		$d["p2_date"]    = $this->admin->get_plink('link cash & carry date');	
		$d["link3"]      = $this->admin->get_plink('link day-today');
		$d["link3_1"]    = $this->admin->get_plink('link day-today2');
		$d["period3"]    = $this->admin->get_plink('link day-today period');
		$d["period3_1"]  = $this->admin->get_plink('link day-today2 period');
		$d["p3_date"]    = $this->admin->get_plink('link day-today date');	
		$d["link3a"]     = $this->admin->get_plink('link day-today upcoming');
		$d["link3a_1"]   = $this->admin->get_plink('link day-today upcoming2');
		$d["period3a"]   = $this->admin->get_plink('link day-today upcoming period');
		$d["period3a_1"] = $this->admin->get_plink('link day-today upcoming2 period');
		$d["p3a_date"]   = $this->admin->get_plink('link day-today upcoming date');	
		$d["link4"]      = $this->admin->get_plink('link usave');
		$d["link4_1"]    = $this->admin->get_plink('link usave2');
		$d["period4"]    = $this->admin->get_plink('link usave period');
		$d["period4_1"]  = $this->admin->get_plink('link usave2 period');
		$d["p4_date"]    = $this->admin->get_plink('link usave date');	
		$d["link4a"]     = $this->admin->get_plink('link usave upcoming');
		$d["link4a_1"]   = $this->admin->get_plink('link usave upcoming2');
		$d["period4a"]   = $this->admin->get_plink('link usave upcoming period');
		$d["period4a_1"] = $this->admin->get_plink('link usave upcoming2 period');
		$d["p4a_date"]   = $this->admin->get_plink('link usave upcoming date');	
		$d["link5"]      = $this->admin->get_plink('link special event');
		$d["link5_1"]    = $this->admin->get_plink('link special event2');
		$d["period5"]    = $this->admin->get_plink('link special event period');
		$d["period5_1"]  = $this->admin->get_plink('link special event2 period');
		$d["p5_date"]    = $this->admin->get_plink('link special event date');
		$d["s1_date"]    = $this->admin->get_plink('s1_date');
		$d["s1_ids"]     = $this->admin->get_plink('s1_ids');
		$d["s2_date"]    = $this->admin->get_plink('s2_date');
		$d["s2_ids"]     = $this->admin->get_plink('s2_ids');
		$d["s3_date"]    = $this->admin->get_plink('s3_date');
		$d["s3_ids"]     = $this->admin->get_plink('s3_ids');
		$d["s4_date"]    = $this->admin->get_plink('s4_date');
		$d["s4_ids"]     = $this->admin->get_plink('s4_ids');
		$d["s5_date"]    = $this->admin->get_plink('s5_date');
		$d["s5_ids"]     = $this->admin->get_plink('s5_ids');
		$d["s6_date"]    = $this->admin->get_plink('s6_date');
		$d["s6_ids"]     = $this->admin->get_plink('s6_ids');
		$d["s7_date"]    = $this->admin->get_plink('s7_date');
		$d["s7_ids"]     = $this->admin->get_plink('s7_ids');
		$d["s8_date"]    = $this->admin->get_plink('s8_date');
		$d["s8_ids"]     = $this->admin->get_plink('s8_ids');
		$d["s9_date"]    = $this->admin->get_plink('s9_date');
		$d["s9_ids"]     = $this->admin->get_plink('s9_ids');
		$d["s10_date"]    = $this->admin->get_plink('s10_date');
		$d["s10_ids"]     = $this->admin->get_plink('s10_ids');
		
		
		// Promotions switch to new data 
		if($t == $d["p1_date"] && ( file_exists($p.'/1.jpg') || file_exists($p.'/1.gif') )){
			if(file_exists($p.'/1.jpg')){ unlink($p.'/1.jpg'); rename($p.'/1_1.jpg',$p.'/1.jpg'); } else{ unlink($p.'/1.gif'); rename($p.'/1_1.gif',$p.'/1.gif'); }
			$this->admin->push_plink("link newsletter",$d["link1_1"]); $this->admin->push_plink("link newsletter period",$d["period1_1"]); 
			$this->admin->push_plink("link newsletter2","");$this->admin->push_plink("link newsletter2 period",""); $this->admin->push_plink("link newsletter date","");
		}
		if($t == $d["p2_date"] && ( file_exists($p.'/2.jpg') || file_exists($p.'/2.gif') )){
			if(file_exists($p.'/2.jpg')){ unlink($p.'/2.jpg'); rename($p.'/2_1.jpg',$p.'/2.jpg'); } else{ unlink($p.'/2.gif'); rename($p.'/2_1.gif',$p.'/2.gif'); }
			$this->admin->push_plink("link cash & carry",$d["link2_1"]);  $this->admin->push_plink("link cash & carry period",$d["period2_1"]); 
			$this->admin->push_plink("link cash & carry2",""); $this->admin->push_plink("link cash & carry2 period",""); $this->admin->push_plink("link cash & carry date","");
		}
		if($t == $d["p3_date"] && ( file_exists($p.'/3.jpg') || file_exists($p.'/3.gif') )){
			if(file_exists($p.'/3.jpg')){ unlink($p.'/3.jpg'); rename($p.'/3_1.jpg',$p.'/3.jpg'); } else{ unlink($p.'/3.gif'); rename($p.'/3_1.gif',$p.'/3.gif'); }
			$this->admin->push_plink("link day-today",$d["link3_1"]); $this->admin->push_plink("link day-today period",$d["period3_1"]); 
			$this->admin->push_plink("link day-today2",""); $this->admin->push_plink("link day-today2 period",""); $this->admin->push_plink("link day-today date","");
		}
		if($t == $d["p3a_date"] && ( file_exists($p.'/3a.jpg') || file_exists($p.'/3a.gif') )){
			if(file_exists($p.'/3a.jpg')){ unlink($p.'/3a.jpg'); rename($p.'/3a_1.jpg',$p.'/3a.jpg'); }else{ unlink($p.'/3a.gif'); rename($p.'/3a_1.gif',$p.'/3a.gif'); }
			$this->admin->push_plink("link day-today upcoming",$d["link3a_1"]); $this->admin->push_plink("link day-today upcoming period",$d["period3a_1"]); 
			$this->admin->push_plink("link day-today upcoming2",""); $this->admin->push_plink("link day-today upcoming2 period",""); $this->admin->push_plink("link day-today upcoming date","");
		}
		if($t == $d["p4_date"] && ( file_exists($p.'/4.jpg') || file_exists($p.'/4.gif') )){
			if(file_exists($p.'/4.jpg')){ unlink($p.'/4.jpg'); rename($p.'/4_1.jpg',$p.'/4.jpg'); } else{ unlink($p.'/4.gif'); rename($p.'/4_1.gif',$p.'/4.gif'); }
			$this->admin->push_plink("link usave",$d["link4_1"]); $this->admin->push_plink("link usave period",$d["period4_1"]); 
			$this->admin->push_plink("link usave2",""); $this->admin->push_plink("link usave2 period",""); $this->admin->push_plink("link usave date","");
		}
		if($t == $d["p4a_date"] && ( file_exists($p.'/4a.jpg') || file_exists($p.'/4a.gif') )){
			if(file_exists($p.'/4a.jpg')){ unlink($p.'/4a.jpg'); rename($p.'/4a_1.jpg',$p.'/4a.jpg'); }else{ unlink($p.'/4a.gif'); rename($p.'/4a_1.gif',$p.'/4a.gif'); }
			$this->admin->push_plink("link usave upcoming",$d["link4a_1"]); $this->admin->push_plink("link usave upcoming period",$d["period4a_1"]); 
			$this->admin->push_plink("link usave upcoming2",""); $this->admin->push_plink("link usave upcoming2 period",""); $this->admin->push_plink("link usave upcoming date","");
		}
		if($t == $d["p5_date"] && ( file_exists($p.'/5.jpg') || file_exists($p.'/5.gif') )){
			if(file_exists($p.'/5.jpg')){ unlink($p.'/5.jpg'); rename($p.'/5_1.jpg',$p.'/5.jpg'); } else{ unlink($p.'/5.gif'); rename($p.'/5_1.gif',$p.'/5.gif'); }
			$this->admin->push_plink("link special event",$d["link5_1"]); $this->admin->push_plink("link special event period",$d["period5_1"]); 
			$this->admin->push_plink("link special event2",""); $this->admin->push_plink("link special event2 period",""); $this->admin->push_plink("link special event date","");
		}
		
		// Slider banners expiry check; delete image & reset data -- SHORTER VERSION
		for($i=1; $i<=10; $i++){
			if($t == $d["s".$i."_date"]){
				if(file_exists($s.'/'.$i.'.jpg')){ unlink($s.'/'.$i.'.jpg'); }else{ unlink($s.'/'.$i.'.gif'); }
				$this->admin->push_plink("s".$i."_date",""); 
				$this->admin->push_plink("s".$i."_ids","");
				$this->admin->push_plink("s".$i."_name","");
				$this->admin->push_plink("s".$i."_period","");
			}
		}
		
		
		// Slider banners expiry check; delete image & reset data
		/*if($t == $d["s1_date"]){
			if(file_exists($s.'/1.jpg')){ unlink($s.'/1.jpg'); }else{ unlink($s.'/1.gif'); }
			$this->admin->push_plink("s1_date",""); 
			$this->admin->push_plink("s1_ids","");
		}
		if($t == $d["s2_date"]){
			if(file_exists($s.'/2.jpg')){ unlink($s.'/2.jpg'); }else{ unlink($s.'/2.gif'); }
			$this->admin->push_plink("s2_date",""); 
			$this->admin->push_plink("s2_ids","");
		}
		if($t == $d["s3_date"]){
			if(file_exists($s.'/3.jpg')){ unlink($s.'/3.jpg'); }else{ unlink($s.'/3.gif'); }
			$this->admin->push_plink("s3_date",""); 
			$this->admin->push_plink("s3_ids","");
		}
		if($t == $d["s4_date"]){
			if(file_exists($s.'/4.jpg')){ unlink($s.'/4.jpg'); }else{ unlink($s.'/4.gif'); }
			$this->admin->push_plink("s4_date",""); 
			$this->admin->push_plink("s4_ids","");
		}
		if($t == $d["s5_date"]){
			if(file_exists($s.'/5.jpg')){ unlink($s.'/5.jpg'); }else{ unlink($s.'/5.gif'); }
			$this->admin->push_plink("s5_date",""); 
			$this->admin->push_plink("s5_ids","");
		}
		if($t == $d["s6_date"]){
			if(file_exists($s.'/6.jpg')){ unlink($s.'/6.jpg'); }else{ unlink($s.'/6.gif'); }
			$this->admin->push_plink("s6_date",""); 
			$this->admin->push_plink("s6_ids","");
		}*/
		
		echo "running cron task";
		
	}
	
	
	// Users Stats - Order count ///////////////////////////////////
	function users_stats(){
		
		$this->load->model('admin');
		$users = $this->admin->get_all_users();
		
		$DT = 0;
		$US = 0;
		$Other = 0;
		$DT_orders = 0;
		$US_orders = 0;
		$Other_orders = 0;
		$DT_data = "";
		$US_data = "";
		$Other_data = "";
		$all = count($users);
		
		foreach ($users as $user){
			
			$orders = $this->admin->get_total_orders($user->person_id);			
			$username = $user->username;
			$email = $user->email;
			
			if($orders > 0){
				if($user->price_list008 == 1 || $user->price_list010 == 1 || $user->price_list011 == 1 ){ 
					$bg = "lightgreen"; 
					$DT = $DT + 1; $DT_orders = $DT_orders + $orders;  
					$DT_data = $DT_data."<div style='padding:5px; margin:0px -20px; background:".$bg."; border-top:white 10px solid; font-size:11px;'>".$user->person_id." | ".$username." | ".$email." | Orders : ".$orders."</div>";
				} 
				else if($user->price_list012 == 1){ 
					$bg = "pink"; $US = $US + 1; $US_orders = $US_orders + $orders; 
					$US_data = $US_data."<div style='padding:5px; margin:0px -20px; background:".$bg."; border-top:white 10px solid; font-size:11px;'>".$user->person_id." | ".$username." | ".$email." | Orders : ".$orders."</div>";
				} 
				else { 
					$bg = "whitesmoke"; $Other = $Other + 1; $Other_orders = $Other_orders + $orders; 
					$Other_data = $Other_data."<div style='padding:5px; margin:0px -20px; background:".$bg."; border-top:white 10px solid; font-size:11px;'>".$user->person_id." | ".$username." | ".$email." | Orders : ".$orders."</div>";
				}
			}
		}
		
		echo "<div style='padding:20px; display:flex; text-align:center; font:18px/30px Arial, Helvetica, sans-serif;'>";
		echo "<div style='flex:1; padding:20px 20px 0px 20px; margin:10px; background:lightgreen;'><h2>DAY-TODAY</h2>Accounts: ".$DT." / ".$all."<br />Total Orders: ".$DT_orders."<br /><br />".$DT_data."</div>";
		echo "<div style='flex:1; padding:20px 20px 0px 20px; margin:10px; background:pink;'><h2>USAVE</h2>Accounts: ".$US." / ".$all."<br />Total Orders: ".$US_orders."<br /><br />".$US_data."</div>";
		echo "<div style='flex:1; padding:20px 20px 0px 20px; margin:10px; background:whitesmoke'><h2>OTHER</h2>Accounts: ".$Other." / ".$all."<br />Total Orders: ".$Other_orders."<br /><br />".$Other_data."</div>";
		echo "</div>";
	}
	
}
?>