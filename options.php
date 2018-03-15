<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
//Account options for user:
	include 'includes/dbConnect.php';
	include_once 'header.php';

//delete character: selects characters tied to userId session var and outputs

$sql = "SELECT CharacterTable.characterId, CharacterTable.characterName, CharacterDetails.build
		FROM CharacterTable LEFT JOIN CharacterDetails
		ON CharacterTable.characterId = CharacterDetails.characterId
		WHERE CharacterTable.userId = '". $_SESSION['userId'] . "'
		ORDER BY CharacterTable.characterId;";	
		
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		$charactercheck = mysqli_num_rows($result);
		
		echo"	<div class='centered'>

					<div class='title'>Options</div>
	
					<div class='charoptions'>
					<div class='title'>Delete A Character</div>";
		
		if($charactercheck > 0){
			
			while($row = mysqli_fetch_assoc($result)){
				$charId = $row['characterId'];
				echo'	<div class="chars">
							<div class="charimg">
								<img src="images/builds/' . $row['build'] . 'mini.png"/>
							</div>
			
							<div class="charname">' . $row['characterName'] . '</div>
			
							<div class="charbutton">
								<form action="includes/processOptions.php" method="POST">
									<button type="submit" name="charDel" value="' . $charId . '" onclick="return confirm(\'Are you sure you want to delete this character?\');">Delete</button>
								</form>
							</div>
						</div>';
			}
		
		}else{
			echo "<div class='nochars'>You have no characters</div>";
		}
		//end of charoption div
		echo "</div>";

//delete account (deletes all tasks tied to all character ids, and then user)

		$sql = "SELECT * 
				FROM UserAccount 
				WHERE userId = '". $_SESSION['userId'] . "'";	
		$result = mysqli_query($conn, $sql);
		
		while($row = mysqli_fetch_assoc($result)){
			$useraccount = $row['email'];
		}
	
	echo'	<div class="charoptions">
				<div class="title">Delete Your Account</div>
		
				<div class="chars">
					<div class="useremail">' . $useraccount . '</div>
			
					<div class="charbutton">
						<form action="includes/processOptions.php" method="POST">
							<button type="submit" name="userDel" onclick="return confirm(\'Are you sure you want to delete your account?\');">Delete Account</button>
						</form>
					</div>
				</div>
			</div>';
	
	//Outputs confirmation message if an action has been taken
	if(isset($_SESSION['messageConfirm'])){
		echo"	<div class='optionsmessage'>
					<div class='title'>" . $_SESSION['messageConfirm'] . "</div>
				</div>";
		unset($_SESSION['messageConfirm']);
	}
	
	//end of centered div
	echo"</div>";
	
}