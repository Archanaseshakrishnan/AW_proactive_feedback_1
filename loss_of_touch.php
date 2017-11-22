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
$q7 = "SELECT `Total_Attempts`, `Total_Correct` FROM $username WHERE `id`='$thisques'";
$q7result = mysql_fetch_array(mysql_query($q7));
$attempts = $q7result["Total_Attempts"];
$attempts+=1;
$correct = $q7result["Total_Correct"];
$q8 = "SELECT `Number_of_attempts`, `Number_correct` FROM `questions` WHERE `id`='$thisques'";
$q8result = mysql_fetch_array(mysql_query($q8));
$totalattempts = $q8result["Number_of_attempts"]; 
$totalattempts+=1;
$totalcorrect = $q8result['Number_correct'];
$q6 = "UPDATE $username SET `Appeared_in_this_test`='1', `Total_Attempts`='$attempts' WHERE `id`='$thisques'";
mysql_query($q6);
$q9 = "UPDATE `questions` SET `Number_of_attempts`='$totalattempts' WHERE `id`='$thisques'";
mysql_query($q9);
	
if($diff == '2'){	
	if($result["Answer"]==$_POST[$result["id"]]){
		$correct+=1;
		$totalcorrect+=1;
		$q10 = "UPDATE $username SET `Total_Correct`='$correct' WHERE `id`='$thisques'";
		mysql_query($q10);
		$q11 = "UPDATE `questions` SET `Number_correct`='$totalcorrect' WHERE `id`='$thisques'";
		mysql_query($q11);
		header( "Location: intermediate_set_okay.php" ) ;
	}
	else{
		header( "Location: intermediate_set_not_okay.php" ) ;
	}
}else if($diff == '3'){
	if($result["Answer"]==$_POST[$result["id"]]){
		$correct+=1;
		$totalcorrect+=1;
		$q10 = "UPDATE $username SET `Total_Correct`='$correct' WHERE `id`='$thisques'";
		mysql_query($q10);
		$q11 = "UPDATE `questions` SET `Number_correct`='$totalcorrect' WHERE `id`='$thisques'";
		mysql_query($q11);
		header( "Location: advanced_set_okay.php" ) ;
	}
	else{
		header( "Location: advanced_set_not_okay.php" ) ;
	}
}
?>