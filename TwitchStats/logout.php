<?php
//Objective: Destroy login/session.
session_start();
//Destroy seesion.
if(session_destroy()) 
    {	
		//Redirect to home.
        header("Location: index.php"); 
    }
?>

