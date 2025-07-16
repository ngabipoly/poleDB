<?php
namespace App\Models;

use CodeIgniter\Model;

class CarryCapacityModel extends Model
{
    protected $table = 'tbl_carry_capacity';
    protected $primaryKey = 'carryCapacityId';
    protected $allowedFields = ['carryCapacityId', 'capacityLabel','CapacityDescription', 'capacityAddDt', 'capacityAddBy', 'capacityIsDeleted','capacityDeletedDt','capacityModifyBy','capacityIsDeleted','capacityDeleteBy','capacityModifyDt','carryType'];
    protected $useTimestamps = true;
    protected $createdField = 'capacityAddDt';
    protected $updatedField = 'capacityModifyDt';
    protected $deletedField = 'capacityDeletedDt';
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'carryType' => 'required|integer',
        'capacityLabel' => 'required|min_length[3]|max_length[15]|is_unique[tbl_carry_capacity.capacityLabel]',
    ];
    protected $validationMessages = [
        'capacityLabel' => [
            'required' => 'Capacity Label is required.',
            'min_length' => 'Capacity Label must be at least 3 characters.',
            'max_length' => 'Capacity Label must not exceed 15 characters.',
            'is_unique' => 'Capacity Label already exists.',
        ],
        'carryType' => [
            'required' => 'Please select a Carry Type.',
            'integer' => 'Please select a valid Carry Type.',
        ],

    ];  

    protected $skipValidation = false;
    protected $returnType = 'object';


    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = ['beforeDelete'];
    protected $afterDelete = [];

    public function beforeInsert(array $data) {
        $data['data']['capacityAddDt'] = date('Y-m-d H:i:s');
        $data['data']['capacityModifyDt'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function beforeUpdate(array $data) {
        $data['data']['capacityModifyDt'] = date('Y-m-d H:i:s');
        return $data;
    }
    public function beforeDelete(array $data) {
        $data['data']['capacityIsDeleted'] = 1;
        $data['data']['capacityDeletedDt'] = date('Y-m-d H:i:s');
        return $data;
    }
}
?>
