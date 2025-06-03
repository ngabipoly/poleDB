<?php
namespace App\Models;

use CodeIgniter\Model;

class PoleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'poles';
    protected $primaryKey       = 'pole_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','code','longitude','latitude','district_id', 'pole_type','pole_condition','created_at','updated_at','deleted_at', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted'];


    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'code' => 'required|alpha_numeric|min_length[3]|max_length[50]',
        'longitude' => 'required|decimal',
        'latitude' => 'required|decimal',
        'district_id' => 'required|integer',
        'pole_type' => 'required|in_list[wooden,metal,concrete]',
        'pole_condition' => 'required|in_list[good,fair,poor]'
    ];
    protected $validationMessages   = [
        'code' => [
            'required' => 'The pole code is required.',
            'alpha_numeric' => 'The pole code may only contain letters and numbers.',
            'min_length' => 'The pole code must be at least 3 characters long.',
            'max_length' => 'The pole code cannot exceed 50 characters.'
        ],
        'longitude' => [
            'required' => 'The longitude is required.',
            'decimal' => 'The longitude must be a valid decimal number.'
        ],
        'latitude' => [
            'required' => 'The latitude is required.',
            'decimal' => 'The latitude must be a valid decimal number.'
        ],
        'district_id' => [
            'required' => 'Please select a district for the pole.',
            'integer' => 'Invalid Type for district ID provided.'
        ],
        'pole_type' => [
            'required' => 'The pole type is required.',
            'in_list' => 'The pole type must be one of: wooden, metal, concrete.'
        ],
        'pole_condition' => [
            'required' => 'Please select a pole condition.',
            'in_list' => 'The pole condition must be one of: good, fair, poor.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setTimestamps'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];




    protected function setTimestamps(array $data)
    {
        $currentDate = date('Y-m-d H:i:s');
        $data['data']['created_at'] = $currentDate;
        $data['data']['updated_at'] = $currentDate;
        return $data;
    }
}
