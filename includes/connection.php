<?php

  $location = 'localhost';
  $user = 'root';
  $pass = 'root';
  $db = 'db_groceries';

  $conn = mysqli_connect($location, $user, $pass, $db);

  if( !$conn ) {
    echo "Error: " . mysqli_error($conn);
  }

 ?>
