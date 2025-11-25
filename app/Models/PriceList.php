<?php
namespace App\Models;

use CodeIgniter\Model;


class PriceList extends Model
{
	protected $table            = 'epos_pricelists';
    
    function get_all()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table($this->table)
            ->orderBy('priority', 'ASC');
        $result = $query->get();

        $data = [];
        foreach($result->getResult() as $pl) {
            // Return all fields as an array
            $data[$pl->id] = (array) $pl;
        }

        return $data;
    }
}