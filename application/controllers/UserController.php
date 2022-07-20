<?php
namespace application\controllers;

class UserController extends Controller {
    public function signup() {
        $json = getJson();
        $result = $this->model->signUp($json);
        if($result) {
            $loginUser = [
                
            ];
            $this->flash(_LOGINUSER, $result);
            return [_RESULT => $result];
        }
        return [_RESULT => 0];
    }

        public function logout() {
           $this->flash(_LOGINUSER);
           return [_RESULT => 1];
    }
}