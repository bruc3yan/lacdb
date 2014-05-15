<?php
/*
 *
 * Check out file
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:   Multifunction check out file that should work for all items
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
			// echo "bikeid: " . $_POST['bikeid'] . "<br />";
			// echo "name is: " . $_POST['inputName'] . "<br />";
			// echo "Student ID: " . $_POST['inputSID'] . "<br />";
			// echo "Waiver: " . $_POST['inputWaiver'] . "<br />";
			// echo "Notes: " . $_POST['inputNotes'] . "<br />";

			// Grab the variables from POST and store locally
			$bikeid = $_POST['bikeid'];
			$sname = htmlentities($_POST['inputName']);
			$sid = htmlentities($_POST['inputSID']);
			$waiver = htmlentities($_POST['inputWaiver']);
			//$notes = htmlentities($_POST['inputNotes']);
			$notes = "";


			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkOutMudderBike($bikeid, $sname, $sid, $waiver, $notes);

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
			$equipmentid = $_POST['equipmentid'];
			$sname = htmlentities($_POST['inputName']);
			$sid = htmlentities($_POST['inputSID']);
			$school = htmlentities($_POST['inputSchool']);
			//$notes = htmlentities($_POST['inputNotes']);
			$dateout = htmlentities($_POST['inputDateOut']);
			$timeout = htmlentities($_POST['inputTimeOut']);
			$timeout = date('Y-m-d h:i:s', strtotime($timeout));
			//$datein = htmlentities($_POST['inputDateIn']);
			//$timein = htmlentities($_POST['inputTimeIn']);


			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkOutEquipment($equipmentid, $sname, $sid, $school, $dateout, $timeout);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=inequipment".'">';
		}
		else if (htmlentities($_GET['mode']) == 'lost') {

			// Grab the variables from POST and store locally
			$itemid = $_POST['itemid'];
			$itemname = htmlentities($_POST['itemname']);
			$inputNotes = htmlentities($_POST['inputNotes']);
			$datelost = htmlentities($_POST['datelost']);
			// $dateclaim = date('Y-m-d', strtotime($dateclaim));
			$dateclaim = date('Y-m-d');
			$inputReturnTo = htmlentities($_POST['inputReturnTo']);


			// echo "itemid: " . $_POST['itemid'] . "<br />";
			// echo "itemname: " . $_POST['itemname'] . "<br />";
			// echo "inputNotes: " . $_POST['inputNotes'] . "<br />";
			// echo "datelost: " . $_POST['datelost'] . "<br />";
			// echo "inputReturnTo: " . $_POST['inputReturnTo'] . "<br />";
			// echo "dateclaim: " . $dateclaim . "<br />";

			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->claimLostAndFoundData(0, $itemid, $itemname, $datelost, $dateclaim, $inputNotes, $inputReturnTo);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=lostandfound".'">';
		}
		else if (htmlentities($_GET['mode']) == 'found') {

			// Grab the variables from POST and store locally
			$itemid = $_POST['itemid'];
			$itemname = htmlentities($_POST['itemname']);
			$inputNotes = htmlentities($_POST['inputNotes']);
			$datefound = htmlentities($_POST['datefound']);
			// $dateclaim = date('Y-m-d', strtotime($dateclaim));
			$dateclaim = date('Y-m-d');
			$inputReturnTo = htmlentities($_POST['inputReturnTo']);


			// echo "itemid: " . $_POST['itemid'] . "<br />";
			// echo "itemname: " . $_POST['itemname'] . "<br />";
			// echo "inputNotes: " . $_POST['inputNotes'] . "<br />";
			// echo "datefound: " . $_POST['datefound'] . "<br />";
			// echo "inputReturnTo: " . $_POST['inputReturnTo'] . "<br />";
			// echo "dateclaim: " . $dateclaim . "<br />";

			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->claimLostAndFoundData(1, $itemid, $itemname, $datefound, $dateclaim, $inputNotes, $inputReturnTo);

			// Has been checked out! Now redirecting
			echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=lostandfound".'">';
		}
		else if (htmlentities($_GET['mode']) == 'room') {

			// Grab the variables from POST and store locally
			$roomid = $_POST['roomid'];
			$inputSID = htmlentities($_POST['inputSID']);
			$inputDateOut = htmlentities($_POST['inputDateOut']);
			$inputTimeOut = htmlentities($_POST['inputTimeOut']);
			$inputTimeOut = $inputDateOut . " " . $inputTimeOut;
			$inputDateIn = htmlentities($_POST['inputDateIn']);
			$inputTimeIn = htmlentities($_POST['inputTimeIn']);
			$inputTimeIn = $inputDateIn . " " . $inputTimeIn;
			$inputNotes = htmlentities($_POST['inputNotes']);

			// echo "room id: " . $roomid . "<br />";
			// echo "inputSID: " . $inputSID . "<br />";
			// echo "inputDateOut: " . $inputDateOut . "<br />";
			// echo "inputTimeOut: " . $inputTimeOut . "<br />";
			// echo "inputDateIn: " . $inputDateIn . "<br />";
			// echo "inputTimeIn: " . $inputTimeIn . "<br />";
			// echo "inputNotes: " . $inputNotes . "<br />";


			// create a new instance of the Records class
			$record = new Records;

			// Perform update (this occurs on 2 tables)
			$record->checkOutRoom($roomid, $inputSID, $inputDateOut, $inputTimeOut, $inputDateIn, $inputTimeIn, $inputNotes);

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
