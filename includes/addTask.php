<?php
session_start();

if(!isset($_POST['submit'])){
	header("Location: index.php");
	exit();
}else{
	
	include_once 'dbConnect.php';
	
	$characterId = $_SESSION['charSelect'];
	
	$taskName = mysqli_real_escape_string($conn, $_POST['taskName']);
	$taskDate = mysqli_real_escape_string($conn, $_POST['taskDate']);
	$taskDifficulty = mysqli_real_escape_string($conn, $_POST['taskDifficulty']);
	$taskPriority = mysqli_real_escape_string($conn, $_POST['taskPriority']);
	
	if (empty($taskName || $taskDate)){
		$_SESSION['errorMessage'] = "Please complete form.";
		header("Location: ../charView.php?incompleteform");
		exit();
		
	} else{
		//adding to TaskTable
		$sql = "INSERT INTO TaskTable (characterId, taskName) VALUES ('$characterId', '$taskName');";
		$result = mysqli_query($conn, $sql);
		
		$id = mysqli_insert_id($conn);
		
		$sql = "INSERT INTO TaskDetails (taskId, deadline, difficulty, priority) VALUES ('$id', '$taskDate', '$taskDifficulty', '$taskPriority');";
		$result = mysqli_query($conn, $sql);
		
		//updating a confirmation message
		$_SESSION['successMessage'] = "Task added.";
		$_SESSION['taskAdded'] = "";
				
		header("Location: ../charView.php?=success");
		exit();
	}
		
}