<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Traits\ModelTrait;

class Branch extends Model
{
    use ModelTrait;

    protected $table            = 'epos_branches';

    function get_all_branches() 
    {
        $branches = Branch::findAll();
        return $branches;
    }
}