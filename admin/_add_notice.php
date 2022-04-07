<?php
 /**
  * adds a notice.
  */
  session_start();
  header("Content-Type: application/json");

  require '../includes/_config.php';
  require '../vendor/autoload.php';

  // Is admin logged in? -_-
  if (!is_admin_logged_in()) {
        http_response_code(401);
        $output = '<div class="alert alert-danger" role="alert">Unauthorized access!</div>';
        echo json_encode(array('data'=>$output));
        exit;
    }

    // Get the data.
    $subject  = trim(process_param_input('subject', false ));
    $message  = trim(process_param_input('message', false ));

    if (!($subject && $message)) {
        http_response_code(400);
        $output = '<div class="alert alert-danger" role="alert">Empty Fields.</div>';
        echo json_encode(array('data'=>$output));
        exit;    
    }

  // DB time!
  $db  = new DBCon(CON_TYPE);
  $con = $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $dbq = new Query($con);
  $dbq->set_fetch_mode('assoc');

// Add to the db.
$data    = array(
    'admin_id'  => '?',
    'subject'   => '?',
    'message'   => '?',
    'date_time' =>'NOW()'
);
$binding = array($_SESSION['admin'], $subject, $message);

if (!$dbq->add('notices', $data, $binding)) { // Error.
    $output = '<div class="alert alert-danger" role="alert">An error occurred trying to add to notice boards:' . $dbq->err_msg . '</div>';
    http_response_code(500);
}else {
  $output = generate_notice_boards($dbq->record);
}

echo json_encode(array('data'=>$output));
