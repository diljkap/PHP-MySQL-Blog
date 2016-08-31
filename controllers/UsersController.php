<?php

class UsersController extends BaseController
{
    public function index()
    {
        $this->authorize();
        $this->users = $this->model->getAll();
    }

    public function register()
    {
        if ($this->isPost) {
            $username = $_POST['username'];
            if (strlen($username) < 2 || strlen($username) > 50) {
                $this->setValidationError("username", "Username should be between 2 and 50 symbols!");
            }
            $password = $_POST['password'];
            if (strlen($password) < 2 || strlen($password) > 50) {
                $this->setValidationError("password", "Password should be between 2 and 50 symbols!");
            }
            $passwordRepeat = $_POST['password-repeat'];
            if ($password != $passwordRepeat) {
                $this->setValidationError("password-repeat", "Password does not match!");
            }
            $full_name = $_POST['full_name'];
            if (strlen($full_name) > 200) {
                $this->setValidationError("full_name", "Full name should be shorter than 200 symbols!");
            }

            if ($this->formValid()) {
                $userId = $this->model->register($username, $password, $full_name);
                if ($userId) {
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $userId;
                    $this->addInfoMessage("Registration successful.");
                    $this->redirect("posts");
                } else {
                    $this->addErrorMessage("Error: user registration failed!");
                }
            }
        }
    }

    public function login()
    {
        if($this->isPost){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $loggedUserId = $this->model->login($username,$password);
            if ($loggedUserId) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $loggedUserId;
                return $this->redirect("posts");
            }
            else {
                $this->addErrorMessage("Error: login failed!");
            }
        }
    }

    public function logout()
    {
        session_destroy();
        $this->addInfoMessage("Logout successful");
        $this->redirect("");
    }
}
