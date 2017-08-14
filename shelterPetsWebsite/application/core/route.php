<?php
/**
 * Class Route запускает методы контроллеров, которые будут генерировать вид страниц
 * При написании класса марштуризации также стоит подумать о безопасности.
 */

class Route
{
    static function start()
    {
        // контроллер и действие по умолчанию
        $controller_name = 'main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']); // у адреса разделяем контроллер и экшн

        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            $controller_name = $routes[1];
        }

        // получаем имя экшена
        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
        }

        // добавляем префиксы
        $model_name = 'model_'.$controller_name;
        $controller_name = 'controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)
        $model_file = strtolower($model_name).'.php';
        $model_path = "application/models/".$model_file;
        if(file_exists($model_path))
        {
            include "application/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "application/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include "application/controllers/".$controller_file;
        }
        else
        {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу делается редирект на страницу 404
            */
            exit("Не видит файл контроллера <br/> controller_file == ".$controller_file);
            Route::ErrorPage404();
        }

        // Тут все соединяем
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            $controller->$action();
        }
        else
        {
            exit("У controller нет такого action");
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }

    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found'); // не обязательно. достаточно вернуть статус
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}