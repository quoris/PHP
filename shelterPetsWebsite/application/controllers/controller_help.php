<?php

class Controller_Help extends Controller{

    function  __construct(){
        $this->view = new View();
    }

    function action_index(){
        $this->view->generate('help_view.php', 'template_view.php');
    }
}