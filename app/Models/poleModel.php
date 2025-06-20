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
    protected $allowedFields    = ['id','PoleCode','sizeId','longitude','latitude','district_id', 'pole_type','pole_condition','created_at','updated_at','deleted_at', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted','deleted_by', 'created_by', 'updated_by'];


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
        'pole_condition' => 'required|in_list[good,damaged,re-used,stolen]'
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
            'in_list' => 'The pole condition must be one of: good, damaged, re-used.'
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
    public function getPolesGroupedBy($column)
    {
        return $this->select("$column, COUNT(*) as count")
                    ->join('tbl_polesize ps', 'tblpole.sizeId = ps.poleSizeId')
                    ->join('tbldistrict', 'tblpole.district_id = tbldistrict.districtId')
                    ->join('region', 'tbldistrict.region_id = region.RegionId')
                    ->groupBy($column)
                    ->orderBy('count', 'DESC')
                    ->findAll();
    }
    public function countDistinct($column)
    {
        return $this->distinct()->select($column)->countAllResults();
    }

    public function getPole(int $poleId = 0, array $conditions = [], array $conditionsNot = [])
    {
        $builder = $this->select([
                'tblpole.PoleId',
                'tblpole.PoleCode',
                'tblpole.latitude',
                'tblpole.longitude',
                'tblpole.pole_condition',
                'tblpole.created_at as pole_created_at',
                'ps.poleSizeId',
                'ps.SizeLabel',
                'd.districtId',
                'd.districtName',
                'r.RegionName',
                'user.firstname',
                'user.lastname'
            ])
            ->join('tbl_polesize ps', 'ps.poleSizeId = tblpole.sizeId')
            ->join('tbldistrict d', 'd.districtId = tblpole.district_id')
            ->join('region r', 'r.RegionId = d.region_id')
            ->join('tb_users user', 'user.user_pf = tblpole.created_by');

        if ($poleId !== 0) {
            $builder->where('tblpole.PoleId', $poleId);
            return $builder->first();
        }

        if(count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $builder->where($key, $value);
            }
        }

        if(count($conditionsNot) > 0) {
            foreach ($conditionsNot as $key => $value) {
                $builder->where($key.' !=', $value);
            }
        }

        return $builder
                ->orderBy('tblpole.created_at', 'DESC')
                ->findAll();
    }
}
