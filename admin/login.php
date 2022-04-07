<?php
    session_start();
    require_once '../includes/_config.php';
    require_once '../vendor/autoload.php';

    var_dump($_SESSION['admin']);
    // Is user already logged in?
    if (is_admin_logged_in()) {
        header('location:index.php');
        exit();    
    }
    if (isset($_POST['submit'])) {
    $email    = strtolower( trim( process_param_input( 'email', false ) ) );
    $password = hash_password( process_param_input( 'password', false ) );

    if (!($email && $password)) {
      $error = 'Empty field(s)';
    }

    $db = new DBCon(CON_TYPE);
    $con = $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $dbq = new Query($con);
    $dbq->set_fetch_mode('assoc');

    // Check if the user is inactive.
 
    if (!$dbq->get( 'select id from admin WHERE email = ? and password = ? ', [$email, $password] ) ) {
        // Error.
        $error = 'An unknown error occurred, please try again.';
    }

    // No error, continue.
    if ($dbq->row_count == 1) {
        $_SESSION['admin'] = $dbq->record[0]['id'];
        header('location:index.php');
    } else {
        $error = 'Incorrect Email/Password.';
    }
    }
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Admin Login</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        </head>
        <body>
<!-- Pills content -->
<div class="tab-content">
    
<div id="note-full-container" class="note-has-grid row">
    <div class="col-md-offset-3 col-md-4 single-note-item all-category note-important" style="margin:auto;">

  <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">

    <form method="post">
      <div class="text-center mb-3">
          <?php 
            if (isset($error)) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
            ?>
       </div>

      <!-- Email input -->
      <div class="form-outline mb-4">
        <input type="email" id="loginName" class="form-control" name="email" required/>
        <label class="form-label" for="loginName">Email</label>
      </div>

      <!-- Password input -->
      <div class="form-outline mb-4">
        <input type="password" id="loginPassword" class="form-control" name="password" required/>
        <label class="form-label" for="loginPassword">Password</label>
      </div>

      </div>

      <!-- Submit button -->
      <button type="submit" class="btn btn-primary btn-block mb-4" name="submit">Sign in</button>

    </form>
  </div>
  </div>
        </div>
        </div>
<!-- Pills content -->
</body>
</html>