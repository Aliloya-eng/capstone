<?php
try{
session_start();

if (!isset($_SESSION["I"])) {
	$_SESSION["er"]="You are NOT Authorized";
	header("Location:index.php");
}
if(!isset($_GET["MID"]))
{
	$_SESSION["er"]="Please chose a message first";
	header("Location:home.php");	
}
if(isset($_GET["MID"]))
{
	require 'conn.php';

	$sql_sel_u = "SELECT * FROM u WHERE I=:X";
	$stmt_u = $conn->prepare($sql_sel_u);
	$stmt_u->execute(array(
		':X' => $_SESSION["I"]
	));
	$row_u = $stmt_u->fetch(PDO::FETCH_ASSOC);

	$sql_sel = "SELECT * FROM m WHERE I=:X";
	$stmt = $conn->prepare($sql_sel);
	$stmt->execute(array(
		':X' => $_GET["MID"]
	));
	if(!empty($row = $stmt->fetch(PDO::FETCH_ASSOC)))
	{
		if($row["T"]!==$_SESSION["I"])
		{
		$_SESSION["er"]="Please chose a message to read if you want";
		header("Location:home.php");	
		}
		elseif($row["T"]===$_SESSION["I"])
		{
			require 'crypto.php';
			$decrypted=openssl_decrypt ($row["C"], $ciphering, $key, $options, $iv); 

			$sql_sel_s = "SELECT * FROM u WHERE I=:X";
			$stmt_s = $conn->prepare($sql_sel_s);
			$stmt_s->execute(array(
				':X' => $row["F"]
			));
			$row_s = $stmt_s->fetch(PDO::FETCH_ASSOC);


		///////////////////////
////		MESSAGES NEED TO BE DECRYPTED
		///////////////////////

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="body_div">
		<a href="home.php" class="nav_link" style="display: block;">Back</a>

<?php
	if(isset($_SESSION["er"]))
	{ ?>
		<p style="color: red">..................................................... <?=$_SESSION["er"]?> .....................................................</p>
<?php	unset($_SESSION["er"]);
	}
	if(isset($_SESSION["msg"]))
	{ ?>
		<p style="color: green">..................................................... <?=$_SESSION["msg"]?> .....................................................</p>
<?php	unset($_SESSION["msg"]);
	}
?>

		<h2 style="display: inline-block; text-decoration: underline;">from:</h2>
		<span style="font-size: 120%;">&nbsp&nbsp <?=$row_s["N"]?></span>	
		<br>
		<h2 style="display: inline-block; text-decoration: underline;">content:</h2>
		<span style="font-size: 120%;">&nbsp&nbsp <?=$decrypted?></span>
	</div>
</body>
</html>

<?php
		}
	}
}
}
catch (Exception $ex) {
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>