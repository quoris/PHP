<?php

/**
Инициирует загрузку приложения, подключает все модули (обработчики).
 */
session_start();
require_once 'core/model.php';  // подключает файл model.php ядра
require_once 'core/view.php';   // подключает файл view.php ядра
require_once 'core/controller.php';     // подключает файл controller.php ядра
require_once 'core/route.php';          // подключает файл с классом марштуризатора
Route::start();                         // запускаем маршрутизатор

/*
 * В ядре написаны все классы, от которых мы будем наследоваться
 * и писать свою реализацию в файлах, которые будут лежать
 * в папках controlles, core, models.
 */