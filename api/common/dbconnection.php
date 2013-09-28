<?php

$con = new mysqli("localhost","portal_user", "HopperMyD0g", "Portal");
//$con=mysqli_connect("localhost","ablef014_blog", "HopperMyD0g", "ablef014_Blog");

/* check connection */
if (mysqli_connect_errno($con))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} 
?>
