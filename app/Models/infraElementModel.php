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

    public function getElementsGroupedBy(string $column, int $deleteStatus = 0, array $conditions = [], array $conditionsNot = []){
        $builder =  $this->select("$column, COUNT(*) as count")
                    ->join('tbl_pole_types pt', 'tbl_infra_element.poleType = pt.TypeId', 'left')
                    ->join('tbl_polesize ps', 'tbl_infra_element.poleSize = ps.poleSizeId', 'left')
                    ->join('tbldistrict', 'tbl_infra_element.district = tbldistrict.districtId', 'left')
                    ->join('region', 'tbldistrict.region_id = region.RegionId', 'left')
                    ->join('tb_users user', 'user.user_pf = tbl_infra_element.elmAddedBy', 'left')
                    ->where('tbl_infra_element.isElmDeleted', $deleteStatus);
        if (count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $builder->where($key, $value);
            }
        }

        if (count($conditionsNot) > 0) {
            foreach ($conditionsNot as $key => $value) {
                $builder->where($key, $value);
            }
        }

        return $builder->groupBy($column)
                        ->orderBy('count', 'DESC')
                    ->findAll();
    }

    public function countDistinct($column, string $infraType = '')
    {
        $builder = $this->distinct()->select($column)
                    ->where('isElmDeleted', 0);

        if ($infraType) {
            $builder->where('elmType', $infraType);
        }

        return $builder->countAllResults();
    }

    public function getInfrastructure(int $infraId = 0, array $conditions = [], array $conditionsNot = []){
        $builder = $this->select([
                'tbl_infra_element.elmId',
                'tbl_infra_element.elmCode',
                'tbl_infra_element.elmCondition',
                'tbl_infra_element.elmType',
                'tbl_infra_element.latitude',
                'tbl_infra_element.longitude',
                'tbl_infra_element.notes',
                'tbl_infra_element.elmCreatedAt',
                'pt.TypeName as poleType',
                'ps.SizeLabel as poleSize',
                'd.districtName',
                'r.RegionName',
                'concat_ws(" ", user.firstname, user.lastname) as elmAddedBy',
                'COALESCE( 
                    JSON_ARRAYAGG(
                        JSON_OBJECT(
                            \'srcElementId\', srcElement.elmId,
                            \'srcElementCode\', srcElement.elmCode,
                            \'srcElementLat\', srcElement.latitude,
                            \'srcElementLong\', srcElement.longitude,
                            \'srcElementType\', srcElement.elmType,
                            \'cableInfo\', concat_ws(" - ",ct.carryTypeName,cc.capacityLabel)
                        )
                    )
                ) AS carriageCables',
                ])
                    ->join('tbldistrict d', 'd.districtId = tbl_infra_element.district', 'left')
                    ->join('region r', 'r.RegionId = d.region_id', 'left')
                ->join('tb_users user', 'user.user_pf = tbl_infra_element.elmAddedBy', 'left')
                ->join('tbl_pole_types pt', 'pt.TypeId = tbl_infra_element.poleType', 'left')
                ->join('tbl_polesize ps', 'ps.poleSizeId = tbl_infra_element.poleSize', 'left')
                ->join('tbl_infra_carrying ic', 'ic.carryElement = tbl_infra_element.elmId and ic.carryIsDeleted = 0', 'left')
                ->join('tbl_carrying_types ct', 'ct.carryTypeId = ic.carryingType', 'left')
                ->join('tbl_carry_capacity cc', 'cc.carryCapacityId = ic.carryCapacity', 'left')
                ->join('tbl_infra_element srcElement', 'srcElement.elmId = ic.carrySource', 'left');

                if($infraId !== 0) {
                    $builder->where('tbl_infra_element.elmId', $infraId);
                }

                if(count($conditions) > 0) {
                    foreach ($conditions as $key => $value) {
                        $builder->where($key, $value);
                    }
                }

                if(count($conditionsNot) > 0) {
                    foreach ($conditionsNot as $key => $value) {
                        $builder->where($key, $value);
                    }
        }

        return $builder
                        ->groupBy('tbl_infra_element.elmId')
                        ->orderBy('tbl_infra_element.elmCreatedAt', 'DESC')
                        ->findAll();
    }


}