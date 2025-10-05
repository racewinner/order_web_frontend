<?php
namespace App\Controllers;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Manager;
// require_once ("admin_area.php");
class Cpanel extends Admin_area /* implements iData_controller*/
{
	// function __construct(){
	// 	parent::__construct('cpanel');	
	// }
	
	function index(){
		$Admin = new Admin();
		$Product = new Product();
		$Manager = new Manager();
		

		if(!$Manager->is_logged_in())
		{
			return redirect()->to(base_url('clogin'));
		}
		
		$codes             = $Admin->get_featured_codes('new');		
		$this->data["prod_id"]   = $Admin->get_featured($codes,'new');
		$this->data["link1"]     = $Admin->get_plink('link newsletter');	
		$this->data["link1_1"]   = $Admin->get_plink('link newsletter2');	
		$this->data["period1"]   = $Admin->get_plink('link newsletter period');	
		$this->data["period1_1"] = $Admin->get_plink('link newsletter2 period');	
		$this->data["p1_date"]   = $Admin->get_plink('link newsletter date');			
		$this->data["link2"]     = $Admin->get_plink('link cash & carry');
		$this->data["link2_1"]   = $Admin->get_plink('link cash & carry2');		
		$this->data["period2"]   = $Admin->get_plink('link cash & carry period');
		$this->data["period2_1"] = $Admin->get_plink('link cash & carry2 period');
		$this->data["p2_date"]   = $Admin->get_plink('link cash & carry date');			
		$this->data["link3"]     = $Admin->get_plink('link day-today');	
		$this->data["link3_1"]   = $Admin->get_plink('link day-today2');		
		$this->data["period3"]   = $Admin->get_plink('link day-today period');	
		$this->data["period3_1"] = $Admin->get_plink('link day-today2 period');
		$this->data["p3_date"]   = $Admin->get_plink('link day-today date');	
		$this->data["link3a"]    = $Admin->get_plink('link day-today upcoming');	
		$this->data["link3a_1"]  = $Admin->get_plink('link day-today upcoming2');
		$this->data["period3a"]  = $Admin->get_plink('link day-today upcoming period');	
		$this->data["period3a_1"]= $Admin->get_plink('link day-today upcoming2 period');
		$this->data["p3a_date"]  = $Admin->get_plink('link day-today upcoming date');			
		$this->data["link4"]     = $Admin->get_plink('link usave');	
		$this->data["link4_1"]   = $Admin->get_plink('link usave2');		
		$this->data["period4"]   = $Admin->get_plink('link usave period');	
		$this->data["period4_1"] = $Admin->get_plink('link usave2 period');
		$this->data["p4_date"]   = $Admin->get_plink('link usave date');	
		$this->data["link4a"]    = $Admin->get_plink('link usave upcoming');
		$this->data["link4a_1"]  = $Admin->get_plink('link usave upcoming2');		
		$this->data["period4a"]  = $Admin->get_plink('link usave upcoming period');
		$this->data["period4a_1"]= $Admin->get_plink('link usave upcoming2 period');		
		$this->data["p4a_date"]  = $Admin->get_plink('link usave upcoming date');		
		$this->data["link5"]     = $Admin->get_plink('link special event');		
		$this->data["link5_1"]   = $Admin->get_plink('link special event2');	
		$this->data["period5"]   = $Admin->get_plink('link special event period');		
		$this->data["period5_1"] = $Admin->get_plink('link special event2 period');	
		$this->data["p5_date"]   = $Admin->get_plink('link special event date');			
		$this->data["switch"]    = $Admin->get_plink('state special event');		
		$this->data["switch2"]   = $Admin->get_plink('state newsletter');	
		$this->data["s1_period"] = $Admin->get_plink('s1_period');		
		$this->data["s2_period"] = $Admin->get_plink('s2_period');		
		$this->data["s3_period"] = $Admin->get_plink('s3_period');		
		$this->data["s4_period"] = $Admin->get_plink('s4_period');		
		$this->data["s5_period"] = $Admin->get_plink('s5_period');		
		$this->data["s6_period"] = $Admin->get_plink('s6_period');	
		$this->data["s7_period"] = $Admin->get_plink('s7_period');	
		$this->data["s8_period"] = $Admin->get_plink('s8_period');	
		$this->data["s9_period"] = $Admin->get_plink('s9_period');	
		$this->data["s10_period"]= $Admin->get_plink('s10_period');	
		$this->data["s1_name"]   = $Admin->get_plink('s1_name');		
		$this->data["s2_name"]   = $Admin->get_plink('s2_name');		
		$this->data["s3_name"]   = $Admin->get_plink('s3_name');		
		$this->data["s4_name"]   = $Admin->get_plink('s4_name');		
		$this->data["s5_name"]   = $Admin->get_plink('s5_name');		
		$this->data["s6_name"]   = $Admin->get_plink('s6_name');	
		$this->data["s7_name"]   = $Admin->get_plink('s7_name');	
		$this->data["s8_name"]   = $Admin->get_plink('s8_name');	
		$this->data["s9_name"]   = $Admin->get_plink('s9_name');	
		$this->data["s10_name"]  = $Admin->get_plink('s10_name');	
		$this->data["s1_ids"]    = $Admin->get_plink('s1_ids');		
		$this->data["s2_ids"]    = $Admin->get_plink('s2_ids');		
		$this->data["s3_ids"]    = $Admin->get_plink('s3_ids');		
		$this->data["s4_ids"]    = $Admin->get_plink('s4_ids');		
		$this->data["s5_ids"]    = $Admin->get_plink('s5_ids');		
		$this->data["s6_ids"]    = $Admin->get_plink('s6_ids');		
		$this->data["s7_ids"]    = $Admin->get_plink('s7_ids');		
		$this->data["s8_ids"]    = $Admin->get_plink('s8_ids');		
		$this->data["s9_ids"]    = $Admin->get_plink('s9_ids');		
		$this->data["s10_ids"]   = $Admin->get_plink('s10_ids');	
		$this->data["s1_date"]   = $Admin->get_plink('s1_date');		
		$this->data["s2_date"]   = $Admin->get_plink('s2_date');		
		$this->data["s3_date"]   = $Admin->get_plink('s3_date');		
		$this->data["s4_date"]   = $Admin->get_plink('s4_date');		
		$this->data["s5_date"]   = $Admin->get_plink('s5_date');		
		$this->data["s6_date"]   = $Admin->get_plink('s6_date');		
		$this->data["s7_date"]   = $Admin->get_plink('s7_date');		
		$this->data["s8_date"]   = $Admin->get_plink('s8_date');		
		$this->data["s9_date"]   = $Admin->get_plink('s9_date');		
		$this->data["s10_date"]  = $Admin->get_plink('s10_date');
		$this->data["sp1_period"] = $Admin->get_plink('sp1_period');		
		$this->data["sp2_period"] = $Admin->get_plink('sp2_period');		
		$this->data["sp3_period"] = $Admin->get_plink('sp3_period');		
		$this->data["sp4_period"] = $Admin->get_plink('sp4_period');		
		$this->data["sp5_period"] = $Admin->get_plink('sp5_period');		
		$this->data["sp6_period"] = $Admin->get_plink('sp6_period');	
		$this->data["sp1_name"]   = $Admin->get_plink('sp1_name');		
		$this->data["sp2_name"]   = $Admin->get_plink('sp2_name');		
		$this->data["sp3_name"]   = $Admin->get_plink('sp3_name');		
		$this->data["sp4_name"]   = $Admin->get_plink('sp4_name');		
		$this->data["sp5_name"]   = $Admin->get_plink('sp5_name');		
		$this->data["sp6_name"]   = $Admin->get_plink('sp6_name');		
		$this->data["sp1_ids"]    = $Admin->get_plink('sp1_ids');		
		$this->data["sp2_ids"]    = $Admin->get_plink('sp2_ids');		
		$this->data["sp3_ids"]    = $Admin->get_plink('sp3_ids');		
		$this->data["sp4_ids"]    = $Admin->get_plink('sp4_ids');		
		$this->data["sp5_ids"]    = $Admin->get_plink('sp5_ids');		
		$this->data["sp6_ids"]    = $Admin->get_plink('sp6_ids');
		$this->data["c34_ids"]    = $Admin->get_plink('c34_ids');	
		$this->data["c13_ids"]    = $Admin->get_plink('c13_ids');	
		$this->data["c30_ids"]    = $Admin->get_plink('c30_ids');	
		$this->data["c1_ids"]     = $Admin->get_plink('c1_ids');	
		$this->data["c20_ids"]    = $Admin->get_plink('c20_ids');			
		$this->data["c31_ids"]    = $Admin->get_plink('c31_ids');	
		$this->data["c5_ids"]     = $Admin->get_plink('c5_ids');	
		$this->data["c3_ids"]     = $Admin->get_plink('c3_ids');	
		$this->data["c2_ids"]     = $Admin->get_plink('c2_ids');	
		$this->data["c24_ids"]    = $Admin->get_plink('c24_ids');	
		$this->data["c33_ids"]    = $Admin->get_plink('c33_ids');	
		$this->data["c15_ids"]    = $Admin->get_plink('c15_ids');	
		$this->data["c7_ids"]     = $Admin->get_plink('c7_ids');	
		$this->data["c8_ids"]     = $Admin->get_plink('c8_ids');		
		$this->data["c22_ids"]    = $Admin->get_plink('c22_ids');		
		$this->data["c23_ids"]    = $Admin->get_plink('c23_ids');		
		$this->data["c27_ids"]    = $Admin->get_plink('c27_ids');		
		$this->data["c26_ids"]    = $Admin->get_plink('c26_ids');		
		$this->data["c21_ids"]    = $Admin->get_plink('c21_ids');		
		$this->data["c6_ids"]     = $Admin->get_plink('c6_ids');		
		$this->data["c10_ids"]    = $Admin->get_plink('c10_ids');		
		$this->data["c28_ids"]    = $Admin->get_plink('c28_ids');		
		$this->data["c4_ids"]     = $Admin->get_plink('c4_ids');		
		$this->data["c35_ids"]    = $Admin->get_plink('c35_ids');		
		$this->data["c14_ids"]    = $Admin->get_plink('c14_ids');		
		$this->data["c32_ids"]    = $Admin->get_plink('c32_ids');					
		$this->data["slides"]     = $Admin->get_scount('slides');	
		$this->data["sponsors"]   = $Admin->get_scount('sponsors');	
	    //$this->data['group']      = $this->Product->fetch_allsubcategories();	
		$this->data['group']      = $Product->fetch_category(0);
		
		// Fetch Image Host
		$this->data['img_host']  = $Admin->get_plink('img_host');
		
		//
		$this->data['period']    = $Admin->get_current_period();
		// print_r($this->data['period']);
		// exit;
		$this->data['year']      = date("Y"); 
		$this->data['tabulator'] = $Admin->get_tracking($this->data['period']);			
		//
		
		$this->data['controller_name']	= "cpanel";
		echo view('cpanel/index', $this->data); // after you stored the query results inside the $data array, send the array to the view 
	}
		
    function refresh_products(){  // show products based on type clicked
        $Admin = new Admin();
		$type = $this->input->post('type');
		
		// Fetch Image Host
		$data['img_host']  = $Admin->get_plink('img_host');
		
		$codes = $Admin->get_featured_codes($type);
		$data["prod_id"]=$Admin->get_featured($codes, $type);
        
		$this->load->view('cpanel/cpanel_products', $data);
    }
		
    function refresh_all_products(){  // show products based on type clicked
        $Admin = new Admin();
		
		$type    = 'new';
		$codes   = $Admin->get_featured_codes($type);
		$type1   = 'sale';
		$codes1  = $Admin->get_featured_codes($type1);
		//$type2   = 'top';
		//$codes2  = $Admin->get_featured_codes($type2);
		//$type3   = 'daytoday';
		//$codes3  = $Admin->get_featured_codes($type3);
		
		// Fetch Image Host
		$d['img_host']  = $Admin->get_plink('img_host');
		
		$data["prod_id"] = $Admin->get_featured($codes, $type);
		$data1["prod_id"] = $Admin->get_featured($codes1, $type1);
		//$data2["prod_id"] = $Admin->get_featured($codes2, $type2);
		//$data3["prod_id"] = $Admin->get_featured($codes3, $type3);
		//$d["prod_id"] = array_merge($data["prod_id"],$data1["prod_id"],$data2["prod_id"],$data3["prod_id"]);
		$d["prod_id"] = $data["prod_id"];
		$d["prod_id1"] = $data1["prod_id"];
		
        echo view('cpanel/cpanel_products', $d);
    }
		
    function update_featured(){  // show products based on type clicked
	    $Admin = new Admin();
		for($i=0; $i<17; $i++){
			$j = $i + 1;
			${"e_" . $j} = request()->getPost('e_'.$j);
			$codes['id'][$i] = $j;
			$codes['fid'][$i] = ${"e_" . $j};
		}
		$data = $Admin->update_featured($codes);
		echo $data;
    }
	
	public function do_uploader($f)
	{
		$ft = $this->request->getPost('ft');

		if ($ft == 1) {
			$config['upload_path'] = FCPATH . 'images/banner';
			$t = "slider";
			$path = '/images/banner';
		} elseif ($ft == 2) {
			$config['upload_path'] = FCPATH . 'images/featured/large';
			$t = "sponsor";
			$path = '/images/featured/large';
		} elseif ($ft == 3) {
			$config['upload_path'] = FCPATH . 'images/promotion';
			$t = "promotion";
			$path = '/images/promotion';
		} elseif ($ft == 4) {
			$config['upload_path'] = FCPATH . 'images/category';
			$t = "cat";
			$path = '/images/category';
		} else {
			echo 'None';
			return;
		}

		$config['allowed_types'] = '*'; //'gif|jpg|png|jpeg';
		$config['max_fename'] = '255';
		$config['encrypt_name'] = TRUE;
		$config['max_size'] = '2048000';
		$config['file_name'] = $f;
		$config['encrypt_name'] = FALSE;
		$config['overwrite'] = TRUE;
		$file = $t . $f;

		if ($this->request->getFile($file)->isValid() && in_array($this->request->getFile($file)->getMimeType(), ['image/gif', 'image/jpeg'])) {
			$ext = $this->request->getFile($file)->getClientExtension();
			if ($ext == "jpg" && file_exists($config['upload_path'] . '/' . $f . '.jpg')) {
				unlink($config['upload_path'] . '/' . $f . '.jpg');
			} elseif(file_exists($config['upload_path'] . '/' . $f . '.gif')) {
				unlink($config['upload_path'] . '/' . $f . '.gif');
			}

						$upload = $this->request->getFile($file);
						$upload->move($config['upload_path'], $config['file_name'] .'.'. $ext);

						echo 'File successfully uploaded: ';
						exit();
						$img_ftp_hostname           = '92.205.133.95';
						$img_ftp_username           = 'ordercp@img.uniteduk.co.uk';
						$img_ftp_password           = 'l4qvIM#PVsd66u~tD';
						
						// File name and path
						$filename = $f.'.'.$ext;
						$source_path_and_filename   = $config['upload_path'].'/'. $filename;
						$img_file_path_and_filename = $path.'/'. $filename;
						
						$ftp_stream = ftp_connect( $img_ftp_hostname );
						
						if ($ftp_stream == false) { echo 'cannot connect to IMG server'; die; }
						//echo 'Connect to ftp server - SUCCESS! </br>';
						ob_flush();
						flush();
						
						$login_stat = ftp_login( $ftp_stream, $img_ftp_username, $img_ftp_password );
						
						if ($login_stat == false) { echo 'cannot log into IMG server';  die; }
						//echo 'Log in - SUCCESS! </br>';
						ob_flush();
						flush();
						
						$file_ul=ftp_put( $ftp_stream, $img_file_path_and_filename, $source_path_and_filename, FTP_BINARY);
						
						if ($file_ul == false)    { echo 'unable to put file '.$filename; die;}
						//echo 'Uploading - Completed! </br>';
						ob_flush();
						flush();
						ftp_close($ftp_stream);
						
						// FTP end *****

			echo $config['upload_path'];
		} else {
			echo 'Please choose an image/jpeg/gif file';
		}
	}


	function do_uploaderxxxxxxxxxxx($f){	
		
		$ft = request()->getPost('ft');
	    if( $ft == 1 ){ $config['upload_path'] = realpath(FCPATH . 'images/banner');         $t = "slider";    $path = '/images/banner'; } else
	    if( $ft == 2 ){ $config['upload_path'] = realpath(FCPATH . 'images/featured/large'); $t = "sponsor";   $path = '/images/featured/large'; } else
	    if( $ft == 3 ){ $config['upload_path'] = realpath(FCPATH . 'images/promotion');      $t = "promotion"; $path = '/images/promotion'; } else
	    if( $ft == 4 ){ $config['upload_path'] = realpath(FCPATH . 'images/category');       $t = "cat";       $path = '/images/category';} else{ echo 'None'; }     
        $config['allowed_types'] = '*'; //'gif|jpg|png|jpeg';
        $config['max_fename'] = '255';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = '2048000';
		$config['file_name'] = $f; 
        $config['encrypt_name'] = FALSE;
		$config['overwrite'] = TRUE;
		$file = $t.$f;
		
        if (isset($_FILES[$file]['name']) && ( $_FILES[$file]['type'] == "image/gif" || $_FILES[$file]['type'] == "image/jpeg" )) {
			$ext = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
			if($ext == "gif"){ unlink($config['upload_path'].'/'. $f.'.jpg'); }else{ unlink($config['upload_path'].'/'. $f.'.gif'); }
            if (0 < $_FILES[$file]['error']) {
                echo 'Error during file upload' . $_FILES[$file]['error'];
            } else {
                if (file_exists($config['upload_path'] . $_FILES[$file]['name'])) {
                    echo 'File already exists : '.$config['upload_path'] .'/'. $config['file_name'].$ext;
                } else {
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload($file)) { echo $this->upload->display_errors(); } 
					else { echo 'File successfully uploaded : '.$config['upload_path'] .'/'. $config['file_name'].$ext; }
									
					 $img_ftp_hostname           = '92.205.133.95';
					 $img_ftp_username           = 'ordercp@img.uniteduk.co.uk';
					 $img_ftp_password           = 'l4qvIM#PVsd66u~tD';
					 
					 // File name and path
					 $filename = $f.'.'.$ext;
					 $source_path_and_filename   = $config['upload_path'].'/'. $filename;
					 $img_file_path_and_filename = $path.'/'. $filename;
					
					 $ftp_stream = ftp_connect( $img_ftp_hostname );
					 
					 if ($ftp_stream == false) { echo 'cannot connect to IMG server'; die; }
					 //echo 'Connect to ftp server - SUCCESS! </br>';
					 ob_flush();
					 flush();
					 
					 $login_stat = ftp_login( $ftp_stream, $img_ftp_username, $img_ftp_password );
					 
					 if ($login_stat == false) { echo 'cannot log into IMG server';  die; }
					 //echo 'Log in - SUCCESS! </br>';
					 ob_flush();
					 flush();
					 
					 $file_ul=ftp_put( $ftp_stream, $img_file_path_and_filename, $source_path_and_filename, FTP_BINARY);
					 
					 if ($file_ul == false)    { echo 'unable to put file '.$filename; die;}
					 //echo 'Uploading - Completed! </br>';
					 ob_flush();
					 flush();
					 ftp_close($ftp_stream);
					
					// FTP end *****
                }
            }
        } else { echo 'Please choose a image/jpeg/gif file'; }	
		echo $config['upload_path'];
	}
	
	function image_uploader(){
		if(isset($_FILES["file"]["type"])){
			$validextensions = array("gif", "jpeg", "jpg", "png");
			$temporary = explode(".", $_FILES["file"]["name"]);
			$file_extension = end($temporary);
			if (( ($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 900000) && in_array($file_extension, $validextensions)) {
				if ($_FILES["file"]["error"] > 0){
					echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
				}
				else{
					if (file_exists("upload/" . $_FILES["file"]["name"])) {
					echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
				}
				else{
					$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
					$targetPath = "upload/".$_FILES['file']['name']; // Target path where file is to be stored
					move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
					echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
					echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
					echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
					echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
					echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
				   }
				}
			}
			else{
				echo "<span id='invalid'>***Invalid file Size or Type***<span>";
			}
		}	
	}
	
	function do_upload_version2($filename){
		$config = array(
			'file_name' => $filename ,
			'upload_path' => realpath(APPPATH . '../images/featured/large'),
			'allowed_types' => 'jpg|jpeg|png|gif', //gif|jpg|png|jpeg
			'overwrite' => TRUE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			'max_height' => "768",
			'max_width' => "1024"
		);
		$this->load->library('upload', $config);
		if($this->upload->do_upload()){
			$error = array('upload_data' => $this->upload->data());
		}else{
			$error = array('error' => $this->upload->display_errors());
		}
		echo $error;
	}
		

	public function push_scount()
	{
		$s1 = $this->request->getPost('s1');
		$s2 = $this->request->getPost('s2');

		$Admin = new Admin();
		$s = $Admin->push_scount($s1, $s2);

		echo $s;
	}
	
	function push_plink(){
		$Admin = new Admin();
		$l = $this->input->post('l');	
		$v = $this->input->post('v');		
		$this->load->model('admin');	
	    $s = $Admin->push_plink($l,$v);	
	    echo $s;
	}
	
	function rename_files(){
			
		// FTP start *****
		 $img_ftp_hostname           = '92.205.133.95';
		 $img_ftp_username           = 'ordercp@img.uniteduk.co.uk';
		 $img_ftp_password           = 'l4qvIM#PVsd66u~tD';
		 
		 // File name and path
		 $filename = $f.'.'.$ext;
		 $source_path_and_filename   = $config['upload_path'].'/'. $filename;
		 $img_file_path_and_filename = $path.'/'. $filename;
		
		 $ftp_stream = ftp_connect( $img_ftp_hostname );
		 
		 if ($ftp_stream == false) { echo 'cannot connect to IMG server'; die; }
		 ob_flush();
		 flush();
		 
		 $login_stat = ftp_login( $ftp_stream, $img_ftp_username, $img_ftp_password );
		 
		 if ($login_stat == false) { echo 'cannot log into IMG server';  die; }
		 ob_flush();
		 flush();
		 
		 $file1 = $path.'1.jpg';
		 $file2 = $path.'2.jpg';
		 $temp  = $path.'temp.jpg';
		 
		 // open the folder that have the file
		 ftp_chdir($ftp_stream, '../images/banner/');
		 
		 $path = ftp_pwd($ftp_stream);
		 echo $path;
		 echo "<br />";
		 $contents = ftp_nlist($ftp_stream, ".");
		 echo print_r($contents);
		 //$files = array_diff(scandir('images/banner/'), array('.', '..'));
		 //echo print_r($files);
		
		 // rename the file
		 //ftp_rename($conn_id, 'file1.jpg', 'new_name_4_file.jpg');
		 
		 
		 ob_flush();
		 flush();
		 ftp_close($ftp_stream);
		
		// FTP end *****
	}
		
	function logout(){
		$this->Manager->logout();
	}

}

?>