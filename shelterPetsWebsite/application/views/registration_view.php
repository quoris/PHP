<head>
    <title>Регистрация</title>
</head>
<body>
<form action="" method="POST" id="regForm">
<div class="textOnRegistrationPage">
<br/>
<h2 class="registrationH2">Все поля обязательны для заполнения.</h2>
    <?php
	
    if($data == null && isset($_POST["login"]) && isset($_POST["password"]) && $_POST["sendToController"] == 'Зарегистрироваться'){
		/*
		var_dump($data);
		var_dump($_POST["login"]);
		var_dump($_POST["password"]);
		var_dump($_POST["sendToController"]);
		exit();
		*/
		header("Location: http://catdog.ru/");
    } else {
        if(isset($data["name"]) && $data["name"] == "no_name"){
            echo "<p>ОШИБКА: Вы не написали имя. Пожалуйста, напишите его.</p>";
        }

        if(isset($data["phoneNumber"]) && $data["phoneNumber"] == "no_phoneNumber"){
            echo "<p>ОШИБКА: Вы не написали телефон. Пожалуйста, напишите его.</p>";
        }
        if(isset($data["phoneNumber"])&& $data["phoneNumber"] == "incorrect_phoneNumber"){
            echo "<p>ОШИБКА: Введен некорректный номер. Введите в формате 89ххххххххх</p>";
        }

        if(isset($data["email"]) && $data["email"] == "no_email"){
            echo "<p>ОШИБКА: Вы не написали email. Пожалуйста, напишите его.</p>";
        }
        if(isset($data["email"]) && $data["email"] == "incorrect_email"){
            echo "<p>ОШИБКА: Введен некорректный email. Пожалуйста, напишите его нормально.</p>";
        }

        if(isset($data["login"]) && $data["login"] == "no_login"){
            echo "<p>ОШИБКА: Вы не написали логин. Пожалуйста, придумайте его.</p>";
        }
        if(isset($data["login"]) && $data["login"] == "incorrect_login"){
            echo "<p>ОШИБКА: Введен некорректный логин. Он должен состоять только из английский букв и цифр</p>";
        }

        if(isset($data["password"]) && $data["password"] == "no_password"){
            echo "<p>ОШИБКА: Вы не написали пароль. Пожалуйста, придумайте его.</p>";
        }
        if(isset($data["password"]) && $data["password"] == "incorrect_password"){
            echo "<p>ОШИБКА: Введен некорректный пароль. Он должен состоять только из английский букв и цифр</p>";
        }
    }
    ?>

<p>1. Как вас зовут:<br/>
<input class="inputRegInfo" type="text" name="name" maxlength="35" placeholder="ФИО"></p>
<p>2. Ваш пол:<br/>
<input type="radio" name="sex" value="мужской" checked> Мужской<input type="radio" name="sex" value="женский"> Женский</p>
<p>3. Ваш телефон:<br/>
<input class="inputRegInfo" type="text" name="phoneNumber" maxlength="11" placeholder="89xxxxxxxxx"></p>
<p>4. Ваш email:<br/>
<input class="inputRegInfo" type="text" name="email" placeholder="example@example.ru"></p>

<p>5. Придумайте, пожалуйста, логин и пароль:<br/>
<input class="inputRegInfo" type="text" name="login" maxlength="10" placeholder="login">
<input class="inputRegInfo" type="text" name="password" maxlength="10" placeholder="password">

<p>6. Запомнить меня:<input name="remember" type=checkbox value="setCookie"></p>
<p><button class="registrationButton" type="submit" name="sendToController" value="Зарегистрироваться">Зарегистрироваться</button></p>

<br/>
<br/>
</div>
</form>
</body>