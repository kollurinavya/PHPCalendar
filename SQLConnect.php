<?php 
$host = "localhost";
$user= "root";
$pwd = "";
$db_name = "phpcalendar";
$con = mysql_connect("$host","$user","$pwd") or die("not able to connect to mysql");
mysql_select_db("$db_name") or die("no database");

?>