<?php
session_start();
include 'databaseconnect.php';
$username = $_SESSION['username'];
$diff = $_SESSION['difficulty'];
$keys=array_keys($_POST);

array_pop($keys);
$order=join(",",$keys);
echo $order;
$question=array();
$response=mysql_query("select `id`,`Answer`,`Question` from `questions` where `id` IN($order) ORDER BY FIELD(`id`,$order)") or die(mysql_error());	
$result = mysql_fetch_array($response);
$id = $result["id"];
mysql_query("UPDATE $username SET `Appeared_in_this_test`='1' WHERE `id`='$id'");
if($diff == '2'){	
	if($result["Answer"]==$_POST[$result["id"]]){
		header( "Location: intermediate_set_okay.php" ) ;
	}
	else{
		header( "Location: intermediate_set_not_okay.php" ) ;
	}
}else if($diff == '3'){
	if($result["Answer"]==$_POST[$result["id"]]){
		header( "Location: advanced_set_okay.php" ) ;
	}
	else{
		header( "Location: advanced_set_not_okay.php" ) ;
	}
}
?>