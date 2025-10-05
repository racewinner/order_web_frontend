<?php
namespace App\Models;
use App\Models\Employee;
use CodeIgniter\Model;
use App\Models\Admin;

class UnknownProduct extends Model
{
    protected $table            = 'epos_api_products';
    protected $primaryKey       = 'id';

    function get_all_products($account) {
        $db = \Config\Database::connect();
        $conditions = array('account' => $account);
        $query = $db->table($this->table)->where($conditions)->get();
        return $query->getResult();
    }

    function delete_product($id) {
        $db = \Config\Database::connect();
        $conditions = array('id' => $id);
        $query = $db->table($this->table)->where($conditions)->delete();
        if($query) {
            $affectedRows = $db->affectedRows();
            return $affectedRows > 0;
        } else {
            return false;
        }
    }
}