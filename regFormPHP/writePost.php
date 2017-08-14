<html>
<head>
    <title>Написать пост.</title>
    <link rel="stylesheet" href="style/Reviews.css">
</head>
<body>
<form action="" method="POST">

<header>
    <h1>Оставьте свой отзыв</h1>
</header>

<main>
<div id="writeReview">
<p>Ваша почта:</p>
<p><input type="text" id="textEnter" name="email"></p>
<p>Оставьте свой отзыв:</p>
<p><textarea rows="10" cols="45" name="postText"></textarea></p>
<p><input type="submit" value="Отправить" id="sendReviewButton"></p>

<?php

if(isset($_POST["postText"]) && isset($_POST["email"])){

    $id = 0;
    $count = 0;
    $email = $_POST["email"];
    $review = $_POST["postText"];

    $fileInOneStr = file_get_contents('files/file.txt'); // записывает весь файл в строку
    $SeparateStrings = explode("\r\n", $fileInOneStr); // разделяет по переносу. возвращает массив

    foreach($SeparateStrings as $value) {
        $JSON_string = json_decode($value);
        $count++;

        // если введенный email совпадаетс c email'ом в базе
        if($JSON_string->login == $email) {
            $id = $count;
            echo "<br/><p>---------------------------------</p>";
            echo "<p>id " . $id . ". Отзыв от " . $email . ":</p>";
            $reviewWithSeparateLine = explode("\r\n", $review);
            foreach ($reviewWithSeparateLine as $separateWord) {
                echo "<p class=reviews>".$separateWord . "<br/>";
            }
            echo "</p>";
            echo "<p>---------------------------------</p>";

            $reviewInFile = array(
                "id" => $id,
                "review" => $review,
            );

            $jsonReview = json_encode($reviewInFile);

            $toOpenFile = fopen('files/reviews.txt', 'a');
            fputs($toOpenFile, "$jsonReview \r\n");
            fclose($toOpenFile);
        }
    }
}
?>
<p><a href="allReviews.php"><input type="button" id="showReviewsButton" name="showAllReviews" value="Показать все отзывы"></a></p>
</div>
</main>
</form>
</body>
</html>
