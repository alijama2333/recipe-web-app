<?php
include "db.php";

$Link=mysql_connect($host, $username, $password) OR die(my_sql_error());

//Insert PHP statements to manage the database

my_sql_close($Link);
?>