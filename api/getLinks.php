<?php
require "common/magicquotes.php";
require "common/dbconnection.php";

//Use Id to select the last record entered
$sql = "select id,url,description,dateadded,status from links";
//$stmt = $con->prepare($sql);
//$stmt->execute();
//$stmt->bind_result($id, $url, $description, $dateadded, $status);



if ($result = $con->query($sql)) {

while ($row = $result->fetch_assoc()) {
    $links[] = $row;
}

$result->free();

echo json_encode($links);
}
    
?>