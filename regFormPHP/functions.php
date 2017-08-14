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
    $fInOneStr = file_get_contents('files/file.txt'); // ���������� ���� ���� � ������
    $SeparateStr = explode("\r\n", $fInOneStr); // ��������� �� ��������. ���������� ������
    foreach($SeparateStr as $val){
        $JSON_str = json_decode($val);

        if($JSON_str->login != ""){
            if($JSON_str->sex == man){
                $forSex = "�������";
            } else {
                $forSex = "�������";
            }

            if($JSON_str->subscribe == subscribe){
                $forSub = "����";
            } else {
                $forSub = "���";
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
        // �������� �� ��������
        if(is_uploaded_file($_FILES['uploadAvatar']['tmp_name'])) {
            $check = false;

            // �������� �� ������
            if (filesize($_FILES['uploadAvatar']['size']) > 5020500) {
                echo "<p>������ ������ 5 ��.</p>";
                $check = false;
            } else {
                $check = true;
            }

            // �������� �� ���
            $types = array(
                '.jpg',
                '.JPG',
                '.jpeg',
                '.png'
            );
            $fname = $_FILES['uploadAvatar']['name'];
            $ext = substr($fname, strpos($fname, '.'), strlen($fname) - 1);
            if (!in_array($ext, $types)) {
                echo "<p>����� ���������� ������ (jpg, JPG, jpeg, png)</p>";
                $check = false;
            } else {
                $check = true;
            }


            // ���������� ���������� �����
            $fname = md5($fname) . rand(999, 100000) . ".jpg";
            $changedName = $fname;

            // �������� ������� ������
            if ($check == true) {
                $set_avatar = true;
                copy($_FILES['uploadAvatar']['tmp_name'], $upload_path . $changedName);


                //���� � ������������ ������������� �����
                $source_path = $upload_path . $changedName;

                //������� ���� � ��� ���������
                $new_name_sized_avatar = md5($changedName) . $ext;
                $resource_src = $upload_sized_avatar_path . $new_name_sized_avatar;


                //�������� ��������� ������������ ������������� �����
                $params = getimagesize($source_path);

                switch ($params[2]) {
                    case 1:
                        $source = imagecreatefromgif($source_path);
                        break;
                    case 2:
                        $source = imagecreatefromjpeg($source_path);
                        break;
                }

                //���� ������ ������ ������
                //��������� ����� ������
                if ($params[1] > $params[0]) {
                    $newheight = 300;
                    $newwidth  = floor($newheight * $params[0] / $params[1]);
                }
                //���� ������ ������ ������
                //��������� ����� ������
                if ($params[1] < $params[0]) {
                    $newwidth  = 300;
                    $newheight = floor($newwidth * $params[1] / $params[0]);
                }

                //������� ��������� ������������ �����������
                $resource = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($resource, $source, 0, 0, 0, 0, $newwidth, $newheight, $params[0], $params[1]);
                imagejpeg($resource, $resource_src, 80); //80 �������� �����������

                //��������� ����� �������
                chmod("$source_path", 0644);
                chmod("$source_path", 0644);


            }
        }
    }
}






