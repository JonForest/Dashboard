<?php
require "common/magicquotes.php";
require "common/dbconnection.php";

//$tags = json_decode($_POST);
$model = json_decode(file_get_contents("php://input"));
$retArray = array();
$retId = array();


// Insert into the database
$sql = "INSERT INTO links (url, description, dateadded, status) VALUES (?, ?, NOW(), 1)"; 
$stmt = $con->prepare($sql);
$url = $model->url;
//$html = $con->real_escape_string($html);
$stmt->bind_param("ss",$model->url, $model->url); 
$stmt->execute(); 
$stmt->close(); //close statement 
$retId["id"] = $con->insert_id;
$retArray[] = $retId;


$con->close();

echo json_encode($retId)    
?>