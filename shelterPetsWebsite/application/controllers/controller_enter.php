<?php

class Controller_Enter extends Controller{

    function  __construct(){
        $this->model = new Model_Enter();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('enter_view.php', 'template_view.php', $data);
    }

    function send_enter_data_to_model(){
        //session_start();

        $enterInfo = array();
        if(isset($_POST['enterLogin']) && isset($_POST['enterPassword']) && $_POST['enterButton'] == '¬ход'){
            $enterInfo['login'] = $_POST['enterLogin'];
            $enterInfo['password'] = $_POST['enterPassword'];
        }
        if(isset($_POST['rememberEnter']) && $_POST['rememberEnter'] == 'setCookieEnter' && $_POST['enterButton'] == '¬ход'){
            $enterInfo['remember'] = $_POST['rememberEnter'];
        }

        return $enterInfo;
    }
}