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
	

//Sets variables for use in the last task output
	if(isset($_SESSION['confirmTask'])){
		$confirmTask = $_SESSION['confirmTask'];
		$confirmStatus = $_SESSION['confirmStatus'];
		$confirmHp = $_SESSION['confirmHp'];
		$confirmXp = $_SESSION['confirmXp'];
		$confirmCoins = $_SESSION['confirmCoins'];
	}

//selects the character attributes of the selected character using characterId passed from profile.php using form
	$sql = "SELECT CharacterTable.characterId, CharacterTable.characterName, CharacterDetails.build, CharacterDetails.characterLevel, CharacterDetails.currentHp, CharacterDetails.xp
			FROM CharacterTable LEFT JOIN CharacterDetails 
			ON CharacterTable.characterId = CharacterDetails.characterId
			WHERE CharacterTable.characterId = '". $_SESSION['charSelect'] . "';";
	$result = mysqli_query($conn, $sql);

//character table output
	while($row = mysqli_fetch_assoc($result)){
		//variable used to determine if a character can be set tasks (hp must be higher than 0)
		$currentHp = $row['currentHp'];
		//Variable used to add a character build to the task output
		$characterBuild = $row['build'];
			
		echo"
			<div class='centered'>
				<div class='page'>
					<div class='title'>" . $row['characterName'] . "'s Tasks</div>
				</div>
	<!--Character output-->
				<div class='charholder'>
					<div class='charhead'>
						<h2>" . $row['characterName'] . "</h2>
						<h2 class='level'>Level: " . $row['characterLevel'] . "</h2>
					</div>
					<div class='charimg'>
						<img src='images/builds/" . $row['build'] . "full.png'>
					</div>
					<div class='charstats'>
						<div class='hp'>
							<h2>HP:</h2>
							<h2 class='level'>" . $row['currentHp'] . "</h2>
						</div>
						<div class='xp'>
							<h2>XP:</h2>
							<h2 class='level'>" . $row['xp'] . "</h2>
						</div>
						<div class='charbutton'>
							<a href='profile.php'><button class='taskbutton'>Back to Profile</button></a>
						</div>
					</div>
				</div>
				
	<!--Last Completed Task output-->			
				<div class='lasttask'>
					<div class='lasttaskhead'>
						<h2>Last Task Completed</h2>
					</div>
					
					<div class='resultholder'>	
						<div class='name'>Task:</div>
						<div class='resultname'>" . $confirmTask . "</div>
						
						<div class='name'>Status:</div>
						<div class='result'>" . $confirmStatus . "</div>

						<div class='name'>HP:</div>
						<div class='result'>" . $confirmHp . "</div>
						
						<div class='name'>XP:</div>
						<div class='result'>" . $confirmXp . "</div>
					
						<div class='name'>Coins:</div>
						<div class='result'>" . $confirmCoins . "</div>
					</div>
				</div>
				
	<!--Add task form-->";
		if($currentHp > 0){

?>	
		<div class="taskadd">
			<div class="title">Add A Task</div>
			
			<div class="taskaddform">
				<form action="includes/addTask.php" method="POST">

				<div class="taskholder">
					<input type="text" name="taskName" placeholder="Add a new task" maxlength="90">
				</div>

				<div class="dateholder">
					<div class="tasklabel">Deadline</div>
					<input type="date" min="<?php echo date("Y-m-d"); ?>" name="taskDate">
				</div>

				<div class="selectholder">
					<div class="tasklabel">Difficulty</div>
					<select name="taskDifficulty">
						<option value="easy">Easy</option>
						<option value="medium">Medium</option>
						<option value="hard">Hard</option>
					</select>
				</div>

				<div class="selectholder">
					<div class="tasklabel">Priority</div>
					<select name="taskPriority">
						<option value="normal">Normal</option>
						<option value="high">High</option>
					</select>
				</div>
				
				 <input type="hidden" name="characterBuild" value="<?php echo $characterBuild; ?>"> 
				
				<button type="submit" name="submit">Add Task</button>
				
				</form>
			</div>
		</div>
<?php
		} else{
			echo "	<div class='taskadd'>
						<div class='title'>Restore character's HP to add a new task.</div>
					</div>";
		}
		
	}
	
	//message to confirm task creation or error for missing fields
	if(isset($_SESSION['charactersuccess'])){
		echo"	<div class='charactersuccess'>
					<div class='title'>" . $_SESSION['charactersuccess'] . "</div>
				</div>";
		unset($_SESSION['charactersuccess']);
	}
	if(isset($_SESSION['charactererror'])){
		echo"	<div class='charactererror'>
					<div class='title'>" . $_SESSION['charactererror'] . "</div>
				</div>";
		unset($_SESSION['charactererror']);
	}
		
//selects the tasks tied to the selected character
	$sql = "SELECT TaskTable.taskId, TaskTable.taskName, TaskDetails.deadline, TaskDetails.difficulty, TaskDetails.priority
				FROM TaskTable LEFT JOIN TaskDetails 
				ON TaskTable.taskId = TaskDetails.taskId
				WHERE TaskTable.characterId = '". $_SESSION['charSelect'] . "' AND TaskDetails.status = 'open'
				ORDER BY TaskDetails.priority ASC, TaskTable.taskId ASC;";
	$result = mysqli_query($conn, $sql);

//Current tasks output		

	echo'	<div class="currenttasks">
				<div class="title">Current Tasks</div>
	
					<div class="currenttasksholder">';

//while loop outputs a row for each task fetched				
		while($row = mysqli_fetch_assoc($result)){

			$task = $row['taskId'];
			
			echo"	<div class='currenttasksoutput'>
						<div class='taskholder'>
							<div class='taskname'>" . $row['taskName'] . "</div>
						</div>
					
						<div class='dateholder'>
							<div class='tasklabel'>Deadline</div>
							<div class='taskdetails'>" . $row['deadline'] . "</div>
						</div>
					
						<div class='selectholder'>
							<div class='tasklabel'>Difficulty</div>
							<div class='taskdetails'>" . $row['difficulty'] . "</div>
						</div>
					
						<div class='selectholder'>
							<div class='tasklabel'>Priority</div>
							<div class='taskdetails'>" . $row['priority'] . "</div>
						</div>
						
						<div class='buttonholder'>";
						
			echo'		<form action="includes/completeTask.php" method="POST">
							<button type = "submit" name="taskSelect" value="'.$task.'">Complete Task</button></a>
						</form>
					</div>
				</div>';
		}
		
			if($numrows = mysqli_num_rows($result) < 1){
				echo "	<div class='currenttasksoutput'>
							<div class='notasks'>No current Tasks</div>
						</div>";
			}
			
	//End of currenttasks, currenttasksholder, and centered. (and fullpage)
	echo "		</div> 
			</div>
		</div>";
	unset($_SESSION['taskSelect']);
	include_once 'footer.php';
	} 