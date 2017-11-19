<?php
session_start();
include 'databaseconnect.php';
$username = $_SESSION['username'];

$keys=array_keys($_POST);
array_pop($keys);
$order=join(",",$keys);
//echo $order;
$question=array();
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
	}
}


$level = $_SESSION['difficulty'];
$user_type = $_SESSION['user_type'];
if($level != $user_type){
	restore_table($question, $username);
	$qscore = "SELECT * FROM $username WHERE `Difficulty`='$level'";
	$resu = mysql_query($qscore);
	$total_num_of_ques = mysql_num_rows($resu);
	$qscore = "SELECT * FROM $username WHERE `Difficulty`='$level' AND `Total_Correct`>=1";
	$resu2 = mysql_query($qscore);
	$correct = mysql_num_rows($resu2);
	$score = $correct/(float)$total_num_of_ques;
	//echo $score;
	if($score > 0.5){
		//update User_Type
		$level+=1;
		$qw = "UPDATE `users` SET `User_Type` = '$level' WHERE `Username`='$username'";
	}
	header( "Location: analysis.php" ) ;
}
else{
	if($level=='2'){
		$q = "SELECT * FROM $username WHERE `Appeared_in_this_test`='1'";
		$r = mysql_query($q);
		if(mysql_num_rows($r)<=7){
			header( "Location: intermediate_set_trial2.php" ) ;
		}
		else{
			$new_set=array();
			while($row=mysql_fetch_array($r)){
				array_push($new_set,$row['id']);
			}
			restore_table($new_set, $username);
			header( "Location: analysis.php" ) ;
			
		}
	}
	else if($level=='3'){
		$q = "SELECT * FROM $username WHERE `Appeared_in_this_test`='1'";
		$r = mysql_query($q);
		if(mysql_num_rows($r)<=7){
			header( "Location: advanced_set2.php" ) ;
		}
		else{
			$new_set=array();
			while($row=mysql_fetch_array($r)){
				array_push($new_set,$row['id']);
			}
			restore_table($new_set, $username);
			header( "Location: analysis.php" ) ;
			
		}
	}
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
				$thisques = $result['Question'];
				if($score<0.3){
						//update difficulty 
						
						$diff = $result['Difficulty'];
						if($diff<3){
						$diff+=1;
						mysql_query("UPDATE `questions` SET `Difficulty`='$diff' WHERE `id`='$q'");
						mysql_query("UPDATE $username SET `Difficulty`='$diff' WHERE `id`='$q'");
						}
				}
			}
		}
	}

?>