<?php
  include('includes/connection.php');
  include('includes/functions.php');

  // variable inits
  $loginError = '';
  $activationError = '';

  if( isset($_GET['activate']) ) {
    $activateEmail = $_GET['email'];
    $activateHash = $_GET['hash'];

    $query = "SELECT hash FROM users WHERE email='$activateEmail'";
    $result = mysqli_query($conn, $query);

    if( $result ) {
      // matching email exists
      while( $row = mysqli_fetch_assoc( $result ) ) {
        $userHash = $row['hash'];
      }

      if( $userHash == $activateHash ) {
        // hashes match, user activated
        $query = "UPDATE users SET active='1' WHERE hash='$activateHash'";

        if( mysqli_query($conn, $query) ) {
          // query successful, activation succeeded
          $activationError = "<div class='alert alert-success'>Activation successful, log in now</div>";
        } else {
          //activation didn't succeed
          $activationError = "<div class='alert alert-danger'>This should never happen, contact administrator</div>";
        }
      } else {
        // hashes don't match, use can't be activated
        $activationError = "<div class='alert alert-danger'>Activation failed, no matching user</div>";
      }
    } else {
      // no matching email
      $activationError = "<div class='alert alert-danger'>Activation failed, no matching emails</div>";
    }
  }

  if( isset( $_POST['submit-login'] ) ) {
    // login button pressed
    $formEmail = validateFormData( $_POST['form-user'] );
    $formPass = validateFormdata( $_POST['form-password'] );

    if( $formEmail && $formPass ) {
      // data has been entered in both fields

      $query = "SELECT email, password, active FROM users WHERE email='$formEmail'";
      $result = mysqli_query($conn, $query);

      if( mysqli_num_rows($result) > 0 ) {
        // matching email found
        while( $row = mysqli_fetch_assoc($result) ) {
          $hashedPassword = $row['password'];
          $active = $row['active'];
        }

        if( password_verify( $formPass, $hashedPassword ) ) {
          // passwords match, check if user is active

          if( $active > 0 ) {
            // user is active
            session_start();
            $_SESSION['currentEmail'] = $formEmail;
            header("Location: lists.php");
          } else {
            // user hasn't activated email yet
            $loginError = "<div class='alert alert-warning'>Account not activated - check your email</div>";
          }
        } else {
          // passwords do not match
          $loginError = "<div class='alert alert-danger'>Incorrect email/password combination</div>";
        }

      } else {
        // no matching emails
        $loginError = "<div class='alert alert-danger'>Incorrect email/password combination</div>";
      }
    } else {
      // data is not in both fields
      $loginError = "<div class='alert alert-danger'>Please fill out both fields</div>";
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

    <title>Log in to Agenda</title>
  </head>
  <body>
    <?php include('includes/header.php'); ?>

    <div class="container">
      <div class="col-md-4 offset-md-4">
        <div class="login-area">

          <div class="circle-logo">
            <h4 class="gradient-text"><i><strong>Agenda</strong></i></h4>
          </div>
          <?php echo $activationError; ?>
          <?php echo $loginError; ?>

          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            <!-- username -->
            <div class="input-group">
              <div class="input-group-prepend">
                <span id="user-icon" class="input-group-text fas fa-user"></span>
              </div>
              <input id="username" class="form-control" type="text" name="form-user" placeholder="Email" />
            </div>

            <!-- password -->
            <div class="input-group">
              <div class="input-group-prepend">
                <span id="pass-icon" class="input-group-text fas fa-lock"></span>
              </div>
              <input id="password" class="form-control" type="password" name="form-password" placeholder="Password" />
            </div>

            <!-- submit button -->
            <div class="button-container">
              <button type="submit" name="submit-login" class="btn btn-primary">Log In</button>
            </div>

          </form>
          <div class="links-section">
            <a href="" class="front-link">Lost your password?</a>
            &nbsp;&nbsp;/&nbsp;&nbsp;
            <a href="signup.php" class="front-link">Register</a>
          </div>
        </div>
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
