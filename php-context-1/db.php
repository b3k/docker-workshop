<?php

$servername = getenv("MYSQL_HOST");
$username = getenv("MYSQL_USER");
$password = getenv("MYSQL_PASS");

try {
  $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<h1>Connected successfully</h1>";
} catch (PDOException $e) {
  echo "<h1>Connection failed: " . $e->getMessage() . '</h1>';
}
