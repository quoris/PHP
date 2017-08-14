<head>
    <title>Корзина</title>
</head>
<body>
<?php

if(isset($data) && $data == null){
    echo '<span class="notSelectedAnimal"><p>Вы не выбрали ни одного животного. Выберите себе понравившегося питомца в разделе "Питомцы".</p></span>';
}

if(isset($data) && $data != null){
    echo "<br/>";
    echo "<h1 class='cartH1'>Вы выбрали следующих животных:</h1>";
    echo "<p><span class='infoToUser'>В ближайшее время наш администратор свяжется с вами.</span></p>";

    foreach($data as $animal){
        if(isset($animal['id_animal']) && isset($animal['name']) && isset($animal['age']) && isset($animal['sex']) && isset($animal['type']) && isset($animal['photo']) && isset($animal['about'])){
            echo '<form action="" method="post">';
            echo "<div class='textOnCartPage'>";
            echo '<div class="showedAnimalInCart">';
            echo '<div class="photo">';
            echo '<p><img src=/files/sizedAvatars/'.$animal['photo'].'></p>';
            echo '<p><input id="id" type="hidden" name="id_animal" value='.$animal['id_animal'].' ></p>'; // задаем айди животным
            echo '</div>';
            echo '<div class="text">';
            echo '<p><span class="animalInfo">Имя:</span> '.$animal['name'].'</p>';
            echo '<p><span class="animalInfo">Пол:</span> '.$animal['sex'].'</p>';
            echo '<p><span class="animalInfo">Возраст:</span> '.$animal['age'].'</p>';
            echo '<p><span class="animalInfo">Кто это:</span> '.$animal['type'].'</p>';
            echo '<p><span class="animalInfo">О животном:</span> '.$animal['about'].'</p>';
            echo '<p><input class="addToCartButton" type="submit" name="deleteAnimal" value="Удалить"></p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</form>';
        }
    }
}
?>
</body>
