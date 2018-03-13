<?php

$floating_labels = 1;
$title = 'Forgotten Password';
include_once( './header.inc' );

?>
<div class="modal fade" id="temp-pw-modal"></div>
<div class="form-signin">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Forgot Your Password?</h1>
        <p>Enter your longform college-issued email address.  This will be something like <code>david.wright@ncc.edu</code>.  You will be sent a temporary password via email.</p>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputEmail">Email address</label>
    </div>

    <button id="pw" class="btn btn-lg btn-primary btn-block" type="submit">Reset My Password</button>
</div>

<script type="text/javascript">
$(function(){
    $('button#pw').on('click',function(){
        var email = $('input#inputEmail').val();
        $.post('create_temp_password.php',
            { faculty_email: email },
            function(data){
                alert('Check your email for your temporary password.');
                location.href = "<?php echo $docroot;?>";
            }
        )
    })
})
</script>

<?php
require_once( './footer.inc' );
?>
