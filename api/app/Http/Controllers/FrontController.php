<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{

    const JWT_TOKEN_NAME  = 'X-User-Jwt-Token';
    protected $requestHeaders = [];
    protected $user;

    public function __construct(){

        $this->user = new UserController();
        $this->link = new ShortLinkController();
        $this->setRequestHeaders();

    }

    public function respond($data, $code = 200 ,$headers = []) {
        return response()->json($data, $code);
    }

    protected function getJwtToken($tokenName = self::JWT_TOKEN_NAME) {
        $token = null;
        if(!empty($this->requestHeaders[$tokenName]))
            $token = trim($this->requestHeaders[$tokenName]);
        return $token;
    }

    protected function setRequestHeaders() {
        $this->requestHeaders = getallheaders();
    }

    protected function getHeaders() {
       return $this->requestHeaders;
    }

    protected function userAccess() {
//        $token    = $this->getJwtToken();
//        $userInfo = $this->verify($token);
//        if(!empty($userInfo['id'])) {
//            foreach ($userInfo as $key => $value) {
//                if(isset($this->userInfo[$key])) {
//                    $this->userInfo[$key] = $value;
//                }
//            }
//            $this->userInfo['access'] = true;
//            $this->userRole = $this->userInfo['role'];
//        }
    }
}
