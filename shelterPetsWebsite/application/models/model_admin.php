<?php

/*
 *  акие пол€ есть у массива $notice_admin_info:
 * 1) errorSize - загрузка картинки больше 5 мб
 * 2) errorFormat - загрузка картинки неподход€щег формата
 * 3) isRemove - введены не существующие данные дл€ удалени€
 * */

class Model_Admin extends Model{

    function get_data(){

        $notice_admin_info = array();
        if(isset($_POST['nameAnimal']) or isset($_POST['ageAnimal']) or isset($_POST['sexAnimal']) or isset($_POST['typeAnimal']) or isset($_POST['aboutAnimal']) && (isset($_POST['add']) or isset($_POST['edit']) or isset($_POST['remove'])) ){
            $conAdmin = new Controller_Admin();
            $admin_info = $conAdmin->send_data_to_admin();

            $nameAnimal = $admin_info['nameAnimal'];
            $ageAnimal = $admin_info['ageAnimal'];
            $sexAnimal = $admin_info['sexAnimal'];
            $typeAnimal = $admin_info['typeAnimal'];
            $aboutAnimal = $admin_info['aboutAnimal'];

            // если нажали кнопку "добавить"
            if(isset($_POST['add'])){

                $resultUploadAnimalPhoto = $this->uploadAnimalPhoto();

                if(isset($resultUploadAnimalPhoto['new_name_sized_avatar']) && isset($resultUploadAnimalPhoto['size']) && isset($resultUploadAnimalPhoto['format'])){
                    $nameAddAvatar = $resultUploadAnimalPhoto['new_name_sized_avatar'];
                    $size = $resultUploadAnimalPhoto['size'];
                    $format = $resultUploadAnimalPhoto['format'];

                    // если нет ошибок при загрузке, добавить в Ѕƒ
                    if($size == true && $format == true){
                        try{
                            $pdoObj = new Model();
                            $connectToDB = $pdoObj->connect_to_db();
                            $connectToDB->query("SET CHARACTER SET 'cp1251'");
                            $connectToDB->query("set character_set_client='cp1251'");
                            $connectToDB->query("set character_set_results='cp1251'");
                            $connectToDB->query("set collation_connection='utf8_general_ci'");
                            $connectToDB->query("SET NAMES 'cp1251'");

                            $insert = $connectToDB->prepare('INSERT INTO animals (name, age, sex, type, photo, about) VALUES (:name, :age, :sex, :type, :photo, :about)');
                            $insert->execute(array(
                                ':name' => $nameAnimal,
                                ':age' => $ageAnimal,
                                ':sex' => $sexAnimal,
                                ':type' => $typeAnimal,
                                ':photo' => $nameAddAvatar,
                                ':about' => $aboutAnimal
                            ));
                        }catch(PDOException $e){
                            echo "Error: ".$e->getMessage();
                        }
                        $notice_admin_info['isAdded'] = true;
                    } else {
                        $notice_admin_info['errorSize'] = $size;
                        $notice_admin_info['errorFormat'] = $format;
                    }
                }
                $connectToDB = null;
            }


            // если нажали кнопку "редактировать"
            if(isset($_POST['edit'])){

                $pdoObj = new Model();
                $connectToDB = $pdoObj->connect_to_db();
                $connectToDB->query("SET CHARACTER SET 'cp1251'");
                $connectToDB->query("set character_set_client='cp1251'");
                $connectToDB->query("set character_set_results='cp1251'");
                $connectToDB->query("set collation_connection='utf8_general_ci'");
                $connectToDB->query("SET NAMES 'cp1251'");

                // если введен возраст
                if($ageAnimal != ''){
                    try{
                        $updateAge = $connectToDB->prepare("UPDATE animals SET age = :age WHERE name = :name AND type = :type");
                        $updateAge->execute(array(
                            ':age' => $ageAnimal,
                            ':name' => $nameAnimal,
                            ':type' => $typeAnimal
                        ));
                    }catch (PDOException $e){
                        echo 'Error: '.$e->getMessage();
                    }
                    $notice_admin_info['isEdit'] = true;
                }

                // если загружена картинка
                if(!empty($_FILES['uploadAvatar']['name'])){

                    $resultUploadAnimalPhoto = $this->uploadAnimalPhoto();

                    if(isset($resultUploadAnimalPhoto['new_name_sized_avatar']) && isset($resultUploadAnimalPhoto['size']) && isset($resultUploadAnimalPhoto['format'])){
                        $nameAddAvatar = $resultUploadAnimalPhoto['new_name_sized_avatar'];
                        $size = $resultUploadAnimalPhoto['size'];
                        $format = $resultUploadAnimalPhoto['format'];

                        // если нет ошибок при загрузке, добавить в Ѕƒ
                        if($size == true && $format == true){
                            try{
                                $updatePhoto = $connectToDB->prepare('UPDATE animals SET photo = :photo WHERE name = :name AND type = :type');
                                $updatePhoto->execute(array(
                                    ':name' => $nameAnimal,
                                    ':type' => $typeAnimal,
                                    ':photo' => $nameAddAvatar
                                ));
                            }catch(PDOException $e){
                                echo "Error: ".$e->getMessage();
                            }
                            $notice_admin_info['isEdit'] = true;
                        } else {
                            $notice_admin_info['errorSize'] = $size;
                            $notice_admin_info['errorFormat'] = $format;
                        }
                    }

                }

                // если введено описание
                if($aboutAnimal != ''){

                    try{
                        $updateAbout = $connectToDB->prepare('UPDATE animals SET about = :about WHERE name = :name AND type = :type');
                        $updateAbout->execute(array(
                            ':name' => $nameAnimal,
                            ':type' => $typeAnimal,
                            ':about' => $aboutAnimal
                        ));
                    }catch (PDOException $e){
                        echo 'Error: '.$e->getMessage();
                    }
                    $notice_admin_info['isEdit'] = true;
                }
                $connectToDB = null;
            }


            // если нажали кнопку "удалить"
            if(isset($_POST['remove'])){

                 //проверка существовани€ данных в бд по возрасту и полу
                $pdoObj = new Model();
                $connectToDB = $pdoObj->connect_to_db();
                $connectToDB->query("SET CHARACTER SET 'cp1251'");
                $connectToDB->query("set character_set_client='cp1251'");
                $connectToDB->query("set character_set_results='cp1251'");
                $connectToDB->query("set collation_connection='utf8_general_ci'");
                $connectToDB->query("SET NAMES 'cp1251'");


                $select = $connectToDB->prepare("SELECT age, sex, photo FROM animals WHERE name = :name AND type = :type"); // дело в синтаксисе
                $select->execute(array(
                    ':name' => $nameAnimal,
                    ':type' => $typeAnimal
                ));

                while($row = $select->fetch()){

                    if($row['age'] == $ageAnimal && $row['sex'] == $sexAnimal){

                        $photoName = $row['photo'];
                        try{
                            $pdoObj = new Model();
                            $connectToDB = $pdoObj->connect_to_db();
                            $connectToDB->query("SET CHARACTER SET 'cp1251'");
                            $connectToDB->query("set character_set_client='cp1251'");
                            $connectToDB->query("set character_set_results='cp1251'");
                            $connectToDB->query("set collation_connection='utf8_general_ci'");
                            $connectToDB->query("SET NAMES 'cp1251'");

                            $remove = $connectToDB->prepare("DELETE FROM animals WHERE name = :name AND type = :type"); // не выполн€ет запрос
                            $remove->execute(array(
                                ':name' => $nameAnimal,
                                ':type' => $typeAnimal
                            ));
                        }catch (PDOException $e){
                            echo 'Error: '.$e->getMessage();
                        }
                        unlink('files/sizedAvatars/'.$photoName);

                        $notice_admin_info['isRemove'] = true;
                    } else {
                        $notice_admin_info['isRemove'] = false;
                    }
                }
                $connectToDB = null;
            }

        }
        return $notice_admin_info;
    }


    function uploadAnimalPhoto(){

        $upload_path = 'files/avatars/';
        $upload_sized_avatar_path = 'files/sizedAvatars/';
        $processUpload = array();


        if(!empty($_FILES['uploadAvatar']['name'])){

            // проверка на загрузку
            if(is_uploaded_file($_FILES['uploadAvatar']['tmp_name'])) {
                $check = false;

                // проверка на размер
                if ($_FILES['uploadAvatar']['size'] > 5020500) {
                    $processUpload['size'] = false;
                    $check = false;
                } else {
                    $processUpload['size'] = true;
                    $check = true;
                }

                // проверка на тип
                $types = array(
                    '.jpg',
                    '.JPG',
                    '.jpeg',
                    '.png'
                );
                $fname = $_FILES['uploadAvatar']['name'];
                $ext = substr($fname, strpos($fname, '.'), strlen($fname) - 1);
                if (!in_array($ext, $types)) {
                    $processUpload['format'] = false;
                    $check = false;
                } else {
                    $processUpload['format'] = true;
                    $check = true;
                }


                // присвоение рандомного имени
                $fname = md5($fname) . rand(999, 100000) . ".jpg";
                $changedName = $fname;

                // проверка наличие ошибок
                if ($check == true) {
                    copy($_FILES['uploadAvatar']['tmp_name'], $upload_path . $changedName);

                    //путь к загруженному пользователем файлу до задани€ ему размеров
                    $source_path = $upload_path . $changedName;

                    //создаем путь и им€ миниатюры
                    $new_name_sized_avatar = md5($changedName) . $ext;  // название подогнанной под размер миниатюры
                    $resource_src = $upload_sized_avatar_path . $new_name_sized_avatar;
                    $processUpload['new_name_sized_avatar'] = $new_name_sized_avatar;

                    //получаем параметры загруженного пользователем файла
                    $params = getimagesize($source_path);

                    $source = '';
                    switch ($params[2]) {
                        case 1:
                            $source = imagecreatefromgif($source_path);
                            break;
                        case 2:
                            $source = imagecreatefromjpeg($source_path);
                            break;
                    }

                    //если высота больше ширины
                    //вычисл€ем новую ширину
                    $newwidth = '';
                    $newheight = '';
                    if ($params[1] > $params[0]) {
                        $newheight = 300;
                        $newwidth  = floor($newheight * $params[0] / $params[1]);
                    }
                    //если ширина больше высоты
                    //вычисл€ем новую высоту
                    if ($params[1] < $params[0]) {
                        $newwidth  = 300;
                        $newheight = floor($newwidth * $params[1] / $params[0]);
                    }

                    //создаем миниатюру загруженного изображени€
                    $resource = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($resource, $source, 0, 0, 0, 0, $newwidth, $newheight, $params[0], $params[1]);
                    imagejpeg($resource, $resource_src, 80); //80 качество изображени€

                    //назначаем права доступа
                    chmod("$source_path", 0644);
                    chmod("$source_path", 0644);
                }
            }
        }
        return $processUpload;
    }
}