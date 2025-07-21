<?php
namespace App\Models;
use CodeIgniter\Model;

class InfraCarryModel extends Model
{
    protected $table = 'tbl_infra_carrying';
    protected $primaryKey = 'carryId';
    protected $allowedFields = [ 'carryElement', 'carryingType', 'sourceType', 'carrySource', 'carryAddDt', 'carryAddBy', 'carryModifyDt', 'carryModifyBy', 'carryIsDeleted', 'carryDeletedDt', 'carryDeleteBy'];
    protected $useTimestamps = true;
    protected $createdField = 'carryAddDt';
    protected $updatedField = 'carryModifyDt';
    protected $deletedField = 'carryDeletedDt';
    protected $useSoftDeletes = true;
    protected $validationRules = [
        'carryElement' => 'required|integer',
        'carryingType' => 'required|integer',
        'sourceType' => 'required|in_list[Pole, OLTE, Manhole, Building]',
        'carrySource' => 'required|integer',
    ];
    protected $validationMessages = [
        'carryElement' => [
            'required' => 'Element is required.',
            'integer' => 'Please select a valid element.',
        ],
        'carryingType' => [
            'required' => 'Carrying Type is required.',
            'integer' => 'Please select a valid carrying type.',
        ],
        'sourceType' => [
            'required' => 'Source Type is required.',
            'in_list' => 'Invalid Source Type selected.',
        ],
        'carrySource' => [
            'required' => 'Source Element is required.',
            'integer' => 'Please select a valid source element.',
        ],
    ];
    protected $skipValidation = false;
    protected $returnType = 'object';
    protected $allowCallbacks = true;

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $beforeDelete = ['beforeDelete'];

    protected function beforeInsert(array $data): array
    {
        // Perform actions before inserting a record
        $data['data']['carryAddDt'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data): array
    {
        // Perform actions before updating a record
        $data['data']['carryModifyDt'] = date('Y-m-d H:i:s');
        return $data;
    }
    protected function beforeDelete(array $data): array
    {
        // Perform actions before deleting a record
        $data['data']['carryIsDeleted'] = 1;
        $data['data']['carryDeletedDt'] = date('Y-m-d H:i:s');
        return $data;
    }
}
