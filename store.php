<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
	
	include 'includes/dbConnect.php';
	include_once 'header.php';	
	
	//if confirmation messgae has been set by buy.php, display here
	if(isset($_SESSION['messageConfirm'])){
		echo $_SESSION['messageConfirm'];
		unset($_SESSION['messageConfirm']);
	}
	//select and store users coins
		$sql = "SELECT coins
				FROM UserAccount
				WHERE userId = '". $_SESSION['userId'] . "';";
		$result = mysqli_query($conn, $sql);
		
		while($row = mysqli_fetch_assoc($result)){
			$coins = $row['coins'];
		}
		echo "Your Coins: " . $coins;
	
	//select character names and Ids 
	$sql = "SELECT characterId, characterName
			FROM CharacterTable
			WHERE userId = '". $_SESSION['userId'] . "'
			ORDER BY characterId;";	
	$result = mysqli_query($conn, $sql);
	
	//item form (item info, character select & posts itemPrice and CharacterId to buy.php)
	echo "<form action='includes/buy.php' method='POST'>";
	echo "<table align='center' border='1'>";
	echo "<tr>
			<th width='180' align='left'>Item Name</th>
			<th width='180' align='left'>Item Description</th>
			<th width='180' align='left'>Character</th>
			<th width='180' align='left'>Item Price</th>
			<th width='180' align='left'>Buy</th></tr>";
			
	echo "<tr>
			<td>Potion</td>
			<td>Heals 20 HP</td>
			<td>
				<select name='charSelect'>";
					while($row = mysqli_fetch_assoc($result)){
						$row = "<option value=" . $row['characterId'] . ">" . $row['characterName'] . "</option>";
						echo $row;
					}
				echo"
				</select>
			</td>
			<td>500 Coins<input type='hidden' value='500' name='price'/></td>
			<td><button type='submit' name='submit' value='potion'>Buy Item</button></td>
		</tr>";
	echo "</table";
	echo "</form>";
	
	
}