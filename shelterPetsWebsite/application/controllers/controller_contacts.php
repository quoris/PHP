<?php

class Controller_Contacts extends Controller{

    function __construct(){
        $this->view = new View();
    }

    function action_index(){
        $this->view->generate('contacts_view.php', 'template_view.php');
    }
}