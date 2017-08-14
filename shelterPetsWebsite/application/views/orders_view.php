<head>
    <title>Заказы</title>
</head>
<body>
<br xmlns="http://www.w3.org/1999/html">
<h1 class="ordersH1">Список всех заявок.</h1>
<div class="textOnOrdersPage">
<?php
if(isset($data)){
?>

<p>
<div class="ordersTable">
<table border="1">
<tr>
    <th>Имя пользователя</th><th>Телефон</th><th>Email</th><th>Имя животного</th><th>Тип животного</th><th>Дата получения заявки</th>
</tr>

<?php
    foreach($data as $orderInfo){
        if(isset($orderInfo['userName']) && isset($orderInfo['phoneNumber']) && isset($orderInfo['email']) && isset($orderInfo['animalName']) && isset($orderInfo['animalType']) && isset($orderInfo['orderDate'])){
            echo '<tr><td>'.$orderInfo['userName'].'</td><td>'.$orderInfo['phoneNumber'].'</td><td>'.$orderInfo['email'].'</td><td>'.$orderInfo['animalName'].'</td><td>'.$orderInfo['animalType'].'</td><td>'.$orderInfo['orderDate'].'</td></tr>';
        }
    }
?>
</table>
</div>
</p>

<?php
} else {
    echo '<p>Пока еще никто не оставил заявку.</p>';
}
?>
</div>
</body>