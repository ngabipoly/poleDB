<?php

namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model
{
    protected $table = 'region';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'deleted'];
    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'code' => 'required|alpha_numeric|min_length[2]|max_length[10]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'The region name is required.',
            'min_length' => 'The region name must be at least 3 characters long.',
            'max_length' => 'The region name cannot exceed 255 characters.'
        ],
        'code' => [
            'required' => 'The region code is required.',
            'alpha_numeric' => 'The region code may only contain letters and numbers.',
            'min_length' => 'The region code must be at least 2 characters long.',
            'max_length' => 'The region code cannot exceed 10 characters.'
        ]
    ];

    /**
     * Retrieve all regions
     *
     * @return array
     */
    public function getAllRegions()
    {
        return $this->findAll();
    }

    /**
     * Retrieve region by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getRegionById($id)
    {
        return $this->find($id);
    }

    /**
     * Insert a new region
     *
     * @param array $data
     * @return bool
     */
    public function insertRegion(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update a region
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateRegion($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a region
     *
     * @param int $id
     * @return bool
     */
    public function deleteRegion($id)
    {
        return $this->delete($id);
    }
}

