<?php

class Board{
	private static $hostName = "localhost";
	private static $userName = "webuser";
	private static $password = "dummypass";
	private static $dbName = "tia";
	private static $NO_AGREEMENT = "Agreement not yet reached";
	private static $db;

	public static function open(){
		self::$db = new mysqli("127.0.0.1", self::$userName, self::$password, self::$dbName, 8889);//connecting to dbq
		if (self::$db->connect_errno)
    	    echo "Failed to connect to MySQL: (" . self::$db->connect_errno . ") " . self::$db->connect_error;

	}
	public static function close(){
		self::$db->close();
	}

	public static function perform_query($query){
		if(!$result = self::$db->query($query))
    		die('There was an error running the query [' . self::$db->error . ']');

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
			self::sendMail($row['prop']);//everytime i check the status after the agreement is reached i send an email
			return "Agreement Reached: ".$row['prop'];
		}
		return self::$NO_AGREEMENT;
	}

	public static function sendMail($proposal){
		$text="An agreement is reached, the final decision is: ".$proposal;
		ini_set ("SMTP","aspmx.l.google.com");
		ini_set ("sendmail_from","Electronic_Board@webprogrammingclass.com");
		ini_set("smtp_port","25");
		if(mail('git-projects@omnip.it','final decision',$text))
			echo "<script type='text/javascript'>alert('Agreement Reached - Email sent');</script>";
	}
}