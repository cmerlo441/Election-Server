<?php

$no_header = 1;
require_once( '../header.inc' );

?>
        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">Election Administration</h1>
            <p class="lead">blah</p>
        </div>

        <div class="container">
            <div class="card-deck mb-3 text-center" id="cards">
                <script type="text/javascript">
                $(function(){
                    $('body').css('background-color','#ccc');
                    $.get('election_cards.php',
                        function(data){
                            $('div#cards').html(data);
                        }
                    )
                })
                </script>
            </div>
        </div>
