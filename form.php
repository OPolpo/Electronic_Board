<?php
//mysql_select_db('tia');
ini_set("display_errors",1);
function __autoload($classname){
 	require_once $classname.'.php';
}
echo "<br>";
if(isset($_POST["proposal"]) and $_POST["proposal"]!="" and $_POST["email"]!="")
	Board::submit($_POST["email"],$_POST["proposal"]);

Board::getHTML();
?>