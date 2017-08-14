<?php

class Model_Orders extends Model{

    function get_data(){

        $pdoObj = new Model();
        $connectToDB = $pdoObj->connect_to_db();
        $connectToDB->query("SET CHARACTER SET 'cp1251'");
        $connectToDB->query("set character_set_client='cp1251'");
        $connectToDB->query("set character_set_results='cp1251'");
        $connectToDB->query("set collation_connection='utf8_general_ci'");
        $connectToDB->query("SET NAMES 'cp1251'");
        $orders_info = array();

        $el = 0;
        $selectOrderInfo = $connectToDB->query('SELECT id_user, id_animal, orderDate FROM orders');
        while($r = $selectOrderInfo->fetch()){
            $id_user = $r['id_user'];
            $id_animal = $r['id_animal'];
            $orderDate = $r['orderDate'];

            $userName = '';
            $phoneNumber = '';
            $email = '';
            $animalName = '';
            $animalType = '';

            // достаем им€, телефон и почту человека
            $selectUserName = $connectToDB->prepare('SELECT name, phoneNumber, email FROM users WHERE id_user = :id_user');
            $selectUserName->execute(array(
                ':id_user' => $id_user
            ));
            while($z = $selectUserName->fetch()){
                $userName = $z['name'];
                $phoneNumber = $z['phoneNumber'];
                $email = $z['email'];
            }

            // достаем им€ животного и его тип
            $select_animal_name_and_type = $connectToDB->prepare('SELECT name, type FROM animals WHERE id_animal = :id_animal');
            $select_animal_name_and_type->execute(array(
                ':id_animal' => $id_animal
            ));
            while($s = $select_animal_name_and_type->fetch()){
                $animalName = $s['name'];
                $animalType = $s['type'];
            }

            // заносим все данные дл€ отправки
            $all_data = array();
            $all_data['userName'] = $userName;
            $all_data['phoneNumber'] = $phoneNumber;
            $all_data['email'] = $email;
            $all_data['animalName'] = $animalName;
            $all_data['animalType'] = $animalType;
            $all_data['orderDate'] = $orderDate;
            $orders_info[$el] = $all_data;
            $el++;
        }
        $connectToDB = null;
        return $orders_info;
    }
}