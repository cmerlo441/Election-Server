<?php

$pricing = 1;
$floating_labels = 1;
require_once( './header.inc' );

?>

        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">MAT/CSC/ITE Department Elections</h1>
            <p class="lead">Welcome to the Departmental Election Server for the Mathematics, Computer Science, and Information Technology Deprtment at Nassau Community College.</p>
        </div>

        <div class="container">
<?php
        if( ! isset( $_SESSION[ 'user' ] ) ) {
?>
            <div class="card-deck mb-3 text-center" id="not_logged_in">
                <div class="card mb-4 box-shadow">
                  <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Sign In</h4>
                  </div>
                  <div class="card-body" id="login_form"></div>
                </div>
            </div>

            <script type="text/javascript">
            $(function(){
                $.get('login_form.php',
                    function(data){
                        $('div#login_form').html(data);
                        $('button#signin').on('click',function(){
                            var u = $('input#inputEmail').val();
                            var p = $('input#inputPassword').val();
                            $.post('do_login.php',
                                { u: u, p: p },
                                function(data){
                                    if(data.indexOf(':') !== -1){
                                        var parts = data.split(':');
                                        var fac_id = parts[ 0 ];
                                        var code = parts[ 1 ];

                                        var form = $('<form></form>');
                                        form.attr('method','post');
                                        form.attr('action','reset_password.php');

                                        var field1 = $('<input></input>');
                                        field1.attr('type','hidden');
                                        field1.attr('name','fac_id');
                                        field1.attr('value',fac_id);

                                        var field2 = $('<input></input>');
                                        field2.attr('type','hidden');
                                        field2.attr('name','code');
                                        field2.attr('value',code);

                                        form.append(field1);
                                        form.append(field2);

                                        $(document.body).append(form);
                                        form.submit();
                                    } else {
                                        location.reload(true);
                                    }
                                }
                            )
                        })
                    }
                )
            })
            </script>
        </div>
<?php
} else {
?>
        <div class="card-deck mb-3 text-center" id="logged_in"></div>
            <script type="text/javascript">
            $(function(){
                $.get('election_cards.php',
                    function(data){
                        $('div#logged_in').html(data);
                    }
                )
            })
            </script>
        </div>
<?php
}
?>


<?php

require_once( './footer.inc' );

?>
