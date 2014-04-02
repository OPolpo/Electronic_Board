<?php

class Board{
	private static $hostName = "localhost";
	private static $userName = "webuser";
	private static $password = "dummypass";
	private static $dbName = "tia";

	public static function perform_query($query){
		$db = new mysqli("127.0.0.1", self::$userName, self::$password, self::$dbName, 8889);//connecting to dbq
    	if ($db->connect_errno)
    	    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
		
		if(!$result = $db->query($query))
    		die('There was an error running the query [' . $db->error . ']');

    	$db->close();//Closing Connection

		return $result;
	}

	public static function getHTML(){
		$result=self::perform_query("SELECT * FROM board WHERE proposal IS NOT NULL");
		
   		printf("(%s)<br />",self::checkStatus());//printing status

   		//printing proposals
		print("<ul>");
		while($row = $result->fetch_assoc())
   			printf("<li>%s<br />",$row['proposal']);
		print("</ul>");
	}

	public static function submit($user,$proposal){
		$result=self::perform_query("INSERT INTO board VALUES(\"$user\",\"$proposal\")");//query
		if(!$result = $db->query($sql))
    		die('There was an error running the query [' . $db->error . ']');
	}

	public static function checkStatus(){
		$result=self::perform_query("SELECT * FROM (SELECT proposal AS prop, COUNT(proposal) AS count FROM board GROUP BY proposal )AS poo HAVING count> (SELECT COUNT(*)/2 FROM board WHERE user IS NOT NULL) AND count>=all(SELECT count(proposal) FROM board GROUP BY proposal)");
		if($row = $result->fetch_assoc())
			return "YES =====> ".$row['prop'];
		return "NO";
	}

	public static function sendMail($proposal){

	}
}