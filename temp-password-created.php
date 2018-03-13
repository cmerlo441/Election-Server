<?php

$no_header = 1;
require_once( './header.inc' );
$email = $db->real_escape_string( $_REQUEST[ 'email' ] );

?>
    <div class="modal-dialog">
        <div class="modal-content">
            <p>If the email address you entered, <code><?php echo $email; ?></code>, is found
                in the system, then you will receive a temporary password at that address.
                Sign in with that address and that temporary password, and you will be
                prompted to enter a new password.</p>
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                Temporary Password Set
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer"><button type="button" class="btn btn-primary">OK</button></div>
        </div>
      </div>
