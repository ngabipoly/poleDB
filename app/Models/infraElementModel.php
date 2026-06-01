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
        'utel_owned',
        'leasor_id',
        'usageStartDate',
        'usageEndDate',
        'notes',
        'elmAddedBy',
        'elmCreatedAt',
        'elmModifiedBy',
        'elmModifiedDate',
        'isElmDeleted',
        'elmDeletedDate',
        'elmDeletedBy',
        'poleType',
        'poleSize',
        'olteTypeId',
        'manholeWidth',
        'manholeDepth',
        'manholeLength',
        'manholeDiameter',
        'manholeLocation',
        'accessRestriction',
        'coverType',
        'operatingStatus',
        'constructionMaterial',
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
        'elmCode' => "required|is_unique[tbl_infra_element.elmCode,elmId,{elmId}]",
        'elmType' => 'required|in_list[Manhole, Pole, OLTE,Building]',
        'elmCondition' => 'required|in_list[Good, Re-used, Damaged, stolen]',
        'latitude' => 'required|decimal',    
        'longitude' => 'required|decimal',
        'district' => 'required|integer',
        'poleType' => 'permit_empty|integer',
        'poleSize' => 'permit_empty|integer',
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
        'utel_owned' => 'required|in_list[Y,N]',
         'leasor_id' => 'permit_empty|integer',
         'usageStartDate' => 'permit_empty|valid_date',
         'usageEndDate' => 'permit_empty|valid_date|after_or_equal[usageStartDate]',
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
        ],
        'utel_owned' => [
            'required' => 'Please specify if the element is owned by UTEL.',
            'in_list' => 'Invalid value for UTEL ownership. Please select either Y or N.'
        ],
        'leasor_id' => [
            'integer' => 'Invalid leasor selection.'
        ],
        'usageStartDate' => [
            'valid_date' => 'The usage start date must be a valid date.'
        ],
        'usageEndDate' => [
            'valid_date' => 'The usage end date must be a valid date.',
            'after_or_equal' => 'The usage end date must be after or equal to the usage start date.'
        ],
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

    public function getInfraCarryData(array $conditions = [], array $conditionsNot = []): array
    {
        $builder = $this->select([
            'elm.elmId',
            'elm.elmCode',
            'elm.elmCondition',
            'elm.elmType',
            'elm.latitude',
            'elm.longitude',
            'srcElement.latitude as srcElementLat',
            'srcElement.longitude as srcElementLong',
            'srcElement.elmType as srcElementType',
            'concat_ws(" - ", ct.carryTypeName, cc.capacityLabel) as cableInfo',
            'carryDistance'
        ])
            ->from('tbl_infra_element elm')
            ->join('tbl_infra_carrying', 'tbl_infra_carrying.carryElement = tbl_infra_element.elmId and tbl_infra_carrying.carryIsDeleted = 0 and ', 'left')
            ->join('tbl_infra_element srcElement', 'srcElement.elmId = tbl_infra_carrying.carrySource', 'left')
            ->join('tbl_carrying_types ct', 'ct.carryTypeId = tbl_infra_carrying.carryingType', 'left')
            ->join('tbl_carry_capacity cc', 'cc.carryCapacityId = tbl_infra_carrying.carryCapacity', 'left');

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

        return $builder
            ->groupBy('tbl_infra_carrying.carryId')
            ->orderBy('tbl_infra_carrying.carryAddDt', 'DESC')
            ->findAll();
    }

    public function getLinkageData(array $conditions, string $direction ='upstream'): array
    {
        $builder  = $this->db->table('tbl_infra_element');
        $streamJoin = $direction === 'upstream' ? '`tic`.`carryElement`' : '`tic`.`carrySource`';
        $this->select([
                'elmId',
                'elmCode',
                'elmCondition',
                'elmType',
                'latitude',
                'longitude',
                'tic.carryId',
                'ct.carryTypeName',
                'cc.capacityLabel',
                'tic.carryDistance as distance',
                'd.districtName as district',
                'r.RegionName as region'
            ])
            ->join('tbl_infra_carrying tic', "$streamJoin = elmId AND tic.carryIsDeleted = 0")
            ->join('tbl_carrying_types ct', 'ct.carryTypeId = tic.carryingType')
            ->join('tbl_carry_capacity cc', 'cc.carryCapacityId = tic.carryCapacity')
            ->join('tbldistrict d', 'd.districtId = district')
            ->join('region r', 'r.RegionId = d.region_id');

        foreach ($conditions as $key => $value) {
            $this->where($key, $value);
        }      
        // Log final query
        //log_message('debug', (string) $this->getCompiledSelect());

        return $this->findAll();
    }

    public function getInfraElementById(int $id): array
    {
        return $this->select(['elmCode',
            'elmCondition',
            'elmType',
            'TypeName poleType',
            'SizeLabel poleSize',
            'latitude',
            'longitude',
            'manholeDepth',
            'manholeDiameter',
            'manholeLength',
            'manholeWidth',
            'constructionMaterial',
            'accessRestriction',
            'coverType',
            'manholeLocation',
            'operatingStatus',
            'd.districtName as district',
            'r.RegionName as region',
            'concat_ws(" ", user.lastname, user.firstname) as createdBy',
            'elmCreatedAt as createdAt'
        ]
        )
            ->join('tbldistrict d', 'd.districtId = district', 'left')
            ->join('region r', 'r.RegionId = d.region_id', 'left')
            ->join('tbl_pole_types pt', 'pt.typeId = poleType', 'left')
            ->join('tbl_polesize ps', 'ps.poleSizeId = poleSize', 'left')
            ->join('tb_users user', 'user.user_pf = elmAddedBy', 'left')
            ->where('elmId', $id)
            ->first();
    }


}