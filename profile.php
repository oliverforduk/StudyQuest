<?php
session_start();

if(isset($_SESSION['email'])){
	echo "You are logged in.";
?>

	<form action="includes/logout.php" method="POST">
		<button type="submit" name="submit">Log Out</button>
	</form>
<?php
} else{
	echo "You must be logged in to view this page.";
}
