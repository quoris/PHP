<?php

class Model_Profile extends Model{

    function get_data(){

        $userLogin = $_SESSION['login'];
        $userData = array();

        $pdoObj = new Model();
        $connectToDB = $pdoObj->connect_to_db();
        $connectToDB->query("SET CHARACTER SET 'cp1251'");
        $connectToDB->query("set character_set_client='cp1251'");
        $connectToDB->query("set character_set_results='cp1251'");
        $connectToDB->query("set collation_connection='utf8_general_ci'");
        $connectToDB->query("SET NAMES 'cp1251'");

        $select = $connectToDB->prepare('SELECT name, sex, phoneNumber, email, password FROM users WHERE login = :login');
        $select->execute(array(
           ':login' => $userLogin
        ));
        $connectToDB = null;

        while($row = $select->fetch()){
            $userData['name'] = $row['name'];
            $userData['sex'] = $row['sex'];
            $userData['phoneNumber'] = $row['phoneNumber'];
            $userData['email'] = $row['email'];
            $userData['login'] = $userLogin;
            $userData['password'] = $row['password'];
        }
        return $userData;
    }
}