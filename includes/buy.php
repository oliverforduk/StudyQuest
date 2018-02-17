<?php
session_start();

include_once 'dbConnect.php';

//only let user in if form has been posted
if(!isset($_POST['submit'])){
	header("Location: index.php");
	exit();	
}else{
	
//Sets variables of character id and item selected from store.php
	$userId = $_SESSION['userId'];
	$characterId = $_POST['charSelect'];
	$item = $_POST['submit'];
	$price = $_POST['price'];
	
//Select the coins of user to be updated
	$sql = "SELECT coins
			FROM UserAccount
			WHERE userId = '". $userId . "';";	
	$result = mysqli_query($conn, $sql);

//Checks user has enough coins to purchase item
	while($row = mysqli_fetch_assoc($result)){
		if($row['coins'] < $price){
			$_SESSION['messageConfirm'] = "You don't have enough coins to buy this.";
			header("Location: ../store.php");
			exit();
		}else{		
				
//select characters hp to check potions will have an effect
		$sql = "SELECT currentHp 
				FROM CharacterDetails 
				WHERE characterId = '$characterId';";
		$result2 = mysqli_query($conn, $sql);

//Potion:
	if($item == 'potion'){
		while($row2 = mysqli_fetch_assoc($result2)){
			if($row2['currentHp'] == 100){
				$_SESSION['messageConfirm'] = "This will have no effect.";
				header("Location: ../store.php");
				exit();
			} 
		}
	//Adds hp to character 
		$sql = "UPDATE CharacterDetails
				SET currentHp = currentHp + 20
				WHERE characterId = '$characterId';";
		mysqli_query($conn, $sql);
	
	//If hp of character is over 100, reset to 100
		$sql = "SELECT currentHp 
				FROM CharacterDetails 
				WHERE characterId = '$characterId';";
		$result2 = mysqli_query($conn, $sql);
	
		while($row2 = mysqli_fetch_assoc($result2)){
			if($row2['currentHp'] > 100){
				$sql = "UPDATE CharacterDetails
						SET currentHp = 100
						WHERE characterId = '$characterId';";
				mysqli_query($conn, $sql);
			}
		}
	}
	}
	
//Updates the user's coins and redirects to store.php with confirmation message
		$coins = $row['coins'] - $price;
		$sql = "UPDATE UserAccount
				SET coins = '$coins'
				WHERE userId = '" . $userId . "';";
		mysqli_query($conn, $sql);
		$_SESSION['messageConfirm'] = "Item purchased and used.";
		header("Location: ../store.php");
	
		}
	}
	


