<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: ../index.php");
	exit();
} else{
	include 'dbConnect.php';
	
	if(isset($_POST['charDel'])){
	//Delete a character:
	$characterId = $_POST['charDel'];

	//Delete records from TaskTable & Task Details that match characterId	
		$sql = "DELETE TaskTable, TaskDetails
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.characterId = '$characterId';";
		mysqli_query($conn, $sql);

	//Delete records from CharacterTable & CharacterDetails that match characterId
		$sql = "DELETE CharacterTable, CharacterDetails
				FROM CharacterTable LEFT JOIN CharacterDetails 
				ON CharacterTable.characterId = CharacterDetails.characterId
				WHERE CharacterTable.characterId = '$characterId';";
		mysqli_query($conn, $sql);
		
	//Constructs a confirmation message
		$_SESSION['messageConfirm'] = "Character has been deleted.";
		
	}elseif(isset($_POST['userDel'])){
		//Delete a user
			$userId = $_SESSION['userId'];
		
		//Select all characters tied to user
			$sql = "SELECT characterId 
					FROM CharacterTable 
					WHERE userId = '$userId';";
			$result = mysqli_query($conn, $sql);
		
		//Delete tasks of character (loop)
			while($row = mysqli_fetch_assoc($result)){
				$sql2 = "DELETE TaskTable, TaskDetails
						FROM TaskTable LEFT JOIN TaskDetails 
						ON TaskTable.taskId = TaskDetails.taskId
						WHERE TaskTable.characterId = '" . implode("','", $row) . "';";
				mysqli_query($conn, $sql2);
				
		//Delete character (loop)		
				$sql2 = "DELETE CharacterTable, CharacterDetails
						FROM CharacterTable LEFT JOIN CharacterDetails 
						ON CharacterTable.characterId = CharacterDetails.characterId
						WHERE CharacterTable.characterId = '" . implode("','", $row) . "';";
				mysqli_query($conn, $sql2);
				
			}
		
		//Delete user record from UserAccount & UserDetails
		$sql = "DELETE UserAccount
				FROM UserAccount 
				WHERE UserAccount.userId = '$userId';";
		mysqli_query($conn, $sql);
		//Destroys session to automaticaly 'logout' user
		session_unset();
		session_destroy();
	}

//Sends user back to options page
header("Location: ../options.php");
exit();
}