<?php

class Controller_Orders extends Controller{

    function __construct(){
        $this->model = new Model_Orders();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate('orders_view.php', 'template_view.php', $data);
    }
}