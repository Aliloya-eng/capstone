<?php

try{

	//$conn = new PDO('mysql:host=name-of-host_OR_IP-address-of-the-host;port=3306;dbname=databasename', 'username', 'password');
	$conn = new PDO('mysql:host=localhost;port=3306;dbname=cybersecurity','root');
	$conn->SetAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

		
}
catch (Exception $ex) {
	echo ("internal error, please contact support");
	error_log("page_name, SQL error=" . $ex->getMessage());
	return;
}

?>