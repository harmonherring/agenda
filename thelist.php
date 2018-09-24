<?php
  session_start();
  include('includes/connection.php');

  // variable inits
  $errorMessage = '';
  $newItemError = '';
  $continue = FALSE;
  $listName = '';

  //check if user is logged in
  if( isset($_SESSION['currentEmail']) ) {
    // user is logged in
    $currentEmail = $_SESSION['currentEmail'];
    if( isset( $_GET['list'] ) ) {
      // list is specified - this is to avoid errors
      // make sure the list exists
      $requestedList = $_GET['list'];
      $query = "SELECT name, user_email FROM lists WHERE unique_id='$requestedList' AND user_email='$currentEmail'";
      $result = mysqli_query($conn, $query);

      // check for results
      if( mysqli_num_rows($result) > 0 ) {
        // list exists
        // set some variables
        // then make sure user is allowed to access
        while( $row = mysqli_fetch_assoc($result) ) {
          $listName = $row['name'];
        }

        $currentEmail = $_SESSION['currentEmail'];
        $query = "SELECT name, unique_id FROM lists WHERE user_email='$currentEmail'";
        $result = mysqli_query($conn, $query);
        if( mysqli_num_rows($result) > 0 ) {
          // user has permission
          while( $row = mysqli_fetch_assoc($result) ) {
            if( $row['unique_id'] = $requestedList ) {
              $continue = TRUE;
              break;
            }
          }
        } else {
          // user does not have permission
          $errorMessage = "<div class='alert alert-danger'>You dont' have permission to view this list</div>";
        }
      } else {
        //list does not exist
        $errorMessage = "<div class='alert alert-danger'>You don't have permission to view this list</div>";
      }
    } else {
      $errorMessage = "<div class='alert alert-danger'>List not specified</div>";
    }
  } else {
    // user not logged in
    header("Location: index.php");
  }


  // add new item
  if( isset( $_POST['submit-new-item'] ) ) {
    if( $_POST['new-item'] ) {
      $newItem = $_POST['new-item'];

      // get the current user's name
      $query = "SELECT firstname FROM users WHERE email='$currentEmail'";
      $result = mysqli_query($conn, $query);

      while($row = mysqli_fetch_assoc($result)) {
        $currentName = $row['firstname'];
      }

      // add the item
      $query = "INSERT INTO items (id, name, added_by, list_id) VALUES (NULL, '$newItem', '$currentName', '$requestedList')";
      $result = mysqli_query($conn, $query);

      if($result) {
        echo "yup";
      } else {
        echo mysqli_error($conn);
      }
    } else {
      $newItemError = "<div class='alert alert-danger'>Enter an item name</div>";
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
    <link rel="stylesheet" href="css/list-styles.css" />

    <title>List</title>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <div class="container">
      <div class="col-md-6 offset-md-3">
        <!--<a class="btn btn-success" <?php echo 'href="adduser.php?list="'; ?> >Add User</a>-->
        <h1><?php echo $listName; ?></h1>

        <?php

          if( $continue ) {

            $query = "SELECT name, added_by FROM items WHERE list_id='$requestedList'";
            $result = mysqli_query($conn, $query);

              if( mysqli_num_rows($result) > 0 ) { ?>
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th style="width:40%">Item</th>
                    <th>Added By</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>

                <?php
                $counter = 1;
                while( $row = mysqli_fetch_assoc($result) ) {
                  $itemName = $row['name'];
                  $addedBy = $row['added_by'];

                  echo "<tr><td>". $counter ."</td><td>". $itemName ."</td><td>". $addedBy ."</td><td>Delete</td></tr>";

                  $counter++;
                }
                ?>

              </tbody>
            </table>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'].'?list='.$requestedList);?>" method="post">
              <div class="row">
                <div class="col-sm-9 mx-auto">
                  <input type="text" name="new-item" placeholder="New Item" class="form-control" />
                </div>
                <div class="col ml-auto">
                  <button type="submit" name="submit-new-item" class="btn btn-success new-item">Add Item</button>
                </div>
              </div>
            </form>
            <br />
            <?php echo $newItemError; ?>
            <?php
          } else {
            $errorMessage = "<div class='alert alert-warning'>This list has no items</div>";
          }
        }

        echo $errorMessage;
        ?>
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
