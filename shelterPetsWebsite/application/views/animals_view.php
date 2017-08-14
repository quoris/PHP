<head>
    <title>Питомцы</title>
    <link rel="stylesheet" type="text/css" href="/css/jquery.autocomplete.css"/>
    <script type="text/javascript" src="/js/jquery-1.5.2.min.js"></script>
    <script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
    <script type="text/javascript" src="/js/script.js"></script>
</head>
<body>
<form action="" method="post" id="showAnimalsForm">

    <br/>
    <div class="textOnAnimalsPage">
        <h1 class="animalsH1">Питомцы.</h1>
        <p>У нас очень много животных, которые ждут своих хозяев. С нашей помощью вы сможете выбрать себе питомца, максимально подходящего вам.<br/>
            Для этого введите свои пожелания в форму ниже и нажмите кнопку "Найти себе зверюшку".<br/>
            <span class="animalInfo">ВНИМАНИЕ:</span> для добавления животного в корзину сперва нужно войти на сайт<br/> (или зарегистрироваться).
        </p>
<?php


    // вывод сообщения о добавленном животном
    if(isset($data) && isset($data['alreadyAdded']) && $data['alreadyAdded'] == 0){
        echo '<p><span class="userAddAnimal">Питомец добавлен в корзину :)</span></p>';
    }


    // вывод сообщения о том, что животное уже добавлено
    if(isset($data) && isset($data['alreadyAdded']) && $data['alreadyAdded'] != 0){
        echo '<p><span class="alreadyAdded">ВНИМАНИЕ: Животное уже добавлено в корзину!</span></p>';
    }


    if(isset($_SESSION['login']) &&  $_SESSION['login'] == 'admin'){
        echo '<p><span class="adminCannotAddAnimals">ВНИМАНИЕ: Вы не можете добавлять животных в корзину!</span></p>';
    }


?>
        <div class="enterParametrsToFind">
            <!--<p>Тип: <input id="animalTypeForJS" type="text" name="typeAnimal" autocomplete="off"> Пол: <input id="sexForJS" type="text" name="sexAnimal" autocomplete="off"> Возраст: <input id="ageForJS" type="text" name="ageAnimal"></p>-->
            <ul class="filter">
                <li>Тип:
                    <select name="typeAnimal" class="inputAnimalsInfo">
                        <option>Кошка</option>
                        <option>Собака</option>
                        <option>Попугай</option>
                        <option>Хомяк</option>
                        <option>Морская свинка</option>
                        <option>Черепаха</option>
                        <option selected >Енот</option>
                    </select>
                </li>
                <li>Пол:
                    <select name="sexAnimal" class="inputAnimalsInfo">
                        <option>женский</option>
                        <option selected>мужской</option>
                    </select>
                </li>
                <li>Возраст (лет):
                    <select name="ageAnimal" class="inputAnimalsInfo">
                        <option>6</option>
                        <option>5</option>
                        <option>4</option>
                        <option>3</option>
                        <option>2</option>
                        <option selected>1</option>
                    </select>
                </li>
            </ul>
            <p><input class="findAnimalButton" type="submit" name="findAnimal" value="Найти себе зверюшку"></p>
        </div>
</form>
<?php

        /*
        // вывод сообщения о добавленном животном
        if(isset($data) && isset($data['added_animal_name']) && $data['added_animal_name'] != 'false'){
            echo '<p><span class="userAddAnimal">Питомец '.$data['added_animal_name'].' добавлен в корзину :)</span></p>';
        }
        if(isset($_SESSION['login']) &&  $_SESSION['login'] == 'admin'){
            echo '<p><span class="adminCannotAddAnimals">ВНИМАНИЕ: Вы не можете добавлять животных в корзину!</span></p>';
        }
        */

        // вывод всех животных
        if(isset($data)){
            foreach($data as $animal){
                if(isset($animal['a_id_animal']) && isset($animal['a_name']) && isset($animal['a_age']) && isset($animal['a_sex']) && isset($animal['a_type']) && isset($animal['a_photo']) && isset($animal['a_about']) && !isset($_POST['findAnimal'])){
                    echo '<form action="" method="post">';
                    echo '<div class="showedAnimal">';
                    echo '<div class="left">';
                    echo '<p><img src=/files/sizedAvatars/'.$animal['a_photo'].'></p>';
                    echo '<p><input id="id" type="hidden" name="id_animal" value='.$animal['a_id_animal'].' ></p>'; // задаем айди животным
                    echo '</div>';
                    echo '<div class="right">';
                    echo '<p><span class="animalInfo">Имя:</span> '.$animal['a_name'].'</p>';
                    echo '<p><span class="animalInfo">Пол:</span> '.$animal['a_sex'].'</p>';
                    echo '<p><span class="animalInfo">Возраст:</span> '.$animal['a_age'].'</p>';
                    echo '<p><span class="animalInfo">Кто это:</span> '.$animal['a_type'].'</p>';
                    echo '<p><span class="animalInfo">О животном:</span> '.$animal['a_about'].'</p>';
                    echo '<p><input class="addToCartButton" type="submit" name="takeAnimal" value="Хочу"></p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</form>';
                }
            }
        }

        //Вывод животных с параметрами
        if(isset($data) && isset($_POST['findAnimal'])){
            foreach($data as $animal){
                $_POST['a_id_animal'] = $animal['a_id_animal'];
                $_POST['a_name'] = $animal['a_name'];
                $_POST['a_age'] = $animal['a_age'];
                $_POST['a_sex'] = $animal['a_sex'];
                $_POST['a_type'] = $animal['a_type'];
                $_POST['a_photo'] = $animal['a_photo'];
                $_POST['a_about'] = $animal['a_about'];

                if(isset($_POST['a_id_animal']) && isset($_POST['a_name']) && isset($_POST['a_age']) && isset($_POST['a_sex']) && isset($_POST['a_type']) && isset($_POST['a_photo']) && isset($_POST['a_about'])){
                    echo '<div class="showedAnimal">';
                    echo '<div class="left">';
                    echo '<p><img src=/files/sizedAvatars/'.$animal['a_photo'].'></p>';
                    echo '<p><input type="hidden" name="id_animal" value='.$animal['a_id_animal'].' ></p>'; // задаем айди животным
                    echo '</div>';
                    echo '<div class="right">';
                    echo '<p><span class="animalInfo">Имя:</span> '.$animal['a_name'].'</p>';
                    echo '<p><span class="animalInfo">Пол:</span> '.$animal['a_sex'].'</p>';
                    echo '<p><span class="animalInfo">Возраст:</span> '.$animal['a_age'].'</p>';
                    echo '<p><span class="animalInfo">Кто это:</span> '.$animal['a_type'].'</p>';
                    echo '<p><span class="animalInfo">О животном:</span> '.$animal['a_about'].'</p>';
                    echo '<p><input class="addToCartButton" type="submit" name="takeAnimal" value="Хочу"></p>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }


        ?>
    </div>
</body>