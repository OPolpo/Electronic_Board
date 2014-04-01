<?php

class Board{
	private static $hostName = "localhost";
	private static $userName = "webuser";
	private static $password = "dummypass";
	private static $dbName = "tia";

	public static function getHTML(){
		$db = new mysqli("127.0.0.1", self::$userName, self::$password, self::$dbName, 8889);
    	
    	if ($db->connect_errno)
    	    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
		
		$sql = "SELECT * FROM board";

		if(!$result = $db->query($sql))
    		die('There was an error running the query [' . $db->error . ']');
		
		while($row = $result->fetch_assoc())
   			echo $row['user'] . '<br />';
	}

	public static function submit($user,$proposal){

	}

	public static function checkStatus(){

	}

	public static function sendMail($proposal){

	}
}