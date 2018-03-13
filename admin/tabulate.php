<?php

/*
 * Rotated Table Column Headers: https://css-tricks.com/rotated-table-column-headers/
 */

require_once( '../header.inc' );
if( isset( $_SESSION[ 'admin' ] ) ) {
    $election_id = $db->real_escape_string( $_REQUEST[ 'election' ] );
    $election_query = 'select title, delegates, alternates, starttime, endtime '
        . "from election_elections "
        . "where id = \"$election_id\"";
    $election_result = $db->query( $election_query );
    if( $election_result->num_rows == 1 ) {
        $now = date( 'Y-m-d H:i:s' );

        $e = $election_result->fetch_object();

        $d = $e->delegates;
        $a = $e->alternates;

        $candidates = array();

        $candidates_query = 'select f.id, f.first, f.last '
            . 'from election_candidates as c, election_faculty as f '
            . 'where c.faculty = f.id '
            . "and c.election = \"$election_id\" "
            . 'order by f.last, f.first';
        $candidates_result = $db->query( $candidates_query );
        $candidates = array();
        $i = 0;
        while( $c = $candidates_result->fetch_object() ) {
            $candidates[ $i ][ 'id' ] = $c->id;
            $candidates[ $i ][ 'first' ] = $c->first;
            $candidates[ $i ][ 'last' ] = $c->last;
            // there will soon be [ $i ][ 1 ] through [ $i ][ n ]
            // to count votes at each rank
            ++$i;
        }

        $ballots_query = 'select ballot '
            . "from election_ballots "
            . "where election = \"$election_id\" "
            . 'order by id';
        $ballots_result = $db->query( $ballots_query );
        $ballots = array();
        for( $i = 0; $ballot = $ballots_result->fetch_object(); ++$i ) {
            $b_count = 1;
            foreach( explode(',', $ballot->ballot ) as $b ) {
                $ballots[ $i ][ $b ] = $b_count;
                ++$b_count;
            }
        }
?>
        <div class="container">
            <div class="row">
                <h1 class="col-sm-12">Tabulate Election Results</h1>
            </div>
            <div class="row">
                <h2 class="col-sm-12">
                    <?php echo $e->title; ?>
                    <span class="small">
                        End<?php echo ( $now < $e->endtime ? 's ' : 'ed ' ) . date( 'l n/j g:i a', strtotime( $e->endtime ) ); ?>
                    </span>
                </h2>
            </div>
            <div class="row">
                <h3 class="col-sm-12">Choosing <?php echo $d; ?> delegate<?php echo $d == 1 ? '' : 's';?> and <?php echo $a; ?> alternate<?php echo $a == 1 ? '' : 's'; ?></h3>
            </div>

            <div class="row">
                <h2 class="col-sm-12">Candidates</h2>
            </div>
            <div class="row">
<?php
        for( $i = 0; $i < sizeof( $candidates ); ++$i ) {
            print "                <div class=\"col-sm-12 col-md-6 col-lg-4 col-xl-3\">"
                . $candidates[ $i ][ 'first' ] . ' ' . $candidates[ $i ][ 'last' ]
                . "</div>\n";
        }
?>
            </div>

            <div class="row">
                <h2 class="col-sm-12 mt-4">Ballots</h2>
            </div>
            <div class="row">
                <table class="table table-sm col-sm-12">
                    <thead>
                        <tr>
                            <th></th>
<?php
        for( $i = 0; $i < sizeof( $candidates ); ++$i ) {
            print "                            <th class=\"rotate\"><div><span>"
                . $candidates[ $i ][ 'first' ] . ' ' . $candidates[ $i ][ 'last' ]
                . "</span></div></th>\n";
        }
?>
                        </tr>
                    </thead>
                    <tbody>
<?php
        for( $i = 0; $i < sizeof( $ballots ); ++$i ) {
            print "                        <tr>\n";
            print "                            <td>Ballot #" . ( $i + 1 ) . "</td>\n";
            for( $j = 0; $j < sizeof( $candidates ); ++$j ) {
                $value = $ballots[ $i ][ $candidates[ $j ][ 'id' ] ];
                print "                            <td>$value</td>\n";
                // add one to that kind of vote for that candidate
                if( $value == '' ) $value = 0;
                $candidates[ $j ][ $value ]++;
            }
            print "                        </tr>\n";
        }

        /******************************************************************
         *
         * Now $candidates has all the votes
         * $candidates[ i ][ j ] has all the j votes that id #i got
         * So, $candidates[ 1 ][ 4 ] has all the 4 votes that Bruce A. got
         *
         *******************************************************************/
?>
                    </tbody>
                </table>
            </div>
<?php

        /******************************************************************
         *
         * Now the tabulations can begin
         *
         *******************************************************************/

        // Subtract number of positions from number of candidates
        // to get number of rounds
        $numPositions = $d + $a;
        $numRounds = sizeof( $candidates ) - $numPositions;

        // If $numRounds <= 0, we still need one tabulation, without removing
        // anyone
        if( $numRounds <= 0 ) $numRounds = 1;
        for( $round = 1; $round <= $numRounds; ++$round ) {
?>
            <div class="row">
                <h2 class="col-sm-12">Tabulation #<?php echo $round; ?></h2>
            </div>
            <div class="row">
                <table class="table col-sm-12">
                    <thead>
                        <th></th>
<?php
            for( $i = 1; $i <= sizeof( $candidates ); ++$i ) {
                print "                        <th>$i</th>\n";
            }
?>
                        <th>Total</th>
                        <th>Weighted</th>
                    </thead>
                    <tbody>
<?php
            for( $i = 0; $i < sizeof( $candidates ); ++$i ) {
                print "                        <tr>\n";
                print "                            <td>"
                    . $candidates[ $i ][ 'first' ][ 0 ] . '. '
                    . $candidates[ $i ][ 'last' ] . "</td>\n";
                $total = 0;
                $weighted_total = 0;
                for( $j = 1; $j <= sizeof( $candidates ); ++$j ) {
                    print "                            <td";
                    if( $j <= $numPositions )
                        print ' class="table-primary"';
                    print ">" . $candidates[ $i ][ $j ] . "</td>\n";
                    if( $j <= $numPositions ) {
                        $total += $candidates[ $i ][ $j ];
                        $weighted_total += ( $numPositions - $j + 1 ) * $candidates[ $i ][ $j ];
                    }
                }
                print "                            <td>$total</td>\n";
                print "                            <td>$weighted_total</td>\n";
                $candidates[ $i ][ 'total' ] = $total;
                $candidates[ $i ][ 'weighted' ] = $weighted_total;
                print "                        </tr>\n";
            } // this tabulation
?>
                    </tbody>
                </table>
            </div>
<?php
            if( $numRounds > 1 ) {
                // let's start eliminating - sort candidates by total
                usort( $candidates, function($a, $b) {
                    if( $a[ 'total' ] == $b[ 'total' ] ) {
                        if( $a[ 'weighted' ] == $b[ 'weighted' ] ) {
                            return $a[ 1 ] <=> $b[ 1 ];
                        }
                        return $a[ 'weighted' ] <=> $b[ 'weighted' ];
                    }
                    return $a[ 'total' ] <=> $b[ 'total' ];
                });

?>
            <div class="row">
                <p class="col-sm-12">
                    <b><?php echo $candidates[ 0 ][ 'first' ] . ' ' . $candidates[ 0 ][ 'last' ]; ?></b> has been eliminated.
                </p>
            </div>
<?php

                //print "<pre>"; print_r( $ballots ); print "</pre>\n";

                // add one rank to (by subtracting one from) ballot votes
                // for candidates behind the eliminated one
                for( $i = 0; $i < sizeof( $ballots ); ++$i ) {
                    $elim_vote = $ballots[ $i ][ $candidates[ 0 ][ 'id' ] ];
                    if( $elim_vote != 0 and $elim_vote != '' ) {
                        foreach( $ballots[ $i ] as $key=>$value ) {
                            if( $value > $elim_vote )
                                $ballots[ $i ][ $key ]--;
                        }
                    }
                    unset( $ballots[ $i ][ $candidates[ 0 ][ 'id' ] ] );
                }

                //print "<pre>"; print_r( $ballots ); print "</pre>\n";

                // and then update candidates
                array_splice( $candidates, 0, 1 );
                usort( $candidates, function($a, $b) {
                    if( $a[ 'last' ] == $b[ 'last' ] )
                        return $a[ 'first' ] <=> $b[ 'first' ];
                    return $a[ 'last' ] <=> $b[ 'last' ];
                });

                // now reset counts within $candidates
                // print "<pre>\n"; print_r( $candidates ); print "</pre>\n";
                for( $i = 0; $i < sizeof( $candidates ); ++$i ) {
                    // print "<pre>\n"; print_r( $candidates[ $i ] ); print "</pre>\n";
                    for( $j = 1; $j < sizeof( $candidates ) + 2; ++$j ) {
                        unset( $candidates[ $i ][ $j ] );
                    }
                    foreach( $ballots as $num => $ballot ) {
                        foreach( $ballot as $fac_id => $vote ) {
                            //print "<pre>"; print_r( $ballot ); print "</pre>\n";
                            if( $fac_id == $candidates[ $i ][ 'id' ] ) {
                                $candidates[ $i ][ $vote ]++;
                            }
                        }
                    }
                }
            }

        } // all the tabulations

        usort( $candidates, function($a, $b) {
            if( $a[ 'total' ] == $b[ 'total' ] )
                return( $b[ 'weighted' ] <=> $a[ 'weighted' ] );
            return $b[ 'total' ] <=> $a[ 'total' ];
        });
?>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h2>Delegates:</h2>
<?php
        for( $i = 0; $i < $d; ++$i ) {
            print "<p>{$candidates[ $i ][ 'first' ]} {$candidates[ $i ][ 'last' ]}</p>\n";
        }
?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <h2>Alternates:</h2>
<?php
        for( $i = $d; $i < sizeof( $candidates ); ++$i ) {
            print "<p>{$candidates[ $i ][ 'first' ]} {$candidates[ $i ][ 'last' ]}</p>\n";
        }
?>
                </div>
            </div>
        </div>
<?php
    } // if the election was found in the database
} // if user is admin

include_once( '../footer.inc' );

?>
<script type="text/javascript">
$(function(){
    $('th.rotate')
        .css('height','140px')
        .css('white-space','nowrap');
    $('th.rotate > div')
        .css('transform','translate(0px,-3px) rotate(315deg)')
        .css('width','30px');
    $('th.rotate > div > span')
        .css('border-bottom','1px solid #ccc')
        .css('padding','5px 5px');
})
</script>
