<?php
session_start();
include 'databaseconnect.php';
$username = $_SESSION['username'];

$keys=array_keys($_POST);
array_pop($keys);
$order=join(",",$keys);
//echo $order;
$question=array();
$crt=0;
$response=mysql_query("select `id`,`Answer`,`Question` from `questions` where `id` IN($order) ORDER BY FIELD(`id`,$order)") or die(mysql_error());	
while($result=mysql_fetch_array($response)){
	echo "here";
	$thisques = $result["id"];
	array_push($question,$thisques);
	echo $thisques;
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
	echo $_POST[$result["id"]];
	if($result["Answer"]==$_POST[$result["id"]]){
		//modify the questions appeared in this test to 1, total_attempts+1, total_correct+1 in both the tables
		$correct+=1;
		$totalcorrect+=1;
		$q10 = "UPDATE $username SET `Total_Correct`='$correct' WHERE `id`='$thisques'";
		mysql_query($q10);
		$q11 = "UPDATE `questions` SET `Number_correct`='$totalcorrect' WHERE `id`='$thisques'";
		mysql_query($q11);
		$crt++;
	}
}

$level = $_SESSION['difficulty'];
$user_type = $_SESSION['user_type'];
if($level == $user_type){
	restore_table($question, $username);
	if($crt>=2){
		//okay
	}
	else{
		//downgrade user_level
		$user_type-=1;
		mysql_query("UPDATE $username SET `Total_Attempts`=0,`Total_Correct`=0");
		mysql_query("UPDATE `users` SET `User_Type`='$user_type', `Strength`=NULL, `Weakness`=NULL, `Undefined`=NULL");
	}
	header( "Location: analysis.php" ) ;
}

function restore_table($question, $username){
		//iterate through all the questions and then recompute the difficulty
		//if there is a change then update the user table
		foreach($question as $q){
			$q12 = "SELECT * FROM `questions` WHERE `id`='$q'";
			$result = mysql_fetch_array(mysql_query($q12));
			$attempt = $result['Number_of_attempts'];
			$correct = $result['Number_correct'];
			mysql_query("UPDATE $username SET `Appeared_in_this_test`='0' WHERE `id`='$q'");
			
			if($attempt>10){
				$score = $correct/(float)$attempt;
				$thisques = $result['id'];
				if($score<0.3){
						//update difficulty 
						$diff = $result['Difficulty'];
						$diff+=1;
						mysql_query("UPDATE `questions` SET `Difficulty`='$diff' WHERE `id`='$q'");
						mysql_query("UPDATE $username SET `Difficulty`='$diff' WHERE `id`='$q'");
				}
			}
		}
	}
?>