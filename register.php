<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<h2>Sign Up</h2>
		
		<form class="register-form" action="includes/signup.php" method="POST">
			<input type="text" name="email" placeholder="Enter your email">
			
			<input type="password" name="password" placeholder="Enter a password">
			
			<button type="submit" name="submit">Sign Up</button>
		</form>
	</div>
</section>

<?php
	include_once 'footer.php';
?>