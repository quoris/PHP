<html>
<head>
    <title>�������� �����������</title>
    <link rel="stylesheet" href="style/answerPage.css">
</head>
<body>

<header>
<h1>��� �������</h1>
</header>

<main>
<div id="Reg">
<?php

include "functions.php";
session_start();

$log = $_SESSION["log"];
$pass = $_SESSION["pass"];
$sex = $_SESSION["sex"];
$checkbox = $_SESSION["checkbox"];
$about = $_SESSION["about"];

$patternLog = "/^[a-zA-Z0-9_\-.]+@[a-zA-Z]+\.[a-z]+$/";
$patternPass = "/^[a-zA-Z0-9_\-.]+$/";

$resPatLog = preg_match($patternLog, $log);
$resPatPass = preg_match($patternPass, $pass);


if($resPatLog == 1 && $resPatPass == 1){
    echo "<p><span class='point'>1. ��� �����:</span> $log</p>
          <p><span class='point'>2. ��� ������:</span> $pass</p>";
    if($sex == man){
        echo "<p><span class='point'>3. ��� ���:</span> �������</p>";
    }else{
        echo "<p><span class='point'>3. ��� ���:</span> �������</p>";
    }
    if($checkbox == subscribe){
        echo "<p><span class='point'>4. �� ����������� �� �������.</span></p>";
    } else {
        echo "<p><span class='point'>4. �� �� ����������� �� �������.</span></p>";
    }
    echo "<p><span class='point'>5. �� � ����:</span> $about</p>";
    echo "<p><a href='writePost.php'>�������� ���� ����.</a></p>";


    echo "set_avatar == ".$set_avatar; // �� ����� ��� ����, ������ ��������� � functions.php


    echo "<br/><p>������ ���� �������������:</p>";
    inFile();
    echo "<table border='5'>";
    echo "<tr><th>�����</th><th>������</th><th>���</th><th>�������� �� �������</th><th>� ����</th></tr>";
    fromFile();

} else {
    if($resPatLog != 1 && $resPatPass != 1){
        echo "<p>����������� ������� ����� � ������.</p><p>����������, ���������.</p><p><a href=index.php><button class='button'>���������</button></a></p>";
    } else {
        if($resPatLog != 1){
            echo "<p>����������� ������ �����.</p><p>����������, ���������.</p><p><a href=index.php><button class='button'>���������</button></a></p>";
        } else {
            echo "<p>����������� ������ ������.</p><p>����������, ���������.</p><p><a href=index.php><button class='button'>���������</button></a></p>";
        }
    }
}
?>
</div>
<div style="clear:both"></div>
</main>



</body>
</html>