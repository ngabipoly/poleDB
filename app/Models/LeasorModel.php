<?php
namespace App\Models;
use CodeIgniter\Model;

class LeasorModel extends Model
{
    protected $table = 'tbl_leasor';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'contact_person', 'contact_number', 'contact_email', 'contract_start_date', 'contract_end_date'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat = 'datetime';
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';
    protected $validationRules = [
        'id' => 'permit_empty|integer',
        'name' => 'required|is_unique[tbl_leasor.name,id,{id}]',
        'contract_start_date' => 'required|valid_date',
        'contract_end_date' => 'required|valid_date|after_or_equal[contract_start_date]',
        'contact_person' => 'required',
        'contact_number' => 'required|is_unique[tbl_leasor.contact_number,id,{id}]',
        'contact_email' => 'required|valid_email|is_unique[tbl_leasor.contact_email,id,{id}]',
    ];
    protected $validationMessages = [
        'id' => [
            'integer' => 'Invalid Type for leasor ID provided.'
        ],
        'name' => [
            'is_unique' => 'Leasor name already exists.',
            'required' => 'Leasor name is required.',
        ],
        'contact_number' => [
            'is_unique' => 'Contact number already exists.',
            'required' => 'Contact number is required.',
        ],
        'contact_email' => [
            'is_unique' => 'Email already exists.',
            'required' => 'Email is required.',
            'valid_email' => 'Please provide a valid email address.',
        ],
        'contract_end_date' => [
            'after_or_equal' => 'Contract end date must be after or equal to contract start date.',
            'valid_date' => 'Please provide a valid contract end date.',
            'required' => 'Contract end date is required.',
        ],
        'contract_start_date' => [
            'valid_date' => 'Please provide a valid contract start date.',
            'required' => 'Contract start date is required.',
        ],
        'contact_person' => [
            'required' => 'Contact person is required.',
        ],
    ];
}