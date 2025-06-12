<?php
namespace App\Models;

use CodeIgniter\Model;

class PoleSizeModel extends Model
{
    protected $table = 'tbl_polesize';
    protected $primaryKey = 'id';
    protected $allowedFields = ['SizeLabel', 'SizeMtrs', 'create_time','date_modified','date_deleted'];
    protected $useTimestamps = true;
    protected $createdField = 'create_time';
    protected $updatedField = 'date_modified';
    protected $deletedField = 'date_deleted';
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'SizeLabel' => 'required|min_length[3]|max_length[15]',
        'SizeMtrs' => 'required|numeric',
    ];
    protected $validationMessages = [
        'SizeLabel' => [
            'required' => 'Pole size label is required.',
            'min_length' => 'Pole size label must be at least 3 characters long.',
            'max_length' => 'Pole size label cannot exceed 15 characters.'
        ],
        'SizeMtrs' => [
            'required' => 'Pole size in meters is required.',
            'numeric' => 'Pole size must be a numeric value.'
        ]
    ];
}