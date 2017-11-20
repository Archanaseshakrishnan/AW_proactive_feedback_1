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
	echo "<p>I am in novice_set</p>";
	$_SESSION['difficulty']="1";
	
	//check User_Type
	$q1 = "SELECT * FROM `users` WHERE `Username`='$username'";
	$result = mysql_fetch_array(mysql_query($q1));
	if($result["User_Type"]==1){
		$_SESSION['user_type']=1;
		$q1 = "SELECT * FROM `users` WHERE `Username`='$username'";
		$result = mysql_fetch_array(mysql_query($q1));
		$query = "SELECT `Difficulty`,COUNT(`Difficulty`) AS `counti` FROM $username GROUP BY `Difficulty` HAVING `Difficulty`='1' ";
		$result = mysql_query($query)or die($myQuery."<br/><br/>".mysql_error());
		$row = mysql_fetch_array( $result);
		$num_of_rows=$row["counti"];
		if($num_of_rows>=10){
			//means there are atleast 10 questions in total in this category
			$q1 = "SELECT * FROM $username WHERE `Difficulty`='1'";
			$res1 = mysql_query($q1);
			//working
			$interestindex=0;
			$interest=array();//interest and no correct - priority1
			$no_interestindex=0;
			$no_interest=array();//no interest and no correct - priority2
			$others_index=0;
			$others=array();//correct but not appeared in this test - priority3
			$questions=array();
			$index=0;//index for the final array
			$appearance=array();
			$appearance['interest']=array();
			$appearance['no_interest']=array();
			$appearance['others']=array();
			while($row=mysql_fetch_array($res1)){
				if($row['Interest']==1){
					if($row["Total_Correct"]==0){
						$interest[$interestindex]=$row["Question"];
						$interestindex+=1;
					}else{
						$others[$others_index]=$row["Question"];
						$others_index+=1;
					}
				}
				else{
					if($row["Total_Correct"]==0){
						$no_interest[$no_interestindex]=$row["Question"];
						$no_interestindex+=1;
					}else{
						$others[$others_index]=$row["Question"];
						$others_index+=1;
					}
				}
			}
			if(count($interest)+count($no_interest)+count($others) >= 10 && count($interest)+count($no_interest)>=4){
				//fine just randomize the questions and ask
				for($i=0;$i<10;$i+=1){
					$index = mt_rand()%10;
					//resolving collision
					while(array_key_exists($index,$questions))
						$index = ($index+1)%10;
					//check incase $interest runs out
					if($i<count($interest)){
						$t=mt_rand()%$interestindex;
						while(in_array($interest[$t],$appearance['interest']))
						{
							$t=mt_rand()%$interestindex;
						}
						$questions[$index]=$interest[$t];
						array_push($appearance['interest'],$interest[$t]);
					}
					else if($i-count($interest)<count($no_interest)){
						//if interest section is over remaining from no_interest
						$t=mt_rand()%$no_interestindex;
						while(in_array($no_interest[$t],$appearance['no_interest'])){
							$t=mt_rand()%$no_interestindex;
						}
						$questions[$index]=$no_interest[$t];
					}
					else{
						//others
						$t=mt_rand()%$others_index;
						while(in_array($others[$t],$appearance['others'])){
							$t=mt_rand()%$others_index;
						}
						$questions[$index]=$others[$t];	
					}
				}
				//print_r($questions);
				display_question($questions, $username,10);//function to select the question from the table, display it, record the response, update the user table and the Questions table namely appeared_in_this_test, total correct, total attempts
			}
			else
			{
				//update User_type and call intermediate function
				$q3 = "UPDATE `users` SET `User_Type` = '2' WHERE `Username`='$username'";
				mysql_query($q3);
				header( "Location: intermediate_set_trial.php" ) ;
			}
		}
		else{
			//less than 10 questions in this level 
			$result2 = mysql_query("SELECT * FROM $username WHERE `Difficulty`='1'");
			while($row = mysql_fetch_array($result2)){
				$diff = $row['Difficulty'];
				$diff+=1;
				$thisques = $row['Question'];
				mysql_query("UPDATE $username SET `Difficulty`='$diff' WHERE `Question`='$thisques'");
			}
			header("Location: intermediate_set_trial.php");
		}
	}
	else{
		//user belong to this type
	}
	function display_question($question, $username, $bound){
?>		<div class="container question">
		<p>	Responsive Quiz Application Using PHP, MySQL, jQuery, Ajax and Twitter Bootstrap</p>
		<form class="form-horizontal" role="form" id='login' method="post" action="result.php">
		</div>
		<?php
			$i=0;
			for($i=0;$i<$bound;$i+=1){
				if($i<$bound-1){
					$q5 = "SELECT * FROM `questions` WHERE `Question` = '$question[$i]'";
					$result = mysql_fetch_array(mysql_query($q5));
				
				?>
				<div id='question<?php echo $i;?>' class='cont'>
				<p class='questions' id="qname<?php echo $i;?>"><?php echo $i+1?>.<?php echo $result['Question'];?><?php echo $result['id']?></p>
				<input type="radio" value="<?php echo $result['Option1'];?>" id='radio1_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option1'];?><br/>
				<input type="radio" value="<?php echo $result['Option2'];?>" id='radio2_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option2'];?><br/>
				<input type="radio" value="<?php echo $result['Option3'];?>" id='radio3_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option3'];?><br/>
				<input type="radio" value="<?php echo $result['Option4'];?>" id='radio4_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option4'];?><br/>
				
				<center><button id='next<?php echo $i;?>' class='next' type='button'>Next</button></center><br>
				</div>
		<?php
				}
				else{
					$q5 = "SELECT * FROM `questions` WHERE `Question` = '$question[$i]'";
					$result = mysql_fetch_array(mysql_query($q5));
				?>
				<div id='question<?php echo $i;?>' class='cont'>
				<p class='questions' id="qname<?php echo $i;?>"><?php echo $i+1?>.<?php echo $result['Question'];?><?php echo $result['id']?></p>
				<input type="radio" value="<?php echo $result['Option1'];?>" id='radio1_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option1'];?><br/>
				<input type="radio" value="<?php echo $result['Option2'];?>" id='radio2_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option2'];?><br/>
				<input type="radio" value="<?php echo $result['Option3'];?>" id='radio3_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option3'];?><br/>
				<input type="radio" value="<?php echo $result['Option4'];?>" id='radio4_<?php echo $i;?>' name='<?php echo $result['id'];?>'/><?php echo $result['Option4'];?><br/>
				
				<center><button id='next<?php echo $i;?>' class='next' type='submit' name='first_seven'>Next</button><br>
				</div>
				<?php
				}
			}?>
		</form>
	<?php
	}
	?>
	
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