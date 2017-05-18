<!-- *********************

Doesn't have any functionality used for aesthetics

	 *********************-->
	 
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
    <h2>Forgot Password?</h2>
	<!--Doesn't have any functionality used for aesthetics-->
	
	<!--Message and text box telling what the user to do-->
	<?php if (!isset($_POST['input'])) : ?>
	<p>Enter your email in the box and we will mail you your password!</p>
	<form action="forgotpass.php" method="post">
	<p>Enter Email:</p>
	<input type = "text" name = "text">
	<br>
	<input type = "submit" name = "input" value = "Get Password!">
	<?php endif; ?>
	
	<!--When the user hits get password-->
	<?php if (isset($_POST['input'])) : ?>
	<p>Thank you! You will get an email shortly! </p>
	<?php endif; ?>
	</form>
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
