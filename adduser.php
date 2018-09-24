<?php
  session_start();
  include('includes/connection.php');
  include('includes/functions.php');

  $currentList = $_GET['listId'];
  // get the list name
  $query = "SELECT name FROM lists WHERE unique_id='$currentList'";
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)) {
    $currentListName = $row['name'];
  }

  if( isset( $_POST['submit-new-user'] ) ) {
    // submit button pressed
    if( $_POST['new-user'] ) {
      // user has been typed out
      // verify that active user is an admin
      $newUserEmail = $_POST['new-user'];
      if(verifyAdmin( $_GET['listId'], $_SESSION['currentEmail'] ) ) {
        // user is admin
        // check if email exists
        $query = "SELECT FROM users WHERE email='$newUserEmail'";
        $result = mysqli_query($conn, $query);

        if( mysqli_num_rows($result) > 0 ) {
          // user exists
          // check that user is not already added to list
          $query = "SELECT FROM lists WHERE user_email='$newUserEmail' AND unique_id='$currentList'";
          $result = mysqli_query($conn, $query);

          if( mysqli_num_rows($result) == 0 ) {
            // user is not already on list, so add them now
            $query = "INSERT INTO lists (id, unique_id, name, user_email) VALUES (NULL, '$currentList', '$currentListName', '$newUserEmail')";
          } else {
            // user already added
            $errorMessage = "<div class='alert alert-danger'>User already added to list</div>";
          }
        } else {
          // user does not exists
          $errorMessage = "<div class='alert alert-danger'>Email is not registered</div>";
        }
      } else {
        //user is not admin
        $errorMessage = "<div class='alert alert-danger'>You must be an administrator to add a user to a list</div>";
      }
    } else {
      // user has not been typed out
      $errorMessage = "<div class='alert alert-danger'>Please enter a user</div>";
    }
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
    <link rel="stylesheet" href="css/styles.css" />

    <title>Add User</title>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <div class="container">
      <div class="col-md-4 offset-md-4">
        <h1 style="text-align:center">Add User</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="row">
            <div class="col-sm-8">
              <input type="text" name="new-user" class="form-control" placeholder="User's Email" />
            </div>
            <div class="col">
              <button type="submit" class="btn btn-success" name="submit-new-user">Add User</button>
            </div>
          </div>
        </form>
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
