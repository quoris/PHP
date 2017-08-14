<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

class Model_Registration extends Model{

    public function get_data(){
        $noties = array();
        if(isset($_POST["login"]) && isset($_POST["password"]) && $_POST["sendToController"] == '«Зарегистрироваться'){
            $cont = new Controller_Registration();
            $reg_info = $cont->send_data_to_model();
			$rememberMe = '';
			
            $name = $reg_info["name"];
            $sex = $reg_info["sex"];
            $phoneNumber = $reg_info["phoneNumber"]; // проверка
            $email = $reg_info["email"];             // проверка
            $login = $reg_info["login"];             // проверка
            $password = $reg_info["password"];       // проверка
            if(isset($reg_info["remember"])){
                $rememberMe = $reg_info["remember"]; // проверка
            }


            // проверка на регул¤рки
            $patternPhoneNumber = "/^\d[\d\(\)\ -]{4,14}\d$/";
            $patternEmail = "/^[a-zA-Z0-9_\-.]+@[a-zA-Z]+\.[a-z]+$/";
            $patternLogin = "/^[a-zA-Z0-9_\-.]+$/";
            $patternPassword = "/^[a-zA-Z0-9_\-.]+$/";

            $resPatPhoneNumber = preg_match($patternPhoneNumber, $phoneNumber);
            $resPatEmail = preg_match($patternEmail, $email);
            $resPatLogin = preg_match($patternLogin, $login);
            $resPatPassword = preg_match($patternPassword, $password);


            // запоминание ошибок
            if($name == null){
                $noties["name"] = "no_name";
            }

            if($phoneNumber == null){
                $noties["phoneNumber"] = "no_phoneNumber";
            }
            if($phoneNumber != null && $resPatPhoneNumber != 1){
                $noties["phoneNumber"] = "incorrect_phoneNumber";
            }

            if($email == null){
                $noties["email"] = "no_email";
            }
            if($email != null && $resPatEmail != 1){
                $noties["email"] = "incorrect_email";
            }

            if($login == null){
                $noties["login"] = "no_login";
            }
            if($login != null && $resPatLogin != 1){
                $noties["login"] = "incorrect_login";
            }

            if($password == null){
                $noties["password"] = "no_password";
            }
            if($password != null && $resPatPassword != 1){
                $noties["password"] = "incorrect_password";
            }

            if(isset($name) && $name != null && isset($phoneNumber) && $phoneNumber != null && $resPatPhoneNumber == 1 && isset($email) && $email != null && $resPatEmail == 1 && isset($login) && $login != null && $resPatLogin == 1 && isset($password) && $password != null && $resPatPassword == 1){

                // запись данных в сессию
                $_SESSION["login"] = $login;
                $_SESSION["password"] = $password;

                // установка кук
                if (isset($_POST['remember'])) {
                    $logToCookie = $login;
                    $passToCookie = $password;
                    setcookie("login", $logToCookie);
                    setcookie("password", $passToCookie);
                }

                // отправка данных в бд
                $pdoObj = new Model();
                $connectToDB = $pdoObj->connect_to_db();
                $connectToDB->query("SET CHARACTER SET 'cp1251'");
                $connectToDB->query("set character_set_client='cp1251'");
                $connectToDB->query("set character_set_results='cp1251'");
                $connectToDB->query("set collation_connection='utf8_general_ci'");
                $connectToDB->query("SET NAMES 'cp1251'");
                try{
                    $insert = $connectToDB->prepare('INSERT INTO users (name, sex, phoneNumber, email, login, password) VALUES (:name, :sex, :phoneNumber, :email, :login, :password)');
                    $insert->execute(array(
                        ':name' =>$name,
                        ':sex' => $sex,
                        ':phoneNumber' => $phoneNumber,
                        ':email' => $email,
                        ':login' => $login,
                        ':password' => $password
                    ));

                }catch(PDOException $e){
                    echo 'Error: '.$e->getMessage();
                }
                $connectToDB = null;
            }

        }
        return $noties;
    }

}