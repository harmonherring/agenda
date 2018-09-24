<?php

  function validateFormData($data) {
    $data = trim(stripslashes(htmlspecialchars($data)));
    return $data;
  }


  function verifyAdmin( $listId, $userEmail ) {
    $query = "SELECT admin FROM lists WHERE user_email='$userEmail' AND unique_id='$listId'";
    $result = mysqli_query( $conn, $query );

    if( $result ) {
      while( $row = mysqli_fetch_assoc( $result ) ) {
        if( $row['admin'] ) {
          return TRUE;
        } else {
          return FALSE;
        }
      }
    } else {
      echo "Wrong list or user";
    }
  }
 ?>
