<!DOCTYPE html>
<html>
<head>
<title>TestPage</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<?php
		session_start();
		include 'databaseconnect.php';
		$username = $_SESSION['username'];
		$query = "SELECT * FROM `users` WHERE `Username`='$username'";
		$result = mysql_query($query);
		$r = mysql_fetch_array($result);
		if($r['User_Type']=='1'){
			//novice
			header( "Location: novice_set.php" ) ;
		}
		else if($r['User_Type']=='2'){
			//intermediate
			header( "Location: intermediate_set_trial.php" ) ;
			//intermediate_set($username);
		}
		else if($r['User_Type']=='3'){
			//advanced
			header( "Location: advance_set.php" ) ;
		}
		else{
			//expert
			header( "Location: expert_set.php" ) ;
		}
		?>
</body>
</html>