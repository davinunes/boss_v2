<?php
include "database.php";
session_start();

if(!$_POST[login]){
	exit;
}else{
	$login = $_POST[login];
}
//echo "<pre>";

foreach(historico($login) as $a){
	echo $a[id]."\n";
	mensagens(dbMsg($a[id]));
}
?>