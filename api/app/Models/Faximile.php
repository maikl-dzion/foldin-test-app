<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Faximile extends Model {

    protected $table = 'document';

    public function documentsAll() {
        $data = DB::table('document')
            ->select('*',
                    'document.id        AS doc_id',
                    'document.create_at AS doc_create_at',
                    'document.path      AS doc_path',
                    'person.id          AS user_id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            ->leftJoin('person' , 'document.person_id' , '=', 'person.id')
            ->leftJoin('company', 'person.company_id', '=', 'company.id')
            ->get();
        return $this->inArray($data);
    }

    public function documentsPerson($userId = 0) {

        $data = DB::table('document')
            ->select('*',
                      'document.id        AS doc_id',
                      'document.create_at AS doc_create_at',
                      'document.path      AS doc_path',
                      'person.id          AS user_id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            ->join('person' , 'document.person_id' , '=', 'person.id')
            ->leftJoin('company', 'person.company_id', '=', 'company.id')
            ->where('person.id', $userId)
            ->where('person.role',  3)
            ->get();
        // lg($data);
        return $this->inArray($data);
    }

    public function userInfoDocList($userId) {

        $data = DB::table('document')
            ->select('*',
                'document.id        AS doc_id',
                'document.create_at AS doc_create_at',
                'document.path      AS doc_path',
                'person.id          AS user_id')
            ->join('userinfo_doctype', 'document.user_infotype_id', '=', 'userinfo_doctype.id')
            ->join('person' , 'document.person_id' , '=', 'person.id')
            //->leftJoin('company', 'person.company_id', '=', 'company.id')
            ->where('person.id', $userId)
            ->where('person.role',  3)
            ->get();
        return $this->inArray($data);
    }

    public function documentsCompany($companyId = 0) {

        $data = DB::table('company')
            ->select('*',
                'document.id        AS doc_id',
                'document.create_at AS doc_create_at',
                'document.path      AS doc_path',
                'person.id          AS user_id')
            ->join('person' , 'company.id' , '=', 'person.company_id')
            ->join('document', 'person.id', '=', 'document.person_id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            ->where('company.id',  $companyId)
            ->get();
        // lg($data);
        return $this->inArray($data);
    }

    public function getCompanyList($role = 0){
        $data = DB::select('SELECT
                                    d.id as doc_id
                                   ,p.id as user_id
                                   ,c.id as company_uid
                                   ,c.phone as c_phone
                                   ,c.email as c_email
                                   ,*

                                 FROM company AS c
                                 LEFT JOIN person   AS p ON p.company_id = c.id
                                 LEFT JOIN document AS d ON d.person_id  = p.id
                                 LEFT JOIN doctype  AS t ON t.id = d.doctype_id
                                 WHERE c.com_type = 2');
        // lg($data);
        return $data;
    }

    public function getPersonList($role = 0){
        $data = DB::select('SELECT 
                                    p.id user_id
                                   ,d.id doc_id
                                   ,c.id company_uid
                                   
                                   ,p.phone as p_phone
                                   ,p.email as p_email
                                   ,*
                                   
                                 FROM person AS p
                                 LEFT JOIN company  AS c ON c.id = p.company_id 
                                 LEFT JOIN document AS d ON d.person_id = p.id
                                 LEFT JOIN doctype  AS t ON t.id = d.doctype_id
                                 WHERE p.role = :role', ['role' => 3]);
        return $data;
    }

    public function getPerson($param, $fieldName = 'id') {
        $data = DB::table('person')
            ->where('person.' .$fieldName, $param)
            ->get();
        return $this->inArray($data, true);
    }

    public function findPerson($value, $fieldName = 'id') {
        $data = DB::table('person')
              ->where('person.' .$fieldName, $value)->get();
        // lg($data->toArray());
        return $this->inArray($data, true);
    }

    public function findCompany($value, $fieldName = 'id') {
        $data = DB::table('company')
                ->where('company.' .$fieldName, $value)->get();
        return $this->inArray($data, true);
    }

    public function findDocument($value, $fieldName = 'id') {
        $data = DB::table('document')
            ->where('document.' .$fieldName, $value)->get();
        return $this->inArray($data, true);
    }

    public function querySelect($query) {
        $data = DB::select($query);
        return $data;
    }

    public function oneTableData($tableName = 'persone', $where = false) {
        if(!$where)
           $data = DB::table($tableName)->select('*')->get();
        else {
           $data = DB::table($tableName)
                    ->where($where)
                    ->get();
        }
        return $this->inArray($data);
    }

    public function addItem($tableName, $data) {
        $r = DB::table($tableName)->insert($data);
        return array('save_result' => $r);
    }

    public function editItem($tableName, $data, $where) {
        // lg(array($tableName, $data, $where));
        $r = DB::table($tableName)
            ->where($where[0], $where[1])
            ->update($data);
        return array('save_result' => $r);
    }

    public function deleteItem($tableName, $where) {
        $r = DB::table($tableName)
            ->where($where[0], $where[1], $where[2])
            ->delete();
        return array('save_result' => $r);
    }

    private function inArray($data, $one = false)  {
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