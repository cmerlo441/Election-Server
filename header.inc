<?php
include_once( 'Faculty.php' );
require_once( ".htpasswd" );
require_once( 'version.php' );

$ten_minutes_ago = date( 'Y-m-d H:i:s', mktime( date('H'), date('i') - 10 ) );
$clear_forgot_query = 'delete from  election_forgot_pw '
    . "where timestamp < \"$ten_minutes_ago\"";
$db->query( $clear_forgot_query );

$docroot = "/~voter";

session_start();
if( isset( $_REQUEST[ 'signout' ] ) ) {
    if( isset( $_SESSION[ 'user' ] ) )
        unset( $_SESSION[ 'user' ] );
    else if( isset( $_SESSION[ 'admin' ] ) )
        unset( $_SESSION[ 'admin' ] );
}

if( !isset( $no_header ) ) {
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!--<link rel="icon" href="../../../../favicon.ico">-->
<?php

    $title_string = "MAT/CSC/ITE Elections";
    if( isset( $title ) )
        $title_string .= " :: $title";

?>

        <title><?php echo $title_string; ?></title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="<?php echo $docroot; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $docroot; ?>/css/sticky-footer.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<?php
    if( isset( $pricing ) )
        print "        <link href=\"$docroot/css/pricing.css\" rel=\"stylesheet\">\n";
    if( isset( $floating_labels ) )
        print "        <link href=\"$docroot/css/floating-labels.css\" rel=\"stylesheet\">\n";
    if( isset( $signin ) )
        print "        <link href=\"$docroot/css/signin.css\" rel=\"stylesheet\">\n";
    if( isset( $datepick ) ) {
        print "          <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css\" />\n";
    }
?>

        <!-- Javascript -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
          integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="<?php echo $docroot; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo $docroot; ?>/js/zxcvbn.js"></script>
<?php
    if( isset( $datepick ) ) {
        print "        <script type=\"text/javascript\" src=\"$docroot/js/moment.js\"></script>\n";
        print "        <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js\"></script>\n";
    }
    if( isset( $sortable ) ) {
        print "            <script src=\"http://rubaxa.github.io/Sortable/Sortable.js\"></script>\n";
    }
?>

    </head>

    <body>
        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
            <h5 class="my-0 mr-md-auto font-weight-normal">NCC MAT/CSC/ITE Elections</h5>
<?php
    if( isset( $_SESSION[ 'user' ] ) ) {
?>
            <nav class="my-2 my-md-0 mr-md-3">
                <a class="p-2 text-dark" href="<?php echo $docroot; ?>/about.php">About This Site</a>
            </nav>
            <a class="m-1 btn btn-outline-danger signout" href="?signout=1">Sign Out</a>
            <a class="m-1 btn btn-outline-primary" href="<?php echo $docroot; ?>/profile.php"><?php echo $_SESSION[ 'name' ]; ?></a>
<?php
    } else if( isset( $_SESSION[ 'admin' ] ) ) {
?>
            <nav class="my-2 my-md-0 mr-md-3">
                <a class="p-2 text-dark" href="<?php echo $docroot; ?>/admin/logins.php">Login History</a>
                <a class="p-2 text-dark" href="<?php echo $docroot; ?>/admin/create.php">Create an Election</a>
            </nav>
            <a class="m-1 btn btn-outline-danger" href="<?php echo $docroot; ?>/admin/">Admin Home</a>
            <a class="m-1 btn btn-danger signout" href="#">Sign Out</a>
<?php
    } else {
?>
            <nav class="my-2 my-md-0 mr-md-3">
                <a class="p-2 text-dark" href="<?php echo $docroot; ?>/about.php">About This Site</a>
                <a class="p-2 text-dark" href="<?php echo $docroot; ?>/forgot_password.php">Forgot Your Password?</a>
            </nav>
<?php
    }
?>
        </div>
        <script type="text/javascript">
        $(function(){
            $('a.signout').on('click',function(){
                location.href = location.href + "?signout=1";
            })
        })
        </script>

<?php
} // no header
?>
