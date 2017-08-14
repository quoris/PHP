<?php

class Controller_Registration extends Controller{

    function __construct(){
        $this->model = new Model_Registration();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('registration_view.php', 'template_view.php', $data);
    }

    function send_data_to_model(){
        session_start();

        $ri = array();
        if(isset($_POST["login"]) && isset($_POST["password"]) && $_POST["sendToController"] == 'Зарегистрироваться'){
            $ri["name"] = $_POST["name"];
            $ri["sex"] = $_POST["sex"];
            $ri["phoneNumber"] = $_POST["phoneNumber"];
            $ri["email"] = $_POST["email"];
            $ri["login"] = $_POST["login"];
            $ri["password"] = $_POST["password"];
        }
        if(isset($_POST['remember'])){
            $ri["remember"] = $_POST["remember"];
        }
		
        return $ri;
    }

}