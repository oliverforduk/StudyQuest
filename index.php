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
	<script src="javascript/tabswitch.js"></script>
</head>
<body>

<div class="tab-panels">
	<div class="form">
		<div class="appname">
			<h2>StudyQuest</h2>
		</div>
		<div class="holder">
			<ul class="tabs">
				<li rel="panel1" class="active">Login</li>
				<li rel="panel2">Sign up</li>
			<ul>
		</div>
		
		<div id="panel1" class="panel active">
			<div class="body" id="form-body">
				<div class="login">
					<div class="form-row">
						<h2 class="formmessage">Please log in</h2>
							<form action="includes/login.php" method="POST">
								<div class="holder">
									<p class="label">Email</p>
									<input type="text" name="email" placeholder="you@example.com">
									
									<p class="label">Password</p>
									<input type="password" name="password" placeholder="Password">
								
									<button type="submit" name="submit">Login</button>
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
		
		<div id="panel2" class="panel">
		<div class="body" id="form-body">
			<div class="register">
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
							
								<button type="submit" name="submit">Sign Up</button>
							</div>
						</form>
			</div>
		</div>
	</div>
	</div>
</div>

<?php
unset($_SESSION['testvar']);
	include_once 'footer.php';
?>