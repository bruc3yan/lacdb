<?php
/*
 *
 * Delete item file
 *
 *
 * Author:  Bruce Yan
 * Updated: May 2014
 * Notes:   Multifunction delete file that should work for all items
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
        //    $record->checkOutMudderBike($bikeid, $sname, $sid, $waiver, $notes);

            // Has been checked out! Now redirecting
        //    echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=inmudderbikes".'">';
        }
        else if (htmlentities($_GET['mode']) == 'equipment') {
            // echo "Equipment ID: " . $_POST['equipmentid'] . "<br />";

            // Grab the variables from POST and store locally
            $equipmentid = $_POST['equipmentid'];

            // create a new instance of the Records class
            $record = new Records;

            // Perform delete
            $record->deleteEquipmentData($equipmentid);

            // Has been deleted! Now redirecting
            echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=outequipment".'">';
        }
    }

// Otherwise if not logged in, redirect to Login page!

} //end session check
else {
    header("Location: login.php");
}
?>
