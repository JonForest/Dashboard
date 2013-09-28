<?php
require "common/magicquotes.php";
require "common/dbconnection.php";

//$tags = json_decode($_POST);
$id = (int)$_GET['id'];


// Insert into the database
$sql = "DELETE FROM links WHERE id=?"; 
$stmt = $con->prepare($sql);

$stmt->bind_param("i",$id); 
$stmt->execute(); 
$stmt->close(); //close statement 
 
?>