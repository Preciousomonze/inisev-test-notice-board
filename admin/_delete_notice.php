<?php
  /**
   * Gets notices.
   */
  session_start();
  header("Content-Type: application/json");

  require '../includes/_config.php';
  require '../vendor/autoload.php';

  // Is user logged in? -_-
  if (!is_admin_logged_in()) {
    http_response_code(401);
    $output = '<div class="alert alert-danger" role="alert">Unauthorized access!</div>';
    echo json_encode(array('data'=>$output));
    exit;
  }
    // Get the data.
    $notice_id = trim(process_param_input('notice_id', false));


if (!$notice_id) {
    http_response_code(400);
    $output = '<div class="alert alert-danger" role="alert">Invalid data.</div>';
    echo json_encode(array('data'=>$output));
    exit;
}

  // DB time!
  $db  = new DBCon(CON_TYPE);
  $con = $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $dbq = new Query($con);
  $dbq->set_fetch_mode('assoc');

  // delete the notice.
  if (!$dbq->remove('notices', 'WHERE id=?', [$notice_id])) {
        $output = '<div class="alert alert-danger" role="alert">An error occurred trying to generate notice boards:' . $dbq->err_msg . '</div>';
        http_response_code(500);
    } else {
        // Do nothinggg.
        $output = '';
    }
    echo json_encode(array('data'=>$output));
