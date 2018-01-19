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
<body onLoad="buttonanim()">

<div class="tab-panels">
	<div class="form">
		<div class="appname">
			<h2>StudyQuest</h2>
		</div>
		<div class="holder">
			<ul class="tabs">
				<li class="active">Login</li>
				<a href="register.php"><li>Sign up</li></a>
			<ul>
		</div>

			<div class="body" id="form-body">
					<div class="form-row">
						<h2 class="formmessage">Please log in</h2>
							<form action="includes/login.php" id="login-form" method="POST">
								<div class="holder">
									<p class="label">Email</p>
									<input type="text" name="email" placeholder="you@example.com">
									
									<p class="label">Password</p>
									<input type="password" name="password" placeholder="Password">
								</div>
								<div class="holder">
									<button type="submit" name="submit" id="subbutton" class="buttonspin">Login</button>
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
								} elseif (isset($_SESSION['successMessage'])){
									echo 	'
												<div class="successmessage">
													<p>' . $_SESSION['successMessage'] . '</p>
												</div>
											';
									unset($_SESSION['successMessage']);
								}
								?>
							</form>
					</div>
			</div>
		
</div>
</div>

<?php
	include_once 'footer.php';
?>