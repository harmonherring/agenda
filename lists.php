<?php
  include('includes/connection.php');
  session_start();

  // variable inits
  $statusMessage = '';

  if( isset($_SESSION['currentEmail']) ) {
    // user is logged in
    $userEmail = $_SESSION['currentEmail'];
    $query = "SELECT name, unique_id, creator FROM lists WHERE user_email='$userEmail'";
    $result = mysqli_query($conn, $query);


  } else {
    // user is not logged in
    header("Location: index.php");
  }

 ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/list-styles.css" />

    <title>Groups</title>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <div class="container">
      <div class="col-md-6 offset-md-3">
        <h1>Your Lists</h1>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th style="width:40%">Name</th>
                <th>Leave</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
    <?php
    if( mysqli_num_rows($result) > 0 ) {
      // there are lists.  display them

      // array of each list's unique id - used to get list items on next page
      $listUniqueIds = array();
      $counter = 1;
      while( $row = mysqli_fetch_assoc($result) ) {
        $arrayName = $row['name'];
        $arrayID = $row['unique_id'];
        $creator = $row['creator'];

        if( $creator != 0 ) {
          // if user is creator of this list
          echo "<tr><th>". $counter ."</th><th><a class='normal-link' href='thelist.php?list=$arrayID'>". $arrayName ."</a></th><th></th><th><button class='btn btn-danger btn-sm hidden-button'><span class='fas fa-times'></span></button></th></tr>";

        } else {
          // if user is not the creator of this list
          echo "<tr><th>". $counter ."</th><th><a class='normal-link' href='thelist.php?list=$arrayID'>". $arrayName ."</a></th><th><button class='btn btn-danger btn-sm hidden-button'><span class='fas fa-sign-out-alt'></span></button></th><th></th></tr>";
        }

        $counter++;
      }
    } else {
      // there are no lists.  complain
      $statusMessage = "<div class='alert alert-warning'>You have no lists to display</div>";
    }

    echo $statusMessage;
     ?>
          </tbody>
        </table>
      </div>
     </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
  </body>
</html>
