<?php
  /**
   * Gets notices.
   */
  session_start();
  header("Content-Type: application/json");

  require '../includes/_config.php';
  require '../vendor/autoload.php';

  // DB time!
  $db  = new DBCon(CON_TYPE);
  $con = $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $dbq = new Query($con);
  $dbq->set_fetch_mode('assoc');

  // Get list of notices.

  if (!$dbq->get('SELECT id,subject,message,date_time FROM notices ORDER BY date_time DESC')) {
      $output = '<div class="alert alert-danger" role="alert">An error occurred trying to generate notice boards:' . $dbq->err_msg . '</div>';
      http_response_code(500);
      echo json_encode(array('data'=>$output));
      exit;
  }

if ($dbq->row_count < 1) {
  $output = '<div class="alert alert-info" role="alert">No notice boards at the moment.</div>';
} else {
  $output = generate_notice_boards($dbq->record);
}

echo json_encode(array('data'=>$output));
