<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
	
	include 'includes/dbConnect.php';
	include_once 'header.php';	
	
	//select and store users coins
		$sql = "SELECT coins
				FROM UserAccount
				WHERE userId = '". $_SESSION['userId'] . "';";
		$result = mysqli_query($conn, $sql);
		
		while($row = mysqli_fetch_assoc($result)){
			$coins = $row['coins'];
		}
	
	//select character names and Ids 
	$sql = "SELECT characterId, characterName
			FROM CharacterTable
			WHERE userId = '". $_SESSION['userId'] . "'
			ORDER BY characterId;";	
	$result = mysqli_query($conn, $sql);
	?>
	<!--item form (item info, character select & posts itemPrice and CharacterId to buy.php)-->
	<div class="centered">

	<div class="wallet">
		<div class="title">Your Wallet</div>
		
		<div class="walletbody"><?php echo $coins ?></div>
	</div>
	
	<!--Item 1 (small potion ('smallpotion', '500'))-->
	<div class="items">
		<div class="title">Items</div>
		
		<div class="item">
			<div class="itemimg">
				<img src="images/items/item1.png"/>
			</div>
			
			<div class="itemdetails">
				<div class="itemlabel">Item Name & Description</div>
				<div class="itemcontent">Small Potion <p>[Heals 20 HP]</p></div>
			</div>
			
			<div class="itemprice">
				<div class="itemlabel">Price</div>
				<div class="itempricecontent">500 Coins</div>
				
				<form action='includes/buy.php' method='POST'>
					<input type='hidden' value='500' name='price'/>
			</div>
			
			<div class="itemchar">
				<div class="itemlabel">Item For:</div>
					<select name='charSelect'>
						<?php
							while($row = mysqli_fetch_assoc($result)){
								$row = "<option value=" . $row['characterId'] . ">" . $row['characterName'] . "</option>";
								echo $row;
							}
						?>
					</select>
			</div>
			
			<div class="itembutton">
				<button type='submit' name='submit' value='smallpotion'>Buy Item</button>
				</form>
			</div>
		</div>
		
		<!--Item 2 (big potion ('bigpotion', '1000'))-->
		<div class="item">
			<div class="itemimg">
				<img src="images/items/item2.png"/>
			</div>
			
			<div class="itemdetails">
				<div class="itemlabel">Item Name & Description</div>
				<div class="itemcontent">Big Potion <p>[Heals 40 HP]</p></div>
			</div>
			
			<div class="itemprice">
				<div class="itemlabel">Price</div>
				<div class="itempricecontent">1000 Coins</div>
				
				<form action='includes/buy.php' method='POST'>
					<input type='hidden' value='1000' name='price'/>
			</div>
			
			<div class="itemchar">
				<div class="itemlabel">Item For:</div>
					<select name='charSelect'>
						<?php
							$result = mysqli_query($conn, $sql);
							while($row = mysqli_fetch_assoc($result)){
								$row = "<option value=" . $row['characterId'] . ">" . $row['characterName'] . "</option>";
								echo $row;
							}
						?>
					</select>
			</div>
			
			<div class="itembutton">
				<button type='submit' name='submit' value='bigpotion'>Buy Item</button>
				</form>
			</div>
		</div>
		
		<!--Item 3 (revive potion ('revivepotion', '2000'))-->
		<div class="item">
			<div class="itemimg">
				<img src="images/items/item3.png"/>
			</div>
			
			<div class="itemdetails">
				<div class="itemlabel">Item Name & Description</div>
				<div class="itemcontent">Revive Potion <p>[Revives dead character]</p></div>
			</div>
			
			<div class="itemprice">
				<div class="itemlabel">Price</div>
				<div class="itempricecontent">2000 Coins</div>
				
				<form action='includes/buy.php' method='POST'>
					<input type='hidden' value='2000' name='price'/>
			</div>
			
			<div class="itemchar">
				<div class="itemlabel">Item For:</div>
					<select name='charSelect'>
						<?php
							$result = mysqli_query($conn, $sql);
							while($row = mysqli_fetch_assoc($result)){
								$row = "<option value=" . $row['characterId'] . ">" . $row['characterName'] . "</option>";
								echo $row;
							}
						?>
					</select>
			</div>
			
			<div class="itembutton">
				<button type='submit' name='submit' value='revivepotion'>Buy Item</button>
				</form>
			</div>
		</div>
		
		<!--Item 4 (special potion ('specialpotion', '5000'))-->
		<div class="item">
			<div class="itemimg">
				<img src="images/items/item4.png"/>
			</div>
			
			<div class="itemdetails">
				<div class="itemlabel">Item Name & Description</div>
				<div class="itemcontent">Special Potion <p>[Raises character's level]</p></div>
			</div>
			
			<div class="itemprice">
				<div class="itemlabel">Price</div>
				<div class="itempricecontent">5000 Coins</div>
				
				<form action='includes/buy.php' method='POST'>
					<input type='hidden' value='5000' name='price'/>
			</div>
			
			<div class="itemchar">
				<div class="itemlabel">Item For:</div>
					<select name='charSelect'>
						<?php
							$result = mysqli_query($conn, $sql);
							while($row = mysqli_fetch_assoc($result)){
								$row = "<option value=" . $row['characterId'] . ">" . $row['characterName'] . "</option>";
								echo $row;
							}
						?>
					</select>
			</div>
			
			<div class="itembutton">
				<button type='submit' name='submit' value='specialpotion'>Buy Item</button>
				</form>
			</div>
		</div>
		
		
	</div>


	
	<?php
	//if confirmation messgae has been set by buy.php, display here
	if(isset($_SESSION['storesuccess'])){
		echo"	<div class='success'>
					<div class='title'>" . $_SESSION['storesuccess'] . "</div>
				</div>
				</div>";
		unset($_SESSION['storesuccess']);
	}elseif(isset($_SESSION['storeerror'])){
		echo"	<div class='error'>
					<div class='title'>" . $_SESSION['storeerror'] . "</div>
				</div>
				</div>";
		unset($_SESSION['storeerror']);
	}else{
		echo"</div>";
	}
	
	echo "	
			</body>
			</html>";
}