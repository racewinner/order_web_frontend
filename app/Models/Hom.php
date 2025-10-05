<?php
namespace App\Models;
use CodeIgniter\Model;

class Hom extends Model { 

  // we will use the function get_featured
  function get_featured($where_arr){
		  
		// all the queries relating to the data we want to retrieve will go in here
		$this->db->select('prod_id,prod_code,prod_desc,price_list,prod_sell');        // the select statement
		//$this->db->group_by('prod_code');                                           
		$this->db->where('price_list', '999');    
		$this->db->where_in('prod_code', $where_arr);                                 // the 'where' clause
		$this->db->order_by("prod_code", "asc");
		$q = $this->db->get('epos_product');                                          // the table
	 
		// after we've made the queries from the database, we will store them inside a variable called $data, and return the variable to the controller 
		if($q->num_rows() > 0){
		  // we will store the results in the form of class methods by using $q->result(), if  store as an array use $q->result_array()
		  foreach ($q->result() as $row){
			$data[] = $row;
		  }
		  return $data;
		}
  }
  
  	function get_total_items_cart($person_id, $type='general', $presell=0){
		$db = \Config\Database::connect();

		$query = "SELECT * FROM epos_cart WHERE person_id='".$person_id."' and group_type='".$type."' and presell='".$presell."'";
		
		$results = $db->query($query);
		$total_quantity = 0;
		$total_amount = 0;
		foreach($results->getResult() as $res){
		   $total_quantity += $res->quantity;
		}
		return $total_quantity;
	}
}