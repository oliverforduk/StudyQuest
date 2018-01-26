<?php
session_start();

if(isset($_POST['charSelect'])){
		$_SESSION['charSelect'] = $_POST['charSelect'];
	}
	
if(!isset($_SESSION['charSelect'])){	
	header("Location: profile.php");
	exit();
}else{

	include 'includes/dbConnect.php';
	include_once 'header.php';

		$sql = "SELECT CharacterTable.characterId, CharacterTable.characterName, CharacterDetails.build, CharacterDetails.characterLevel, CharacterDetails.currentHp, CharacterDetails.xp
				FROM CharacterTable LEFT JOIN CharacterDetails 
				ON CharacterTable.characterId = CharacterDetails.characterId
				WHERE CharacterTable.characterId = '". $_SESSION['charSelect'] . "';";
		$result = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($result)){
						echo "<table align='center' border='1'>";
					echo "<tr>
					<th width='180' align='left'>Character Name</th>
					<th width='180' align='left'>Character Build</th>
					<th width='180' align='left'>Character Level</th>
					<th width='180' align='left'>Character HP</th>
					<th width='180' align='left'>Character XP</th></tr>";

					echo "<tr>";
					echo "<td>" . $row['characterName'] . "</td>";
					echo "<td>" . $row['build'] . "</td>";
					echo "<td>" . $row['characterLevel'] . "</td>";
					echo "<td>" . $row['currentHp'] . "</td>";
					echo "<td>" . $row['xp'] . "</td>";
					
					echo "</tr>";
					echo "</table>";
		}
		
		$sql = "SELECT TaskTable.taskName, TaskDetails.deadline, TaskDetails.difficulty, TaskDetails.priority
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.characterId = '". $_SESSION['charSelect'] . "'
				ORDER BY TaskDetails.priority ASC;";
		$result = mysqli_query($conn, $sql);

		echo "Tasks for this character:";
		
		echo "<table align='center' border='1'>";
		echo "<tr>
				<th width='180' align='left'>Task Name</th>
				<th width='180' align='left'>Task Deadline</th>
				<th width='180' align='left'>Task Difficulty</th>
				<th width='180' align='left'>Task Priority</th>";
		
		while($row = mysqli_fetch_assoc($result)){

			echo "<tr>";
			echo "<td>" . $row['taskName'] . "</td>";
			echo "<td>" . $row['deadline'] . "</td>";
			echo "<td>" . $row['difficulty'] . "</td>";
			echo "<td>" . $row['priority'] . "</td>";
		}
		
		echo "</tr>";
		echo "</table>";
		
	} 
?>

	<form action="includes/addTask.php" method="POST">

		<p class="label">Task:</p>
		<input type="text" name="taskName" placeholder="character name">

		<p class="label">Deadline</p>
		<input type="date" min="<?php echo date("Y-m-d"); ?>" name="taskDate">

		<p class="label">Difficulty</p>
		<select name="taskDifficulty">
			<option value="easy">Easy</option>
			<option value="medium">Medium</option>
			<option value="hard">Hard</option>
		</select>

		<p class="label">Priority</p>
		<select name="taskPriority">
			<option value="normal">Normal</option>
			<option value="high">High</option>
		</select>
		
		<button type="submit" name="submit" class="buttonspin">Add Task</button>
		
	</form>