<?php
  include('includes/connection.php');
  include('includes/functions.php');

  // variable inits
  $submitMessage = '';


  if( isset( $_POST['submit-signup'] ) ) {
    $firstname = validateFormData($_POST['form-firstname']);
    $lastname = validateFormData($_POST['form-lastname']);
    $email = validateFormData($_POST['form-email']);
    $password = validateFormData($_POST['form-password']);
    $confirmPassword = validateFormData($_POST['form-confirm-password']);

    if($firstname && $lastname && $email && $password && $confirmPassword) {
      // all inputs have data
      // check for email duplicate
      $query = "SELECT * FROM users WHERE email='$email'";
      $result = mysqli_query($conn, $query);

      if( mysqli_num_rows($result) == 0 ) {
        // email is brand fuckin new
        if($password == $confirmPassword) {
          // passwords match
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);
          $hash = hash('crc32', $email) . hash('crc32', $hashed_password);



          $query = "INSERT INTO users (id, firstname, lastname, email, password, hash) VALUES (NULL, '$firstname', '$lastname', '$email', '$hashed_password', '$hash')";

          if(mysqli_query($conn, $query)) {
            // query successful, send mail

            $to = $email;
            $subject = "Agenda Registration";
            $message = '

            Thank you for registering with Agenda, the poorly coded web application by Harmon Herring.

            You will log in with this email address.

            Activate your account by clicking on this link:
            http://harmonherring.com/agenda/index.php?activate=1&email='.$email.'&hash='.$hash.'

            ';
            $headers = 'From:Harmon Herring<noreply@harmonherring.com>' . "\r\n";
            $headers .= 'Return-Path: <noreply@harmonherring.com> ' . "\r\n";


            mail( $to, $subject, $message, $headers );
              // mail sent successfully
            $submitMessage = "<div class='alert alert-success'>Check email for activation link</div>";
          } else {
            // query failed
            $submitMessage = "Error: " . mysqli_error($conn);
          }
        } else {
          // passwords do not match
          $submitMessage = "<div class='alert alert-danger'>Passwords do not match</div>";
        }
      } else {
        // email is a duplicate
        $submitMessage = "<div class='alert alert-danger'>Email already registered</div>";
      }
    } else {
      // all inputs do not have data
      $submitMessage = "<div class='alert alert-danger'>Please fill out all fields</div>";
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
    <link rel="stylesheet" href="css/signup-styles.css" />

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
          <div class="row">
            <div class="col">
              <?php echo $submitMessage; ?>
            </div>
          </div>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            <!-- first name and last name -->
            <div class="row">
              <div class="col">
                <input id="firstname" class="form-control" type="text" name="form-firstname" placeholder="First Name" />
              </div>
              <div class="col">
                <input id="lastname" class="form-control" type="text" name="form-lastname" placeholder="Last Name" />
              </div>
            </div>

            <!-- email -->
            <div class="row">
              <div class="col">
                <input id="email" class="form-control" type="email" name="form-email" placeholder="Email Address" />
              </div>
            </div>

            <!-- password -->
            <div class="row">
              <div class="col">
                <input id="password" class="form-control" type="password" name="form-password" placeholder="Password" />
              </div>
            </div>

            <!-- password -->
            <div class="row">
              <div class="col">
                <input id="confirm-password" class="form-control" type="password" name="form-confirm-password" placeholder="Confirm Password" />
              </div>
            </div>

            <!-- submit button -->
            <div class="button-container">
              <button type="submit" name="submit-signup" class="btn btn-primary">Sign Up</button>
            </div>

          </form>
          <div class="links-section">
            Already Registered?&nbsp;&nbsp;<a href="index.php" class="front-link">Log In</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
  </body>
</html>
