<?php
session_start();

include_once 'dbConnect.php';

//Task is selected by POST if it has been selected, if not session is picked up by automatic overdue check
if(isset($_POST['taskSelect'])){
	$_SESSION['taskSelect'] = $_POST['taskSelect'];
}

//Selects the task attributes of the selected task
	$sql = "SELECT TaskTable.taskId, TaskTable.taskName, TaskDetails.deadline, TaskDetails.difficulty, TaskDetails.priority
			FROM TaskTable LEFT JOIN TaskDetails 
			ON TaskTable.taskId = TaskDetails.taskId
			WHERE TaskTable.taskId = '". $_SESSION['taskSelect'] . "';";
	$result = mysqli_query($conn, $sql);
	
//fetches the values of those attributes

	$userId = $_SESSION['userId'];
	$characterId = $_SESSION['charSelect'];
	
	while($row = mysqli_fetch_assoc($result)){
		$taskId = $row['taskId'];
		$taskName = $row['taskName'];
		$taskDate = $row['deadline'];
		$taskDifficulty = $row['difficulty'];
	}
	
//Sets vars based on difficulty level
	if($taskDifficulty == "easy"){
		$hp = 10;		
		$xp = 100;
		$coins = 100;
		
	}elseif($taskDifficulty == "medium"){
		$hp = 20;		
		$xp = 200;
		$coins = 200;		
		
	}else{
		$hp = 30;
		$xp = 300;
		$coins = 300;	
		
	}

//Sets todays date to compare to deadline
	$todayDate = date("Y-m-d");

//Determines if the task has been completed within the deadline	
	if($todayDate <= $taskDate){
	//Task within time
	
	//Updates character (adds xp)
	$sql = "UPDATE CharacterDetails
			SET xp = xp + '$xp'
			WHERE characterId = '$characterId';";
	mysqli_query($conn, $sql);
	
	//If xp is over 1000, reset to 0 and add a level
	$sql = "SELECT xp 
			FROM CharacterDetails 
			WHERE characterId = '$characterId';";
	$result = mysqli_query($conn, $sql);	
	
	while($row = mysqli_fetch_assoc($result)){
		if($row['xp'] >= 1000){
			$sql = "UPDATE CharacterDetails
					SET xp = xp - 1000, characterLevel = characterLevel + 1
					WHERE characterId = '$characterId';";
			mysqli_query($conn, $sql);
		}
	}
	
	//Adds hp to character upon completing task
	$sql = "UPDATE CharacterDetails
			SET currentHp = currentHp + '$hp'
			WHERE characterId = '$characterId';";
	mysqli_query($conn, $sql);
	
	//If hp of character is over 100, reset to 100
	$sql = "SELECT currentHp 
			FROM CharacterDetails 
			WHERE characterId = '$characterId';";
	$result = mysqli_query($conn, $sql);
	
	while($row = mysqli_fetch_assoc($result)){
		if($row['currentHp'] > 100){
			$sql = "UPDATE CharacterDetails
					SET currentHp = 100
					WHERE characterId = '$characterId';";
			mysqli_query($conn, $sql);
		}
	}
	//Updates user (coins)
	$sql = "UPDATE UserAccount
			SET coins = coins + '$coins'
			WHERE userId = '$userId';";
	mysqli_query($conn, $sql);
	
	//constructs a confirmation message using vars to output on character view page	
	$_SESSION['messageConfirm'] = "Task: " . $taskName . ", Status: task completed within time, HP gained, XP gained: " . $xp . ", coins gained: " . $coins . ".";

	}else{
	//Task overdue
	
	//Updates character (removes hp)
	$sql = "UPDATE CharacterDetails
			SET currentHp = currentHp - '$hp'
			WHERE characterId = '$characterId';";
	mysqli_query($conn, $sql);
	
	//Checks hp cannot fall below 0
	$sql = "SELECT currentHp
			FROM CharacterDetails
			WHERE characterId = '$characterId';";
	$result = mysqli_query($conn, $sql);
	
	while($row = mysqli_fetch_assoc($result)){
		if($row['currentHp'] < 0){
			$sql = "UPDATE CharacterDetails
				SET currentHp = 0
				WHERE characterId = '$characterId';";
		mysqli_query($conn, $sql);
		}
	}
	
	//constructs a confirmation message using vars to output on character view page	
	$_SESSION['messageConfirm'] = "Task: " . $taskName . ", Status: task not completed on time, HP lost, XP gained: 0, coins gained: 0.";
	
	}
	
//Updates the task log
	$_SESSION['taskLog'] = $_SESSION['messageConfirm'] . "<br/>" . $_SESSION['taskLog'];
	
//Updates task (status)
	$sql = "UPDATE TaskDetails
			SET status = 'closed'
			WHERE taskId = '$taskId';";
	mysqli_query($conn, $sql);
		
//Sends user back to character view page
header("Location: ../charView.php");
exit();