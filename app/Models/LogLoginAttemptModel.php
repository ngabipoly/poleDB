<?php
namespace App\Models;
use CodeIgniter\Model;
class LogLoginAttemptModel extends Model
{
    protected $table = 'login_audit';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_pf', 'ip_address', 'user_agent', 'timestamp','reason', 'logout_time', 'status'];

    //validation rules
    protected $validationRules = [
        'user_pf' => 'required|alpha_numeric',
        'ip_address' => 'required|valid_ip',
        'user_agent' => 'required|string',
        'timestamp' => 'required|valid_date',
        'reason' => 'permit_empty|string',
        'logout_time' => 'permit_empty|valid_date',
        'status' => 'required|in_list[success,failed]'
    ];

    protected $validationMessages = [
        'user_pf' => [
            'required' => 'PF Number is required.',
            'alpha_numeric' => 'PF Number must be alphanumeric.'
        ],
        'ip_address' => [
            'required' => 'IP Address is required.',
            'valid_ip' => 'Invalid IP Address format.'
        ],
        'user_agent' => [
            'required' => 'User Agent is required.',
            'string' => 'User Agent must be a string.'
        ],
        'timestamp' => [
            'required' => 'Timestamp is required.',
            'valid_date' => 'Invalid date format for timestamp.'
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be either success or failed.'
        ]
    ];

    protected $beforeInsert = ['setTimestamp'];

    protected function setTimestamp(array $data)
    {
        if (!isset($data['data']['timestamp'])) {
            $data['data']['timestamp'] = date('Y-m-d H:i:s');
        }
        return $data;
    }
}