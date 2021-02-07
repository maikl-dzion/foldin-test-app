<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Auth\JwtAuth;

class UserController extends Controller
{
    protected $model;

    public function __construct(){
        $this->model = new User();
        $this->jwt   = new JwtAuth();
    }

    // Регистрация пользователя
    public function userCreate($request){

        if(!empty($request['password']))
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);

        try {
            $result = $this->model->insert($request);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return ['save_result' => $result];
    }

    public function login($email, $password){

        $user = $this->model->where('email', $email)->first()->toArray();

        if(empty($user))
            return false;

        $pwdHash = $user['password'];
        $token = $status = null;
        if (password_verify($password, $pwdHash)) {
            $token = $this->jwt->createToken($user);
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

    public function userCheckAccessToken($token) {
        $result = $this->jwt->verifyToken($token);
        if (!empty($result))
            return $result;
        return false;
    }

}
