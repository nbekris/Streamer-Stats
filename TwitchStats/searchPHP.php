
<? 
//Objective: The back end part of search.php
include('connection.php'); 
?>

<!--Javascript to display or hide the saved streamers button.-->
<script type="text/javascript">
	function myFunction(id) {
    var x = document.getElementById('myDIV');
	<!--If the display is blocked, the button will hide the content.-->
    if (x.style.display == 'block') {
        x.style.display = 'none';
	<!--Otherwise (when the button is clicked) the content is displayed.-->
    } else {
        x.style.display = 'block';
    }
	}
	
</script>

<?php
//Function to print games. Passed variables from connection.php and the statement from the search.php
function printGames($stmt, $conn) {
	
	$stmt->execute(); 
	
	//If no results were found, the user is presented with an error message.
	if (!$res = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "No games found.";
		
	//Otherwise, the results are displayed by a tables.
	}else{
		
		//While there is data to fetched, the table prints.
		while($res = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
		
			<!--Print header-->
			<table>
				<tr>
					<th width="35%">Game Name</th>
					<th width="20%">Genre</th>
					<th width="20%">Setting</th>
					<th width="10%">Player Capacity</th>
					<th width="10%">Tournaments</th>
					<th width="20%">Company</th>
				</tr>
				
				<!--Print game data from databse-->
				<tr>
					<td width="35%"><?php echo $res['GameName']; ?></td>
					<td width="20%"><?php echo $res['Genre']; ?></td>
					<td width="20%"><?php echo $res['Setting']; ?></td>
					<td width="10%"><?php echo $res['PlayerCapacity']; ?></td>
					<td width="10%"><?php echo $res['Tournaments']; ?></td>
					<td width="20%"><?php echo $res['GameCompany']; ?></td>
				</tr>
			</table>
			<br></br>
			
			<!--Print header for the streamers who play those games-->
			<table>
				<tr>
					<th width="30%" bgcolor="#fbfbb6" >Streamer Name</th>
					<th width="20%" bgcolor="#fbfbb6" >Hours</th>
					<th width="20%" bgcolor="#fbfbb6" >Views</th>
					<th width="10%" bgcolor="#fbfbb6" >Viewer Average</th>
					<th width="10%" bgcolor="#fbfbb6" >Followers</th>
					<th width="10%" bgcolor="#fbfbb6" >Personality</th>
					<th width="10%" bgcolor="#fbfbb6" >Save?</th>
				</tr>
			<?php
			
			//New php statement that takes streamers who play the game from above.
			$stmt2 = $conn->prepare(
				"SELECT Streamers.StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers,Personality 
				FROM Streamers 
				JOIN GameStream
					ON GameStream.StreamerID = Streamers.StreamerID
				JOIN Games
					ON Games.GameID = GameStream.GameID
				WHERE Games.GameID =:gID
				ORDER BY StreamerName"); //Uses a join to join the game table to streamers. We want
				//to display the streamers who play the games above. Uses a linking table to
				//join those tables.
				
			$stmt2->bindParam(":gID",$res['GameID']);
			$stmt2->execute();
			
			//Print streamer data from the streamer table as a table row.
			while($res2 = $stmt2->fetch(PDO::FETCH_ASSOC)){ ?>
				<tr>
					<td width="30%"><?php echo $res2['StreamerName']; ?></td>
					<td width="20%"><?php echo $res2['HoursOfOperation']; ?></td>
					<td width="20%"><?php echo $res2['Views']; ?></td>
					<td width="10%"><?php echo $res2['AverageViewers']; ?></td>
					<td width="10%"><?php echo $res2['Followers']; ?></td>
					<td width="10%"><?php echo $res2['Personality']; ?></td>
					<?php 
					
					//Check what streamer the user has already saved to either display a save or remove button.
					$check = $conn->prepare("SELECT StreamerID, UserID FROM UserStream WHERE StreamerID =:strId");
					$check->bindParam(":strId", $res2['StreamerID']);
					$check->execute();
					
					//Depending on whether the user is logged in or not, a different button is displayed to the user.
					if(isset($_SESSION['login_user'])){?>
						
						<!--If there are results in from the check, the button displayed is remove.-->
						<?php if ($result = $check->fetch(PDO::FETCH_OBJ)){ ?>
						<td width="10%"><button type="submit" name = "remove" value = "<?php echo $res2['StreamerID']; ?>">Remove</button></td>
						
						<!--Otherwise, the user is presented to save.-->
					<?php }else { ?>
						<td width="10%"><button type="submit" name = "save" value = "<?php echo $res2['StreamerID']; ?>">Save</button></td>
					<?php } ?>
				
				<?php }else{ ?>	
						<!--If the user isn't logged in, the user is redirected to the login page-->
						<td width="10%"><button type="submit" name = "login" value = "Login To Use!"></button></td>
				<?php } ?>
				</tr>
			<?php } ?>
			</table>
			<br></br>
	<?php } 
	} 
} ?>

<!--Print the Streamers from the streamer table. Works similarly to the games function-->
<?php 
function printStreamers($stmt, $conn){ 

	$stmt->execute(); 
	
	//If no results were found, the user is presented with an error message.
	if (!$res = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "No streamers found.";
	}else { ?>
	
		<!--Print headers -->
		<table>
			<tr>
				<th>Name</th>
				<th>Hours</th>
				<th>Views</th>
				<th>Viewer Average</th>
				<th>Followers</th>
				<th>Personality</th>
				<th>Save?</th>
			</tr>
		
	<!--While there are still rows to be fetched, print data as a table -->
	<?php while($res = $stmt->fetch(PDO::FETCH_ASSOC)){ ?> 
		<tr>
			<td><?php echo $res['StreamerName']; ?></td>
			<td><?php echo $res['HoursOfOperation']; ?></td>
			<td><?php echo $res['Views']; ?></td>
			<td><?php echo $res['AverageViewers']; ?></td>
			<td><?php echo $res['Followers']; ?></td>
			<td><?php echo $res['Personality']; ?></td>
			<?php 
				
			//Check what streamer the user has already saved to either display a save or remove button.
			$check = $conn->prepare("SELECT StreamerID, UserID FROM UserStream WHERE StreamerID =:strId");
			$check->bindParam(":strId", $res['StreamerID']);
			$check->execute();
				
			//Depending on whether the user is logged in or not, a different button is displayed to the user.
			if(isset($_SESSION['login_user'])){?>
				
				<!--If there are results in from the check, the button displayed is remove.-->
				<?php if ($result = $check->fetch(PDO::FETCH_OBJ)){ ?>
						<td width="10%"><button type="submit" name = "Remove" value = "<?php echo $res['StreamerID']; ?>">Remove</button></td>
						
				<!--Otherwise, the user is presented to save.-->
				<?php }else { ?>
						<td width="10%"><button type="submit" name = "save" value = "<?php echo $res['StreamerID']; ?>">Save</button></td>
				<?php } ?>
		
		<?php }else{ ?>	
					<!--If the user isn't logged in, the user is redirected to the login page-->
					<td width="10%"><button type="submit" name = "login" value = "Login To Use!"></button></td>
			<?php } ?>
				</tr> 
		<?php } ?>	
		
		</table>
		<br></br>
<?php } 
}?>

<?php  
//Other parts of form functionality.

//Saves or deletes the streamer after either the save or remove button was hit. Controlled by SQL interaction with database.
//Last if redirects to login page.
if (isset($_POST['save'])) {
	$streamer = filter_input(INPUT_POST, 'save');
	if(isset($_SESSION['login_user'])){
		$update = $conn->prepare("INSERT INTO UserStream (StreamerID, UserID) VALUES (:strID, :usrID)");
		$update->bindParam(":strID",$streamer);
		$update->bindParam(":usrID",$user_id );
		$update->execute();
	}
}

if (isset($_POST['remove'])) {  
	$streamer = filter_input(INPUT_POST, 'remove');
	if(isset($_SESSION['login_user'])){
		$update = $conn->prepare("DELETE FROM UserStream WHERE StreamerID =:strID");
		$update->bindParam(":strID", $streamer);
		$update->execute();
		echo "Streamer deleted.";
	}
}

//Executed if Login is pressed.
if (isset($_POST['login'])) {
	header("location: login.php");
}
?>