<?php
/*
 *
 * Logout page File
 * 
 * 
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular logout page file for things to display on home page
 * 
 */

	// session_start();

	// session_destroy();

    unset($_SESSION['user']);

 ?>

<div class="page-header">
	<h2>Logout <small>You are logged out!</small></h2>
	<p class="lead">
		Now redirecting you back to the login page!
	</p>

	<META HTTP-EQUIV="REFRESH" CONTENT="7" URL="http://lacdb.claremontbooks.com/">

</div>
