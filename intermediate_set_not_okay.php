<!DOCTYPE html>
<html>
<head>
<title>Intermediate_trial</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<?php
//just display the $_SESSION inter_8 and 9
	session_start();
	include 'databaseconnect.php';
	$username = $_SESSION['username'];
	$diffi = $_SESSION['difficulty'];
	echo "<p>I am in intermediate_set</p>";?>
	<div class="container question">
	<p>	Responsive Quiz Application Using PHP, MySQL, jQuery, Ajax and Twitter Bootstrap</p>
	<form class="form-horizontal" role="form" id='login' method="post" action="result_loss_of_touch.php">
	</div>
<?php
	$temp = $diffi-1;
	$q1 = "SELECT * FROM $username WHERE `Difficulty`= $temp AND `Appeared_in_this_test`='0'";
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
	for(int i=0;i<$pref1index;$i+=1){
		$t = mt_rand()%count($pref1);
		$question[$index]=$pref1[$t];
		$index+=1;
	}
	for(int i=0;i<$pref2index;$i+=1){
		$t = mt_rand()%count($pref2);
		$question[$index]=$pref2[$t];
		$index+=1;
	}
	
?>
<?php
	$i=0;
	for($i=0;$i<2;$i+=1){
		if($i<2-1){
			$q5 = "SELECT * FROM `questions` WHERE `id` = '$question[$i]'";
			$result = mysql_fetch_array(mysql_query($q5));
?>
	<div id='question<?php echo $i;?>' class='cont'>
	<p class='questions' id="qname<?php echo $i;?>"><?php echo $i+9?>.<?php echo $result['Question'];?><?php echo $result['id']?></p>
	<input type="radio" value="<?php echo $result['Option1'];?>" id='radio1_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option1'];?><br/>
	<input type="radio" value="<?php echo $result['Option2'];?>" id='radio2_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option2'];?><br/>
	<input type="radio" value="<?php echo $result['Option3'];?>" id='radio3_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option3'];?><br/>
	<input type="radio" value="<?php echo $result['Option4'];?>" id='radio4_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option4'];?><br/>
	
	<center><button id='next<?php echo $i;?>' class='next' type='button'>Next</button></center><br>
	</div>
<?php
		}
		else{
?>
	<div id='question<?php echo $i;?>' class='cont'>
	<p class='questions' id="qname<?php echo $i;?>"><?php echo $i+9?>.<?php echo $result['Question'];?><?php echo $result['id']?></p>
	<input type="radio" value="<?php echo $result['Option1'];?>" id='radio1_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option1'];?><br/>
	<input type="radio" value="<?php echo $result['Option2'];?>" id='radio2_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option2'];?><br/>
	<input type="radio" value="<?php echo $result['Option3'];?>" id='radio3_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option3'];?><br/>
	<input type="radio" value="<?php echo $result['Option4'];?>" id='radio4_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option4'];?><br/>
	
	<center><button id='next<?php echo $i;?>' class='next' type='submit' name='inter_set_okay'>Next</button><br>
	</div>
	<?php
		}
	}?>
	</form>
	<script>
		$('.cont').hide();
		count=$('.questions').length;
		 $('#question'+0).show();

		 $(document).on('click','.next',function(){
		     element=$(this).attr('id');
		     last = parseInt(element.substr(element.length - 1));
		     nex=last+1;
		     $('#question'+last).hide();

		     $('#question'+nex).show();
		 });

</script>
</body>
</html>