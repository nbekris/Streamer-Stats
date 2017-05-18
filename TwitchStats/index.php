<?php 
//Objective: Create a general index page.
//Starts the session to see if the user is logged in.
include ('session.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!--Enables styles for IE 9-->
<!--[if lt IE 9]>
<script src="html5shiv.js"></script>
<![endif]--> 
<title>Home</title>
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
    <h1>Homepage</h1>
</header>

<!--Creates a set of navigation links to make a navigation bar and provide access to different parts of the website.
Altered by the CSS to create a bar at the top under the header. If the user is logged in, the login tag is 
set to log out.-->
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

<!--The main content goes here. The CSS alters this by setting margins in which the text goes. Gives the user some info on the site.-->
<main>
<!--Uses an h2 tag to display the title of the current page.-->
    <h2>Welcome to Streamer Stats!</h2>
		<h2>About</h2>
		<p>
		<br>
		This website holds a large amount of information on yout favorite twitch streamers and games! You have the ability to log on, save your preferred streamers and search statistics and games that they play. 
		Look below for the latest news, information, and streamers on twitch. 
		<br><br>
		<!--If the user is logged in, the content changes.-->
		<?php if(!isset($_SESSION['login_user'])) : ?>
		If you are not registered, head to the <a href="login.php">Log In</a> page to sign up and start selecting your preffered streamers.
		<?php endif; ?>
		<?php if(isset($_SESSION['login_user'])) : ?>
		You are logged in! Head over to the <a href="search.php">search</a> page and start selecting your favourite streamers!
		<?php endif; ?>
		<br> <br>
		<h2>News</h2>
		<br>
		<!--Image is configured to the right with configured dimensions.-->
		<img class="floatright" src="photo.png" alt="Photo" height="150" width="300">
			Today we all witnessed history being made. First time a channel has reached and passed over 1 million concurrent viewers by itself. This is a major breakthrough for Twitch, CS:GO and Eleaguetv which broke the record.
		Eleaguetv has reached over 1 million concurrent viewers on January 29, 2017 with 1,025,493 recorded viewers. This is the highest ever on Twitch.
		Eleaguetv had an average of 370,341 viewers in this time frame.
		<br><br><br>
		<figcaption>Fans cheer on their favorite CS:GO players, broadcasted live by Eleaguetv.</figcaption>
		<br><br><br>
			On the MOBA side of things, impressive records have been set in recent times with the 2016 League of Legends World Championship bringing in
		14.7 million concurrent viewers at the highest peak, over 23 broadcasts in 18 languages.
	<br><br>
		<img class="floatleft" src="LoLchamp3.png" alt="LeagueOfLegendsChampionship" height="200" width ="355">
		</p>
	<br>
		<figcaption>The 2016 League of Legends World Championship boasted a massive crowd.</figcaption>

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
