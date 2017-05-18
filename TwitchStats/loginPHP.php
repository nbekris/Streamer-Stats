<?php
//Objective: A form that contains mostly PHP code to supplement the login page. Essentially the back end 
//of the application
session_start(); // Starting Session
include ('connection.php');

//If cookies are set by the remember me checkbox, the following variables will be set.
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])){
				$username = $_COOKIE['username'];
				$password = $_COOKIE['password'];
}
//If the user mis-inputs incorrect information (User, password, email), correct information
//is stored in the text boxes after refresh. (For example, passwords don't match, but the email and username were
//accepted, after the page refreshes, user and email are stored in the input text boxes for the convenience of
//the user.)
switch($_COOKIE['invalidfield']){
	case 'nonmatch':
	$error = '*Passwords do not match';
	$register = 'Yes';
	$log = 'No';
	if (isset($_COOKIE['email']) && isset($_COOKIE['username'])){
	$email = $_COOKIE['email'];
	$username = $_COOKIE['username'];
	}
	break;

	case 'invalemail':
	$error = '*Invalid email';
	$register = 'Yes';
	$log = 'No';
	if (isset($_COOKIE['username'])){
	$username = $_COOKIE['username'];
	}
	break;
	
	case 'usrexist':
	$error = '*User already exists';
	$register = 'Yes';
	$log = 'No';
	if (isset($_COOKIE['email'])){
	$email = $_COOKIE['email'];
	}
	break;
	
	default:	
	$log = 'No';
	$register = 'No';
	break;
	
}

$error=''; // Variable To Store Error Message

//Variables taken from the login/registration forms. 
$action = filter_input(INPUT_POST, 'input');
$username=filter_input(INPUT_POST,'username');
$password=filter_input(INPUT_POST,'password');
$password2 =filter_input(INPUT_POST,'password2');
$email =filter_input(INPUT_POST,'email');

//Switch statement that either logs the user in or creates their account.
switch( $action ) {
	
	//Case: Login.
	case 'Login':
	
		//Prepare SQL statement
		$stmt = $conn->prepare("SELECT Username, UserPassword FROM Users WHERE Username =:usrname AND UserPassword =:pssword");
		//Bind SQL statements to user input.
		$stmt->bindParam(":usrname",$username);
		$stmt->bindParam(":pssword",$password);
		//Execute statement
		$stmt ->execute();
		
		//On successful login
		if ($result = $stmt->fetch(PDO::FETCH_OBJ)){
		//Create a session bound to the username of the user.
		$_SESSION['login_user'] = $username;
		header("location: index.php"); // Redirecting To home page
		
		//If the user clicked on the remember me button, their username and password is stored in a cookie
		//for 5 minutes.
			if (($_POST['remember'])) {
				setcookie("username", $username, time() + 5 * 60);
				setcookie("password", $password, time() + 5 * 60);
				$username=filter_input(INPUT_POST,'username');
				$password=filter_input(INPUT_POST,'password');
			}
		//If unsuccessful, the user is redirected to the login page and an error message is displayed.
		}else{
			$log = 'Yes';
			$register = 'No';
			$error = "Username or Password is invalid";
		}
		
	break;
	
	//Case: Signup
	case 'signup':
	
		//Statement to check and see if user already exists.
		$stmt = $conn->prepare("SELECT Username FROM Users WHERE Username =:usrname");
		$stmt->bindParam(":usrname",$username);
		$stmt ->execute();
		
		//If the passwords don't match, a cookie is set to store some of the information that was valid.
		if ($_POST['password'] != $_POST['password2']){
			setcookie("invalidfield","nonmatch",time() + 5 * 1);
			setcookie("username", $username, time() + 5 * 60);
			setcookie("email", $email, time() + 5 * 60);
			//Redirect to the login page with the cookie variables in place.
			header("location: login.php");
			
		//If the email is invalid, a cookie is set to store valid information.
		}else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			setcookie("invalidfield","invalemail",time() + 5 * 1);
			setcookie("username", $username, time() + 5 * 60);
			header("location: login.php");
			
		//If the username is already taken, a cookie is set to store the correct information.
		}else if ($result = $stmt->fetch(PDO::FETCH_OBJ)){
			setcookie("invalidfield","usrexist",time() + 5 * 1);
			setcookie("email", $email, time() + 5 * 60);
			header("location: login.php");
			
		//Else, the user has entered the correct information and an account is create.
		}else{
	
			//INsert SQL statement to insert the user input into the table Users.
			$stmt = $conn->prepare("INSERT INTO Users VALUES ('DEFAULT', :email, :user, :password)");
			
			//Bind params to the user input.
			$stmt->bindParam(":user",$username);
			$stmt->bindParam(":password",$password);
			$stmt->bindParam(":email",$email);
			
			//Execute
			$stmt->execute();
			header("location: login.php"); // Redirecting To Other Page to log in.
			break;
		}
    }

?>