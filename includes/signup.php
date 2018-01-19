<?php
session_start();

if(isset($_POST['submit'])){
	
	include_once 'dbConnect.php';
	
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	//Error handlers
	//check for empty fields
	if (empty($email || $password)){
		$_SESSION['errorMessage'] = "Please fill out form before submitting.";
		header("Location: ../register.php?signup=emptyfields");
		exit();
	} else{
		
		//checks email is valid
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$_SESSION['errorMessage'] = "Invalid email address.";
			header("Location: ../register.php?signup=invalidemail");
			exit();
		} else {
			
			$sql = "SELECT * FROM UserAccount WHERE email='$email'";
			$result = mysqli_query($conn, $sql);
			$emailcheck = mysqli_num_rows($result);
			
			if($emailcheck > 0){
				$_SESSION['errorMessage'] = "This email is already in use.";
				header("Location: ../register.php?index=existingemail");
				exit();
			} else{
				
				//Hashing password
				$hashpassword = password_hash($password, PASSWORD_DEFAULT);
				
				//Insert details into database
				$sql = "INSERT INTO UserAccount (email, password) VALUES ('$email', '$hashpassword');";
				$result = mysqli_query($conn, $sql);
				
				//updating a confirmation message
				$_SESSION['successMessage'] = "Account created, please sign in.";
				
				header("Location: ../index.php?signup=success");
				exit();
				
			}
		}
	}
	
} else{
	header("Location: ../register.php");
	exit();
}