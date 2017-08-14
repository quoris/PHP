<html>
<head>
    <title>Страница результатов</title>
    <link rel="stylesheet" href="style/answerPage.css">
</head>
<body>

<header>
<h1>Мой профиль</h1>
</header>

<main>
<div id="Reg">
<?php

include "functions.php";
session_start();

$log = $_SESSION["log"];
$pass = $_SESSION["pass"];
$sex = $_SESSION["sex"];
$checkbox = $_SESSION["checkbox"];
$about = $_SESSION["about"];

$patternLog = "/^[a-zA-Z0-9_\-.]+@[a-zA-Z]+\.[a-z]+$/";
$patternPass = "/^[a-zA-Z0-9_\-.]+$/";

$resPatLog = preg_match($patternLog, $log);
$resPatPass = preg_match($patternPass, $pass);


if($resPatLog == 1 && $resPatPass == 1){
    echo "<p><span class='point'>1. Ваш логин:</span> $log</p>
          <p><span class='point'>2. Ваш пароль:</span> $pass</p>";
    if($sex == man){
        echo "<p><span class='point'>3. Ваш пол:</span> мужской</p>";
    }else{
        echo "<p><span class='point'>3. Ваш пол:</span> женский</p>";
    }
    if($checkbox == subscribe){
        echo "<p><span class='point'>4. Вы подписались на новости.</span></p>";
    } else {
        echo "<p><span class='point'>4. Вы не подписались на новости.</span></p>";
    }
    echo "<p><span class='point'>5. Вы о себе:</span> $about</p>";
    echo "<p><a href='writePost.php'>Написать свой пост.</a></p>";


    echo "set_avatar == ".$set_avatar; // не видит это поле, которе находится в functions.php


    echo "<br/><p>Список всех пользователей:</p>";
    inFile();
    echo "<table border='5'>";
    echo "<tr><th>Логин</th><th>Пароль</th><th>Пол</th><th>Подписка на новости</th><th>О себе</th></tr>";
    fromFile();

} else {
    if($resPatLog != 1 && $resPatPass != 1){
        echo "<p>Неправильно введены логин и пароль.</p><p>Пожалуйста, исправьте.</p><p><a href=index.php><button class='button'>Исправить</button></a></p>";
    } else {
        if($resPatLog != 1){
            echo "<p>Неправильно введен логин.</p><p>Пожалуйста, исправьте.</p><p><a href=index.php><button class='button'>Исправить</button></a></p>";
        } else {
            echo "<p>Неправильно введен пароль.</p><p>Пожалуйста, исправьте.</p><p><a href=index.php><button class='button'>Исправить</button></a></p>";
        }
    }
}
?>
</div>
<div style="clear:both"></div>
</main>



</body>
</html>