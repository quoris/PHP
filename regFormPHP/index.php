<html>
<head>
		<meta charset="utf-8" />
        <title>Форма регистрации</title>
		<link rel="stylesheet" href="style/style.css">
		<script src="js/script.js"></script>
</head>
<body>
	
<header>
<h1>Регистрация</h1>
</header>

<main>
<div id="formInformation">
<form action="" method="POST" name="regForm" enctype="multipart/form-data">
<p><label>Введите логин (email): <input type="text" id="login" name="log"></label></p>
<p><label>Введите пароль: <input type="password" id="password" maxlength="10" name="pass"></label></p>
<p>Ваш пол:<input type="radio" name="sex" value="man" checked> Мужской<input type="radio" name="sex" value="women"> Женский</p>
<p>Подписаться на новости:<input name="checkbox" type=checkbox value="subscribe"></p>
<p>Запомнить меня:<input name="remember" type=checkbox value="setCookie"></p>
<p>Выберите аватар: <input type="file" name="uploadAvatar" value="isPressButton"></p>

<?php
	include "functions.php";
	uploadAvatar();
?>

<p>Напишите о себе:</p>
<p><textarea id="textAbout" cols="50" rows="8" maxlength="200" onclick="length_check(200, 'textAbout', 'counter')" onkeyup="length_check(200, 'textAbout', 'counter')" name="about"></textarea></p>
<p>Осталось <span id="counter">200 символов</span></p>
<p><input id="buttonSend" type="image" src="http://innopraktika.ru/upload/medialibrary/5ce/send_message.png" name="send" value="sendData"></p>
<?php

	session_start();

	// проверка на нажатие кнопки: name == value
	if(isset($_POST["log"]) && isset($_POST["pass"]) && $_POST["send"] == 'sendData'){
		$_SESSION["log"] = $_POST["log"];
		$_SESSION["pass"] = $_POST["pass"];
		$_SESSION["sex"] = $_POST["sex"];
		$_SESSION["checkbox"] = $_POST["checkbox"];
		$_SESSION["about"] = $_POST["about"];
		$_SESSION["uploadAvatar"] = $_POST["uploadAvatar"];

		if($_POST["remember"] == 'setCookie'){
			$logToCookie = $_POST["log"];
			$passToCookie = $_POST["pass"];
			setcookie("log", $logToCookie);
			setcookie("pass", $passToCookie);
		}
		header("Location: script.php");
	}
?>
</form>
</div>
</main>
<div style="clear:both"></div>
<!--
<footer id="textInFooter">
<p>© Copyright</p>
<p><a href="#">Политика конфиденциальности</a></p>
</footer>
-->
</body>
</html>