<?php
try{
session_start();

if (!isset($_SESSION["I"])) {
	$_SESSION["er"]="You are NOT Authorized";
	header("Location:index.php");
}

if(isset($_POST["Send"])&&$_POST["Send"]==="Send")
{
	if(isset($_POST["Content"])&&isset($_POST["To"]))
	{
		require 'crypto.php';
		$encrypted = openssl_encrypt($_POST["Content"], $ciphering, $key, $options, $iv); 
		
		require 'conn.php';
		$sql_sel = "SELECT * FROM u WHERE N=:X";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['To']
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row)
		{
			$sql_ins = "INSERT INTO m (T,F,C) VALUES (:X,:Y,:Z)";
			$stmt=$conn->prepare($sql_ins);
			$stmt->execute(array(
				':X' => $row["I"],
				':Y' => $_SESSION["I"],
				':Z' => $encrypted
			));
			$_SESSION["msg"]="Message sent seccessfully";
			header("Location:write.php");
		}
		else
		{
			$_SESSION["er"]="No such contact name";
			header("Location:write.php");
		}
	}
	else
	{
		$_SESSION["er"]="please fill all the fields";
		header("Location:write.php");
	}
}
else
{
?>

<!DOCTYPE html>
<html>
<head>
	<title>Compose</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="body_div">
		<h1 class="head">Compose a Message</h1>
		<a href="home.php" class="nav_link">Back</a>

<?php
	if(isset($_SESSION["er"]))
	{ ?>
		<p style="color: red">..................................................... <?=$_SESSION["er"]?> .....................................................</p>
<?php unset($_SESSION["er"]);
	}
	if(isset($_SESSION["msg"]))
	{ ?>
		<p style="color: green">..................................................... <?=$_SESSION["msg"]?> .....................................................</p>
<?php unset($_SESSION["msg"]);
	}
?>

		<p>please fill both fields before you click Send</p>
		<form method="post">
			<label>To: <input type="text" name="To"></label>
			<label>Content: <input type="text" name="Content"></label>
			<input class="botton" type="submit" name="Send" value="Send">			
		</form>
	</div>
</body>
</html>
<?php }
}
catch (Exception $ex) {
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}
 ?>