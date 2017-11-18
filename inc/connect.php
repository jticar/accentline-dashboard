<?php
error_reporting (E_ALL ^ E_DEPRECATED);
 $host = "username"; //host name
 $port ="3306";
 $username ="cron"; //Mysql username
 $password="yourpassword"; //mysql password
 $database = "asterisk"; //database name
$mysql=mysql_connect("$host", "$username", "$password", "$port") or die("cannot connect");
mysql_select_db("$database")or die("cannot select DB");
?>
