<?php

$no_header = 1;
require_once( '../header.inc' );
?>
        <div class="row align-items-center justify-content-center text-center" id="admin">
            <div class="form-signin" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-3 font-weight-normal">Administrator Sign On</h1>
                </div>

                <div class="form-label-group">
                    <input type="text" id="inputAdmin" class="form-control" placeholder="Username" required autofocus>
                    <label for="inputAdmin">Username</label>
                </div>

                <div class="form-label-group">
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                    <label for="inputPassword">Password</label>
                </div>

                <button class="btn btn-lg btn-primary btn-block" type="submit" id="signin">
                    Sign in
                </button>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            $('body').css('background-color','#333');
            $('div#admin').css('color','#fff');

            $('button#signin').on('click', function(){
                var u = $('input#inputAdmin').val();
                var p = $('input#inputPassword').val();

                $.post('do_admin_login.php',
                    { u: u, p: p },
                    function(data){
                        if( data == "1" ) {
                            location.href = location.href;
                        }
                    }
                )
            })

        })
        </script>
