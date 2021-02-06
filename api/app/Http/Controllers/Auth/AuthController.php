<?php

namespace App\Http\Controllers\Auth;

use App\Models\PersonModel;
use App\Http\Controllers\Auth\JwtAuth;

class AuthController
{
    protected $jwt;
    protected $model;

    public function __construct(){
        $this->model = new PersonModel();
        $this->jwt   = new JwtAuth();
    }

    public function login($email, $password){

        $user = $this->model->where('email', $email)->first()->toArray();

        if(empty($user))
            return false;

        $pwdHash = $user['password'];
        $token = $status = '';
        if (password_verify($password, $pwdHash)) {
            $token = $this->jwt->makeToken($user);
            $status = true;
        } else {
            return false;
        }

        return [
            'user'   => $user,
            'token'  => $token,
            'status' => $status,
        ];
    }

}