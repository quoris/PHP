<?php

class Controller_Cart extends Controller{

    function __construct(){
        $this->model = new Model_Cart();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('cart_view.php', 'template_view.php', $data);
    }


    function pressToDelete(){
        $idDeletedAnimal = array();
        if(isset($_POST['deleteAnimal']) && isset($_POST['id_animal'])){
            $idDeletedAnimal['id'] = $_POST['id_animal'];
        }
        return $idDeletedAnimal;
    }

}