<?php
// Objective: Create a page so all of the databse info is the same.
// Connection info for the database. 

$servername = "localhost";
$username = "icoolsho_souchi";
$password = "$!985-86-9333";
$dbname = "icoolsho_souchi";

//Try to connect, if not, display an error.
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>