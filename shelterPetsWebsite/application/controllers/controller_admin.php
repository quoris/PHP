<?php

class Controller_Admin extends Controller{

    function  __construct(){
        $this->model = new Model_Admin();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('admin_view.php', 'template_view.php', $data);
    }

    function send_data_to_admin(){

        $adi = array();
        if(isset($_POST['nameAnimal']) or isset($_POST['ageAnimal']) or isset($_POST['sexAnimal']) or isset($_POST['typeAnimal']) or isset($_POST['aboutAnimal']) && (isset($_POST['add']) or isset($_POST['edit']) or isset($_POST['remove'])) ){
            $adi['nameAnimal'] = $_POST['nameAnimal'];
            $adi['ageAnimal'] = $_POST['ageAnimal'];
            $adi['sexAnimal'] = $_POST['sexAnimal'];
            $adi['typeAnimal'] = $_POST['typeAnimal'];
            $adi['aboutAnimal'] = $_POST['aboutAnimal'];
        }
        return $adi;
    }
}