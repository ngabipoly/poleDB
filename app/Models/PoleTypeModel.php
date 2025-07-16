<?php
namespace App\Models;
use CodeIgniter\Model;
class PoleTypeModel extends Model
{
    protected $table = 'tbl_pole_types';
    protected $primaryKey = 'TypeId';
    protected $allowedFields = ['TypeName', 'TypeDesc'];

    protected $validationRules    = [
        'TypeName' => 'required|is_unique[tbl_pole_types.TypeName]'
    ];
    protected $validationMessages = [
        'TypeName' => [
            'is_unique' => 'Pole Type already exists.',
            'required' => 'Pole Type is required.'
        ]
    ];
    protected $skipValidation     = false;
}