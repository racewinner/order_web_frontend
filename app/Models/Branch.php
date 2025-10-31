<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Traits\ModelTrait;
use Config\Database;

class Branch extends Model
{
    use ModelTrait;

    protected $table            = 'epos_branches';

    function get_all_branches() 
    {
        $branches = Branch::findAll();
        return $branches;
    }

    function get_allocated_branches() 
    {
        $person_id = session()->get('person_id');

        $Employee = new Employee();
        $person_info = $Employee->get_info($person_id);

        $person_branch_ids = array_map('intval', $person_info->branches);

        $db = \Config\Database::connect();
        $builder = $db->table('epos_branches');
        $builder->whereIn('id', $person_branch_ids);
        $builder->orderBy('site_name', 'asc');
        $allocated_branches = $builder->get()->getResult();

        return $allocated_branches;
    }

    function getBranchNameById($id)
    {
        $branch = Branch::find($id);
        return $branch['site_name'];
    }
}