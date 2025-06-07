<?php
namespace App\Models;

use CodeIgniter\Model;

class PoleSizeModel extends Model
{
    protected $table = 'tbl_polesize';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'created_at'];
}