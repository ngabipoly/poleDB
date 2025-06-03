<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleRightsModel extends Model
{
    protected $table      = 'tb_role_rights';
    protected $primaryKey = 'assignment_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['assign_type','menu_id','entity_id','assigned_by'];

        // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'creation_date';
    protected $updatedField  = 'modified_date';
    protected $deletedField  = 'delete_date';

}