<?php
/*
 * —одержит метод вывода данных. ¬ наследуемом классе мы его переопределим.
 */

class Model
{
    public function get_data()
    {
    }

    function connect_to_db(){
        $host = 'localhost';
        $dbname = 'catdog';
        $user = 'catdog';
        $pass = 'catdog';
        
		$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		
		return $pdo;
    }
}