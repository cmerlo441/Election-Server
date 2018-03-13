<?php

$pricing = 1;
$floating_labels = 1;
require_once( '../header.inc' );

print "        <div id=\"admin_index\"></div>\n";
if( isset( $_SESSION[ 'admin' ] ) ) {
?>
        <script type="text/javascript">
        $(function(){
            $.get('main.php',
                function(data){
                    $('div#admin_index').html(data);
                }
            )
        })
        </script>
<?php
} else {
?>
        <script type="text/javascript">
        $(function(){
            $.get('admin_login_form.php',
                function(data){
                    $('div#admin_index').html(data);
                }
            )
        })
        </script>
<?php
}

require_once( '../footer.inc' );

?>
