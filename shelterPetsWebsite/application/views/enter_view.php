<head>
    <title>Вход</title>
</head>
<body>
<div class="textOnEnterPage">
<br/>
<form action="" method="POST">
<p>Введите логин:<br/>
<input class="inputEnterInfo" type="text" name="enterLogin" maxlength="10" placeholder="login"></p>
<p>Введите пароль:<br/>
<input class="inputEnterInfo" type="password" name="enterPassword" maxlength="10" placeholder="password"></p>
<p>Запомнить меня:<input name="rememberEnter" type=checkbox value="setCookieEnter"></p>
<p><input class="enterButton" type="submit" name="enterButton" value="Вход"></p>
<?php

if(isset($data) && isset($_POST['enterButton']) && $data['isBase'] == false && $_POST['enterButton'] == 'Вход'){
    echo "<p>Таких данных нет в базе данных. Пожалуйста, введите зарегистрированного пользователя</p>";

}
// убрать
if(isset($data) && isset($_POST['enterButton']) && $data['isBase'] == true && $_POST['enterButton'] == 'Вход'){
    echo "<p>есть такой чувак</p>";
}
?>
</form>
</div>
</body>