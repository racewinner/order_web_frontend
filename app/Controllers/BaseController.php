<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Employee;
use App\Models\Module;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Cms;
use App\Models\Branch;
use App\Models\Category;
use App\Models\FooterConfig;
use App\Models\TopRibbonConfig;
use App\Services\MyAccountService;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
	public $data = [];

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    function __construct($module_id=null)
    {
        $request = request();
        $Employee = new Employee();
		$Module = new Module();
		$Admin = new Admin();
		$Product = new Product();
		$Branch = new Branch();
		$branch = session()->get('branch');

		if(!request()->isAJAX()) {
			$data['s1_name']  = $Admin->get_plink('s1_name');
			$data['s2_name']  = $Admin->get_plink('s2_name');	
			$data['s3_name']  = $Admin->get_plink('s3_name');	
			$data['s4_name']  = $Admin->get_plink('s4_name');	
			$data['s5_name']  = $Admin->get_plink('s5_name');	
			$data['s6_name']  = $Admin->get_plink('s6_name');
			$data['s7_name']  = $Admin->get_plink('s7_name');
			$data['s8_name']  = $Admin->get_plink('s8_name');
			$data['s9_name']  = $Admin->get_plink('s9_name');
			$data['s10_name'] = $Admin->get_plink('s10_name');
			$data['s1_period']  = $Admin->get_plink('s1_period');	
			$data['s2_period']  = $Admin->get_plink('s2_period');	
			$data['s3_period']  = $Admin->get_plink('s3_period');	
			$data['s4_period']  = $Admin->get_plink('s4_period');	
			$data['s5_period']  = $Admin->get_plink('s5_period');	
			$data['s6_period']  = $Admin->get_plink('s6_period');
			$data['s7_period']  = $Admin->get_plink('s7_period');
			$data['s8_period']  = $Admin->get_plink('s8_period');
			$data['s9_period']  = $Admin->get_plink('s9_period');
			$data['s10_period'] = $Admin->get_plink('s10_period');
			$data['s1_ids']  = $Admin->get_plink('s1_ids');	
			$data['s2_ids']  = $Admin->get_plink('s2_ids');	
			$data['s3_ids']  = $Admin->get_plink('s3_ids');	
			$data['s4_ids']  = $Admin->get_plink('s4_ids');	
			$data['s5_ids']  = $Admin->get_plink('s5_ids');	
			$data['s6_ids']  = $Admin->get_plink('s6_ids');
			$data['s7_ids']  = $Admin->get_plink('s7_ids');
			$data['s8_ids']  = $Admin->get_plink('s8_ids');
			$data['s9_ids']  = $Admin->get_plink('s9_ids');
			$data['s10_ids'] = $Admin->get_plink('s10_ids');
			$data['sp1_name'] = $Admin->get_plink('sp1_name');	
			$data['sp2_name'] = $Admin->get_plink('sp2_name');	
			$data['sp3_name'] = $Admin->get_plink('sp3_name');	
			$data['sp4_name'] = $Admin->get_plink('sp4_name');	
			$data['sp5_name'] = $Admin->get_plink('sp5_name');	
			$data['sp6_name'] = $Admin->get_plink('sp6_name');	
			$data['sp1_period'] = $Admin->get_plink('sp1_period');	
			$data['sp2_period'] = $Admin->get_plink('sp2_period');	
			$data['sp3_period'] = $Admin->get_plink('sp3_period');	
			$data['sp4_period'] = $Admin->get_plink('sp4_period');	
			$data['sp5_period'] = $Admin->get_plink('sp5_period');	
			$data['sp6_period'] = $Admin->get_plink('sp6_period');	
			$data['sp1_ids'] = $Admin->get_plink('sp1_ids');	
			$data['sp2_ids'] = $Admin->get_plink('sp2_ids');	
			$data['sp3_ids'] = $Admin->get_plink('sp3_ids');	
			$data['sp4_ids'] = $Admin->get_plink('sp4_ids');	
			$data['sp5_ids'] = $Admin->get_plink('sp5_ids');	
			$data['sp6_ids'] = $Admin->get_plink('sp6_ids');
			$data['img_host'] = $Admin->get_plink('img_host');		
			$data['catnames'] = $Admin->fetch_all_categories($request->getGet('view_mode') ?? 'grid');
			$data['all_brands'] = $Product->get_brands($Employee->get_logged_in_employee_info(), []);
			$data['top_ribbon'] = Cms::getActiveTopRibbon();
			$data['all_branches'] = $Branch->findAll(); 
			$data['is_mobile'] = session()->get('is_mobile');
			$data['footerconfig'] = FooterConfig::getConfig();
			$data['topRibbonConfig'] = TopRibbonConfig::getConfig();
	
			// data for v2
			$data['top_categories'] = Category::getCategoryTree($branch);
	
			if(!$Employee->is_logged_in()) {
				$data['allowed_modules'] = [];
			} else {
				$logged_in_employee_info=$Employee->get_logged_in_employee_info();
			
				$data['allowed_modules'] = $Module->get_allowed_modules($logged_in_employee_info->person_id);
				$data['user_info'] = $logged_in_employee_info;
	
				// To get credit account
				$response = MyAccountService::get_credit_account($logged_in_employee_info->username);
				if(isset($response['data'])) $data['credit_account'] = $response['data'];
	
				// To get loyalty
				$response = MyAccountService::get_loyalty($logged_in_employee_info->username);
				if(isset($response['data']) && $response['data']['url']) $data['loyalty_url'] = $response['data']['url'];
			}

			$this->data = $data;
		}
    }
}
