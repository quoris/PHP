<?php

class Model_Enter extends Model{

    function get_data(){
        //session_start();

        $notice_enter_info = array();
        $notice_enter_info['inBase'] = true;

        $logEnt = '';
        $passEnt = '';
        $rememb = '';

        if(isset($_POST['enterLogin']) && isset($_POST['enterPassword']) && $_POST['enterButton'] == 'Вход'){
            $con = new Controller_Enter();
            $enter_info = $con->send_enter_data_to_model();

            if(isset($enter_info['login']) && isset($enter_info['password'])) {
                $logEnt = $enter_info['login'];
                $passEnt = $enter_info['password'];
            }

            if(isset($enter_info['remember'])){
                $rememb = $enter_info['remember'];
            }

            // проверка входа админа, установка сессий и кук
            if($logEnt == 'admin' && $passEnt == 'admin'){
                $_SESSION["login"] = 'admin';
                $_SESSION["password"] = 'admin';

                if($rememb != ''){
                    $setLogInCook = 'admin';
                    $setPassInCook = 'admin';
                    setcookie("login", $setLogInCook);
                    setcookie("password", $setPassInCook);
                }
                header("Location: http://catdog.ru/admin/");
            }

            // проверка входа пользователя, установка сессий и кук
            $pdoObj = new Model();
            $connectToDB = $pdoObj->connect_to_db();

            $isLog = 0;
            $loginFromDB = $connectToDB->query('SELECT login FROM users');
            while($rowLog = $loginFromDB->fetch()){
                 if($rowLog['login'] == $logEnt){
                    $isLog++;
                 }
            }

            $isPass = 0;
            $passwordFromDB = $connectToDB->query('SELECT password FROM users');
            while($rowPass = $passwordFromDB->fetch()){
                 if($rowPass['password'] == $passEnt){
                    $isPass++;
                 }
            }

            // закрываем соединение с БД
            $connectToDB = null;

            if($isLog != 0 && $isPass !=0){
                $_SESSION['login'] = $logEnt;
                $_SESSION['password'] = $passEnt;

                if($rememb != ''){
                    $setLogInCook = $logEnt;
                    $setPassInCook = $passEnt;
                    setcookie("login", $setLogInCook);
                    setcookie("password", $setPassInCook);
                }
                header("Location: http://catdog.ru/"); // если такой пользователь есть, то выкидывает на главную страницу
            } else {
                $notice_enter_info['inBase'] = false;
            }


        }
        return $notice_enter_info['inBase'];
    }
}