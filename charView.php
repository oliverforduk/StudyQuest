<?php
session_start();

//sets charSelect var only if submit button is pressed, so it wont get overwritten after addTask.php redirects to this page
if(isset($_POST['charSelect'])){
		$_SESSION['charSelect'] = $_POST['charSelect'];
	}
	
//access to page is granted if charSelect session var has been set
if(!isset($_SESSION['charSelect'])){	
	header("Location: profile.php");
	exit();
}else{
//Automatically processes tasks that are overdue
include 'includes/dbConnect.php';

$sql = "SELECT TaskTable.taskId, TaskTable.taskName, TaskDetails.deadline, TaskDetails.difficulty, TaskDetails.priority
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.characterId = '". $_SESSION['charSelect'] . "' AND TaskDetails.status = 'open'
				ORDER BY TaskDetails.priority ASC;";
$result = mysqli_query($conn, $sql);

while($overdueCheck = mysqli_fetch_assoc($result)){
	$todayDate = date("Y-m-d");
	$taskDate = $overdueCheck['deadline'];
	$task = $overdueCheck['taskId'];
if($todayDate > $taskDate){
			$_SESSION['taskSelect'] = $task;
			header("Location: includes/completeTask.php");
			exit();
		}
}


	include_once 'header.php';
	
//Displays confirmation message if a task has been completed
	if(isset($_SESSION['messageConfirm'])){
		echo "Last completed Task:";
		echo "<br/>";
		echo $_SESSION['messageConfirm'];
		unset($_SESSION['messageConfirm']);
	}

//selects the character attributes of the selected character using characterId passed from profile.php using form
	$sql = "SELECT CharacterTable.characterId, CharacterTable.characterName, CharacterDetails.build, CharacterDetails.characterLevel, CharacterDetails.currentHp, CharacterDetails.xp
			FROM CharacterTable LEFT JOIN CharacterDetails 
			ON CharacterTable.characterId = CharacterDetails.characterId
			WHERE CharacterTable.characterId = '". $_SESSION['charSelect'] . "';";
	$result = mysqli_query($conn, $sql);

//character table output
	while($row = mysqli_fetch_assoc($result)){
			
		$currentHp = $row['currentHp'];
			
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
		
//selects the tasks tied to the selected character
	$sql = "SELECT TaskTable.taskId, TaskTable.taskName, TaskDetails.deadline, TaskDetails.difficulty, TaskDetails.priority
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.characterId = '". $_SESSION['charSelect'] . "' AND TaskDetails.status = 'open'
				ORDER BY TaskDetails.priority ASC;";
	$result = mysqli_query($conn, $sql);

		echo "Tasks for this character:";

//task table output		
		echo "<table align='center' border='1'>";
		echo "<tr>
				<th width='180' align='left'>Task Name</th>
				<th width='180' align='left'>Task Deadline</th>
				<th width='180' align='left'>Task Difficulty</th>
				<th width='180' align='left'>Task Priority</th>
				<th width='180' align='left'>Complete</th></tr>";

//while loop outputs a row for each task fetched				
		while($row = mysqli_fetch_assoc($result)){

			$task = $row['taskId'];
			echo "<tr>";
			echo "<td>" . $row['taskName'] . "</td>";
			echo "<td>" . $row['deadline'] . "</td>";
			echo "<td>" . $row['difficulty'] . "</td>";
			echo "<td>" . $row['priority'] . "</td>";
			echo '<td><form action="includes/completeTask.php" method="POST">
			<button type = "submit" name="taskSelect" value="'.$task.'">Complete Task</button></a>
			</form></td>';
		}
		
		echo "</tr>";
		echo "</table>";
		
	} 
	
	if($currentHp > 0){
?>

<!-- form for adding a task to the selected character -->
	<form action="includes/addTask.php" method="POST">

		<p class="label">Task:</p>
		<input type="text" name="taskName" placeholder="Task name">

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
	
<?php
	echo "Session Task Log:";
	echo "<br/>";
	echo $_SESSION['taskLog'];
	
	}else{
		echo "Tasks cannot be added to a character with no HP.";
	}