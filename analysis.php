<!DOCTYPE html>
<html>
<head>
<title>Intermediate_trial</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<a href="logout.php">Logout</a>
<form action="dashboard.php" method="post">
	<button type="submit" name="back">Dashboard</button>
</form>
<?php
//update the strength and weakness and undefined columns
session_start();
include 'databaseconnect.php';
$username = $_SESSION['username'];
$strength = "";
$weakness = "";
$undefined = "";
$res = mysql_query("SELECT count(`Category`) AS `category_count`, `Category` FROM $username GROUP BY `Category` ");
while($row = mysql_fetch_array($res)){
	$total_in_this = $row['category_count'];
	$category = $row['Category'];
	$res2 = mysql_query("SELECT * FROM $username WHERE `Category`='$category' AND `Total_Correct`='1'");
	$total_correct = mysql_num_rows($res2);
	$percent = ($total_correct/(float)$total_in_this);
	echo "<p>".$category.": ";
	echo $percent."</p>";
	if($percent >= 0.5)
		$strength .= $category . ",";
	else if($percent ==0.5)
		$undefined .= $category . ",";
	else
		$weakness .= $category . ",";
	
}
$strength = rtrim($strength, ',');
$undefined = rtrim($undefined, ',');
$weakness = rtrim($weakness, ',');
echo "<p>Strength: ".$strength."</p>";
echo "<br>";
echo "<p>Undefined: ".$weakness."</p>";
echo "<br>";
echo "<p>Weakness: ".$weakness."</p>";
echo "<br>";
mysql_query("UPDATE `users` SET `Strength`='$strength', `Weakness`='$weakness', `Undefined`='$undefined'");
?>

</body>
</html>