<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
	include_once 'header.php';
?>

<div class="centered">
	<div class="title">Create a Character:</div>
	
	<div class="info">
		<div class="infoimage">
			<img src="images/info/info1.png">
		</div>
		
		<div class="infodetails">
			<p>Before you can start creating tasks you must make a character to give them to. You can do this on your profile, select a build from the drop-down menu and give your character a name (make sure it's a safe name). Click profile at the top of the page or press the button below to make a character.<p>
			<a href="profile.php"><button>Make a character</button></a>
		</div>
	</div>
	
	<div class="title">Add a Task:</div>
	
	<div class="info">
		<div class="infoimage">
			<img src="images/info/info2.png">
		</div>
		
		<div class="infodetails">
			<p>Once you have a character you can see thier page using the 'See Character' button, now you can give them a task. Add a task name, deadline, and press 'Add Task' to assign a task to a character. If you complete a task, check it off by selecting the 'Complete Task' button of that task. Complete a task on time your character will be rewarded, fail to and your character will lose HP.<p>
			<a href="profile.php"><button>Go to profile</button></a>
		</div>
	</div>
	
	<div class="title">Buying Items:</div>
	
	<div class="info">
		<div class="infoimage">
			<img src="images/info/info3.png">
		</div>
		
		<div class="infodetails">
			<p>Go to the store by clicking the 'Store' button at the top of the page, When you have enough coins you can buy potions here. Make sure to select the character you want to give the potion to from the drop-down menu. Coins can be obtained by completing tasks on time.<p>
			<a href="store.php"><button>Go to store</button></a>
		</div>
	</div>
</div>
<?php
}