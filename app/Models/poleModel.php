<?php
namespace App\Models;

use CodeIgniter\Model;

class PoleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblpole';
    protected $primaryKey       = 'PoleId';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','PoleCode','sizeId','longitude','latitude','district_id', 'pole_type','pole_condition','created_at','updated_at','deleted_at', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted'];


    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'PoleCode' => 'required|min_length[13]|max_length[13]',
        'longitude' => 'required|decimal',
        'latitude' => 'required|decimal',
        'district_id' => 'required|integer',
        'sizeId' => 'required|integer',
        'pole_condition' => 'required|in_list[good,fair,poor]'
    ];
    protected $validationMessages   = [
        'PoleCode' => [
            'required' => 'The pole code is required.',
            'min_length' => 'The pole code must be at least 13 characters long.',
            'max_length' => 'The pole code cannot exceed 13 characters.'
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
        'sizeId' => [
            'required' => 'Please select a pole Size.',
            'integer' => 'Invalid Type for size ID provided.'
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
