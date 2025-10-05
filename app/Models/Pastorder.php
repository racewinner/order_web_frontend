<?php
namespace App\Models;

use CodeIgniter\Model;


class Pastorder extends Model
{
	protected $table            = 'epos_orders';
    protected $primaryKey       = 'order_id';


	function get_all($user_info, $limit = 30, $offset = 0, $sort_key = 4)
	{
		$db = \Config\Database::connect();

		$db->table('epos_orders');
		if ($user_info->username != 'admin') {
			// $db->where('epos_orders.person_id', $user_info->person_id);
			$query = $db->table('epos_orders');
			$query->where('epos_orders.person_id', $user_info->person_id);
		}
		// $db->join('epos_employees', 'epos_employees.person_id=epos_orders.person_id');
		$query = $db->table('epos_orders');
		$query->join('epos_employees', 'epos_employees.person_id = epos_orders.person_id');

		switch ($sort_key) {
			case 1:
				$query->orderBy("username", "asc");
				break;
			case 2:
				$query->orderBy("username", "desc");
				break;
			case 3:
				$query->orderBy("order_date", "asc");
				break;
			case 4:
				$query->orderBy('order_date', 'desc');
       		 break;
			case 5:
				$query->orderBy("completed", "asc");
				break;
			case 6:
				$query->orderBy("completed", "desc");
				break;
			default:
				$query->orderBy("order_date", "desc");
				break;
		}
		$intVal = intval($offset);
	
		$query->limit($limit);
		$query->offset($intVal);
		return $query->get();
	}

	function count_all($user_info)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_orders');
		if ($user_info->username != 'admin') {
			$query->where('person_id', $user_info->person_id);
		}
		// Use countAllResults() instead of count_all_results()
		return $query->countAllResults();

	}

	function trolley_type($order_id)
	{
		$db = \Config\Database::connect();
		$res = $db->table('epos_orders')
					->where('order_id', $order_id)
					->get()
					->getRow();
		
		return $res->type;
	}

	function get_search_suggestions($user_info, $search, $limit = 30)
	{
		$suggestions = array();
		return $suggestions;
	}

	function get_order_product($order_id, $completed, $cust_info)
	{
		$db = \Config\Database::connect();

		$results = $db->table('epos_orders_products')
			->where('order_id', $order_id)
			->where('quantity != ', 0)
			->get();

		$data = "<tbody>";

		$nCount = 0;
		foreach ($results->getResult() as $res) {
			$product = Product::getLowestPriceProductByCode($cust_info, $res->prod_code);
			if(!$product) $product = Product::getLowestPriceProductByCode($cust_info, $res->prod_code, false);

			if($product) {
				if ($completed == 0) {
					$builder->update(array(
						'price' => $product->prod_sell,
					));
				}
	
				$Admin = new Admin();
				$img_host = $Admin->get_plink('img_host');
				$data .= '<tr><td style="width:15%;"><div>';
				$data .= '<img src="' . $img_host . '/product_images/100px/' . intval(substr($res->prod_code, 1)) . '.jpg" width="48" height="48">';
				$data .= "<span>$res->prod_code</span>";
				$data .= "</div>";
				
				$time = $product->price_end + ((23 * 3600) + 3599);
	
				if ((time() >= $product->price_start && time() <= $time) || $product->price_start == 0) {
					$price = $product->prod_sell;
				} else {
					$price = 'Call For Price';
				}
	
				if ($product->epoints != 0) {
					$data .= '<br /><img src="images/epoints.png" width="35px" style="margin-bottom:-2px; width:35px !important; height:13px !important"> ' . $product->epoints;
				}
				$data .= "</td><td style='width:32%;'>";
				$data .= $product->prod_desc;
				$data .= "</td><td style='width:10%;'>";
				$data .= $product->prod_pack_desc;
				$data .= "</td><td style='width:5%;'>";
				$data .= $product->prod_uos;
				$data .= "</td><td style='width:10%;'>";
				$data .= $price;
				$data .= "</td><td style='width:8%;'>";
				$data .= $res->quantity . "</td>";
				if($product->is_disabled == 'Y') {
					$data .= "<td><div style='background-color:#ff0000; color:white; padding: 3px; border-radius: 3px;'>Unavailable</div></td>";
				} else if ($completed != 0 && $res->presell != 1) {
					$data .= "<td><a href='javascript:void()' onclick='set_qty(this , $product->prod_id, \"$product->prod_code\");'><i class='material-icons adjust'>add_shopping_cart</i></a>";
				}
				$data .= "</tr>";

				$nCount = $nCount + 1;
				if ($nCount > 1)
					$nCount = 0;
			}
		}
		$data .= "</tbody></table>";
		return $data;
	}

	function get_order_completed($order_id)
	{
		$db = \Config\Database::connect();
		$res = $db->table('epos_orders')
					->where('order_id', $order_id)
					->get()
					->getRow();
		return $res->completed;
	}

	function get_order_opened($order_id)
	{
		$db = \Config\Database::connect();
		$res = $db->table('epos_orders')		
				->where('order_id', $order_id)
				->get()
				->getRow();
		return $res->opened;
	}

	/* Modified function to load saved orders in cart
	function set_my_trolley($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id' , $order_id);
		$res = $this->db->get()->row();
		$person_id = $res->person_id;

		$this->db->where('order_id' , $order_id);
		$this->db->update('orders' , array('opened' => 0));

		$this->db->from('orders_products');
		$this->db->where('order_id' , $order_id);
		$results = $this->db->get();

		foreach($results->result() as $res)
		{
			$this->db->from('cart');
			$this->db->where('person_id' , $person_id);
			$this->db->where('prod_id' , $res->prod_id);
			$res1 = $this->db->get();

			if($res1->num_rows() == 0)
			{
				$insert_data = array(
						'prod_id' => $res->prod_id ,
						'quantity' => $res->quantity ,
						'person_id' => $person_id,
						'prod_code'=>$res->prod_code ,
						'prod_uos'=>$res->prod_uos ,
						'start_date'=>$res->start_date ,
						'prod_desc'=>$res->prod_desc ,
						'prod_pack_desc'=>$res->prod_pack_desc ,
						'vat_code'=>$res->vat_code ,
						'prod_price'=>$res->prod_price ,
						'group_desc'=>$res->group_desc ,
						'prod_code1'=>$res->prod_code1 ,
						'price_list'=>$res->price_list ,
						'prod_level1'=>$res->prod_level1 ,
						'prod_level2'=>$res->prod_level2 ,
						'prod_level3'=>$res->prod_level3 ,
						'prod_sell'=>$res->prod_sell ,
						'prod_rrp'=>$res->prod_rrp ,
						'wholesale'=>$res->wholesale ,
						'retail'=>$res->retail ,
						'p_size'=>$res->p_size);
				$this->db->insert('cart' , $insert_data);
			}
			else if($res1->num_rows() == 1)
			{
				$res1_row = $res1->row();
				$quantity = $res1_row->quantity + $res->quantity;
				$insert_data = array('quantity' => $quantity);
				$this->db->where('prod_id' , $res1_row->prod_id);
				$this->db->where('person_id' , $res1_row->person_id);
				$this->db->update('cart' , $insert_data);
			}
			else return -1;

		}

		//$query = "DELETE FROM epos_orders_products WHERE order_id='".$order_id."'";
		//$this->db->query($query);

		return true;
	}*/

	/** backup of old function*/
	function set_my_trolley($order_id)
	{
		$db = \Config\Database::connect();

		$builder = $db->table('epos_orders')
					->where('order_id', $order_id);

		$res0 = $builder->get()
				->getRow();
		$person_id = $res0->person_id;
		$type = $res0->type;

		$builder->where('order_id', $order_id);
		$builder->update(array('opened' => 1));

		$results = $db->table('epos_orders_products')->where('order_id', $order_id)->get();

		foreach ($results->getResult() as $res) {
			$builder2 = $db->table('epos_cart');
			$builder2->where('person_id', $person_id);
			$builder2->where('prod_code', $res->prod_code);
			$builder2->where('presell', '0');
			$res1 = $builder2->get();

			if ($res1->getNumRows() == 0) {
				$insert_data = array(
					'quantity' => $res->quantity,
					'person_id' => $person_id,
					'prod_code' => $res->prod_code,
					'group_type' => $res->group_type,
				);
				$db->table('epos_cart')->insert($insert_data);
			} else if ($res1->getNumRows() == 1) {
				$res1_row = $res1->getRow();
				$quantity = $res1_row->quantity + $res->quantity;
				$insert_data = array('quantity' => $quantity);
				$db->table('epos_cart')
					->where('id', $res1_row->id)
					->update($insert_data);
			} else
				return -1;

		}

		$query = "DELETE FROM epos_orders_products WHERE order_id='" . $order_id . "'";
		$db->query($query);

		return true;
	}

	/** Reuse - Bulk Add Items to Trolley */
	function reuse_trolley($order_id)
	{
		$db = \Config\Database::connect();
		$res0 = $db->table('epos_orders')
					->where('order_id', $order_id)
					->get()
					->getRow();
		$person_id = $res0->person_id;
		$type = $res0->type;

		$results = $db->table('epos_orders_products')->where('order_id', $order_id)->get();

		foreach ($results->getResult() as $res) {
			// Check Cart
			$res1 = $db->table('epos_cart')
						->where('person_id', $person_id)
						->where('prod_code', $res->prod_code)
						->where('presell', '0')
						->get();

			// Check Products
			$res2 = $db->table('epos_product')
						->where('prod_code', $res->prod_code)
						->where('is_disabled', 'N')
						->get();

			if ($res1->getNumRows() == 0 && $res2->getNumRows() != 0) {
				$insert_data = array(
					'prod_code' => $res->prod_code,
					'quantity' => $res->quantity,
					'person_id' => $person_id,
					'group_type' => $res->group_type,
				);
				if ($res->prod_id != "") {
					$db->table('epos_cart')->insert($insert_data);
				}
			} else if ($res1->getNumRows() == 1 && $res2->getNumRows() != 0) {
				$res1_row = $res1->getRow();
				$quantity = $res->quantity;
				$insert_data = array('quantity' => $quantity);

				$db->table('epos_cart')->where('id', $res1_row->id)->update($insert_data);
			} else
				return -1;
		}

		return true;
	}


	function add_to_cart1($prod_code, $quantity, $person_id, $order_id)
	{
		$query = "SELECT * FROM epos_orders_products WHERE order_id='" . $order_id . "' and prod_code='" . $prod_code . "'";
		$res_op = $this->db->query($query);
		if ($res_op->num_rows() == 0 || $res_op->num_rows() > 1)
			return -1;
		$row_op = $res_op->row();

		$this->db->from('product');
		$this->db->where('prod_code', $row_op->prod_code);
		$this->db->where('prod_desc', $row_op->prod_desc);
		$this->db->where('prod_uos', $row_op->prod_uos);
		$this->db->where('prod_sell', $row_op->prod_sell);
		$res_prod = $this->db->get();
		if ($res_prod->num_rows() == 0)
			return -2;
		else if ($res_prod->num_rows() > 1)
			return -3;

		$res_prod_row = $res_prod->row();
		$this->db->from('cart');
		$this->db->where('person_id', $person_id);
		$this->db->where('prod_id', $prod_id);
		$this->db->where('presell', '0');
		$res = $this->db->get();
		if ($res->num_rows() == 0) {
			$insert_data = array(
				'prod_id' => $prod_id,
				'quantity' => $quantity,
				'person_id' => $person_id,
				'prod_code' => $res_prod_row->prod_code,
				'prod_uos' => $res_prod_row->prod_uos,
				'start_date' => $res_prod_row->start_date,
				'prod_desc' => $res_prod_row->prod_desc,
				'prod_pack_desc' => $res_prod_row->prod_pack_desc,
				'vat_code' => $res_prod_row->vat_code,
				'prod_price' => $res_prod_row->prod_price,
				'group_desc' => $res_prod_row->group_desc,
				'prod_code1' => $res_prod_row->prod_code1,
				'price_list' => $res_prod_row->price_list,
				'prod_level1' => $res_prod_row->prod_level1,
				'prod_level2' => $res_prod_row->prod_level2,
				'prod_level3' => $res_prod_row->prod_level3,
				'prod_sell' => $res_prod_row->prod_sell,
				'prod_rrp' => $res_prod_row->prod_rrp,
				'wholesale' => $res_prod_row->wholesale,
				'retail' => $res_prod_row->retail,
				'p_size' => $res_prod_row->p_size
			);
			$this->db->insert('cart', $insert_data);
			return true;
		} else if ($res->num_rows() == 1) {
			$res_row = $res->row();
			$quantity1 = $res_row->quantity + $quantity;
			$insert_data = array('quantity' => $quantity1);
			$this->db->where('person_id', $person_id);
			$this->db->where('prod_id', $prod_id);
			$this->db->where('presell', '0');
			$this->db->update('cart', $insert_data);
			return true;
		} else
			return -4;

		return true;
	}

	function add_to_cart($prod_id, $quantity, $person_id)
	{
		$this->db->from('cart');
		$this->db->where('person_id', $person_id);
		$this->db->where('prod_id', $prod_id);
		$this->db->where('presell', '0');
		$res = $this->db->get();

		if ($res->num_rows() == 0) {
			$insert_data = array('prod_id' => $prod_id, 'quantity' => $quantity, 'person_id' => $person_id, 'presell' => '0');
			$this->db->insert('cart', $insert_data);
		} else if ($res->num_rows() == 1) {
			$res_row = $res->row();
			$quantity1 = $res_row->quantity + $quantity;
			$insert_data = array('quantity' => $quantity1);
			$this->db->where('person_id', $person_id);
			$this->db->where('prod_id', $prod_id);
			$this->db->where('presell', '0');
			$this->db->update('cart', $insert_data);
		} else
			return -1;

		return true;

	}
}
?>