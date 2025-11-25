<?php

namespace App\Models;

use CodeIgniter\Model;

class Promo extends Model
{
    protected $table = 'epos_product';
    protected $primaryKey = 'prod_id';
    protected $useTimestamps = false;
    protected $allowedFields = [];

    /**
     * Get featured products
     * 
     * @param array $where_arr Array of product codes
     * @return array|null
     */
    public function get_featured($where_arr)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('epos_product');
        $builder->select('prod_id,prod_code,prod_desc,price_list,prod_sell');
        $builder->where('price_list', '999');
        $builder->whereIn('prod_code', $where_arr);
        $builder->orderBy('prod_code', 'ASC');
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            $data = [];
            foreach ($query->getResult() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        
        return null;
    }
  
    /**
     * Get total items in cart for a person
     * 
     * @param int $person_id
     * @return int
     */
    public function get_total_items_cart($person_id)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('epos_cart');
        $builder->where('person_id', $person_id);
        $query = $builder->get();
        
        $total_quantity = 0;
        foreach ($query->getResult() as $res) {
            $total_quantity += $res->quantity;
        }
        
        return $total_quantity;
    }
}