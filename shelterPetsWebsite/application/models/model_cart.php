<?php

class Model_Cart extends Model{

    function get_data(){

        $id_user = '0';
        $orders = array();
        $animalsIDs = array();
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


        // если нажали "удалить"
        if(isset($_POST['deleteAnimal']) && isset($_POST['id_animal'])){
            $v = new Controller_Cart();
            $idDelAn = $v->pressToDelete();

            try{
                $deleteAnimalFromCart = $connectToDB->prepare('DELETE FROM orders WHERE id_user = :id_user AND id_animal = :id_animal ');

                $deleteAnimalFromCart->execute(array(
                    ":id_user" => $id_user,
                    "id_animal" => $idDelAn['id']
                ));
            }catch (PDOException $e){
                echo 'Error: '.$e->getMessage();
            }
        }


        // достаю айди всех заказаннх животных
        $coa = 0;
        try{
            $selectAnimalId = $connectToDB->prepare('SELECT id_animal FROM orders WHERE id_user = :id_user');
            $selectAnimalId->execute(array(
                ':id_user' => $id_user
            ));
            while($rowAnID = $selectAnimalId->fetch()){
                $animalsIDs[$coa] = $rowAnID['id_animal'];
                $coa++;
            }
        }catch (PDOException $e){
            echo 'Error: '.$e->getMessage();
        }


        // достаю всех заказанных животных по их айди
        $cai = 0;
        $c = 0;
        foreach($animalsIDs as $find_an_id){
            try{
                $selectAllInfo = $connectToDB->prepare('SELECT name, age, sex, type, photo, about FROM animals WHERE id_animal = :id_animal');
                $selectAllInfo->execute(array(
                   ':id_animal' => $find_an_id // либо добавить отсчет с нулевого индекса $find_an_id[$cai]
                ));

                while($rowAllinfo = $selectAllInfo->fetch()){
                    $arr = array();
                    $arr['name'] = $rowAllinfo['name'];
                    $arr['age'] = $rowAllinfo['age'];
                    $arr['sex'] = $rowAllinfo['sex'];
                    $arr['type'] = $rowAllinfo['type'];
                    $arr['photo'] = $rowAllinfo['photo'];
                    $arr['about'] = $rowAllinfo['about'];
                    $arr['id_animal'] = $find_an_id;

                    $orders[$c] = $arr;
                    //$cai++;
                    $c++;
                }
            }catch (PDOException $e){
                echo 'Error: '.$e->getMessage();
            }
        }

        $connectToDB = null;
        return $orders;
    }
}