<head>
    <title>Мой профиль</title>
</head>
<body>
<br/>
<h1 class="profileH1">Мой профиль</h1>
<?php
if(isset($data) && isset($data['name']) && isset($data['sex']) && isset($data['phoneNumber']) && isset($data['email']) && isset($data['login']) && isset($data['password'])){

?>
<div class="textOnProfilePage">
<p><span class="userInfo">1. Ваше имя:</span> <?php echo $data['name']?></p>
<p><span class="userInfo">2. Ваш пол:</span> <?php echo $data['sex']?></p>
<p><span class="userInfo">3. Ваш телефон:</span> <?php echo $data['phoneNumber']?></p>
<p><span class="userInfo">4. Ваш email:</span> <?php echo $data['email']?></p>
<p><span class="userInfo">5. Ваш логин:</span> <?php echo $data['login']?></p>
<p><span class="userInfo">6. Ваш пароль:</span> <?php echo $data['password']?></p>
</div>
<?php
}
?>
</body>
