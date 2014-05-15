<?php
/*
 *
 * Check in file
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:   Multifunction check in file that should work for all items
 *
 */

include 'includes/functions.php';

session_start();

// If logged in:
if(isset($_SESSION['user'])) {

	// ensure that people got to this form from the correct place
	if (!isset($_POST['save'])) {
		// Immediatebly takes user back to home page
		header("Location: ./");
		exit;
	}
	// If it came from mudder bike check out
	else if (isset($_POST['save'])) {
		if (htmlentities($_GET['mode']) == 'mudderbike') {

			// Grab the variables from POST and store locally
			$rentid = $_POST['rentid'];
			$bikeid = $_POST['bikeid'];
			// $sname = htmlentities($_POST['sname']); // not thru
			// $sid = htmlentities($_POST['sid']); // not thru
			// $waiver = htmlentities($_POST['waiver']); // not thru
			$datein = htmlentities($_POST['inputDateIn']);
			$status = htmlentities($_POST['inputStatus']);
			$keyreturnedto = htmlentities($_POST['inputkeyreturnedto']);
			$late = htmlentities($_POST['inputLate']);
			$paid = htmlentities($_POST['inputPaid']);
			$notes = htmlentities($_POST['inputNotes']);

			// echo "rentid: " . $rentid . "<br />";
			// echo "bikeid: " . $bikeid . "<br />";
			// // echo "name is: " . $sname . "<br />";
			// // echo "Student ID: " . $sid . "<br />";
			// // echo "Waiver: " . $waiver . "<br />";
			// echo "Date In: " . $datein . "<br />";
			// echo "Status: " . $status . "<br />";
			// echo "Key Returned To: " . $keyreturnedto . "<br />";
			// echo "Late: " . $late . "<br />";
			// echo "Paid: " . $paid . "<br />";
			// echo "Notes: " . $notes . "<br />";


			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkInMudderBike($rentid, $bikeid, $datein, $status, $keyreturnedto, $late, $paid, $notes);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=inmudderbikes".'">';
		}
		else if (htmlentities($_GET['mode']) == 'equipment') {
			// echo "bikeid: " . $_POST['bikeid'] . "<br />";
			// echo "name is: " . $_POST['inputName'] . "<br />";
			// echo "Student ID: " . $_POST['inputSID'] . "<br />";
			// echo "Waiver: " . $_POST['inputWaiver'] . "<br />";
			// echo "Notes: " . $_POST['inputNotes'] . "<br />";

			// Grab the variables from POST and store locally
			$rentid = $_POST['rentid'];
			$equipmentid = $_POST['equipmentid'];
			// $sname = htmlentities($_POST['inputName']);
			// $sid = htmlentities($_POST['inputSID']);
			// $school = htmlentities($_POST['inputSchool']);
			$notes = htmlentities($_POST['inputNotes']);
			// $dateout = htmlentities($_POST['inputDateOut']);
			// $timeout = htmlentities($_POST['inputTimeOut']);
			$datein = htmlentities($_POST['inputDateIn']);
			$timein = htmlentities($_POST['inputTimeIn']);
			$timein = date('Y-m-d h:i:s', strtotime($timein));
			// echo "notes is: " . $notes;
			// echo "date in is: ". $datein;
			// echo "time in is: ". $timein;
			// echo "equipment id: ". $equipmentid;
			// echo "rentid: ".$rentid;

			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkInEquipment($rentid, $equipmentid, $datein, $timein, $notes);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=inequipment".'">';
		}
		else if (htmlentities($_GET['mode']) == 'room') {
			// Grab the variables from POST and store locally
			$rentid = $_POST['rentid'];
			$notes = htmlentities($_POST['inputNotes']);

			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkInRoom($rentid, $notes);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=inrooms".'">';
		}
	}

// Otherwise if not logged in, redirect to Login page!

} //end session check
else {
	header("Location: login.php");
}
?>
