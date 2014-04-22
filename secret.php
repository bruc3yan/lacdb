<?php
/*
 *
 * Secret page for ensuring users do not access pages without loggin in
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular check in page file
 *
 */
	include 'includes/functions.php';

    if(empty($_SESSION['user']))
    {
        header("Location: ./");
        die("Redirecting to ./");
    }
?>
