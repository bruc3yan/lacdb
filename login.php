<?php
/*
 *
 * Login page File
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes: Check if you're logged in
 *
 */

session_start();

// If logged in:
if(isset($_SESSION['user']))
{
    header("Location: ./");
} else {
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile viewport optimized -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Website Title & Description for Search Engine purposes -->
    <title>LAC Database</title>
    <meta name="description" content="Web Database for assisting LAC Staff to organize checking in/out of equipments, rooms, and other materials in the Linde Activities Center (LAC) at Mudd.">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="includes/css/styles.css" rel="stylesheet">

    <!-- Include Modernizr in the head, before any other Javascript -->
    <script src="includes/js/modernizr-2.6.2.min.js"></script>

  </head>

  <body>

  <div class="container" id="signin">

    <form class="form-signin" role="form" action="./" method="post">
        <h2 class="form-signin-heading">Please Sign In.</h2>
        <input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <label class="checkbox">
            <input type="checkbox" value="remember-me"> Remember me
        </label>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>





  </div> <!-- end main container -->

  <?php
    // Footer is included outside of the main container div
    // because we want it to stretch across the entire bottom
    // include('includes/footer.php');
  ?>

  <!-- Google Analytics code -->
  <?php include_once("analyticstracking.php") ?>

  <!-- First try for the online version of jQuery-->
  <script src="http://code.jquery.com/jquery.js"></script>

  <!-- If no online access, fallback to our hardcoded version of jQuery -->
  <script>window.jQuery || document.write('<script src="includes/js/jquery.min.js"><\/script>')</script>

  <!-- Bootstrap JS -->
  <script src="includes/js/bootstrap.min.js"></script>

  <!-- Custom JS -->
  <script src="includes/js/script.js"></script>

  </body>
</html>
<?php
} //end else
?>
