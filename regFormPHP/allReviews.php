<html>
<head>
    <title>�������� ����.</title>
    <link rel="stylesheet" href="style/Reviews.css">
</head>
<body>

<header>
    <h1>������ ���� �������</h1>
</header>
<main>
<div id="showReview">

<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

$reviewsInOneStr = file_get_contents('files/reviews.txt'); // ���������� ���� ���� � ������
$SeparateReviews = explode("\r\n", $reviewsInOneStr); // ��������� �� ��������. ���������� ������

$allinfInOneStr = file_get_contents('files/file.txt'); // ���������� ���� ���� � ������
$SeparateStrAllinf = explode("\r\n", $allinfInOneStr); // ��������� �� ��������. ���������� ������

$numberStr = 0;
$getEmail = "";

for($i = 0; $i < count($SeparateReviews)-1; $i++){
    $JSON_toFindId = json_decode($SeparateReviews[$i]);
    $getId = $JSON_toFindId->id;
    $getReview = $JSON_toFindId->review;
    $SeparateReviewsStr = explode("\r\n", $getReview);

    for($j = 0; $j < count($SeparateStrAllinf); $j++){
        $numberStr++;

        if($getId == $numberStr) {
            $JSON_toGetEmail = json_decode($SeparateStrAllinf[$j]);
            $getEmail = $JSON_toGetEmail->login;
        }
    }
    $numberStr = 0;

    echo "<p>#".$getId.". ����� �� ".$getEmail.":</p>";
    foreach($SeparateReviewsStr as $elemRev){
        echo "<p class='reviews'>".$elemRev."<br/>";
    }
    echo "</p>";
    echo "<br/><br/>";
}
/*
foreach($SeparateReviews as $toFindId){
    $JSON_toFindId = json_decode($toFindId);
    $getId = $JSON_toFindId->id;
    $getReview = $JSON_toFindId->review;

    foreach($SeparateStrAllinf as $toGetEmail){
        $numberStr++;
        $JSON_toGetEmail = json_decode($toGetEmail);

        if($getId == $numberStr){
            $getEmail = $JSON_toGetEmail->login;
        }
    }

    echo "<p>#".$getId.". ����� �� ".$getEmail.":</p>";
    echo $getReview."<br/><br/>";
}
*/
?>

</div>
</main>
</body>
</html>
