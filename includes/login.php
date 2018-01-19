<?php

session_start();

if(isset($_POST['submit'])){
	
	include 'dbConnect.php';
	
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	//Error handlers
	//Check for empty fields
	if(empty($email) || empty($password)){
		
		header("Location: ../index.php?login=error");
		$_SESSION['errorMessage'] = "Please fill out form before submitting.";
		exit();
	} else{
		
		$sql = "SELECT * FROM UserAccount Where email='$email';";
		$result = mysqli_query($conn, $sql);
		$emailcheck = mysqli_num_rows($result);
		
		if($emailcheck < 1){
			
			header("Location: ../index.php?login=error");
			$_SESSION['errorMessage'] = "This account does not exist.";
			exit();
		} else{
			
			if($row = mysqli_fetch_assoc($result)){
				
				//Dehash password and check
				$hashpasswordcheck = password_verify($password, $row['password']);
				if($hashpasswordcheck == false){
					header("Location: ../index.php?login=error");
					$_SESSION['errorMessage'] = "Email and password do not match.";
					exit();
				} elseif ($hashpasswordcheck == true){
					
					//log in user
					$_SESSION['userId'] = $row['userId'];
					$_SESSION['email'] = $row['email'];
					header("Location: ../profile.php?login=success");
					exit();
				}
			}
		}
	}
	
} else {
	header("Location: ../index.php?login=error3");
	exit();
}