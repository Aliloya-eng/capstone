<?php
session_start();
require 'conn.php';

$stmt = $conn->prepare('SELECT * FROM u ');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt2 = $conn->prepare('SELECT * FROM m');
$stmt2->execute();
$messages = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>All Users</h2>
<table border='1'>
	<tr>
		<th>username</th>
		<th>password</th>
	</tr>
	<?php foreach ($users as $user) { ?>
		<tr>
			<td><?php echo $user['N'] ?></td>
			<td><?php echo $user['P'] ?></td>
		</tr>
	<?php } ?>
	
</table>


<h2>All Messages</h2>
<table border='1'>
	<tr>
		<th>ID</th>
		<th>From User</th>
		<th>To User</th>
		<th>Content</th>
	</tr>
	<?php foreach ($messages as $message) { ?>
		<tr>
			<th><?php echo $message['I'] ?></th>
			<th><?php echo $message['F'] ?></th>
			<th><?php echo $message['T'] ?></th>
			<th><?php echo $message['C'] ?></th>
		</tr>
	<?php } ?>
	
</table>


