<?php

require_once( './header.inc' );

$fac_id = $db->real_escape_string( $_REQUEST[ 'fac_id' ] );
$code = $db->real_escape_string( $_REQUEST[ 'code' ] );

$code_query = 'select id, faculty, temp '
    . 'from election_forgot_pw '
    . "where faculty = \"$fac_id\" "
    . "and temp = \"$code\"";
$code_result = $db->query( $code_query );
if( $code_result->num_rows == 1 ) {

?>

        <div class="container">
            <div class="row">
                <h1 class="col-sm-12">Choose a New Password</h1>
            </div>
            <div class="row">
                <p class="col-sm-12">Choose a new password for this site, and
                    type it into both boxes on this form.  The site won't
                    force you to make it a certain length, or use three
                    pieces of punctuation, because we have a new cybersecurity
                    program here, and so we know that you know the value of
                    cryptographically strong passwords.  Please don't
                    embarrass us.
                </p>
            </row>

            <div class="form-row align-items-center">
                <div class="col-auto">
                    <input type="password" class="form-control" id="p1" aria-describedby="New Password" placeholder="New Password">
                </div>
                <div class="col-auto">
                    <input type="password" class="form-control" id="p2" aria-describedby="New Password Again" placeholder="New Password Again">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary disabled" id="go">Set Password</button>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-auto ml-2" id="strength"></div>
                <div class="col-auto" id="zxcvbn"></div>
            </div>
        </div>

        <script type="text/javascript">
        $(function(){
            function same(){
                var p1 = $('input#p1').val();
                var p2 = $('input#p2').val();
                return p1.length > 0 && p1 === p2;
            }

            $('input').on('input',function(){
                var s = zxcvbn( $('input#p1').val() );
                $('div#strength').html('Password strength: ' + s.score + ' out of 4' );

                $('div#zxcvbn').html('(from <a href="https://github.com/dropbox/zxcvbn">zxcvbn</a>)');

                if( same() ) {
                    $('button#go').removeClass('disabled');
                } else {
                    $('button#go').addClass('disabled');
                }
            })
            $('button#go').on('click',function(){
                var fac_id = "<?php echo $fac_id; ?>";
                var code = "<?php echo $code; ?>";
                var pw = $('input#p1').val();
                $.post('do_reset_password.php',
                    { fac_id: fac_id, code: code, pw: pw },
                    function(data){
                        if(data==1){
                            alert('Your password has been changed.  Log in ' +
                                'using your email address and new password now.');
                            location.href = "<?php echo $docroot; ?>";
                        }
                    }
                )
            })
        })
        </script>
<?php
} else {
    print 'This code is invalid.';
}
require_once( './footer.inc' );
?>
