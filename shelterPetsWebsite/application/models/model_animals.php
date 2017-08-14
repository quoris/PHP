<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

class Model_Animals extends Model{

    function get_data(){

        $animals = array();
        $countAn = 0;

        $pdoObj = new Model();
        $connectToDB = $pdoObj->connect_to_db();
        $connectToDB->query("SET CHARACTER SET 'cp1251'");
        $connectToDB->query("set character_set_client='cp1251'");
        $connectToDB->query("set character_set_results='cp1251'");
        $connectToDB->query("set collation_connection='utf8_general_ci'");
        $connectToDB->query("SET NAMES 'cp1251'");

        // просто вывод
        try{
            $selectAll = $connectToDB->query('SELECT id_animal, name, age, sex, type, photo, about FROM animals');


            while($q = $selectAll->fetch()){

                $strElements = array();
                $strElements['a_id_animal'] = $q['id_animal'];
                $strElements['a_name'] = $q['name'];
                $strElements['a_age'] = $q['age'];
                $strElements['a_sex'] = $q['sex'];
                $strElements['a_type'] = $q['type'];
                $strElements['a_photo'] = $q['photo'];
                $strElements['a_about'] = $q['about'];

                $animals[$countAn] = $strElements;
                $countAn++;
            }
        }catch (PDOException $e){
            echo 'Error: '.$e->getMessage();
        }


        //Выбор животных по параметрами
        if(isset($_POST['typeAnimal']) && isset($_POST['sexAnimal']) && isset($_POST['ageAnimal']) && isset($_POST['findAnimal'])){
            $contAnimals = new Controller_Animals();
            $selectInfo = $contAnimals->send_data_with_paramters();

            $animals = null;
            $countAn = 0;

            try{
                $pdoObj = new Model();
                $connectToDB = $pdoObj->connect_to_db();
                $connectToDB->query("SET CHARACTER SET 'cp1251'");
                $connectToDB->query("set character_set_client='cp1251'");
                $connectToDB->query("set character_set_results='cp1251'");
                $connectToDB->query("set collation_connection='utf8_general_ci'");
                $connectToDB->query("SET NAMES 'cp1251'");

                $selectWithParam = $connectToDB->prepare("SELECT id_animal, name, photo, about FROM animals WHERE age = :age AND sex = :sex AND type = :type"); // не работает запрос
                $selectWithParam->execute(array(
                    ':age' => $selectInfo['ageAnimal'],
                    ':sex' => $selectInfo['sexAnimal'],
                    ':type' => $selectInfo['typeAnimal']
                ));


                while($a = $selectWithParam->fetch()){

                    $strElem = array();
                    $strElem['a_id_animal'] = $a['id_animal'];
                    $strElem['a_name'] = $a['name'];
                    $strElem['a_age'] = $selectInfo['ageAnimal'];
                    $strElem['a_sex'] = $selectInfo['sexAnimal'];
                    $strElem['a_type'] = $selectInfo['typeAnimal'];
                    $strElem['a_photo'] = $a['photo'];
                    $strElem['a_about'] = $a['about'];

                    $animals[$countAn] = $strElem;
                    $countAn++;
                }
            }catch(PDOException $e){
                echo 'Error: '.$e->getMessage();
            }
        }


        // обработка нажатия кнопки "Хочу"
        if(isset($_POST['takeAnimal']) && isset($_SESSION['login']) && $_SESSION['login'] != 'admin' && isset($_POST['id_animal'])){

            $pdoObj = new Model();
            $connectToDB = $pdoObj->connect_to_db();
            $connectToDB->query("SET CHARACTER SET 'cp1251'");
            $connectToDB->query("set character_set_client='cp1251'");
            $connectToDB->query("set character_set_results='cp1251'");
            $connectToDB->query("set collation_connection='utf8_general_ci'");
            $connectToDB->query("SET NAMES 'cp1251'");

            // достаю айди заказавшего
            try{
                $selectUserId = $connectToDB->prepare('SELECT id_user FROM users WHERE login = :login');
                $selectUserId->execute(array(
                    ':login' => $_SESSION['login']
                ));
                $rowUsId = $selectUserId->fetch();
                $id_user = $rowUsId['id_user'];
            }catch (PDOException $e){
                echo 'Error: '.$e->getMessage();
            }

            // достаю айди и имя заказанного животного
            $conAn = new Controller_Animals();
            $an_id = $conAn->send_selected_animal_id();
            $id_sel_animal = $an_id['id_animal']; // id выбранного животного

            // достаю время заказа
            $time = date('Y-m-d').' '.date("H:i:s");


            // проверяю есть ли в orders среди айдишников животных тот, который выбрали
            $isAlreadyAdded = 0;
            try{
                $selectAnimalsIDsFromOrders = $connectToDB->prepare('SELECT id_animal FROM orders WHERE id_user = :id_user ');
                $selectAnimalsIDsFromOrders->execute(array(
                    ':id_user'=> $id_user
                ));
                while($IDs = $selectAnimalsIDsFromOrders->fetch()){
                    if($id_sel_animal == $IDs['id_animal']){
                        $isAlreadyAdded++;
                    }
                }
            }catch (PDOException $e){
                echo 'Error: '.$e->getMessage();
            }

            // если совпадений не найдено
            if($isAlreadyAdded == 0){
                try{
                    $insertOrders = $connectToDB->prepare('INSERT INTO orders (id_user, id_animal, orderDate) VAlUES (:id_user, :id_animal, :orderDate)');
                    $insertOrders->execute(array(
                        ':id_user' => $id_user,
                        ':id_animal' => $id_sel_animal,
                        ':orderDate' => $time
                    ));
                    $isAlreadyAdded = 0;
                }catch (PDOException $e){
                    echo 'Error: '.$e->getMessage();
                }
            }
            $animals['alreadyAdded'] = $isAlreadyAdded;
        }

    $connectToDB = null;
    return $animals;
    }
}