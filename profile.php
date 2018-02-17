<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{

	include 'includes/dbConnect.php';
	
	//If user has no characters redirect to info.php
	$sql = "SELECT characterId
			FROM CharacterTable
			WHERE userId = '" . $_SESSION['userId'] . "';";
	$result = mysqli_query($conn, $sql);
	
		if($numrows = mysqli_num_rows($result) < 1){
			header("Location: info.php");
			exit();
		}
	
	include_once 'header.php';
	
	//Message runs first time a user logs in only
	if(isset($_SESSION['taskCheck'])){
		unset($_SESSION['taskCheck']);
		
		$userId = $_SESSION['userId'];
		$todayDate = date("Y-m-d");
		
	//selects tasks that both belong to current user and overdue
		$sql = "SELECT TaskTable.taskId, TaskTable.taskName, TaskTable.characterId, TaskDetails.deadline
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.userId = '$userId' AND TaskDetails.deadline < '$todayDate' AND TaskDetails.status = 'open';";
		$result = mysqli_query($conn, $sql);
	
	//selects tasks that both belong to current user and are due today
		$sql2 = "SELECT TaskTable.taskId, TaskTable.taskName, TaskTable.characterId, TaskDetails.deadline
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.userId = '$userId' AND TaskDetails.deadline = '$todayDate' AND TaskDetails.status = 'open';";
		$result2 = mysqli_query($conn, $sql2);
	
	//output table
		echo "<table border ='1'>";
		echo "<th>Overdue tasks</th>";
	//loop for overdue tasks
	if($numrows = mysqli_num_rows($result) < 1){
		echo "<tr><td>No overdue tasks.</td></tr>";
	}else{
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>
					<td>" . $row['taskName'] . "</td>
					<td>" . $row['deadline'] . "</td>";
					echo '	<td><form action="charView.php" method="POST">
							<button type = "submit" name="charSelect" value="'.$row['characterId'].'">See Task</button></a>
							</form></td></tr>';
		}
	}
		
	//loop for todays tasks
		echo "<th>Todays tasks</th>";
		
	if($numrows = mysqli_num_rows($result2) < 1){
		echo "<tr><td>No tasks due today.</td></tr>";
	}else{	
		while($row = mysqli_fetch_assoc($result2)){
			echo "<tr>
					<td>" . $row['taskName'] . "</td>
					<td>" . $row['deadline'] . "</td>";
					echo '	<td><form action="charView.php" method="POST">
							<button type = "submit" name="charSelect" value="'.$row['characterId'].'">See Task</button></a>
							</form></td></tr>';
		}
	}
		
		echo "<tr><td><button onclick='window.location.reload(true);'>Dismiss</button></td></tr>";
	}
		
	//selects characterids that belong to the logged in user	
	$sql = "
	SELECT characterId 
	FROM CharacterTable 
	WHERE userId = '". $_SESSION['userId'] . "'
	ORDER BY characterId;";	
	$result = mysqli_query($conn, $sql);
	$charactercheck = mysqli_num_rows($result);
	
	if($charactercheck > 0){
	
		echo "Your characters:";
		echo "<br/>";
		echo "<br/>";

//loops thru each character id that belongs to logged in user		
		while($row = mysqli_fetch_assoc($result)){	
		
//selects characterId, characterName & build from CharacterTable/CharacterDetails that match each characterId belonging to user
			$sql2 = "
			SELECT CharacterTable.characterId, CharacterTable.characterName, CharacterDetails.build, CharacterDetails.characterLevel, CharacterDetails.currentHp, CharacterDetails.xp
			FROM CharacterTable LEFT JOIN CharacterDetails 
			ON CharacterTable.characterId = CharacterDetails.characterId
			WHERE CharacterTable.characterId = '" . implode("','", $row) . "';
			";
			$result2 = mysqli_query($conn, $sql2);
			
			$count++;
			echo "Character " . $count . ":";
		
//loop will output details of character in table
			while($row2 = mysqli_fetch_assoc($result2)){
				echo "<table align='center' border='1'>";
			echo "<tr>
			<th width='180' align='left'>Character Name</th>
			<th width='180' align='left'>Character Build</th>
			<th width='180' align='left'>Character Level</th>
			<th width='180' align='left'>Character HP</th>
			<th width='180' align='left'>Character XP</th></tr>";

			echo "<tr>";
			echo "<td>" . $row2['characterName'] . "</td>";
			echo "<td>" . $row2['build'] . "</td>";
			echo "<td>" . $row2['characterLevel'] . "</td>";
			echo "<td>" . $row2['currentHp'] . "</td>";
			echo "<td>" . $row2['xp'] . "</td>";
			
			echo "</tr>";
			echo "</table>";
			
			$id = $row2['characterId'];
			echo '
			<form action="charView.php" method="POST">
			<button type = "submit" name="charSelect" value="'.$id.'">See Character</button></a>
			</form>';
			echo "<br />";
			}
		}
	} else{
		echo "You have no characters.";
	}
	if($charactercheck >= 0 && $charactercheck < 4){
		echo "Create a character:";


?>

	<form action="includes/addCharacter.php" method="POST">

		<p class="label">Character Name</p>
		<input type="text" name="characterName" placeholder="character name">
									
		<p class="label">Build</p>
		<input type="text" name="characterBuild" placeholder="Build type">
								
		<button type="submit" name="submit" class="buttonspin">Create</button>
		
	</form>

<?php
	}
}
