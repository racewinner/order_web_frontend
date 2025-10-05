<?php
namespace App\Models;

use CodeIgniter\Model;


class PriceList extends Model
{
	protected $table            = 'epos_pricelists';
    
    function get_all()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table($this->table);
        $result = $query->get();

        $data = [];
        foreach($result->getResult() as $pl) {
            $data[$pl->id] = [
                'ribbon_label' => $pl->ribbon_label,
                'promo_page' => intval($pl->promo_page),
                'ribbon_colour' => $pl->ribbon_colour
            ];
        }

        return $data;
    }
}