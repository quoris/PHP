<?php

class Controller_Animals extends Controller{

    function __construct(){
        $this->model = new Model_Animals();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('animals_view.php', 'template_view.php', $data);
    }

    // вывод с параметрами
    function send_data_with_paramters(){

        $selectAnimalInfo = array();
        if(isset($_POST['typeAnimal']) && isset($_POST['sexAnimal']) && isset($_POST['ageAnimal']) && isset($_POST['findAnimal'])){
            $selectAnimalInfo['typeAnimal'] = $_POST['typeAnimal'];
            $selectAnimalInfo['sexAnimal'] = $_POST['sexAnimal'];
            $selectAnimalInfo['ageAnimal'] = $_POST['ageAnimal'];
        }
        return $selectAnimalInfo;
    }

    // нажатие на кнопку "хочу"
    function send_selected_animal_id(){
        $id_selected_animal = array();
        if(isset($_POST['takeAnimal']) && isset($_SESSION['login']) && isset($_POST['id_animal'])){
            $id_selected_animal['id_animal'] = $_POST['id_animal'];
        }
        return $id_selected_animal;
    }

}