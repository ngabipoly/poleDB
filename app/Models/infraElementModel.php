<?php
namespace App\Models;

use CodeIgniter\Model;

class InfraElementModel extends Model
{
    protected $user;
    protected $table      = 'tbl_infra_element';
    protected $primaryKey = 'elmId';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'elmId',
        'elmCode',
        'elmType',
        'elmCondition',
        'district',
        'latitude',
        'longitude',
        'notes',
        'elmAddedBy',
        'elmCreatedAt',
        'elmModifiedBy',
        'elmModifiedDate',
        'isElmDeleted',
        'elmDeletedDate',
        'elmDeletedBy',
        'poleTypeId',
        'poleSizeId',
        'olteTypeId',
        'manholeWidth',
        'manholeDepth',
        'manholeLength',
        'manholeDiameter',
        'buildingName',
        'buildingStreet',
        'landlordName',
        'landlordPhone',
        'landlordEmail'
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'elmCreatedAt';
    protected $updatedField  = 'elmModifiedDate';
    protected $deletedField  = 'elmDeletedDate';
    protected $validationRules    = [
        'elmCode' => "required|is_unique[tbl_infra_element.elmCode]",
        'elmType' => 'required|in_list[Manhole, Pole, OLTE,Building]',
        'elmCondition' => 'required|in_list[Good, Re-used, Damaged, stolen]',
        'latitude' => 'required|decimal',    
        'longitude' => 'required|decimal',
        'district' => 'required|integer',
        'poleTypeId' => 'permit_empty|integer',
        'poleSizeId' => 'permit_empty|integer',
        'olteTypeId' => 'permit_empty|integer',
        'manholeWidth' => 'permit_empty|decimal',
        'manholeDepth' => 'permit_empty|decimal',
        'manholeLength' => 'permit_empty|decimal',
        'manholeDiameter' => 'permit_empty|decimal',
        'buildingName' => 'permit_empty',
        'buildingStreet' => 'permit_empty',
        'landlordName' => 'permit_empty',
        'landlordPhone' => 'permit_empty',
        'landlordEmail' => 'permit_empty|valid_email',
        'elmAddedBy' => 'required',
    ];
    protected $validationMessages = [
        'elmCode' => [
            'required' => 'The element code is required.',
            'is_unique' => 'The element code must be unique.'
        ],
        'elmType' => [
            'required' => 'The element type is required.',
            'in_list' => 'The element type must be one of the following: Manhole, Pole, OLTE, Building.'
        ],
        'elmCondition' => [
            'required' => 'The element condition is required.',
            'in_list' => 'The element condition must be one of the following: Good, Re-used, Damaged, Stolen.'
        ],
        'latitude' => [
            'required' => 'The latitude is required.'
        ],
        'longitude' => [
            'required' => 'The longitude is required.'
        ],
        'district' => [
            'required' => 'Please Select a District.',
            'integer' => 'Invalid District Data, Please Select a District.'
        ],
        'manholeWidth' => [
            'decimal' => 'The manhole width must be a number.'
        ],
        'manholeDepth' => [
            'decimal' => 'The manhole depth must be a number.'
        ],
        'manholeLength' => [
            'decimal' => 'The manhole length must be a number.'
        ],
        'manholeDiameter' => [
            'decimal' => 'The manhole diameter must be a number.'
        ],
        'landlordEmail' => [
            'valid_email' => 'The landlord email must be a valid email address.'
        ],
        'elmAddedBy' => [
            'required' => 'Your session seems to have expired. Please login again.'
        ]
    ];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setTimestamps'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setTimestamps'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function __construct($db = null)
    {
        parent::__construct($db);
        $this->user = session()->get('userData');
    }
  

    public function setTimestamps(array $data)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');

            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \RuntimeException('No data provided for insert/update.');
            }

            if (!empty($data['method']) && $data['method'] === 'update') {
                $data['data']['elmModifiedDate'] = $currentDate;
            } else {
                // Typically, insert operation
                $data['data']['elmCreatedAt'] = $currentDate;
            }

            return $data;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error setting timestamps: ' . $e->getMessage());
        }
    }

    public function setDeletedTimestamp(array $data){
        try {
            $currentDate = date('Y-m-d H:i:s');

            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \RuntimeException('No data provided for deletion.');
            }

            $data['data']['elmDeletedDate'] = $currentDate;
            $data['data']['elmDeletedBy'] = $this->user['user_pf'];
            $data['data']['isElmDeleted'] = 1;
            return $data;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error setting deleted timestamp: ' . $e->getMessage());
        }
    }
}