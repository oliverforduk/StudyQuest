<?php
session_start();

if(!isset($_POST['submit'])){
	header("Location: index.php");
	exit();
}else{
	
	include_once 'dbConnect.php';

//setting vars for inputting into TaskTable & TaskDetails	
	$characterId = $_SESSION['charSelect'];
	$userId = $_SESSION['userId'];
	
	$taskName = mysqli_real_escape_string($conn, $_POST['taskName']);
	$taskDate = mysqli_real_escape_string($conn, $_POST['taskDate']);
	$taskDifficulty = mysqli_real_escape_string($conn, $_POST['taskDifficulty']);
	$taskPriority = mysqli_real_escape_string($conn, $_POST['taskPriority']);
	$characterBuild = mysqli_real_escape_string($conn, $_POST['characterBuild']);
	
	if (empty($taskName)){
		$_SESSION['charactererror'] = "Add a task name.";
		header("Location: ../charView.php?incompleteform");
		exit();		
	}elseif (empty($taskDate)){
		$_SESSION['charactererror'] = "Add a deadline.";
		header("Location: ../charView.php?incompleteform");
		exit();	
	}else{
		//adding to TaskTable
		$sql = "INSERT INTO TaskTable (userId, characterId, taskName) VALUES ('$userId', '$characterId', '$taskName');";
		mysqli_query($conn, $sql);
		
		$id = mysqli_insert_id($conn);
		
		$sql = "INSERT INTO TaskDetails (taskId, deadline, difficulty, priority, characterBuild) VALUES ('$id', '$taskDate', '$taskDifficulty', '$taskPriority', '$characterBuild');";
		mysqli_query($conn, $sql);
		
		//updating a confirmation message
		$_SESSION['charactersuccess'] = "Task added.";
		$_SESSION['taskAdded'] = "";
				
		header("Location: ../charView.php?=success");
		exit();
	}
		
}