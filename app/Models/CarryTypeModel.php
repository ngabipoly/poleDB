<?php
namespace App\Models;

use CodeIgniter\Model;

class CarryTypeModel extends Model
{
    protected $table = 'tbl_carrying_types';
    protected $primaryKey = 'carryTypeId';
    protected $returnType = 'object';
    protected $allowedFields = ['carryTypeName', 'carryTypeDescription','typeCreatedBy', 'typeCreatedDt', 'TypeDeletedDt','TypeIsDeleted', 'typeUpdatedBy', 'typeUpdatedDt'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'typeCreatedDt';
    protected $updatedField  = 'typeUpdatedDt';
    protected $deletedField  = 'typeDeletedDt';
    protected $validationRules    = [
        'carryTypeName' => 'required|is_unique[tbl_carrying_types.carryTypeName]',
        'typeCreatedBy' => 'required',
    ];
    protected $validationMessages = [
        'carryTypeName' => [
            'required' => 'Carry Type Name is required.',
            'is_unique' => 'Carry Type Name already exists.',
        ],

        'typeCreatedBy' => [
            'required' => 'You could have been logged out. Please login again.',
        ],
    ];

    protected $skipValidation = false;
    protected $protectFields = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $beforeDelete = ['beforeDelete'];

    public function beforeInsert(array $data) {
        $data['data']['typeCreatedDt'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function beforeUpdate(array $data) {
        $data['data']['typeUpdatedDt'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function beforeDelete(array $data) {
        $data['data']['TypeDeletedDt'] = date('Y-m-d H:i:s');
        $data['data']['TypeIsDeleted'] = 1;
        return $data;
    }

}
