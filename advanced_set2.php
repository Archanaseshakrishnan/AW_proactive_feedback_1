<!DOCTYPE html>
<html>
<head>
<title>Intermediate_trial</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<a href="logout.php">Logout</a>
<?php
	session_start();
	include 'databaseconnect.php';
	$username = $_SESSION['username'];
	echo "<p>I am in advanced set</p>";
	$diffi = $_SESSION['difficulty'];
	//implement loss of touch?>
	<div class="container question">
	<p>	Responsive Quiz Application Using PHP, MySQL, jQuery, Ajax and Twitter Bootstrap</p>
	<form class="form-horizontal" role="form" id='login' method="post" action="loss_of_touch.php">
	</div>
<?php
	$temp = $diffi-1;
	$q1 = "SELECT * FROM $username WHERE `Difficulty`= $temp";
	$result = mysql_query($q1);
	$pref1 = array();
	$pref1index=0;
	$pref2 = array();
	$pref2index=0;
	
	$question = array();
	$index = 0;
	while($row=mysql_fetch_array($result)){
		if($row['Total_Correct']==0){
			$pref1[$pref1index]=$row['id'];
			$pref1index+=1;
		}
		else{
			$pref2[$pref2index]=$row['id'];
			$pref2index+=1;
		}
	}
	if($pref1index!=0){
		$t = mt_rand()%count($pref1);
		$question[$index]=$pref1[$t];
		$index+=1;
	}
	else{
		$t = mt_rand()%count($pref2);
		$question[$index]=$pref2[$t];
		$index+=1;
	}
	echo $question[0];
	$q2 = "SELECT * FROM `questions` WHERE `id`='$question[0]'";
	$result = mysql_fetch_array(mysql_query($q2));
	//echo $result['Question'];
	//echo $result['Option1'];
?>
<div id='question<?php echo "8";?>' class='cont'>
<p class='questions' id="qname<?php echo "8";?>"><?php echo "8";?>.<?php echo $result['Question'];?><?php echo $result['id']?></p>
<input type="radio" value="<?php echo $result['Option1'];?>" id='radio1_<?php echo "8";?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option1'];?><br/>
<input type="radio" value="<?php echo $result['Option2'];?>" id='radio2_<?php echo "8";?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option2'];?><br/>
<input type="radio" value="<?php echo $result['Option3'];?>" id='radio3_<?php echo "8";?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option3'];?><br/>
<input type="radio" value="<?php echo $result['Option4'];?>" id='radio4_<?php echo "8";?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option4'];?><br/>
<center><button id='next<?php echo "8";?>' class='next' type='submit' name='eighth'>Next</button><br>
</div>
</form>


</body>
</html>