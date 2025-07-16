<?php
namespace App\Models;

use CodeIgniter\Model;

class PoleCarryingModel extends Model
{
    protected $table      = 'pole_carrying';
    protected $primaryKey = 'carryId';
    protected $allowedFields = ['carryPole', 'carryingType','sourceType', 'carrySource','carryNotes','carryAddBy','carryAddDt','carryModifyDt','carryModifyBy','carryIsDeleted','carryDeleteDt','carryDeleteBy'];
    protected $timestamps = true;
    protected $useSoftDeletes = true;
    protected $validationRules    = [
        'carryPole'=>'required|integer',
        'carryingType'=>'required|integer',
        'sourceType'=>'required|inlist[Pole,OLTE,Manhole,Building]',
        'carrySource'=>'required|integer',
        'carryAddBy'=>'required|string',
    ];
    protected $validationMessages = [
        'carryPole' => [
            'required' => 'Pole is required',
        ],
        'carryingType' => [
            'required' => 'Please select Carrying Type',
        ],
        'sourceType' => [
            'required' => 'Please select Source Type',
            'inlist' => 'Invalid Source Type Encountered',
        ],
        'carrySource' => [
            'required' => 'Please select Source',
            'integer' => 'Invalid Carrying Source Encountered',
        ],
        'carryAddBy' => [
            'required' => 'You could have been logged out. Please login again.',
            'string' => 'Please check that you are logged in.',
        ],
    ];
    protected $skipValidation     = false;


}