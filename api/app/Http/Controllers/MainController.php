<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends FrontController
{

    public function index(){
        echo 'MainIndex';
        // return $this->respond($data, 200);
    }

    // Регистрация пользователя
    public function userCreate(Request $request) {
        $data = $request->all();
        $result = $this->user->userCreate($data);
        return $this->respond($result, 200);
    }

    // Аутентификация пользователя
    public function login(Request $request) {
        $email    = $request->input('email');
        $password = $request->input('password');
        $result = $this->user->login($email, $password);
        return $this->respond($result, 200);
    }

    // Добавление новой ссылки
    public function addLink(Request $request) {
        $data = $request->all();
        $result = $this->link->addLink($data);
        return $this->respond($result, 200);
    }

    // Изменение ссылки
    public function updateLink($linkId, Request $request) {
        $data = $request->all();
        $result = $this->link->updateLink($linkId, $data);
        return $this->respond($result, 200);
    }

    // Удаление ссылки
    public function deleteLink($linkId) {
        $result = $this->link->deleteLink($linkId);
        return $this->respond($result, 200);
    }

    // Получение ссылок по user_id
    public function getLinksByUserId($userId) {
        $result = $this->link->getLinksByUserId($userId);
        return $this->respond($result, 200);
    }

    public function redirectUrl($randomUrl) {
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $result = $this->link->getRealUrl($url);
        $realLink = $result['result'];
        header('Location: http://' . $realLink);
    }

}
