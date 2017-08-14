<head>
    <title>Админка</title>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
    <br/>
    <h1 class="adminH1">Вы вошли как админ.</h1>
    <div class="textOnAdminPage">
        <p>Заполните все поля формы и нажмите на кнопку с нужным действием. <br/>
            <span class="attention">ВНИМАНИЕ:</span><br/>Для удаления можете ввести только: Имя, Возраст, Пол и Тип животного.<br/>
            Для редактирования: обязательно введите Имя и Тип животного.
        </p>
        <?php

        if(isset($data) && isset($data['isAdded']) && $data['isAdded']== true){
            echo "<p>Ура! Животное добавлено :)</p>";
        }

        if(isset($data) && isset($data['isEdit']) && $data['isEdit']== true){
            echo "<p>Ура! Животное отредактировано :)</p>";
        }

        if(isset($data) && isset($data['isRemove']) && $data['isRemove']== true){
            echo "<p>Животное удалено :)</p>";
        }
        // тест
        if(isset($data) && isset($data['isRemove']) && $data['isRemove']== false){
            echo "<p>ОШИБКА: Животного с такими данными нет. Пожалуйста, введите существующую информацию.</p>";
        }

        if(isset($data) && isset($data['errorSize']) && $data['errorSize'] == false ) {
            echo "<p>Постойте! Загружемый файл больше 5мб. Пожалуйста, выберите размер поменьше.</p>";
        }
        if(isset($data) && isset($data['errorFormat']) && $data['errorFormat'] == false) {
            echo "<p>Постойте! Нужен подходящий формат (jpg, JPG, JPEG, png).</p>";
        }
        ?>
        <p><span class="infoToAddAnimal">1. Имя:</span> <input class="inputAdminInfo" type="text" name="nameAnimal" maxlength="20" placeholder="имя"></p>
        <p><span class="infoToAddAnimal">2. Возраст</span> (1-6 лет): <input class="inputAdminInfo" type="text" name="ageAnimal" maxlength="2" placeholder="возраст"></p>
        <p><span class="infoToAddAnimal">3. Пол:</span><input type="radio" name="sexAnimal" value="мужской" checked> мужской<input type="radio" name="sexAnimal" value="женский"> женский</p>
        <p><span class="infoToAddAnimal">4. Кто это:</span>
            <select class="inputAdminInfo" name="typeAnimal">
                <option>Кошка</option>
                <option>Собака</option>
                <option>Попугай</option>
                <option>Хомяк</option>
                <option>Морская свинка</option>
                <option>Черепаха</option>
                <option selected>Енот</option>
            </select>
        </p>
        <p><span class="infoToAddAnimal">5. Фото</span> (до 5 мб): <input class="uploadPhoto" type="file" name="uploadAvatar" value="downloadAnimalAvatar"></p>
        <p><span class="infoToAddAnimal">6. Информация о животном</span> (макс. 200 символов): </p>

        <p><textarea class="textareaAdminInfo" cols="50" rows="8" maxlength="200" name="aboutAnimal"></textarea></p>
        <p><button class="adminButton" type="submit" name="add" value="addAnimal">Добавить животное</button></p>
        <p><button class="adminButton" type="submit" name="edit" value="editAnimal">Редактировать животное</button></p>
        <p><button class="adminButton" type="submit" name="remove" value="removeAnimal">Удалить животное</button></p>
        <br/>
    </div>
</form>
</body>