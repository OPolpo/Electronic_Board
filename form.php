<?php
//mysql_select_db('tia');
ini_set("display_errors",1);
function __autoload($classname){
 	require_once $classname.'.php';
}



Board::getHTML();
?>