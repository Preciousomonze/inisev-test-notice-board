<?php
/**
 * Stores all necessary global variables and constants.
 *
 * To be used in most files, cause i do not like stress :).
 * 
 * @author Precious Omonzejele
 */ 

 // Environment , production, test or offline :) dont forget to change.
define('ENV', 'production');

###############################################################################
/** Defining ENVIRONMENT for DB*/
$db_data = array(
	'production' => array( // Defining LIVE ENVIRONMENT for DB.
		'db_host'     => 'localhost',
		'db_name'     => 'cx_notice_board',
		'db_user'     => 'codexplorer',
		'db_password' => 'aRctNdJduXFtRhWH7UuRAT',
		'db_charset'  => 'utf8',
	),
	'test' => array( // Defining TEST ENVIRONMENT for DB.
		'db_host'     => 'localhost',
		'db_name'     => 'test_notice_board',
		'db_user'     => 'root',
		'db_password' => '',
		'db_charset'  => 'utf8',
	),
);
//test80-inisev-interview
// Run the Environment sorting.
sort_env_vars(ENV);

/** Default connection type to the database, incase you wanna change driver ;), uses default of the query class: PDO:mysql */
define('CON_TYPE', 'pdo');

/**
 * Sort Environment Variables.
 *
 * Throws Exception if its not a valid value.
 * Can't be allowing nonsense, street is cold abeg.
 *
 * @param string $env
 */
function sort_env_vars($env) {
	$exception_postfix = ' Constant ENV. Remember it has to be production or test :) dont forget to change to the respective value, to avoid stories that touch! serious oh. :-|';
	if (empty( $env ))
		throw new Exception('Empty value for' . $exception_postfix);

	global $db_data;
	$env = trim(strtolower($env));

	if(!array_key_exists($env, $db_data))
		throw new Exception('Invalid value for' . $exception_postfix);

	foreach($db_data[$env] as $key => $value) {
		$key = strtoupper($key);
		define($key, $value);
	}
}


/**
 * Process the parameters
 *
 * @param string $param the parameter name
 * @param mixed $false_default (optional) default value if parameter name isn't set, default is false
 * @param mixed $true_default (optional) default value to put if parameter name is set and you dont want to use the param value, default is null
 * @param string $method (optional) the method type, any valid http METHOD, GET and POST for now, default is get.
 * @return mixed
 */
function process_param_input($param, $false_default = false, $true_default = null, $method = 'post') {
	$method = trim( strtolower( $method ) );
	$param = isset( $param ) ? trim( $param ) : '';
	$result = false;

	switch( $method ){
		case 'get':
			$result = isset( $_GET[$param] ) ? $_GET[$param] : null;
		break;
		case 'post':
			$result = isset( $_POST[$param] ) ? $_POST[$param] : null;
		break;
	}
	$result = ( $result ? ( !$true_default ? $result : $true_default ) : $false_default );
	return $result;
 }

 /**
 * For encrypting password.
 *
 * Uses MD5 for now.
 *
 * @param string $str the string to be encrypted 
 * @return string empty string if empty, else the encrypted password
 */
function hash_password( $str ){
    return ( empty( $str ) ? '' : md5( $str ) );
}


/**
 * Checks if admin is logged in.
 * 
 * @return bool
 */
function is_admin_logged_in() {
    if(isset($_SESSION['admin']) && !empty($_SESSION['admin']) )
        return true;
    else 
        return false;
}

/**
 * Generates notice boards.
 *
 * @param array $notices
 * @return string
 */
function generate_notice_boards($notices) {
    $notice_data = '';
    foreach ($notices as $data) {
        $notice_data .='
        <div class="col-md-4 single-note-item all-category" style="">
        <div class="card card-body">
        <span class="side-stick"></span>
        <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="' . $data['subject'] . '">' . $data['subject'] . ' <i class="point fa fa-circle ml-1 font-10"></i></h5>
        <p class="note-date font-12 text-muted">' . date('d M, Y', strtotime($data['date_time'])). '</p>
        <div class="note-content">
            <p class="note-inner-content text-muted" data-notecontent="' . $data['message'] . '">' . $data['message'] . '</p>
        </div>
        <div class="d-flex align-items-center">' .
        (is_admin_logged_in() ? '<span class="mr-1"><i class="fa fa-trash remove-note" data-notice-id="' . $data['id'] . '"></i></span>' : '')
           . '<div class="ml-auto">
            </div>
        </div>
        </div>
        </div>';
    }
    return $notice_data;
}

/**
 * Handles DB error properly.
 *
 * @param mixed  $query The query object
 * @param bool   $exit if true, it exits the script.
 * 
 * @param string $msg (optional) To replace default error.
 * @return mixed
 */
function handle_db_error($query, $exit, $load_html = false, $msg = '') {
	// Dose it belong to any?
	if (!(is_object($query) || $query instanceof DBCon || $query instanceof Query ) )
		return;

	$err_msg = '';

	if (property_exists($query, 'con_err_msg')) {
		$err_msg = $query->con_err_msg;
	} elseif (property_exists($query, 'err_msg')) {
		$err_msg = $query->err_msg;
	} else {
		return;
	}

	if (empty($err_msg)) { // No error.
		return;
	}

	// Error.
	if ( !empty(trim($msg))) { // Replace with custom message.
		$err_msg = $msg;
	}

	if ($load_html) {
	?>
	<!DOCTYPE html>
	<html>
	 <head>
        <title>Error - Precious Inisev Notice boards</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body>
		<div class="container"><div class="alert alert-danger" role="alert"><?php echo $err_msg; ?></div></div>
	</body>
	</html>
	<?php
	} else {
		echo '<div class="alert alert-danger" role="alert">' . $err_msg . '</div>';
	}
	if ($exit) 
		exit;
}
