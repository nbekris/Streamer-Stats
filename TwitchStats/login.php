<?php
include('loginPHP.php'); // Includes Login Script
//Php switch statement to control the flow of the form
$formswitch = filter_input(INPUT_POST, 'action');
switch( $formswitch ) {
	case 'log':
	$log = 'Yes';
	$register = 'No';
	break;
	
	case 'register':
	$log = 'No';
	$register = 'Yes';
	break;
	
	case 'cancel':
	$log = 'No';
	$register = 'No';
	break;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<!--Enables styles for IE 9-->
<!--[if lt IE 9]>
<script src="html5shiv.js"></script>
<![endif]--> 
<title>Login</title>
<!--Character attribute: Unicode-->
<meta charset="utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="wrapper">
<header>
    <h1>Login</h1>
</header>
<!--Same as the index -->
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

<main>
<!--Different titles are displayed based on the user input. Controlled by the switch statement at the top-->
	<?php if ($log == 'No' && $register == 'No' ): ?>
		<h2>Welcome</h2>
	<?php endif; ?>
	<?php if ($log == 'Yes' && $register == 'No' ): ?>
		<h2>Login</h2>
	<?php endif; ?>
	<?php if ($register == 'Yes' && $log == 'No'): ?>
		<h2>Register</h2>
	<?php endif; ?>
	
	<form action="login.php" method="post">
	
	<!--Login avatar for decor. Contained by the imagecontainer class-->
		<div class="imgcontainer">
			<img src="avatar.png" alt="Avatar" class="avatar" height="350" width="200">
		</div>
		
	<!-- The user is presented with two options of the login or register. Controlled
	by the form switch.	-->
	<?php if ($log == 'No' && $register == 'No'): ?>
		<button type="submit" name="action" value="log" formnovalidate>Login</button>
		<button type="submit" name="action" value="register" formnovalidate>Register</button>
	<?php endif; ?>
	
	<!--Error to be displayed if the user got their user or password wrong -->
	<?php if ($error == "Username or Password is invalid"): ?>
	<?php echo "Username or Password is invalid"; ?>
	<?php endif; ?>
	
	<!--If the user clicks on the login button, the user is prompted to log in. -->
	<?php if ($log == 'Yes' && $register == 'No' ): ?>
		<div class="container">
		
			<label><b>Username</b></label>
			<!-- -->
			<?php if (!isset($_COOKIE['username']) && !isset($_COOKIE['password'])): ?>
			<input type="text" placeholder="Enter Username" name="username" required>
			<?php endif; ?>
			<?php if (isset($_COOKIE['username']) && isset($_COOKIE['password'])): ?>
			<input type="text" name="username" value = "<?php echo $username; ?>">
			<?php endif; ?>
			
			<label><b>Password</b></label>
			<!--If the two cookies are set, controlled by the remember me check box, 
			the username and password of the user is displayed in the check box.
			Else, the two fields are empty and required to advance in the form.-->
			<?php if (!isset($_COOKIE['username']) && !isset($_COOKIE['password'])): ?>
			<input type="password" placeholder="Enter Password" name="password" required>
			<?php endif; ?>
			<?php if (isset($_COOKIE['username']) && isset($_COOKIE['password'])): ?>
			<input type="password" name="password" value = "<?php echo $password; ?>">
			<?php endif; ?>
			
			<!--More login form actions. -->
			<button type="submit" name="input" value="Login">Login</button>
			<div class="floatleft"><b>Remember Me:</b></div>
			<!--Remember me check box to the create a cookie for the user login info to remain in the browser -->
			<input type="checkbox" name = "remember" checked="checked"></input>
			<!--Two options that are formatted by a CSS class. -->
			<div class="container" style="background-color:#f1f1f1">
				<!--Action form control to revert the form back to its original state. -->
				<button type="submit" name="action" value="cancel" class="cancelbtn" formnovalidate>Cancel</button>
				<!--Link to another page the user uses to get their password back. -->
				<span class="psw"><b>Forgot <a href="forgotpass.php">password?<b></a></span>
			</div>
		</div>
	<?php endif; ?>
	
	<!--Registration form control.-->
	<?php if ($register == 'Yes' && $log == 'No'): ?>
		<div class="container">
		
			<!-- Username form is dictated by cookies set after the register button has been clicked.-->
			<label><b>Username</b></label>
			
			<!--If the cookie is not set, the regular output is displayed. -->
			<?php if ($_COOKIE['invalidfield'] == ''): ?>
			<input type="text" placeholder="Enter Username" name="username" required>
			<?php endif; ?>
			
			<!--If the password entered do not match, the username the user entered is saved and redisplayed. -->
			<?php if ($_COOKIE['invalidfield'] == 'nonmatch' || $_COOKIE['invalidfield'] == 'invalemail'): ?>
			<input type="text" name="username" required value="<?php echo nl2br(htmlspecialchars($username)); ?>">
			<?php endif; ?>
			
			<!--If the user exist, an error will be displayed. -->
			<?php if ($_COOKIE['invalidfield'] == 'usrexist'): ?>
			<?php echo nl2br(htmlspecialchars($error));?>
			<input type="text" placeholder="Enter Username" name="username" required>
			<?php endif; ?>
			
			<!--Like the username, the password info is controlled by cookies. -->
			<label><b>Password</b></label>
			
			<!--If the user entered non matching passwords, the user will be presented with an error. -->
			<?php if ($_COOKIE['invalidfield'] == 'nonmatch'): ?>
			<?php echo nl2br(htmlspecialchars($error));?>
			<?php endif; ?>
			
			<!--The password is never redisplayed, this is for user protection.  -->
			<input type="password" placeholder="*******" name="password" required>
			<label><b>Re-enter Password</b></label>
			<input type="password" placeholder="*******" name="password2" required>
			
			<!--Email is also controlled by cookies. -->
			<label><b>Email</b></label>
			
			<!--The next few lines are similar to the username. If the user enters in an incorrect field
			a cookie is used to store that information to be redisplayed to the user.-->
			<?php if ($_COOKIE['invalidfield'] == 'invalemail'):?>
			<?php echo nl2br(htmlspecialchars($error));?>
			<input type="text" placeholder="example@email.com" name="email" required>
			<?php endif; ?>
			
			<?php if ($_COOKIE['invalidfield'] == 'nonmatch'): ?>
			<input type="text" name="email" required value ="<?php echo nl2br(htmlspecialchars($email)); ?>">
			<?php endif; ?>
			
			<?php if ($_COOKIE['invalidfield'] == 'usrexist'): ?>
			<input type="text" name="email" required value ="<?php echo nl2br(htmlspecialchars($email)); ?>">
			<?php endif; ?>
			
			<?php if ($_COOKIE['invalidfield'] == ''): ?>
			<input type="text" placeholder="example@email.com" name="email" required>
			<?php endif; ?>
			
			<!--Signup button along with the aforementioned cancel. -->
			<button type="submit" name="input" value="signup">Sign-up</button>
			<div class="container" style="background-color:#f1f1f1">
				<button type="submit" name="action" value="cancel" class="cancelbtn" formnovalidate>Cancel</button>
			</div>
		</div>
		<?php endif; ?>
	</form>
</main>

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
