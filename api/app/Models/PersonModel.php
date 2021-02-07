<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonModel extends BaseModel {
    protected $primaryKey = 'id';
    public function __construct(){
        $this->table = 'users';
    }
}