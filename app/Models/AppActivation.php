<?php

namespace App\Models;

use CodeIgniter\Model;

class AppActivation extends Model
{
    protected $table = 'app_activation';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_serial',
        'user_activation',
        'client_ref',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Find activation record by user_serial, user_activation, and client_ref
     * 
     * @param string $user_serial
     * @param string $user_activation
     * @param string $client_ref
     * @return array|null
     */
    public function findByCredentials($user_serial, $user_activation, $client_ref)
    {
        return $this->where('user_serial', $user_serial)
                    ->where('user_activation', $user_activation)
                    ->where('client_ref', $client_ref)
                    ->first();
    }

    /**
     * Check if activation credentials are valid
     * 
     * @param string $user_serial
     * @param string $user_activation
     * @param string $client_ref
     * @return bool
     */
    public function isValid($user_serial, $user_activation, $client_ref)
    {
        $activation = $this->findByCredentials($user_serial, $user_activation, $client_ref);
        return $activation !== null;
    }
}

