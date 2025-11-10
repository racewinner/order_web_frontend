<?php
namespace App\Models;
use App\Models\Employee;
use CodeIgniter\Model;
use App\Models\Admin;
use App\Models\Cms;
use App\Models\CmsItem;

use App\Models\Category;

class Product extends Model
{
	protected $table = 'epos_product';
	protected $primaryKey = 'prod_id';

	function exists($item_id)
	{
		$this->db->from('product');
		$this->db->where('prod_id', $item_id);
		$query = $this->db->get();
		return ($query->num_rows() == 1);
	}

	function fetch_category($cat)
	{
		$db = \Config\Database::connect();

		$conditions = array('parent_id' => $cat, 'display' => '1');
		$query = $db->table("epos_categories")->orderBy("category_name", "ASC")
			->where($conditions)
			->get();

		return $query->getResult();
	}

	function fetch_allsubcategories()
	{
		$conditions = array('parent_id !=' => '0');
		$this->db->order_by("category_name", "ASC");
		$this->db->where($conditions);
		$query = $this->db->get("epos_categories");
		return $query->getResult();
	}

	function fetch_parent($cat)
	{
		$conditions = array('category_id' => $cat, 'parent_id !=' => '0', 'display' => '1');
		$this->db->order_by("category_name", "ASC");
		$this->db->where($conditions);
		$q = $this->db->get("epos_categories");
		if ($q->num_rows() > 0) {
			$r = $q->row();
			return $r->parent_id;
		}
	}

	function get_all_categories($category_id = 0)
	{
		$is_mobile = session()->get('is_mobile');

		$Admin = new Admin();

		$db = \Config\Database::connect();

		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');

		$url = 'products/index/';
		$table_rows = "<div class='c_container' id='cat_main' style='display:none'>";

		$table_rows .= "<div class='c_item 0' id='cat_1' onclick='refresh_page(1, 0);'>"
			. "<div class='category-image'><div class='d-flex justify-content-center align-items-center'><img src='$img_host/images/categories/all/main.jpg'></div></div>"
			. "<div class='category-name'>All Products</div>"
			. "</div>";

		$m_values = array();
		$s_values = array();
		$m_values[0] = "All Products";

		$results = $db->query("SELECT * FROM epos_categories WHERE parent_id=0 AND display=1 ORDER BY category_name ASC");
		$c = 0;
		foreach ($results->getResult() as $res) {
			$c = $c + 1;
			$j = $c + 1;
			$catname = strtolower($res->category_name);
			$catname = preg_replace("/[.,&\s\/_]+/", "-", $catname);
			$m_values[$res->category_id] = $res->category_name;
			//echo $m_values[$res->category_id] . "<br />";

			$img_src = "$img_host/images/categories/$catname/main.jpg";
			$category = Category::findOne($res->category_id);
			if (!empty($category)) {
				if ($is_mobile == '1') {
					if (!empty($category['logo_mobile']['url']))
						$img_src = $category['logo_mobile']['url'];
					else if (!empty($category['logo_web']['url']))
						$img_src = $category['logo_web']['url'];
				} else {
					if (!empty($category['logo_web']['url']))
						$img_src = $category['logo_web']['url'];
					else if (!empty($category['logo_mobile']['url']))
						$img_src = $category['logo_mobile']['url'];
				}
			}

			$table_rows .= "<div class='c_item $res->category_id' id='cat_$j' onclick='refresh_page($j, $res->category_id);'>"
				. "<div class='category-image'><div class='d-flex justify-content-center align-items-center'><img src='$img_src'/></div></div>"
				. "<div class='category-name'>$res->category_name</div>"
				. "</div>";

			$catnum[$c] = $res->category_id;
			$cname[$c] = $catname;
		}
		//for($e=$c; $e<=15; $e++){  $table_rows .= "<div class='c_item'>&nbsp;</div>";	}
		for ($e = $c; $e <= 33; $e++) {
			$table_rows .= "<div class='c_item'>&nbsp;</div>";
		}
		$table_rows .= "</div>";
		$table_rows .= "<div id='subcategories'>";
		for ($i = 1; $i <= $c; $i++) {
			$j = $i + 1;
			$table_rows .= "<div class='c_container' id='subcat_$j' style='display:none;'>";
			$query = "select * from epos_categories where parent_id='" . $catnum[$i] . "' AND display=1 ORDER BY category_name ASC";
			$results_sub = $db->query($query);
			if (count($results_sub->getResult()) == 1) {
				$solo = 'c_item solo';
			} else {
				$solo = 'c_item';
			}

			$sc = 0;
			foreach ($results_sub->getResult() as $res_sub) {
				$sc = $sc + 1;
				$scname = strtolower($res_sub->category_name);

				$scname = preg_replace("/[.,&\s\/_]+/", "-", $scname);
				if (count($results_sub->getResult()) == 1) {
					$scname = "main";
				}
				$s_values[$res_sub->category_id] = $res_sub->category_name;

				// image
				$img_src = "$img_host/images/categories/$cname[$i]/$scname.jpg";
				$category = Category::findOne($res_sub->category_id);
				if (!empty($category)) {
					if ($is_mobile == '1') {
						if (!empty($category['media_mobile']['url']))
							$img_src = $category['media_mobile']['url'];
						else if (!empty($category['media_web']['url']))
							$img_src = $category['media_web']['url'];
					} else {
						if (!empty($category['media_web']['url']))
							$img_src = $category['media_web']['url'];
						else if (!empty($category['media_mobile']['url']))
							$img_src = $category['media_mobile']['url'];
					}
				}

				$table_rows .= "<div class='$solo $res_sub->category_id $catnum[$i]' onclick='refresh_page(0, $res_sub->category_id);'>"
					. "<div class='category-image'><div class='d-flex justify-content-center align-items-center'><img src='$img_src'></div></div>"
					. "<div class='category-name'>$res_sub->category_name</div>"
					. "</div>";
			}
			if ($sc < 6) {
				for ($e = $sc; $e <= 7; $e++) {
					$table_rows .= "<div class='c_item'>&nbsp;</div>";
				}
			} else {
				for ($e = $sc; $e <= 20; $e++) {
					$table_rows .= "<div class='c_item'>&nbsp;</div>";
				}
			}
			$table_rows .= "</div>";
		}
		$table_rows .= "</div>";

		return $table_rows;
	}

	function get_all_favorites($user_info, $limit = 15000, $offset = 0, $sort_key = 3, $category_id = 0)
	{
		return $this->load_favorites('', '', '', $user_info, $limit, $offset, $sort_key, $category_id);
	}

	function count_all_category($user_info, $filter)
	{
		return $this->total_search_num_rows_category($user_info, $filter);
	}

	public function get_info($prod_id)
	{
		$db = \Config\Database::connect();
		$builder = $db->table('epos_product p');
		$builder->select("p.*, 
			pi.url as image_url, 
			pi.version as image_version,
			(CASE WHEN (prod_rrp * prod_uos > 0) 
				THEN ROUND((1-((prod_sell * (1.00 + (CASE WHEN vat_code='A' THEN 0.2 WHEN vat_code='C' THEN 0.05 ELSE 0 END))) / (prod_rrp * prod_uos)))*100, 1)
				ELSE 0 
			END) AS por
		");
		$builder->join('epos_product_images pi', 'CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code', 'left');
		$builder->where('prod_id', $prod_id);

		$result = $builder->get()->getRow();
		return $result;
	}

	function get_item_id($item_number)
	{
		return;
	}

	function get_multiple_info($item_ids)
	{
		// $this->db->from('items');
		// $this->db->where_in('item_id',$item_ids);
		// $this->db->order_by("item", "asc");
		// return $this->db->get();

		$db = \Config\Database::connect();
		$builder = $db->table('items');
		$builder->whereIn('item_id', $item_ids);
		$builder->orderBy('item', 'asc');
		return $builder->get();
	}
	public function save($row): bool
	{

		return true;
	}

	function delete_all()
	{
		return;
	}

	function save_excel(&$prod_data, $prod_id = false)
	{
		return;
	}
	function update_multiple($item_data, $item_ids)
	{
		return;
	}
	function delete($item_id = null, bool $purge = false)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id', $item_ids);
		return $this->db->update('items', array('deleted' => 1));
	}
	function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();
		return $suggestions;
	}

	function get_search_suggestions0($user_info, $search, $limit = 30)
	{
		$suggestions = array();
		return $suggestions;
	}

	function get_search_suggestions1($user_info, $search, $limit = 30)
	{
		$suggestions = array();
		return $suggestions;
	}

	function get_search_suggestions2($user_info, $search, $limit = 30, $category_id = 0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$Admin = new Admin();

		$category_id = intval($category_id);
		$suggestions = array();

		$cond = "";
		$search0 = $search;
		try {
			$cond .= "AND (";
			$arr0 = preg_split("/\,/", $search0);
			foreach ($arr0 as $index0 => $a0) {
				if ($index0 > 0)
					$cond .= " OR ";
				$cond .= "( ";
				//------------
				$a0 = trim($a0);
				$arr = preg_split("/\ /", $a0);
				foreach ($arr as $index => $a) {
					$keyword = $a;
					if (strpos($keyword, '%') !== false) {
						$keyword = str_replace(['%', '_'], ['\%', '\_'], $keyword);
					} else {
						$keyword = $db->escapeLikeString($a);
					}
					if ($index > 0)
						$cond .= " AND ";
					$cond .= "( ";
					// original search engine...
						// $cond .= "p.prod_code LIKE '%" . $keyword . "' ";
						// $cond .= "OR retail LIKE '" . 	$keyword . "%' ";
						// $cond .= "OR wholesale LIKE '" . $keyword . "%' ";
						// $cond .= "OR prod_desc LIKE '%". $keyword . "%' ";
						// $cond .= "OR brand LIKE '%" . 	$keyword . "%' ";
					// new search engine, holla.ardy
					$cond .= "p.prod_code LIKE '%" . 		$keyword . "' ";		
					//----------
					$cond .= "OR retail LIKE '" . 			$keyword . "%' ";			
					//----------
					$cond .= "OR wholesale LIKE '" . 		$keyword . "%' ";		
					//----------
					$cond .= "OR prod_desc LIKE '% " . 		$keyword . "' ";		
					$cond .= "OR prod_desc LIKE '" . 		$keyword . " %' ";
					$cond .= "OR prod_desc LIKE '% " . 		$keyword . " %' ";
					$cond .= "OR prod_desc = '" . 			$keyword . "' ";
					//----------
					$cond .= "OR prod_pack_desc LIKE '%" . 	$keyword . "%' ";
					//----------
					$cond .= "OR brand LIKE '%" . 			$keyword . "%' ";
					$cond .= ") ";
				}
				//------------
				$cond .= ") ";
			}
			$cond .= ") ";
		} catch (\Exception $e) {

		}
		//-------------------
		$search_cond = $cond;

		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		$query =  "SELECT p.prod_code, p.prod_desc, pi.url as image_url, pi.version as image_version 
                   FROM epos_product as p 
                   LEFT JOIN epos_product_images as pi on CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code 
                   LEFT JOIN epos_categories as ct on p.group_desc = ct.filter_desc 
                   WHERE is_disabled='N' AND ct.display=1 AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query .= "AND p.organization_id={$organization_id} ";
		}
    	$query .= $search_cond . " AND ";
		// limit results to current selected category only. (original, till 2025.10.23)
		// but from 2025.10.24, because of feedback, not limit, instead of apply on all products
		/*
		if ($category_id != 0) {
			$results_category = $db->query("SELECT * FROM epos_categories WHERE category_id = '" . $category_id . "'");
			$res_category = $results_category->getRow();
			if ($res_category->parent_id == 0) {
				$results_subcategory = $db->query("SELECT * FROM epos_categories WHERE parent_id = '" . $res_category->category_id . "'");
				$nCount = 0;
				foreach ($results_subcategory->getResult() as $res_subcategory) {
					if ($nCount == 0) {
						$query .= "(group_desc = '" . $res_subcategory->filter_desc . "' ";
					} else {
						$query .= "OR group_desc = '" . $res_subcategory->filter_desc . "' ";
					}
					$nCount++;
				}
				$query .= ") AND ";
			} else {
				$query = "group_desc = '" . $res_category->filter_desc . "' AND ";
			}
		}*/
		$query .= "(price_list = '000' ";
		if (!empty($user_info)) {
			if (!empty($user_info->price_list999))
				$query .= "OR price_list = '999' ";
			if (!empty($user_info->price_list012))
				$query .= "OR price_list = '12' ";
			if (!empty($user_info->price_list011))
				$query .= "OR price_list = '11' ";
			if (!empty($user_info->price_list010))
				$query .= "OR price_list = '10' ";
			if (!empty($user_info->price_list009))
				$query .= "OR price_list = '09' ";
			if (!empty($user_info->price_list008))
				$query .= "OR price_list = '08' ";
			if (!empty($user_info->price_list007))
				$query .= "OR price_list = '07' ";
			if (!empty($user_info->price_list005))
				$query .= "OR price_list = '05' ";
			if (!empty($user_info->price_list001))
				$query .= "OR price_list = '01' ";
		} else {
			$query .= "OR price_list='999'";
		}
		$query .= ") GROUP BY prod_desc ";
		$query .= " ORDER BY prod_desc ASC, prod_pack_desc ASC, brand ASC, meta ASC ";
		// here, we paginate the result as many as limit because speed up
		$query .= " LIMIT " . $limit; 
		$suggestions = array();
		$result = $db->query($query);
		foreach ($result->getResult() as $row) {
			$prod_desc = $row->prod_desc;
			$prod_desc_alt = str_replace("&", "%26", $prod_desc);
			$prod_desc_alt = str_replace("%", "%25", $prod_desc);

			$url = base_url() . 'products/index?';
			$url .= '&search_mode=search';
			// $url .= '&search0=' . $row->prod_code;
			$url .= '&search0=' . $prod_desc_alt;
			$url .= '&category_id=0';

			$suggestions[] = 
				'<a href="' . $url . '" style="padding:0px;">
					<div class="autosearch" style="display:flex; align-items:center; background-color:aliceblue;" >
						<div style="margin:5px; padding:10px;">
							<img width="50" height="50" src="' . $img_host . '/product_images/' . $row->image_url . '?=v' . $row->image_version . '" />
						</div>
						<div style="margin:5px; padding:10px; font-size:14px; align-self: center;">' . $prod_desc . '</div>
					</div>
				</a>';
		}
		return $suggestions;
	}
	
	function get_item_search_suggestions($search, $limit = 25)
	{
		return;
	}
	function get_category_suggestions($search)
	{
		return;
	}

	function total_search_num_rows($user_info, $filter)
	{
		return $this->total_search_num_rows_category($user_info, $filter);
	}

	function total_search_num_rows_category($user_info, $filter)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$cond = $this->buildCondition($user_info, $filter);

		$query = "SELECT p.prod_code FROM epos_product as p ";
		if (!empty($user_info) && !empty($filter['favorite'])) {
			$query .= " INNER JOIN epos_favorites as ef on (ef.prod_code = p.prod_code and ef.person_id=" . $user_info->person_id . ") ";
   		}
    	$query .= " LEFT JOIN epos_categories as ct on p.group_desc = ct.filter_desc ";
		$query .= " WHERE ct.display=1 AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query .= " AND p.organization_id={$organization_id} ";
		}
		$query .= " AND " . $cond . " GROUP BY p.prod_code ";

		$results = $db->query($query);
		return $results->getNumRows();
	}
	function search($user_info, $filter)
	{
		return $this->search_category($user_info, $filter);
	}

	// function search_category($user_info, $limit = 30 ,  $offset = 0 , $sort_key = 6 , 
	// 					$category_id=0, $im_new = 0, $plan_profit = 0, $own_label='N', $brand='', $favorite=0)
	function search_category($user_info, $filter)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$cond = $this->buildCondition($user_info, $filter);
		$porformula = ", (CASE WHEN (prod_rrp * prod_uos > 0) 
							   THEN ROUND((1-((prod_sell * (1.00 + (CASE WHEN vat_code='A' THEN 0.2 WHEN vat_code='C' THEN 0.05 ELSE 0 END))) / (prod_rrp * prod_uos)))*100, 1)
							   ELSE 0 END)
						 AS por ";
		if (request()->uri->getSegment(1) == 'presells') {
			$tablename = "presell";
			$addcols = "period_ref, g_qty, g_min, g_max, s_qty, s_min, s_max, m_qty, m_min, m_max, l_qty, l_min, l_max, e_qty, e_min, e_max, ordered, ";
		} else {
			$tablename = "product";
			$addcols = "";
		}
		$query = "SELECT 
						{$addcols} 
						p.prod_id, p.prod_code, prod_uos, prod_desc, prod_pack_desc, price_start, price_end, brand, epoints,
						vat_code, prod_price, group_desc, prod_code1,
						price_list, prod_level1, prod_level2, prod_level3,
						non_promo_sell_price,
						pi.url as image_url,
						pi.version as image_version,
						IF(brand='', 'zzzz', brand) as v_brand,
						MIN(prod_sell) as prod_sell, 
						prod_rrp, wholesale, retail, p_size, is_disabled, promo, van, shelf_life, 
						availability, pfp 
						{$porformula} 
        		FROM epos_{$tablename} as p 
				LEFT JOIN epos_product_images as pi on CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code 
        		LEFT JOIN epos_categories as ct on p.group_desc = ct.filter_desc ";
		if (!empty($filter['favorite'])) {
			$query .= " INNER JOIN epos_favorites as ef on (ef.prod_code = p.prod_code and ef.person_id={$user_info->person_id}) ";
		}
		$query .= " WHERE ct.display=1 AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query .= "AND p.organization_id={$organization_id} ";
		}
    	$query .= "AND " . $cond;

		$sort_key = isset($filter['sort_key']) ? $filter['sort_key'] : 3;
		switch ($sort_key) {
			case 1:
				$group = 'p.prod_code';
				$order_by = 'prod_code ASC, ';
				break;
			case 2:
				$group = 'p.prod_code';
				$order_by = 'prod_code DESC, ';
				break;
			case 3:
				;
				$group = 'p.prod_code';
				$order_by = 'prod_desc ASC, ';
				break;
			case 4:
				$group = 'p.prod_code';
				$order_by = 'prod_desc DESC, ';
				break;
			case 5:
				$group = 'p.prod_code';
				$order_by = 'por ASC, ';
				break;
			case 6:
				$group = 'p.prod_code';
				$order_by = 'por DESC, ';
				break;
			case 7:
				$group = 'p.prod_code';
				$order_by = 'prod_sell ASC, ';
				break;
			case 8:
				$group = 'p.prod_code';
				$order_by = 'prod_sell DESC, ';
				break;
			case 9:
				$group = 'p.prod_code';
				$order_by = 'ranking DESC, ';
				break;
			case 10:
				$group = 'p.prod_code';
				$order_by = 'v_brand ASC, ';
				break;
			default:
				$group = 'p.prod_code';
				$order_by = '';
				break;
		}
		$order_by .= "p.brand ASC, p.prod_desc ASC, p.prod_pack_desc ASC, p.meta ASC ";
		$query .= " GROUP BY " . $group . " ";
    	$query .= " ORDER BY " . $order_by . " ";

		$offset = isset($filter['offset']) ? $filter['offset'] : 0;
		$query .= ' LIMIT ' . $offset . ', ' . $filter['limit'];

		$results = $db->query($query);
		return $results;
	}

	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}

	function to_cart($prod_code, $mode, $person_id, $quantity = 1, $spresell = 0, $type=null)
	{
		$Employee = new Employee();
		$person_info = $Employee->get_info($person_id);
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');
		$db = \Config\Database::connect();

		if ($mode == 4) {
			$query =  " DELETE FROM epos_cart WHERE person_id={$person_id}" .
                " AND branch={$branch}";
      if (!empty($organization_id)) {
      $query .= " AND organization_id={$organization_id} ";
      }
      $query .= " AND prod_code='{$prod_code}' AND presell=0 AND group_type='{$type}'";
			$db->transStart();
			$db->query($query);
			$db->transComplete();

			if ($db->transStatus() === FALSE)
				return -1;
			else
				return 0;
		} else {
			$cond = " prod_code='{$prod_code}' and person_id={$person_id}" . 
              " AND branch={$branch}";
      if (!empty($organization_id)) {
      $cond.= " AND organization_id={$organization_id} ";
      }
      $cond .= " AND presell=0 ";
			$cond .= ($spresell == 1) ? " AND group_type='spresell'" : " AND group_type!='spresell'";
			$query = "SELECT * FROM epos_cart WHERE " . $cond;
			$res = $db->query($query);
	
			if ($res->getNumRows() == 0) {
				if ($mode == 1 || $mode == 3) {
					$res_prod = $this->getLowestPriceProductByCode($person_info, $prod_code, true, $spresell);
					if ($spresell) {
						$type = "spresell";
					} else {
						// Get Category type
						$category = $db->table('epos_categories')->where('filter_desc', $res_prod->group_desc)->get()->getRow();
						$type = !empty($category) ? $category->type : 'general';
					}
	
          $line_position = $this->genCartLinePosition($person_id);

					$cart_data = array(
						'prod_code' => $prod_code,
						'quantity' => $quantity,
						'person_id' => $person_id,
						'group_type' => $type,
            'line_position' => $line_position,
            'branch' => $branch,
            'organization_id' => $organization_id,
					);
					$db->table('epos_cart')->insert($cart_data);
					return $quantity;
				} else if ($mode == 2)
					return 0;
				else
					return -1;
			} else if ($res->getNumRows() == 1) {
				$res_row = $res->getRow();
				$quantity1 = $res_row->quantity;
				if ($mode == 1)
					$quantity1 = $quantity1 + 1;
				else if ($mode == 2) {
					if ($quantity1 > 0)
						$quantity1 = $quantity1 - 1;
				} else if ($mode == 3) {
					$quantity1 = $quantity;
				}
	
				if ($quantity1 == 0) {
					$db = \Config\Database::connect();
					$db->query("DELETE FROM epos_cart WHERE " . $cond);
					return 0;
				} else {
          // $line_position = $this->genCartLinePosition($person_id);
          $cart_data = ['quantity' => $quantity1/*, 'line_position' => $line_position*/];
					$builder = $db->table('epos_cart');
					$builder->where($cond);
					$builder->update($cart_data);
					return $quantity1;
				}
			} else
				return -1;
		}
	}

	function presell_to_cart($prod_code, $mode, $person_id, $quantity = 1)
	{
		$db = \Config\Database::connect();

		$query = "SELECT * FROM epos_cart WHERE prod_code='" . $prod_code . "' and person_id='" . $person_id . "' and presell=1";
		$res = $db->query($query);

		if ($res->num_rows() == 1) {
			$res_row = $res->row();
			$quantity1 = $res_row->quantity;

			if ($mode == 1) {
				$quantity1 = $quantity1 + 1;
			} else if ($mode == 2) {
				if ($quantity1 > 0)
					$quantity1 = $quantity1 - 1;
			} else if ($mode == 3) {
				$quantity1 = $quantity;
			}

			$cart_data = array('quantity' => $quantity1);
			$this->db->where('prod_code', $prod_code);
			$this->db->where('person_id', $person_id);
			$this->db->where('presell', '1');
			$this->db->update('cart', $cart_data);
			return $quantity1;
		} else
			return -1;
	}

	public static function get_cart_quantity($prod_code, $person_id, $spresell = 0)
	{
		$db = \Config\Database::connect();
		$query = "SELECT quantity FROM epos_cart WHERE prod_code='" . $prod_code . "' and person_id='" . $person_id . "' and presell=0";
		$query .= ($spresell == 1) ? " AND group_type='spresell'" : " AND group_type!='spresell'";
		$results = $db->query($query)->getRow();
		if ($results)
			return $results->quantity;
		return 0;
	}

	function get_presell_quantity($prod_code, $person_id)
	{
		$db = \Config\Database::connect();

		$query = "SELECT quantity FROM epos_cart WHERE prod_code='" . $prod_code . "' and person_id='" . $person_id . "' and presell=1";
		$results = $db->query($query)->getRow();
		if ($results)
			return $results->quantity;
		return 0;
	}

	function get_duplicate($prod_code)
	{
		$db = \Config\Database::connect();
		$Employee = new Employee();
		$user_info = $Employee->get_logged_in_employee_info();
		$data = $this->check_daytoday_usave($user_info->person_id);
		if ($data != '') {
			$q = "SELECT * FROM epos_product WHERE prod_code='" . $prod_code . "' and ( ";
			$or = 0;
			if (substr($data, 0, 1) == "1") {
				$q .= "price_list='8' ";
				$or = 1;
			}
			if ($or == 1 && substr($data, 1, 1) == "1") {
				$q .= "OR ";
				$or = 0;
			}
			if (substr($data, 1, 1) == "1") {
				$q .= "price_list='10' ";
				$or = 1;
			}
			if ($or == 1 && substr($data, 2, 1) == "1") {
				$q .= "OR ";
				$or = 0;
			}
			if (substr($data, 2, 1) == "1") {
				$q .= "price_list='11' ";
				$or = 1;
			}
			if ($or == 1 && substr($data, 3, 1) == "1") {
				$q .= "OR ";
				$or = 0;
			}
			if (substr($data, 3, 1) == "1") {
				$q .= "price_list='12' ";
			}
			//$q .= "price_list='8' OR price_list='10' OR price_list='11' OR price_list='12'";
			$q .= ")";
			$r = $db->query($q);
			if ($r->num_rows() != 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	function check_daytoday_usave($pid)
	{
		$this->db->select('price_list008,price_list010,price_list011,price_list012');
		$this->db->where('person_id', $pid);
		$q = $this->db->get('epos_employees');
		if ($q->num_rows() > 0) {
			$r = $q->row();
			$data = '';
			if ($r->price_list008 == "1" || $r->price_list010 == "1" || $r->price_list011 == "1" || $r->price_list012 == "1") {
				if ($r->price_list008 == "1") {
					$data .= "1";
				} else {
					$data .= "0";
				}
				if ($r->price_list010 == "1") {
					$data .= "1";
				} else {
					$data .= "0";
				}
				if ($r->price_list011 == "1") {
					$data .= "1";
				} else {
					$data .= "0";
				}
				if ($r->price_list012 == "1") {
					$data .= "1";
				} else {
					$data .= "0";
				}
				return $data;
			} else {
				return $data;
			}
		} else {
			$data = "No Rows Found";
		}
		//echo $data;
	}

	function presell($prod_id)
	{
		//$this->db->select('*');
		$this->db->where('prod_id', $prod_id);
		$q = $this->db->get('epos_presell');
		//$query = "SELECT * FROM epos_presell WHERE prod_id='".$prod_id."'";
		if ($q->num_rows() > 0) {
			$r = $q->row();
			return $r;
		}
		return 0;
	}

	public static function get_favorite_state($pid, $prod_code)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_favorites')
			->select('*')
			->where('person_id', $pid)
			->where('prod_code', $prod_code)
			->get();


		if ($query->getNumRows() > 0) {
			return "active";
		}
		return "";
	}

	function favorite($pid, $prod_code, $state)
	{
		$db = \Config\Database::connect();
		if ($state == "active") {
			$db->query("DELETE FROM epos_favorites WHERE person_id='" . $pid . "' and prod_code='" . $prod_code . "'");
		} else {
			$data = array('person_id' => $pid, 'prod_code' => $prod_code);
			$res = $db->table('epos_favorites')->insert($data);
		}
		return true;
	}

	function load_favorites($search0, $search1, $search2, $user_info, $limit = 30, $offset = 0, $sort_key = 6, $category_id = 0)
	{
		$db = \Config\Database::connect();
		$favorites_list = '';
		$query0 = "SELECT * FROM epos_favorites WHERE person_id='" . $user_info->person_id . "'";
		$result_favorites = $db->query($query0);
		$nCount = 0;

		foreach ($result_favorites->getResult() as $res_favorites) {
			if ($nCount == 0) {
				$favorites_list .= "p.prod_code='" . $res_favorites->prod_code . "'";
			} else {
				$favorites_list .= " OR p.prod_code='" . $res_favorites->prod_code . "'";
			}
			$nCount++;
		}
		if ($favorites_list == '') {
			$favorites_list .= " p.prod_code!='' ";
		}

		$porformula = ", (CASE WHEN (prod_rrp * prod_uos > 0) 
							   THEN ROUND((1-((prod_sell * (1.00 + (CASE WHEN vat_code='A' THEN 0.2 WHEN vat_code='C' THEN 0.05 ELSE 0 END))) / (prod_rrp * prod_uos)))*100, 1)
							   ELSE 0 
						 END)
						 AS por";
		$query = "SELECT prod_id, p.prod_code, prod_uos, prod_desc, prod_pack_desc, price_start, price_end, brand, epoints,vat_code, prod_price, group_desc, 
					prod_code1, price_list, prod_level1, prod_level2, prod_level3,
					pi.url as image_url, pi.version as image_version,
                    MIN(prod_sell) as prod_sell, prod_rrp, wholesale, retail, p_size, is_disabled, promo, van, shelf_life" . $porformula . "
                FROM epos_product as p
				LEFT JOIN epos_product_images as pi on CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code 
                WHERE (" . $favorites_list . ") ";

		switch ($sort_key) {
			case 1:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_code ASC ";
				break;
			case 2:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_code DESC ";
				break;
			case 3:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_desc ASC ";
				break;
			case 4:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_desc DESC ";
				break;
			case 5:
				$query .= "GROUP BY p.prod_code ORDER BY p.por ASC ";
				break;
			case 6:
				$query .= "GROUP BY p.prod_code ORDER BY p.por DESC ";
				break;
			case 7:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_sell ASC ";
				break;
			case 8:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_sell DESC ";
				break;
			case 9:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_pack_desc ASC ";
				break;
			case 10:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_pack_desc DESC ";
				break;
			case 11:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_uos ASC ";
				break;
			case 12:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_uos DESC ";
				break;
			default:
				$query .= "GROUP BY p.prod_code ORDER BY p.prod_code DESC ";
				break;
		}

		if (intval($offset) < 1)
			$offset = 0;
		$query .= 'LIMIT ' . $offset . ',' . $limit;

		//$query1 = "SELECT * FROM epos_product LIMIT 0,200";
		$results = $db->query($query);
		if ($nCount > 0) {
			return $results;
		} else {
			return '0';
		}
		//return $results;
	}

	function get_ftp_location()
	{
		return;
	}

	public static function get_brands($user_info, $filter)
	{
		$db = \Config\Database::connect();

		$cond = self::buildCondition($user_info, $filter);
		$query = "SELECT brand FROM epos_product as p ";
		if (!empty($user_info) && !empty($filter['favorite'])) {
			$query .= " INNER JOIN epos_favorites as ef on (ef.prod_code = p.prod_code and ef.person_id=" . $user_info->person_id . ") ";
		}
		$query .= " WHERE " . $cond . " AND brand !='' ";
		$query .= " GROUP BY brand";

		$query = $db->query($query);
		$brands = [];
		foreach ($query->getResult() as $row) {
			$brands[] = $row->brand;
		}
		return $brands;
	}

	public static function get_priceEnds($user_info, $filter)
	{
		$db = \Config\Database::connect();

		$cond = self::buildCondition($user_info, $filter);
		$query = "SELECT price_end FROM epos_product as p ";
		if (!empty($user_info) && !empty($filter['favorite'])) {
			$query .= " INNER JOIN epos_favorites as ef on (ef.prod_code = p.prod_code and ef.person_id=" . $user_info->person_id . ") ";
		}
		$query .= " WHERE " . $cond;
		$query .= " GROUP BY price_end ORDER BY price_end";

		$query = $db->query($query);
		$priceEnds = [];
		foreach ($query->getResult() as $row) {
			$priceEnds[] = $row->price_end;
		}
		return $priceEnds;
	}

	public static function populate(&$product, $priceList, $user_info, $spresell=0) 
	{
		self::getAvailable($product);

		if(!empty($user_info)) {
			// To get cart quantity
			$product->cart_quantity = self::get_cart_quantity($product->prod_code, $user_info->person_id, $spresell);

			// To check whether it is favorite product.
			$f_state = self::get_favorite_state($user_info->person_id, $product->prod_code);
			$product->favorite = $f_state;
		}

		$product->p_label = '';
		$product->ribbon_background = '';
		$product->promo_end_text = '';
		if ($product->promo=='Y') {
			self::getPriceInfo($product, $priceList);
		} else {
			$product->price = number_format($product->prod_sell,2,'.','');
		}

    $product->non_promo_price = number_format($product->non_promo_sell_price,2,'.','');
    if($product->non_promo_price == 0 || $product->price == $product->non_promo_price) {
      $product->is_show_non_promo_price = false;
    } else {
      $product->is_show_non_promo_price = true;
    }

		$product->case = self::getCase($product);
	}

	public static function buildCondition($user_info, $filter)
	{
		try {
			$db = \Config\Database::connect();    
      		$organization_id = session()->get('organization_id');

			$cond = " is_disabled='N' ";
			// category_id
			if (!empty($filter['category_id'])) {
				$CmsItem = new CmsItem();
				$cms_items = $CmsItem->getCmsItemsByType('category_carousel');

				$prod_codes = [];
				foreach($cms_items as $cms_itm) {
					$str_top_cat_id = $cms_itm['data']['top_cat_id'];
					$str_sub_cat_id = $cms_itm['data']['sub_cat_id'];

					if ($str_top_cat_id == $filter['category_id'] || $str_sub_cat_id == $filter['category_id']) {
						// if($cms_itm->data->brand == $filter['brand']) {
						$prod_codes[] = $cms_itm['prod_codes'];
					}
				}

				$query = "SELECT * FROM epos_categories WHERE category_id='" . $filter['category_id'] . "' ";
				if (!empty($organization_id)) {
					$query .= "AND organization_id={$organization_id} ";
				}
				$results_category = $db->query($query);
				$res_category = $results_category->getRow();
				if ($res_category->parent_id == 0) {
					$query = "SELECT * FROM epos_categories WHERE parent_id='" . $res_category->category_id . "' ";
					if (!empty($organization_id)) {
						$query .= "AND organization_id={$organization_id} ";
					}
					$results_subcategory = $db->query($query);
					$nCount = 0;
					foreach ($results_subcategory->getResult() as $res_subcategory) {
						if ($nCount == 0) {
							$cond .= "AND (group_desc = '" . $res_subcategory->filter_desc . "' ";
						} else {
							$cond .= "OR group_desc = '" . $res_subcategory->filter_desc . "' ";
						}
						$nCount++;
					}
					if (implode(',', $prod_codes)) {
						$cond .= "OR p.prod_code in (" . implode(',', $prod_codes) . ") ";
					}
					$cond .= ") ";
				} else {
					$cond .= "AND (group_desc = '" . $res_category->filter_desc . "' ";
					if (implode(',', $prod_codes)) {
						$cond .= "OR p.prod_code in (" . implode(',', $prod_codes) . ") ";
					}
					$cond .= ") ";
				}
			}
			// im_new 
			if (!empty($filter['im_new'])) {
				$cond .= "AND availability = 'N' ";
			}
			// plan_profit
			if (!empty($filter['plan_profit'])) {
				$cond .= "AND pfp = '1' ";
			}
			// rrp
			if (!empty($filter['rrp'])) {
				$cond .= "AND prod_rrp = 1.00 ";
			}
			// pmp
			if (!empty($filter['pmp'])) {
				$cond .= "AND meta Like '%PMP%' ";
			}
			// non_pmp
			if (!empty($filter['non_pmp'])) {
				$cond .= "AND meta NOT Like '%PMP%' ";
			}
			// own_label
			if (!empty($filter['own_label'])) {
				$cond .= "AND own_label='Y' ";
			}
			// brand
			if (!empty($filter['brand'])) {
				$brand_arr = json_decode($filter['brand'], true);

				$CmsItem = new CmsItem();
				$cms_items = $CmsItem->getCmsItemsByType('brand');

				$prod_codes = [];
				foreach($cms_items as $cms_itm) {
					$str_brand = $cms_itm['data']['brand'];
					if (in_array($str_brand, $brand_arr, true)) {
						// if($cms_itm->data->brand == $filter['brand']) {
						$prod_codes[] = $cms_itm['prod_codes'];
					}
				}
				$brand = $filter['brand'];
				$cond .= "AND (brand in (" . substr($brand, 1, strlen($brand) - 2) . ") ";
				if (implode(',', $prod_codes)) {
					$cond .= "OR p.prod_code in (" . implode(',', $prod_codes) . ") ";
				}
				$cond .= ") ";
			}
			// price_end
			if (!empty($filter['priceEnd'])) {
				$cond .= "AND price_end in (" . substr($filter['priceEnd'], 1, strlen($filter['priceEnd']) - 2) . ") ";
			}
			// price mode
			if (isset($user_info->price_list010) && !empty($filter['spresell'])) {
				$cond .= "AND price_list = '06' ";
			} else {
				$cond .= "AND (price_list = '99999' ";
				if (!empty($user_info)) {
					if (empty($filter['price_mode']) || $filter['price_mode'] == 'cc') {
						if ($user_info->price_list001)
							$cond .= "OR price_list = '01' ";
						if ($user_info->price_list005)
							$cond .= "OR price_list = '05' ";
						if ($user_info->price_list007)
							$cond .= "OR price_list = '07' ";
						if ($user_info->price_list009)
							$cond .= "OR price_list = '09' ";
						if ($user_info->price_list999)
							$cond .= "OR price_list = '999' ";
					}
					if (empty($filter['price_mode']) || $filter['price_mode'] == 'du') {
						if ($user_info->price_list008)
							$cond .= "OR price_list = '08' ";
						if ($user_info->price_list010)
							$cond .= "OR price_list = '10' ";
						if ($user_info->price_list011)
							$cond .= "OR price_list = '11' ";
					}
					if (empty($filter['price_mode']) || $filter['price_mode'] == 'us') {
						if ($user_info->price_list012)
							$cond .= "OR price_list = '12' ";
					}
				} else {
					$cond .= "OR price_list = '999' ";
				}
				$cond .= ") ";
			}
			// presell
			if (!empty($filter['presell'])) {
				$cond .= "AND ordered=0 ";
			}
			// promo
			if (!empty($filter['promo'])) {
				$cond .= "AND promo='Y' ";
			}
			// branch
			$branch = session()->get('branch');
			if (!empty($branch)) {
				$cond .= "AND branch={$branch} ";
			}
      		// organization_id
			$organization_id = session()->get('organization_id');
			if (!empty($organization_id)) {
				$cond .= "AND p.organization_id={$organization_id} ";
			}
			// search0
			if (!empty($filter['search0'])) {
				$search0 = urldecode($filter['search0']);
				try {
					$cond .= "AND (";
					$arr0 = preg_split("/\,/", $search0);
					foreach ($arr0 as $index0 => $a0) {
						if ($index0 > 0)
							$cond .= " OR ";
						$cond .= "( ";
						//------------
						$a0 = trim($a0);
						$arr = preg_split("/\ /", $a0);
						foreach ($arr as $index => $a) {
							$keyword = $a;
							if (strpos($keyword, '%') !== false) {
								$keyword = str_replace(['%', '_'], ['\%', '\_'], $keyword);
							} else {
								$keyword = $db->escapeLikeString($a);
							}
							if ($index > 0)
								$cond .= " AND ";
							$cond .= "( ";
							// original search engine...
								// $cond .= "p.prod_code LIKE '%" . $keyword . "' ";
								// $cond .= "OR retail LIKE '" . 	$keyword . "%' ";
								// $cond .= "OR wholesale LIKE '" . $keyword . "%' ";
								// $cond .= "OR prod_desc LIKE '%". $keyword . "%' ";
								// $cond .= "OR brand LIKE '%" . 	$keyword . "%' ";
							// new search engine, holla.ardy
							$cond .= "p.prod_code LIKE '%" . 		$keyword . "' ";		
							//----------
							$cond .= "OR retail LIKE '" . 			$keyword . "%' ";			
							//----------
							$cond .= "OR wholesale LIKE '" . 		$keyword . "%' ";		
							//----------
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . "' ";		
							$cond .= "OR prod_desc LIKE '" . 		$keyword . " %' ";
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . " %' ";
							$cond .= "OR prod_desc = '" . 			$keyword . "' ";
							//----------
							$cond .= "OR prod_pack_desc LIKE '%" . 	$keyword . "%' ";
							//----------
							$cond .= "OR brand LIKE '%" . 			$keyword . "%' ";
							$cond .= ") ";
						}
						//------------
						$cond .= ") ";
					}
					$cond .= ") ";
				} catch (\Exception $e) {

				}
			}
			// search1
			if (!empty($filter['search1'])) {
				$search0 = urldecode($filter['search1']);
				try {
					$cond .= "AND (";
					$arr0 = preg_split("/\,/", $search0);
					foreach ($arr0 as $index0 => $a0) {
						if ($index0 > 0)
							$cond .= " OR ";
						$cond .= "( ";
						//------------
						$a0 = trim($a0);
						$arr = preg_split("/\ /", $a0);
						foreach ($arr as $index => $a) {
							$keyword = $a;
							if (strpos($keyword, '%') !== false) {
								$keyword = str_replace(['%', '_'], ['\%', '\_'], $keyword);
							} else {
								$keyword = $db->escapeLikeString($a);
							}
							if ($index > 0)
								$cond .= " AND ";
							$cond .= "( ";
							// original search engine...
								// $cond .= "p.prod_code LIKE '%" . $keyword . "' ";
								// $cond .= "OR retail LIKE '" . 	$keyword . "%' ";
								// $cond .= "OR wholesale LIKE '" . $keyword . "%' ";
								// $cond .= "OR prod_desc LIKE '%". $keyword . "%' ";
								// $cond .= "OR brand LIKE '%" . 	$keyword . "%' ";
							// new search engine, holla.ardy
							$cond .= "p.prod_code LIKE '%" . 		$keyword . "' ";		
							//----------
							$cond .= "OR retail LIKE '" . 			$keyword . "%' ";			
							//----------
							$cond .= "OR wholesale LIKE '" . 		$keyword . "%' ";		
							//----------
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . "' ";		
							$cond .= "OR prod_desc LIKE '" . 		$keyword . " %' ";
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . " %' ";
							$cond .= "OR prod_desc = '" . 			$keyword . "' ";
							//----------
							$cond .= "OR prod_pack_desc LIKE '%" . 	$keyword . "%' ";
							//----------
							$cond .= "OR brand LIKE '%" . 			$keyword . "%' ";
							$cond .= ") ";
						}
						//------------
						$cond .= ") ";
					}
					$cond .= ") ";
				} catch (\Exception $e) {

				}
			}
			// search3
			if (!empty($filter['search3'])) {
				$search0 = urldecode($filter['search3']);
				try {
					$cond .= "AND (";
					$arr0 = preg_split("/\,/", $search0);
					foreach ($arr0 as $index0 => $a0) {
						if ($index0 > 0)
							$cond .= " OR ";
						$cond .= "( ";
						//------------
						$a0 = trim($a0);
						$arr = preg_split("/\ /", $a0);
						foreach ($arr as $index => $a) {
							$keyword = $a;
							if (strpos($keyword, '%') !== false) {
								$keyword = str_replace(['%', '_'], ['\%', '\_'], $keyword);
							} else {
								$keyword = $db->escapeLikeString($a);
							}
							if ($index > 0)
								$cond .= " AND ";
							$cond .= "( ";
							// original search engine...
								// $cond .= "p.prod_code LIKE '%" . $keyword . "' ";
								// $cond .= "OR retail LIKE '" . 	$keyword . "%' ";
								// $cond .= "OR wholesale LIKE '" . $keyword . "%' ";
								// $cond .= "OR prod_desc LIKE '%". $keyword . "%' ";
								// $cond .= "OR brand LIKE '%" . 	$keyword . "%' ";
							// new search engine, holla.ardy
							$cond .= "p.prod_code LIKE '%" . 		$keyword . "' ";		
							//----------
							$cond .= "OR retail LIKE '" . 			$keyword . "%' ";			
							//----------
							$cond .= "OR wholesale LIKE '" . 		$keyword . "%' ";		
							//----------
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . "' ";		
							$cond .= "OR prod_desc LIKE '" . 		$keyword . " %' ";
							$cond .= "OR prod_desc LIKE '% " . 		$keyword . " %' ";
							$cond .= "OR prod_desc = '" . 			$keyword . "' ";
							//----------
							$cond .= "OR prod_pack_desc LIKE '%" . 	$keyword . "%' ";
							//----------
							$cond .= "OR brand LIKE '%" . 			$keyword . "%' ";
							$cond .= ") ";
						}
						//------------
						$cond .= ") ";
					}
					$cond .= ") ";
				} catch (\Exception $e) {

				}
			}
			$cond .= "AND prod_sell > 0 ";

			return $cond;
		} catch (\Exception $e) {
			return " is_disabled='N' ";
		}
	}

	public static function getPriceInfo(&$product, $priceList)
	{
		$ribbon_background = '';
		$p_label = '';
		$promo_end_text = '';

		if ($product->price_list == '08' || $product->price_list == '10' || $product->price_list == '11' || $product->price_list == '12' || $product->price_list == '999') {
			$ribbon_background = "#{$priceList[$product->price_list]['ribbon_colour']}";
			$p_label = $priceList[$product->price_list]['ribbon_label'];
		} else {
			$ribbon_background = "#b4e9f8";
			$p_label = "CC";
		}

		$time = $product->price_end + ((23 * 3600) + 3599);
		if (time() >= $product->price_start && time() <= $time) {
			$product->price = number_format($product->prod_sell, 2, '.', '');
			$promo_end_text = 'Offer Ends ' . date('d/m', $product->price_end);
		} else {
			$product->price = 0;
			$promo_end_text = 'Offer Ended';
		}

		$product->ribbon_background = $ribbon_background;
		$product->p_label = $p_label;
		$product->promo_end_text = $promo_end_text;
	}

	public static function getAvailable(&$product)
	{
		$icon_name = '';
		$icon_title = '';
		$prod_avail = true;

		if (isset($product->availability) && $product->availability != "") {
			if ($product->availability == "C") {
				$icon_name = "coming-soon";
				$icon_title = "Coming Soon";
				$prod_avail = false;
			} else if ($product->availability == "N") {
				$icon_name = "new-item";
				$icon_title = "New Item";
				$prod_avail = true;
			} else if ($product->availability == "L") {
				$icon_name = "low-stock";
				$icon_title = "Low Stock";
				$prod_avail = true;
			} else if ($product->availability == "O") {
				$icon_name = "out-of-stock";
				$icon_title = "Out Of Stock";
				$prod_avail = false;
			} else if ($product->availability == "T") {
				$icon_name = "best-seller";
				$icon_title = "Best Seller";
				$prod_avail = true;
			}
		}

		if ($product->price_list == PriceList['Season_Presell']) {
			$prod_avail = false;
			$icon_name = "coming-soon";
			$icon_title = "Coming Soon";
		}

		$product->available = [
			'avail' => $prod_avail,
			'icon_name' => $icon_name,
			'icon_title' => $icon_title,
		];
	}

	public static function getLowestPriceProductByCode($user_info, $prod_code, $excludeDisabled = true, $spresell = 0)
	{
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');
		$db = \Config\Database::connect();

		$query = "SELECT 
						p.*, type, 
						(CASE WHEN (prod_rrp * prod_uos > 0) 
							THEN ROUND((1-((prod_sell * (1.00 + (CASE WHEN vat_code='A' THEN 0.2 WHEN vat_code='C' THEN 0.05 ELSE 0 END))) / (prod_rrp * prod_uos)))*100, 1)
							ELSE 0   
						END)  AS por, 
						 MIN(prod_sell) as prod_sell, 
						pi.url as image_url, 
						pi.version as image_version, 
						vat.rate as vat_rate 
          		  FROM epos_product as p ";
		$query.= "LEFT JOIN epos_product_images as pi on CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code ";
    	$query.= "LEFT JOIN epos_vat as vat on vat.code=p.vat_code ";
		$query.= "LEFT JOIN epos_categories as ct on p.group_desc = ct.filter_desc ";
		$query.= "WHERE p.branch={$branch} ";
    	if (!empty($organization_id)) {
    		$query.= "AND p.organization_id={$organization_id} ";
		}
		$query.= "AND p.prod_code={$prod_code} ";
		$query.= $spresell ? 
				 "AND price_list='06' " : " AND price_list!='06' ";
		if ($excludeDisabled) {
			$query.= "AND is_disabled='N' ";
		}
		$query.= "AND (price_list = '99999' ";
		if (!empty($user_info)) {
			if (!empty($user_info->price_list001))
				$query .= "OR price_list = '01' ";
			if (!empty($user_info->price_list005))
				$query .= "OR price_list = '05' ";
			if (!empty($user_info->price_list007))
				$query .= "OR price_list = '07' ";
			if (!empty($user_info->price_list009))
				$query .= "OR price_list = '09' ";
			if (!empty($user_info->price_list999))
				$query .= "OR price_list = '999' ";
			if (!empty($user_info->price_list008))
				$query .= "OR price_list = '08' ";
			if (!empty($user_info->price_list010))
				$query .= "OR (price_list = '10' || price_list='06') ";
			if (!empty($user_info->price_list011))
				$query .= "OR price_list = '11' ";
			if (!empty($user_info->price_list012))
				$query .= "OR price_list = '12' ";
		} else {
			$query .= " OR price_list='999'";
		}
		$query.= ") ";
		$query.= "AND prod_sell > 0 ";
		$query.= "GROUP BY p.prod_code ";

		$query = $db->query($query);
		return $query->getNumRows() > 0 ? $query->getRow() : null;
	}

	public static function getCase($product)
	{
		$case = '';

		if (empty($product)) return '';

		if ($product->prod_uos > 1) {
			$case = 'CASE';
		}

		if (
			$product->prod_uos > 1 && $product->prod_uos < 7 && $product->group_desc == 'CIGS CIGARS TOBACCO'
			&& substr($product->prod_pack_desc, -1) != 'G' && substr($product->prod_pack_desc, -1) != 'g'
			&& substr($product->prod_pack_desc, -2) != 'gm' && substr($product->prod_pack_desc, -2) != 'GM'
		) {
			$case = 'HALF';
		}

		return $case;
	}

  public static function genCartLinePosition($person_id, $tblName = 'epos_cart')
  {
    $db = \Config\Database::connect();
    $line_position = 0;

    $query = "SELECT line_position FROM " . $tblName . " WHERE person_id=" . $person_id . " ORDER BY line_position DESC LIMIT 1";
    $query = $db->query($query);
    if ($query->getNumRows() > 0) {
        $row = $query->getRow();
        $line_position = intval($row->line_position) + 1;
    }
    


    return $line_position;
  }
}


?>