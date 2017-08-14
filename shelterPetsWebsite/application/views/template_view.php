<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="icon" href="/images/favicon.jpg" type="image/x-icon">
    <link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
</head>
<body>
<div id="heightWeightPage">

<div id="header">
<?php
if(!isset($_SESSION)){
    session_start();
}

// для тех кто не зарегистрирован
if(!isset($_SESSION["login"])){
    echo "<div class='registration'><p><a href='http://catdog.ru/registration/'>Регистрация</a> | <a href='http://catdog.ru/enter/'>Вход</a></p></div>";
}


// вошел обычный пользователь
if(isset($_SESSION["login"]) && $_SESSION["login"] != 'admin'){
    echo "<div class='whoEntered'>";
    echo "<form action='' method='POST'>";
    echo "<p>Здравствуйте, $_SESSION[login]!<br/>";
    echo "<a href='http://catdog.ru/profile/'><button class='whoEnteredButtons' type='button' name=profile value='goToProfile'>Мой профиль</button></a>  <a href='http://catdog.ru/cart/'><button class='whoEnteredButtons' type='button' name=cart value='goToCart'>Корзина</button></a>  <button class='whoEnteredButtons' type='submit' name='exit' value='pressExit'>Выход</button></p>";
    echo "</form>";
    echo "</div>";
}
// если нажали "выход"
if(isset($_POST['exit']) && $_POST['exit'] == 'pressExit'){
    unset($_SESSION["login"]);
    unset($_SESSION["password"]);
    header("Location: http://catdog.ru/");
    exit();
}


// вошел админ
if(isset($_SESSION["login"]) && $_SESSION["login"] == 'admin'){
    echo "<div class='adminEntered'>";
    echo "<form action='' method='POST'>";
    echo "<p>Здравствуйте, admin!<br/>";
    echo "<a href='http://catdog.ru/admin/'><button class='adminControlButtons' type='button' name=admin value='goToAdmin'>Управление</button></a> <a href='http://catdog.ru/orders/'><button class='adminControlButtons' type='button' name='orders' value='pressOrders'>Заявки</button></a> <button class='adminControlButtons' type='submit' name='exit' value='pressExit'>Выход</button></p>";
    echo "</form>";
    echo "</div>";
}
?>


    <div class="backgroundMenuText">
        <ul class="menu">
            <li><a href="http://catdog.ru/">Главная</a></li>
            <li><a href="http://catdog.ru/animals/">Питомцы</a></li>
            <li><a href="http://catdog.ru/help/">Я хочу помочь</a></li>
            <li><a href="http://catdog.ru/about/">О приюте</a></li>
            <li><a href="http://catdog.ru/contacts/">Контакты</a></li>
        </ul>
    </div>
</div>


<div id="body">
<?php

include 'application/views/'.$content_view;

?>
</div>

<div id="footer">
<footer>
<p>© 2015 catdog.ru - Тепло и забота превыше всего.</p>
</footer>
</div>

</div>
</body>
</html>