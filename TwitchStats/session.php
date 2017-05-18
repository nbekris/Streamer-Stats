
<?php
//Objective: Establish a session if the user is logged in.
include ('connection.php');
session_start();// Starting Session

// Storing Session
$user_check=$_SESSION['login_user'];

// SQL Query To Fetch Complete Information Of User
$result = $conn->prepare("SELECT UserID, Username, UserPassword FROM Users WHERE Username = :user_check");
$result->execute(array(":user_check"=>$user_check));
$row = $result->fetch(PDO::FETCH_ASSOC);

//Grab some variables that can be used throughout the form. Mainly, Username and ID
$login_session = $row['Username'];
$user_id =$row['UserID'];
$user_passwords = $row['UserPassword'];


?>