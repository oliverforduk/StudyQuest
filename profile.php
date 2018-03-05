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
	
		if($numrows = mysqli_num_rows($result) < 1 && isset($_SESSION['taskCheck'])){
			unset($_SESSION['taskCheck']);
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
		echo "<div class='overlay'>";
		echo "<div class='centered'>";
		echo "<div class='taskcheckcontent'>";
		echo "<div class='appname'><h2>Welcome Back</h2></div>";
		echo "<div class='tasktitle'>Overdue Tasks</div>";
	//loop for overdue tasks
	if($numrows = mysqli_num_rows($result) < 1){
		echo "<div class='tasktext'>No overdue tasks.</div>";
	}else{
		while($row = mysqli_fetch_assoc($result)){
			echo "<table>";	
			echo "<tr>
					<td class='tdimg'><img src='images/char-h-mini2.png'></td>
					<td class='tdtask'>" . $row['taskName'] . "</td>
					<td class='tddate'>[" . $row['deadline'] . "]</td>";
					echo '	<td class="tdbutton"><form action="charView.php" method="POST">
							<button type = "submit" name="charSelect" value="'.$row['characterId'].'">See Task</button></a>
							</form></td></tr>';
			echo "</table>";
		}
	}
		
	//loop for todays tasks
		echo "<div class='tasktitle'>Todays tasks</div>";
		
	if($numrows = mysqli_num_rows($result2) < 1){
		echo "<div class='tasktext'>No tasks due today.</div>";
	}else{			
		while($row = mysqli_fetch_assoc($result2)){
			echo "<div class='testable'><table>";
			echo "<tr>
					<td class='tdimg'><img src='images/char-a-mini2.png'></td>
					<td class='tdtask'>" . $row['taskName'] . "</td>
					<td class='tddate'>[" . $row['deadline'] . "]</td>";
					echo '	<td class="tdbutton"><form action="charView.php" method="POST">
							<button type = "submit" name="charSelect" value="'.$row['characterId'].'">See Task</button></a>
							</form></td></tr>';
			echo "</table></div>";
		}
	}
		
		echo "<button class='taskbutton' onclick='window.location.reload(true);'>Dismiss</button>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	} else{
	//selects characterids that belong to the logged in user	
	$sql = "
	SELECT characterId 
	FROM CharacterTable 
	WHERE userId = '". $_SESSION['userId'] . "'
	ORDER BY characterId;";	
	$result = mysqli_query($conn, $sql);
	$charactercheck = mysqli_num_rows($result);
	
	if($charactercheck > 0){
	
		echo "<div class='centered'>
				<div class='profile'>
					<div class='title'>Your Characters:</div>";

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
		
//loop will output details of character 
			while($row2 = mysqli_fetch_assoc($result2)){
				echo "
				<div class='charholder'>
					<div class='charhead'>
						<h2>" . $row2['characterName'] . "</h2>
						<h2 class='level'>Level: " . $row2['characterLevel'] . "</h2>
					</div>
					<div class='charimg'>
						<img src='images/builds/" . $row2['build'] . "'>
					</div>
					<div class='charstats'>
						<div class='hp'>
							<h2>HP:</h2>
							<h2 class='level'>" . $row2['currentHp'] . "</h2>
						</div>
						<div class='xp'>
							<h2>XP:</h2>
							<h2 class='level'>" . $row2['xp'] . "</h2>
						</div>";
						
						$id = $row2['characterId'];
					
					echo'
						<div class="charbutton">
							<form action="charView.php" method="POST">
								<button class="taskbutton" type = "submit" name="charSelect" value="'.$id.'">See Character</button>
							</form>
						</div>
					</div>
					</div>';
					
					
			}
		}
	} else{
		echo "<div class='centered'><div class='title'>Create A Character:</div></div>";
	}
	if($charactercheck >= 0 && $charactercheck < 4){
//end of profile and centered divs
		echo "</div></div>";

?>
<div class="createcharacter">
	<form action="includes/addCharacter.php" method="POST">

		<p class="label">Character Name</p>
		<input type="text" name="characterName" placeholder="character name">
									
		<p class="label">Build</p>
		<input type="text" name="characterBuild" placeholder="Build type">
								
		<button type="submit" name="submit" class="buttonspin">Create</button>
		
	</form>
</div>
<?php
	}
	}
}
