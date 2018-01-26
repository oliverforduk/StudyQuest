<?php
	session_start();
if(isset($_SESSION['userId'])){
	header("Location: profile.php");
	exit();
} else {
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
<body class="bodyindex" onLoad="buttonanim()">

<div class="tab-panels">
	<div class="form">
		<div class="appname">
			<h2>StudyQuest</h2>
		</div>
		<div class="holder">
			<ul class="tabs">
				<a href="index.php"><li>Login</li></a>
				<li class="active">Sign up</li>
			<ul>
		</div>
		
		<div class="body" id="form-body">
				<div class="form-row">
					<h2 class="formmessage">Create an account</h2>
						<form class="register-form" action="includes/signup.php" method="POST">
							<div class="holder">
								<p class="label">Email</p>
								<input type="text" name="email" placeholder="you@example.com">
							
								<p class="label">Password</p>
								<input type="password" name="password" placeholder="Password">
								
								<!--<p class="label">Confirm password</p>
								<input type="password" name="passwordConfirm" placeholder="Confirm password">-->
							</div>
							<div class="holder">
								<button type="submit" name="submit" id="subbutton" class="buttonspin">Register</button>
							</div>
							<!-- Error Message -->
								<?php
								if(isset($_SESSION['errorMessage'])){
									echo 	'
												<div class="errormessage">
													<p>' . $_SESSION['errorMessage'] . '</p>
												</div>
											';
									unset($_SESSION['errorMessage']);
								} 
								?>
						</form>
			</div>
	</div>
</div>
</div>
<?php
	include_once 'footer.php';
}
?>