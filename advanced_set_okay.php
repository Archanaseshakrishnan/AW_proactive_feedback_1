<!DOCTYPE html>
<html>
<head>
<title>Advanced_trial</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<a href="logout.php">Logout</a>
<?php
//just display the $_SESSION inter_8 and 9
	session_start();
	include 'databaseconnect.php';
	$username = $_SESSION['username'];
	echo "<p>I am in advanced set</p>";
	$question = array();
	$in = 0;
	$res = mysql_query("SELECT * FROM $username WHERE `Difficulty`='3' AND `Appeared_in_this_test`='0'");
	while($row=mysql_fetch_array($res)){
		$question[$in]=$row['id'];
		$in+=1;
	}
?>
	<div class="container question">
	<p>	Responsive Quiz Application Using PHP, MySQL, jQuery, Ajax and Twitter Bootstrap</p>
	<form class="form-horizontal" role="form" id='login' method="post" action="result.php">
	</div>
<?php
	$i=0;
	for($i=0;$i<2;$i+=1){
		if($i<1){
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
			$q5 = "SELECT * FROM `questions` WHERE `id` = '$question[$i]'";
			$result = mysql_fetch_array(mysql_query($q5));
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