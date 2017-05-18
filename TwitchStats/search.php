<?php 
//Objective, create a search page for the user to find and save their favourite streamers.
include('session.php');
include('connection.php');
include('searchPHP.php')
?>

<!DOCTYPE html>

<html lang="en">
<head>
<!--Enables styles for IE 9-->
<!--[if lt IE 9]>
<script src="html5shiv.js"></script>
<![endif]--> 
<title>Search</title>
<!--Character attribute: Unicode-->
<meta charset="utf-8">
<!--Uses the "teadex.css" to reformat the page-->
<link href="style.css" rel="stylesheet">
</head>
<body>
<!--Reformats the webpage's margins based on the CSS sheet provided.
Configured to 80%-->
<div id="wrapper">
<!--Display the main header for the website. This is hidden by the CSS and replaced with
an image.-->
<header>
    <h1>Search</h1>
</header>

<!--Creates a set of navigation links to make a navigation bar and provide access to different parts of the website.
Altered by the CSS to create a bar at the top under the header.-->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li> 
        <li><a href="search.php">Search</a></li>
		<?php if(!isset($_SESSION['login_user'])) : ?>
        <li><a href="login.php">Log In</a></li>
		<?php endif; ?>
		<?php if(isset($_SESSION['login_user'])) : ?>
        <li><a href="logout.php">Log Out</a></li>
		<?php endif; ?>
    </ul>   
</nav>

<!--The main content goes here. The CSS alters this by setting margins in which the text goes.-->
<main>
	<form action="search.php" method="post">
		<h2>Search</h2>
		
		<!--Display an image for decoration. -->
		<div class="imgcontainer">
			<img src="search.png" alt="search" height="200" width="200">
		</div>
		<!--Buttons that control how the form is controlled. -->
		<button type="submit" name="game" value="game" class = "floated">Game</button>
		<button type="submit" name="streamer" value="streamer" class = "floated">Streamer</button>
		
		<!--If the streamer button is pressed, display the following options. -->
		<?php if (isset($_POST['streamer']) && !empty($_POST['streamer'])) : ?>
		
			<!--Search by the filter options. Presented as drop downs. -->
			<h3>Filter Options</h3>
		
			<!--Search by gender. No non-binary streamers were found sadly. -->
			<p>Choose Streamer Gender</p>
			<select name="gender">
				<option value="select" selected>Select</option>
				<option value="Female">Female</option>
				<option value="Male">Male</option>
			</select>
			
			<!--Search by the streamers, "personality" -->
			<p>Choose Streamer Personality</p>
			<select name="personality">
				<option value="select" selected>Select</option>
				<option value="Competitive">Competitive</option>
				<option value="Funny">Funny</option>
				<option value="Informative">Informative</option>
				<option value="Laid back">Laid Back</option>
				<option value="Speedrun">Speed Run</option>
				<option value="Talkative">Talkative</option>
			</select>
			<br><br>
			<!--Submit button. Sends data to the searchPHP form. -->
			<input type = "submit" name = "input" value = "Search Streamers">
			<br>
			
			<!--Or you can search by user text input -->
			<h3>Search Streamer</h3>
			<p>Or type in the name of the streamer you want to search :</p>
			<input type="search" name="streamer_search">
			<input type = "submit" name = "input" value = "Search by Name">
		<?php endif;?>
		
		<!--Same as the streamers, gmes have the same options. Drop down list or search bar. -->
		<?php if (isset($_POST['game']) && !empty($_POST['game'])) : ?>
			<h3>Filter Options</h3>
			
			<p>Choose Game Genre</p>
			<select name="game_genre">
				<option value="select" selected>Select</option>
				<option value="CARD GAME">Card Game</option>
				<option value="FIGHTING">Fighting</option>
				<option value="FPS">FPS</option>
				<option value="RPG">RPG</option>
				<option value="MMO">MMO</option>
				<option value="MOBA">MOBA</option>
				<option value="RTS">RTS</option>
				<option value="SPORTS">Sports</option>
				<option value="SANDBOX">Sandbox</option>
			</select>
			
			<p>Choose Setting</p>
			<select name="game_setting">
				<option value="select" selected>Select</option>
				<option value="Fantasy">Fantasy</option>
				<option value="Future">Future</option>
				<option value="Historical">Historical</option>
				<option value="Modern">Modern</option>
			</select>
			<br><br>
			<input type="submit" name="input" value="Search Games">
			<br>
			<h3>Search Game</h3>
			<p>Or type in the name of the game you want to search :</p>
			<input type="search" name="game_search">
			<input type="submit" name="input" value="Search by Game">
		<?php endif; ?>
	
	<!--START OF PHP CODE:
		Used to display results back to the user.-->
	<?php
	//If they saved a removed a streamer from their favourites, the user is given one of the messages.
	if (isset($_POST['save'])) {
		echo "Streamer saved";
	}
	if (isset($_POST['remove'])) {
		echo "Streamer deleted";
	}
	
	//Get variables from the user search options.
	if (isset ($_POST['input'])){
	$search_filter = filter_input(INPUT_POST, 'input');

	$streamer_search = filter_input(INPUT_POST, 'streamer_search');
	$game_search = filter_input(INPUT_POST, 'game_search');

	$gender_type = filter_input(INPUT_POST, 'gender');
	$personality_type = filter_input(INPUT_POST, 'personality');

	$genre_type = filter_input(INPUT_POST, 'game_genre');
	$setting_type = filter_input(INPUT_POST, 'game_setting');
	
	//Search queries made based on the user input.
	switch ($search_filter){
		
		//Next two cases handle game searching.
		case ("Search by Game"):
		
			//If the user enters a blank field, the user is given every game and streamer.
			if($game_search == ''){
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games ORDER BY GameName");
			}else{
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games WHERE GameName = :game_name ORDER BY GameName");
				$stmt->bindValue(":game_name", $game_search);
			}
			//Calls on the printGames function to display results.
			printGames($stmt, $conn);
			break;
			
		case ("Search Games"):
			
			//If the user doesn't fill ($variable = select) out certain fields, the query changes.
			if($genre_type == "select" && $setting_type == "select"){
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games ORDER BY GameName");
			}else if($genre_type == "select"){
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games WHERE Setting = :setting ORDER BY GameName");
				$stmt->bindValue(":setting", $setting_type);
			}else if($setting_type == "select"){
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games WHERE  Genre = :genre ORDER BY GameName");
				$stmt->bindValue(":genre", $genre_type);				
			}else{
				$stmt = $conn->prepare("SELECT GameID, GameName, Genre, Setting, PlayerCapacity, Tournaments,
				GameCompany FROM Games WHERE Genre = :genre AND Setting = :setting ORDER BY GameName");
				$stmt->bindValue(":genre", $genre_type);
				$stmt->bindValue(":setting", $setting_type);
			}
			printGames($stmt, $conn);
			break;
		
		//Next two cases handle streamer searching. Works in similar fashion to the game queries.
		case("Search by Name" ): 
			
			if ($streamer_search == ''){
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers,Personality 
				FROM Streamers ORDER BY StreamerName");
			}else{
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation, Views, AverageViewers, Followers,Personality 
			FROM Streamers WHERE StreamerName = :namesearch ORDER BY StreamerName");
				$stmt->bindValue(":namesearch", $streamer_search);
			}
			printStreamers($stmt, $conn);
			break;
		
		case("Search Streamers"):
			
			if($personality_type == "select" && $gender_type == "select"){
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers, Personality 
				FROM Streamers ORDER BY StreamerName");
			}else if($personality_type == "select"){
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers, Personality 
				FROM Streamers WHERE Gender = :gnder ORDER BY StreamerName");
				$stmt->bindValue(":gnder", $gender_type);
			}else if($gender_type == "select"){
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers, Personality 
				FROM Streamers WHERE Personality = :prson ORDER BY StreamerName");
				$stmt->bindValue(":prson", $personality_type);
			}else{
				$stmt = $conn->prepare("SELECT StreamerID, StreamerName, HoursOfOperation,Views, AverageViewers, Followers, Personality 
				FROM Streamers WHERE Gender = :gnder AND Personality = :prson ORDER BY StreamerName");
				$stmt->bindValue(":gnder", $gender_type);
				$stmt->bindValue(":prson", $personality_type);
			}
			printStreamers($stmt, $conn);
			break;
		
		}
	} ?>
	</form>
	
	<!--Form for seeing which streamers the user has saved. Controlled by a clickable button.
	Button can be clicked to reveal content. Controlled by Java script function-->
	<button name ="clickMe" onclick="myFunction('myDIV')">Show/Hide Saved Streamers</button>
	
		<!--Div tag that contains the content controlled by function-->
		<div id="myDIV" style="display:none">
		
		<!--If the user isn't logged in, the table feature is replaced by a prompt to log in.-->
		<?php if (!isset($_SESSION['login_user'])): ?>
		<b><a href="login.php">Log In </a></b> to use this feature!
		<?php endif; ?>
		
		<!--If the user is logged in, the user is presented with their saved streamers.-->
		<?php if (isset($_SESSION['login_user'])): ?>
		<form action="search.php" method="post">
		
		<!--Print table headers.-->
		<table>
		<tr>
		<th>Name</th>
		<th>Hours</th>
		<th>Views</th>
		<th>Viewer Average</th>
		<th>Followers</th>
		<th>Personality</th>
		<th>Remove?</th>
		</tr>
		
			<!-- SQL statement to find the . $user_id pulled from session.-->
			<?php
			$stmt = $conn->prepare(
			"SELECT Streamers.StreamerID, StreamerName, HoursOfOperation, Views, AverageViewers, Followers,Personality 
			FROM Streamers 
			JOIN UserStream
				ON UserStream.StreamerID = Streamers.StreamerID
			WHERE UserID =:usrID
			ORDER BY StreamerName"); //Statement to join the user table with the streamers table. We want to the 
			//streamers but only pertaining to thbe userID
			$stmt->bindParam(":usrID",$user_id); 
			$stmt->execute(); 
			
			//Print results as table rows.
			while($res = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
				<tr>
					<td width="30%"><?php echo $res['StreamerName']; ?></td>
					<td width="20%"><?php echo $res['HoursOfOperation']; ?></td>
					<td width="20%"><?php echo $res['Views']; ?></td>
					<td width="10%"><?php echo $res['AverageViewers']; ?></td>
					<td width="10%"><?php echo $res['Followers']; ?></td>
					<td width="10%"><?php echo $res['Personality']; ?></td>
					
					<!--Prints out a remove button to remove the streamer. Value is equal to their StreamerID-->
					<td width="10%"><button type="submit" name =  "remove" value = "<?php echo $res['StreamerID']; ?>">Remove</td>
				</tr>
		<?php } ?>
		</table>
		</form>
		<?php endif; ?>
		</div>
</main>

<!--Email and copyright information is housed in this area. Configured by the CSS
to remain with certain margins.-->
<footer>

	<!--Configures small navigation area at the bottom just in case the on at the top
	cannot be displayed.-->
	<a href="index.php">Home</a>
		&nbsp;|&nbsp;
	<a href="search.php">Search</a>
		&nbsp;|&nbsp;
	<?php if(!isset($_SESSION['login_user'])) : ?>
		<a href="login.php">Log In</a>
	<?php endif; ?>
	<?php if(isset($_SESSION['login_user'])) : ?>
		<a href="logout.php">Log Out</a>
	<?php endif; ?>
		<br><br>

Copyright &copy; 2017 Name<br>
E-mail: <a href="mailto:email@gmail.com">
email@gmail.com</a><br><br> 
</footer>
</div>
</body>
</html>