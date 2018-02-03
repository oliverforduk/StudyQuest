<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
//Account options for user:
	include 'includes/dbConnect.php';
	include_once 'header.php';

//Outputs confirmation message if an action has been taken
	if(isset($_SESSION['messageConfirm'])){
		echo $_SESSION['messageConfirm'];
		unset($_SESSION['messageConfirm']);
	}

//delete character: selects characters tied to userId session var and outputs

$sql = "SELECT * 
		FROM CharacterTable 
		WHERE userId = '". $_SESSION['userId'] . "'
		ORDER BY characterId;";	
		$result = mysqli_query($conn, $sql);
		$charactercheck = mysqli_num_rows($result);
		
		if($charactercheck > 0){
	
			echo "Your characters:";
			echo "<br/>";
			
			echo "<table align='center' border='1'>";
			echo "<tr>
					<th width='180' align='left'>Charater Name</th>
					<th width='180' align='left'>Delete</th></tr>";
			
			while($row = mysqli_fetch_assoc($result)){
				$charId = $row['characterId'];
				echo "<tr>";
				echo "<td>" . $row['characterName'] . "</td>";
				echo '<td><form action="includes/processOptions.php" method="POST">
					<button type="submit" name="charDel" value="'.$charId.'" onclick="return confirm(\'Are you sure you want to delete this character?\');">Delete</button>
					</form></td>';
			}
			
			echo "</table>";
		
		}else{
			echo "You have no characters.";
		}

//delete account (deletes all tasks tied to all character ids, and then user)
	echo "Your account:";
	echo "<br/>";
	echo "<table align='center' border='1'>";
	echo "<tr><th width='180' align='left'>Delete Account?</th>";
	echo '<td><form action="includes/processOptions.php" method="POST">
					<button type="submit" name="userDel" onclick="return confirm(\'Are you sure you want to delete your account?\');">Delete Account.</button>
					</form></td>';
	echo "</form>";
	
}