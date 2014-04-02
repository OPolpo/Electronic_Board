<?php

class Board{
	private static $hostName = "localhost";
	private static $userName = "webuser";
	private static $password = "dummypass";
	private static $dbName = "tia";
	private static $NO_AGREEMENT = "Agreement not yet reached";

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
		if(self::checkStatus()==self::$NO_AGREEMENT){
			$query="UPDATE board SET proposal='$proposal' WHERE user='$user'";
			self::perform_query($query);
		}
	}

	public static function checkStatus(){
		$result=self::perform_query("SELECT * FROM (SELECT proposal AS prop, COUNT(proposal) AS count FROM board GROUP BY proposal )AS poo HAVING count> (SELECT COUNT(*)/2 FROM board WHERE user IS NOT NULL) AND count>=all(SELECT count(proposal) FROM board GROUP BY proposal)");
		if($row = $result->fetch_assoc()){
			return "Agreement Reached: ".$row['prop'];
			self::sendMail($row['prop']);//everytime i check the status after the agreement is reached i send an exif_thumbnail(filename)
		}
		return self::$NO_AGREEMENT;
	}

	public static function sendMail($proposal){
		$text="è stato raggiunto un accordo, la proposta scelta dalla maggior parte dei membri è: ".$proposal;
		ini_set('SMTP', 'smtp.gmail.com');
		mail('git-projects@omnip.it','final decision',$text,"From:Electronic_Board@webprogrammingclass.com");
	}
}