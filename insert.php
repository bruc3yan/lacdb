<?php
/*
 *
 * Insert file
 *
 *
 * Author:  Bruce Yan
 * Updated: May 2014
 * Notes:   Multifunction insert file that should work for all items
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
            //$equipmentid = $_POST['equipmentid'];
            $inputEquipmentName = $_POST['inputEquipmentName'];
            $inputQtyleft = $_POST['inputQtyleft'];
            $inputEquipmentNotes = $_POST['inputEquipmentNotes'];
            $inputEquipmentOwner = $_POST['inputEquipmentOwner'];

            // create a new instance of the Records class
            $record = new Records;

            // Perform update (this occurs on 2 tables)
            $record->addEquipmentData($inputEquipmentName, $inputQtyleft, $inputEquipmentNotes, $inputEquipmentOwner);
            // Has been added! Now redirecting
            echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=outequipment".'">';
        }
        else if (htmlentities($_GET['mode']) == 'lost') {
            // Grab the variables from POST and store locally
            $inputItemName = $_POST['inputItemName'];
            $inputDateLost = $_POST['inputDateLost'];
            $inputItemDescription = $_POST['inputItemDescription'];

            // create a new instance of the Records class
            $record = new Records;

            // Perform update (this occurs on 2 tables)
            $record->addLostAndFoundData(0, $inputItemName, $inputDateLost, $inputItemDescription);
            // Has been added! Now redirecting
            echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=lostandfound".'">';
        }
        else if (htmlentities($_GET['mode']) == 'found') {
            // Grab the variables from POST and store locally
            $inputItemName = $_POST['inputItemName'];
            $inputDateFound = $_POST['inputDateFound'];

            // create a new instance of the Records class
            $record = new Records;

            // Perform update (this occurs on 2 tables)
            $record->addLostAndFoundData(1, $inputItemName, $inputDateFound);
            // Has been added! Now redirecting
            echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '."URL=./?page=lostandfound".'">';
        }
    }

// Otherwise if not logged in, redirect to Login page!

} //end session check
else {
    header("Location: login.php");
}
?>
