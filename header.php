<?php
	session_start();
?>

<! DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login | Register</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="javascript/javascript.js"></script>
</head>
<body>
	<div class="header">
		<div class="navbar">
			<ul>
				<a href="profile.php"><li>Characers</li></a>
				<a href=""><li>Tasks</li></a>
			</ul>
			
			<form action="includes/logout.php" method="POST">
				<button type="submit" name="submit">Log Out</button>
			</form>
		</div>
	</div>
	<br />
	<br />