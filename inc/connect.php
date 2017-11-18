<?php
error_reporting (E_ALL ^ E_DEPRECATED);
 $host = ""; //host name
 $port ="3306";
 $username ="cron"; //Mysql username
 $password="!!p00dtr1ff911"; //mysql password
 $database = "asterisk"; //database name
$mysql=mysql_connect("$host", "$username", "$password", "$port") or die("cannot connect");
mysql_select_db("$database")or die("cannot select DB");
?>
