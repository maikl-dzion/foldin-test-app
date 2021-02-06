<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model {

    protected $table;

    public function findUser($param, $fieldName = 'id') {
        $data = DB::table('users')
                 ->select('users.id AS user_id', '*')
                 ->where('person.' .$fieldName, $param)->get();
        return $this->inArray($data, true);
    }


    public function querySelect($query) {
        $data = DB::select($query);
        return $data;
    }

    public function getTableData($where = false, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        if(!$where)
           $data = DB::table($tableName)->select('*')->get();
        else {
           $data = DB::table($tableName)
                    ->where($where)
                    ->get();
        }
        return $this->inArray($data);
    }

    public function addItem($data, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)->insert($data);
        return array('save_result' => $r);
    }

    public function editItem($data, $where, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)
            ->where($where[0], $where[1])
            ->update($data);
        return array('save_result' => $r);
    }

    public function deleteItem($where, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)
            ->where($where[0], $where[1], $where[2])
            ->delete();
        // DB::table('users')->where('votes', '>', 100)->delete();
        return array('save_result' => $r);
    }

    protected function inArray($data, $one = false)  {
        $data = $data->toArray();
        if(empty($data))
            return array();
        if($one)
            $data = $data[0];
        return (array)$data;
    }

}

// $users = DB::select('select * from users where active = ?', [1]);
// DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
// $affected = DB::update('update users set votes = 100 where name = ?', ['John']);
// $deleted = DB::delete('delete from users');
// $users = DB::table('users')->select('name', 'email as user_email')->get();
