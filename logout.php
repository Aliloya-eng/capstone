<?php
try{
session_start();
session_destroy();
header("Location:index.php");
}
catch (Exception $ex) {
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>