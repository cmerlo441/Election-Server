<?php

$sortable = 1;
require_once( './header.inc' );

if( isset( $_SESSION[ 'user' ] ) ) {
    $election_id = $db->real_escape_string( $_REQUEST[ 'election_id' ] );
    $now = date( 'Y-m-d H:i:s' );
?>
        <div class="container">
            <div class="row">
<?php
    $election_query = 'select title, delegates, alternates, starttime, endtime '
        . 'from election_elections '
        . "where id = $election_id";
    $election_result = $db->query( $election_query );
    if( $election_result->num_rows == 0 ) {
        print "                <h1 class=\"col-sm-12\">Election Not Found</h1>\n";
        print "                <p class=\"col-sm-12\">You seem to have clicked "
            . "a link for an election that isn't running right now.  We "
            . "apologize for the inconvenience.</p>\n";
    } else {
        $election = $election_result->fetch_object();
        print "                <h1 class=\"col-sm-12\">$election->title</h1>\n";
        $voted_query = 'select timestamp '
            . 'from election_voted '
            . "where election = \"$election_id\" "
            . "and faculty = \"{$_SESSION[ 'user' ]}\"";
        $voted_result = $db->query( $voted_query );
        if( $voted_result->num_rows == 1 ) {
            $voted = $voted_result->fetch_object();
            print "                <p class=\"col-sm-12\">You voted in this election on "
                . date( 'l n/j \a\t g:i a', strtotime( $voted->timestamp ) )
                . "</p>.\n";
        } else if( $now < $election->starttime ) {
            print "                <p class=\"col-sm-12\">This election will open for voting on "
                . date( 'l n/j \a\t g:i a', strtotime( $election->starttime ) )
                . ".</p>\n";
        } else if( $now > $election->endtime ) {
            print "                <p class=\"col-sm-12\">This election ended on "
                . date( 'l, n/j \a\t g:i a', strtotime( $election->endtime ) )
                . ".</p>\n";
        } else {
            print "                <p class=\"col-sm-12\">We're choosing "
                . "$election->delegates winner"
                . ( $election->delegates == 1 ? '' : 's' );
            if( $election->alternates > 0 )
                print " and $election->alternates alternate"
                    . ( $election->alternates == 1 ? '' : 's' );
                print " in this election.  The candidates are listed below in "
                    . "alphabetical order.  Drag the candidates into the order "
                    . "of your preference.</p>\n";
                print "                <p class=\"col-sm-12\"><b>Remember:</b> "
                    . "every ranked vote potentially helps.  If you <em>really</em> "
                    . "don't want someone to get your vote, drag him/her to the "
                    . "right.</p>\n";
            $candidates_query = 'select f.id, f.first, f.last '
                . 'from election_candidates as c, election_faculty as f '
                . 'where c.faculty = f.id '
                . "and c.election = \"$election_id\" "
                . 'order by f.last, f.first';
            $candidates_result = $db->query( $candidates_query );
            print "            </div>\n";
            print "            <div class=\"row\">\n";
            print "                    <p class=\"col-sm-12 col-md-5\">Drag "
                . "these candidates to reorder them:</p>\n";
            print "                    <div class=\"col-sm-12 col-md-2\"></div>\n";
            print "                    <p>Drag candidates whom you don't want "
                    . "to rank here:</p>\n";
            print "            </div>\n";
            print "            <div class=\"row\">\n";
            print "                <div id=\"candidates\" "
                . "class=\"list-group col-sm-12 col-md-5 p-3 mr-auto\">\n";
            while( $candidate = $candidates_result->fetch_object() ) {
                print "                    <div class=\"list-group-item "
                    . "list-group-item-success candidate\" "
                    . "id=\"$candidate->id\">"
                    . "<span class=\"order\"></span>"
                    . "$candidate->last, $candidate->first</div>\n";
            }
            print "                </div>\n";

            print "                <div id=\"zeroes\" "
                . "class=\"list-group col-sm-12 col-md-5 p-3 ml-auto\">\n";
            /*
            print "                        <div class=\"list-group-item "
                . "list-group-item-danger zero\">Drag your zeroes here</div>\n";
            */
            print "                </div>\n";
        }
    }
?>
            </div>
            <div class="row">
                <p class="mt-4 col-sm-12">
                    <button class="btn btn-primary btn-block btn-lg" id="vote">Vote!</button>
                </p>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            function numberTheCandidates(){
                var i = 0;
                $('div#candidates > div').each(function(){
                    $(this).removeClass('list-group-item-danger')
                        .addClass('list-group-item-success');
                    $(this).find('span').html(++i + ': ');
                })
            }
            function markZeroes(){
                $('div#zeroes > div').each(function(){
                    $(this).removeClass('list-group-item-success')
                        .addClass('list-group-item-danger');
                    $(this).find('span').html('');
                })
            }
            function votes(){
                var votes = [];
                $('div#candidates > div').each(function(){
                    votes.push( $(this).attr('id') );
                })
                return votes;
            }

            $('div.list-group').css('border','5px dashed #333');
            Sortable.create(candidates,{
                group: {
                    name: 'candidates',
                    put: ['candidates', 'zeroes']
                },
                onUpdate: function(){
                    numberTheCandidates();
                },
                onAdd: function(e){
                    numberTheCandidates();
                }
            });
            Sortable.create(zeroes,{
                ghostClass: 'ghost',
                group: {
                    name: 'zeroes',
                    put: ['candidates', 'zeroes']
                },
                onUpdate: function(){
                    numberTheCandidates();
                    markZeroes();
                },
                onAdd: function(e){
                    numberTheCandidates();
                    markZeroes();
                }
            })
            numberTheCandidates();

            $('button#vote').on('click',function(){
                $.post('cast_vote.php',
                    {
                        election_id: <?php echo $election_id; ?>,
                        ballot: votes().join(',')
                    },
                    function(data){
                        alert(data);
                        window.location.href = "<?php echo $docroot; ?>";
                    }
                )
            })
        })
        </script>
<?php
}
include_once( './footer.inc' );
?>
