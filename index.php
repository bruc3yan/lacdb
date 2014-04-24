<?php
/*
 *
 * Index page File
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Main page for loading all other pages
 *
 */

include 'includes/functions.php';

session_start();

// New authentication
$auth = new Users;

// Check to see if we came from login page OR if we're logged in (see below this section)
if (!empty($_POST))	{
	// if we got here through a form, then we grab the values and check with our database
	$email = htmlentities($_POST['email']);
	$password = htmlentities($_POST['password']);

	// store array into users for checking later
	$user = $auth->checkLoginInfo($email, $password);
	// Check the return type of users
	if($user != NULL) {
	    $_SESSION['user'] = $user['uid'];

	    header("Location: ./");
	} else {
	    header("Location: login.php");
	}
}

// If logged in:
if(isset($_SESSION['user']))

{

// Checks to see if the global page is set
$page = (isset($_GET['page']) ? $_GET['page'] : 'main');

// Mudder bike check out stuff
// if(isset($_POST['save'])) {
// 					echo "bikeid" . $_POST['bikeid'] . "<br />";
// 					echo "name is: " . $_POST['inputName'] . "<br />";
// 					echo "Student ID: " . $_POST['inputSID'] . "<br />";
// 					echo "Waiver: " . $_POST['inputWaiver'] . "<br />";
// 					echo "Notes: " . $_POST['inputNotes'] . "<br />";
// 				}

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

	<div class="container" id="main">

		<?php
			//Header
			include('includes/header.php');
		?>

		<?php
			// main body content
			$body = new Navigation($page);

			include $body->fetchPage();
		?>


	</div> <!-- end main container -->

	<?php
		// Footer is included outside of the main container div
		// because we want it to stretch across the entire bottom
		include('includes/footer.php');
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
// Otherwise if not logged in, redirect to Login page!

} //end if
else {
	header("Location: login.php");
}
?>
