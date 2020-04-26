<?php
try{
if (isset($_POST["Register"])&&$_POST["Register"]==="Register")
{
	if(isset($_POST["Username"])&&
		($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))||
		($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"])))
	{
		if($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))
		{
			$password = $_POST["Password"];
			$uppercase = preg_match('@[A-Z]@', $password);
			$lowercase = preg_match('@[a-z]@', $password);
			$number    = preg_match('@[0-9]@', $password);
			$specialChars = preg_match('@[^\w]@', $password);

			if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8)
			{
				session_start();
				$_SESSION["er"]="Please chose a stronger password (one that is at lease 8 characters and conains capital and small letters and numbers and special characters";
				header("Location:register.php");
			}
			else
			{
				$pass=sha1($_POST["Password"]);
			}
		}
		elseif($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"]))
		{
			$password = $_POST["First"].$_POST["Second"].$_POST["Third"];
			$uppercase = preg_match('@[A-Z]@', $password);
			$lowercase = preg_match('@[a-z]@', $password);
			$number    = preg_match('@[0-9]@', $password);
			$specialChars = preg_match('@[^\w]@', $password);

			if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8)
			{
				session_start();
				$_SESSION["er"]="Please chose a stronger password (one that is at lease 8 characters and conains capital and small letters and numbers and special characters";
				header("Location:register.php");
			}
			else
			{
				$pass=sha1($_POST["First"].$_POST["Second"].$_POST["Third"]);
			}
		}

		require 'conn.php';

		$sql_sel = "SELECT * FROM u WHERE N=:X";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['Username'],
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row)
		{
			session_start();
			$_SESSION["er"]="name is already taken";
			header("Location:register.php");
		}
		else
		{
		$sql_ins = "INSERT INTO u (N,P) VALUES (:X,:Y)";
		$stmt=$conn->prepare($sql_ins);
		$stmt->execute(array(
			':X' => $_POST['Username'],
			':Y' => $pass
		));

		session_start();
		$_SESSION["msg"]="You have been registered successfully, you can login now";
		header("Location:login.php");
		}
	}
	else
	{
		session_start();
		$_SESSION["er"]="please fill all the fields";
		header("Location:register.php");
	}
}

elseif (isset($_GET)) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<link rel="stylesheet" href="style.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body >
	<div class="body_div">
		<h1 class="head" style="margin-bottom: 10%;">This Is Your Registration Page</h1>
		<a href="index.php" class="nav_link">Back</a>

<?php
	session_start();
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
		<form method="post">
			<label>Username: <input type="text" name="Username" placeholder="Username" autofocus></label>
			<p>to help you chose a secure and easy-to-remember password, you have two ways to select a password:</p>
			<label><input type="radio" name="Password_Type" id="Regular_Radio" value="Regular">Regular</label>
			<label><input type="radio" name="Password_Type" id="Advised_Radio" value="Advised" checked>Advised</label>
			<div id="Regular_Div" style="display: none;">
				<p>This is the reqular way, but to make it more secure please select a password that contains at least 8 characters, include numbers, capital and small letter characters, and special characters</p>
				<label>Password: <input type="Password" name="Password" placeholder=""></label>		
			</div>
			<div id="Advised_Div">
				<p>We have developed a new password methode, please fill in the following three spaces with a name, a number and any additional field of you choice.<br>We advise you pick something that is meaningful to you but unknown by other people.</p>
				<label>Password: <input type="Password" name="First">-<input type="Password" name="Second">-<input type="Password" name="Third"></label>
			</div>
			<input type="submit" name="Register" value="Register" class="botton">
		</form>
	</div>
	<script type="text/javascript">
		$('input[type=radio][name=Password_Type]').change(function() {
    		if (this.value == 'Regular') {
    			$("#Regular_Div").css("display","block");
    			$("#Advised_Div").css("display","none");
  			}
		    else if (this.value == 'Advised') {
    			$("#Advised_Div").css("display","block");
    			$("#Regular_Div").css("display","none");
		    }
		});
	</script>
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