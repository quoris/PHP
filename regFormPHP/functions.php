<?php
/*
error_reporting(E_ALL);
ini_set("display_errors", true);
*/

function inFile(){
    $data = array(
        "login" => $_SESSION["log"],
        "password" => $_SESSION["pass"],
        "sex" => $_SESSION["sex"],
        "subscribe" => $_SESSION["checkbox"],
        "about" => $_SESSION["about"],
    );

    $toFile = json_encode($data);

    $fEncode = fopen('files/file.txt', 'a');
    fputs($fEncode, "$toFile \r\n");
    fclose($fEncode);
}

function fromFile(){
    $fInOneStr = file_get_contents('files/file.txt'); // записывает весь файл в строку
    $SeparateStr = explode("\r\n", $fInOneStr); // раздел€ет по переносу. возвращает массив
    foreach($SeparateStr as $val){
        $JSON_str = json_decode($val);

        if($JSON_str->login != ""){
            if($JSON_str->sex == man){
                $forSex = "ћужской";
            } else {
                $forSex = "∆енский";
            }

            if($JSON_str->subscribe == subscribe){
                $forSub = "≈сть";
            } else {
                $forSub = "Ќет";
            }

            echo "<tr><td>$JSON_str->login</td><td>$JSON_str->password</td><td>$forSex</td><td>$forSub</td><td>$JSON_str->about</td></tr>";
        }
    }
}

$set_avatar = "";
function uploadAvatar(){

    global $set_avatar;
    $upload_path = 'files/avatars/avatars/';
    $upload_sized_avatar_path = 'files/avatars/sizedAvatars/';

    if(!empty($_FILES['uploadAvatar']['name'])){
        // проверка на загрузку
        if(is_uploaded_file($_FILES['uploadAvatar']['tmp_name'])) {
            $check = false;

            // проверка на размер
            if (filesize($_FILES['uploadAvatar']['size']) > 5020500) {
                echo "<p>–азмер больше 5 мб.</p>";
                $check = false;
            } else {
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
                echo "<p>Ќужен подход€щий формат (jpg, JPG, jpeg, png)</p>";
                $check = false;
            } else {
                $check = true;
            }


            // присвоение рандомного имени
            $fname = md5($fname) . rand(999, 100000) . ".jpg";
            $changedName = $fname;

            // проверка наличие ошибок
            if ($check == true) {
                $set_avatar = true;
                copy($_FILES['uploadAvatar']['tmp_name'], $upload_path . $changedName);


                //путь к загруженному пользователем файлу
                $source_path = $upload_path . $changedName;

                //создаем путь и им€ миниатюры
                $new_name_sized_avatar = md5($changedName) . $ext;
                $resource_src = $upload_sized_avatar_path . $new_name_sized_avatar;


                //получаем параметры загруженного пользователем файла
                $params = getimagesize($source_path);

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
}






