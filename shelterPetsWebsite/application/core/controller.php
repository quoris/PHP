<?php

/*
 * Для каждой страницы из папки views имеется свой контроллер из папки controllers
 * template_view.php (находится в папке views) - это шаблон, содержащий общую для всех страниц разметку
 */

class Controller {

    public $model;
    public $view;

    function __construct()
    {
        $this->view = new View();
    }

    function action_index()
    {
    }
}