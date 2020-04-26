<?php
try{
session_start();

if(isset($_SESSION["I"]))
{
	$_SESSION["msg"]="you are already logged in, if you want to login as a different user please logout first";
	header("Location:home.php");
}

if(isset($_POST["Login"])&&$_POST["Login"]==="Login")
{
	if(isset($_POST["Username"])&&
		($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))||
		($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"])))
	{
		if($_POST["Password_Type"]==="Regular"&&isset($_POST["Password"]))
		{
			$pass=sha1($_POST["Password"]);
		}
		elseif($_POST["Password_Type"]==="Advised"&&isset($_POST["First"])&&isset($_POST["Second"])&&isset($_POST["Third"]))
		{
			$pass=sha1($_POST["First"].$_POST["Second"].$_POST["Third"]);
		}

		require 'conn.php';
		$sql_sel = "SELECT * FROM u WHERE N=:X AND P=:Y";
		$stmt = $conn->prepare($sql_sel);
		$stmt->execute(array(
			':X' => $_POST['Username'],
			':Y' => $pass
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($row))
		{
			$_SESSION["I"]=$row["I"];
			header("Location:home.php");
		}
		else
		{
			$_SESSION["er"]="Incorrect information, Please try again";
			header("Location:login.php");			
		}
	}
	else
	{
		$_SESSION["er"]="please fill all the fields";
		header("Location:Login.php");
	}

}



elseif (isset($_GET)) { ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="body_div">
		<h1 class="head">Login</h1>
		<a href="index.php" class="nav_link">Back</a>

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
		<form method="post">
			<p><label>Username: <input type="text" name="Username" placeholder="Username" autofocus></label></p>
			<p>Password Type</p>
			<label><input type="radio" name="Password_Type" id="Regular_Radio" value="Regular" >Regular</label>
			<label><input type="radio" name="Password_Type" id="Advised_Radio" value="Advised" checked>Advised</label>
			<br><br>
			<div id="Regular_Div" style="display: none;">
				<label>Password<input type="Password" name="Password"></label>		
			</div>
			<div id="Advised_Div">
				<input type="Password" name="First">-<input type="Password" name="Second">-<input type="Password" name="Third">
			</div>
			<input class="botton" type="submit" name="Login" value="Login">
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