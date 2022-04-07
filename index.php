<?php
/**
  * Notice board page.
  */

  require 'includes/_config.php';
  require 'vendor/autoload.php';

  $db  = new DBCon(CON_TYPE);
  $con = $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $dbq = new Query($con);
  $dbq->set_fetch_mode('assoc');

  // Get list of notices.

  if (!$dbq->get('SELECT id,subject,message,date_time from notices')) {
      $error = 'An error occurred trying to generate notice boards:' . $dbq->err_msg;
  }

  if ($dbq->row_count < 1) {
    $notices = '<div class="alert alert-info" role="alert">No notice boards at the moment.</div>';
  } else {
    $notices = generate_notice_boards($dbq->record);
  }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pekky Inisev Notice boards</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/notes.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
            var userPage = true;
        </script>
        <script src="assets/js/index.js"></script>

    </head>
    <body>
    <div class="page-content container note-has-grid">
    <ul class="nav nav-pills p-3 bg-white mb-3 rounded-pill align-items-center">
        <li class="nav-item">
            <a href="#" class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2">
                <i class="icon-layers mr-1"></i><span class="d-none d-md-block">All Notes</span>
            </a>
        </li>
    </ul>

    <div class="tab-content bg-transparent">
        <div id="note-full-container" class="note-has-grid row">
        <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
            elseif (isset($notices)) {
                echo $notices;
            }
        ?>
        </div>
    </div>

</div>
</body>
</html>