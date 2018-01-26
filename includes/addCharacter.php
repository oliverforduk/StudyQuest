<?php
session_start();

if(!isset($_POST['submit'])){
	header("Location: index.php");
	exit();
}else{
	
	include_once 'dbConnect.php';
	
	$userId = $_SESSION['userId'];
	$characterName = mysqli_real_escape_string($conn, $_POST['characterName']);
	$characterBuild = mysqli_real_escape_string($conn, $_POST['characterBuild']);
	
	if (empty($characterName || $characterBuild)){
		$_SESSION['errorMessage'] = "Please complete form.";
		header("Location: ../profile.php");
		exit();
		
	} else{
		//adding to CharacterTable
		$sql = "INSERT INTO CharacterTable (userId, characterName) VALUES ('$userId', '$characterName');";
		$result = mysqli_query($conn, $sql);
		
		$id = mysqli_insert_id($conn);
		
		$sql = "INSERT INTO CharacterDetails (characterId, build) VALUES ('$id', '$characterBuild');";
		$result = mysqli_query($conn, $sql);
		
		//updating a confirmation message
		$_SESSION['successMessage'] = "Character created.";
				
		header("Location: ../profile.php?=success");
		exit();
	}
		
}