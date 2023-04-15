<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_agency";

$conn = mysqli_connect($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . mysqli_connect_error());
}
else{
  mysqli_select_db($conn, $dbname);
  echo "Connected successfully";
}

?>
