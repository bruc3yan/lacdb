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

	session_start();

	session_destroy();

//	header("Location: index.php");

 ?>
<div class="page-header">
	<h2>Logout <small>You are logged out!</small></h2>
	<p class="lead">
		Please go back to the main page to login again!
	</p>
</div>
