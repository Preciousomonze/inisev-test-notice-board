<?php
  session_start();

  require_once '../includes/_config.php';
  require_once '../vendor/autoload.php';

  // Is user logged in? -_-
  if (!is_admin_logged_in()) {
    header('location:login.php');
    exit();
  }

  // DB time!
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

  require_once '_parts/header.php';
?>

<div class="page-content container note-has-grid">
    <ul class="nav nav-pills p-3 bg-white mb-3 rounded-pill align-items-center">
        <li class="nav-item">
            <a href="#" class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 active">
                <i class="icon-layers mr-1"></i><span class="d-none d-md-block">All Notes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 active">
                <i class="icon-layers mr-1"></i><span class="d-none d-md-block">Logout</span>
            </a>
        </li>
        <li class="nav-item ml-auto">
            <a href="javascript:void(0)" class="nav-link btn-primary rounded-pill d-flex align-items-center px-3" id="add-notes"> <i class="icon-note m-1"></i><span class="d-none d-md-block font-14">Add Notes</span></a>
        </li>
    </ul>
    <div class="tab-content bg-transparent">
        <div id="note-full-container" class="note-has-grid row">
        <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
            else if (isset($notices)) {
                echo $notices;
            }
        ?>
            <div class="col-md-4 single-note-item all-category note-important" style="">
                <div class="card card-body">
                    <span class="side-stick"></span>
                    <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Give salary to employee">Give salary to employee <i class="point fa fa-circle ml-1 font-10"></i></h5>
                    <p class="note-date font-12 text-muted">15 Fabruary 2020</p>
                    <div class="note-content">
                        <p class="note-inner-content text-muted" data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.">Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="mr-1"><i class="fa fa-trash remove-note"></i></span>
                        <div class="ml-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add notes -->
    <div class="modal fade" id="addnotesmodal" tabindex="-1" role="dialog" aria-labelledby="addnotesmodalTitle" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Add Notes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="notes-box">
                        <div class="notes-content">
                            <form action="javascript:void(0);" id="addnotesmodalTitle">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert-place"></div>
                                        <div class="note-title">
                                            <label>Note Title/Subject</label>
                                            <input required type="text" id="note-has-title" class="form-control" minlength="25" placeholder="Title" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="note-description">
                                            <label>Note Description/Message</label>
                                            <textarea required id="note-has-description" class="form-control" minlength="60" placeholder="Description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-n-save" class="float-left btn btn-success" style="display: none;">Save</button>
                    <button class="btn btn-danger" data-dismiss="modal">Discard</button>
                    <button id="btn-n-add" class="btn btn-info" disabled="disabled">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once '_parts/footer.php'; ?>
