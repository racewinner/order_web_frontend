<?php
namespace App\Models;
use CodeIgniter\Model;
class Payment extends Model
{
	protected $table            = 'epos_payments';
    protected $primaryKey       = 'id';

    protected $allowedFields = ['order_id', 'amount', 'description', 'person_id', 'status', 'vps_tx_id', 'tx_auth_no',
        'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}