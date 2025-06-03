<?php

namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'tbldistrict';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'region_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useSoftDeletes = false;

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'region_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'The district name is required.',
            'min_length' => 'The district name must be at least 3 characters long.',
            'max_length' => 'The district name cannot exceed 255 characters.'
        ],
        'region_id' => [
            'required' => 'Please select a region for the district.',
            'integer' => 'Invalid region ID provided.'
        ]
    ];

    /**
     * Retrieve all districts
     *
     * @return array
     */
    public function getAllDistricts($regionId = null)
    {
        if ($regionId !== null) {
            return $this->where('region_id', $regionId)->findAll();
        }
         // If no region ID is provided, return all districts
        return $this->findAll();
    }

    /**
     * Retrieve district by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getDistrictById($id)
    {
        return $this->find($id);
    }

    /**
     * Insert a new district
     *
     * @param array $data
     * @return bool
     */
    public function insertDistrict(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update a district
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateDistrict($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a district
     *
     * @param int $id
     * @return bool
     */
    public function deleteDistrict($id)
    {
        return $this->delete($id);
    }
}

