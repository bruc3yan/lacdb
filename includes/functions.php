<?php
/*
 *
 * Functions
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Object Oriented functions
 *
 */

// turn on all error reporting
error_reporting(-1);


class Navigation {

	private $allowedPages = array();	// List of allowed pages
	private $requestedPage;		// Page that's requested by URL

	function __construct($rp) {
		$this->setRequestedPage($rp); // get the reques
		$this->setAllowedPages(); // set the available pages
	}

	// returns the requested page with the correct file extension
	function fetchPage() {
		return $this->getAllowedPages($this->getRequestedPage());
	}

	// returns the pages allowed by the list,
	// if not found, go back to home
	function getAllowedPages($request) {
		if (isset($this->allowedPages[$request])) {
			return $this->allowedPages[$request];
		} else {
			return $this->allowedPages['main'];
		}
	}

	// return the requested page
	function getRequestedPage() {
		return $this->requestedPage;
	}

	// Initialize our array
	function setAllowedPages() {
		$this->allowedPages = array (
	        'main' => './main.php',
	        'inmudderbikes' => './inmudderbikes.php',
	        'inequipment' => './inequipment.php',
	        'inrooms' => './inrooms.php',
	        'outmudderbikes' => './outmudderbikes.php',
	        'outequipment' => './outequipment.php',
	        'outrooms' => './outrooms.php',
	        'lostandfound' => './lostandfound.php',
	        'profile' => './profile.php',
	        'manageusers' => './manageusers.php',
	        'login' => './login.php',
	        'logout' => './logout.php'
		);
	}

	// Sets the page
	function setRequestedPage($rp) {
		if (isset($rp)) {
			$this->requestedPage = trim(strtolower($rp));
		} else {
			$this->requestedPage = 'main';
		}

	}

} // end navigation

class Records {
	protected $db;

	// Constructor - opens DB connection
	function __construct() {
		if (!$this->db instanceof mysqli) {
			$this->db = new mysqli('mysql.claremontbooks.com', 'byaz', 'Zonkey9387!$', 'zonkey');
        	$this->db->autocommit(FALSE);
		}

  	    if ($this->db->connect_errno) {
    	   	printf("Connection failed: %s \n", $this->db->connect_error);
			exit();
		}
    }

    // Destructor - close DB connection
    function __destruct() {
        $this->db->close();
    }

    function listLostAndFound($json) {
    	// Print all items in database
        $stmt = $this->db->prepare('SELECT itemid, item, datefound, returnedto, datereturn, notes FROM lostandfound ORDER BY datefound ASC');
        $stmt->execute();
        $stmt->bind_result($itemid, $item, $datefound, $returnedto, $datereturn, $notes);
        $stmt->store_result(); // store result set into buffer


        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('itemid' => $itemid, 'item' => $item, 'datefound' => $datefound, 'returnedto' => $returnedto, 'datereturn' => $datereturn, 'notes' => $notes);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("loan" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

		// Loop through the associative array and output all results.
		if ($stmt->num_rows == 0)
			echo "<h4>No Lost and Found items currently in the database!</h4>";
		else
		{
			// Print table header
			echo "				<div class=\"table-responsive\">";
			echo "					<table class=\"table table-striped table-hover table-bordered\">";
			echo "			    	<thead>";
			echo "			    	<tr>";
			echo "			        	<th>Item ID</th>";
			echo "			        	<th>Item Name</th>";
			echo "			        	<th>Date Found</th>";
			echo "			        	<th>Returned To</th>";
			echo "			        	<th>Date Returned</th>";
			echo "			        	<th>Notes</th>";
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";

			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
				echo "			    	<tr>";
				echo "			        	<td>$itemid</td>";
				echo "			        	<td>$item</td>";
				echo "			        	<td>" . date('m/d/y', strtotime($datefound)) . "</td>";
				echo "			        	<td>$returnedto</td>";
				echo "			        	<td>" . ($datereturn == "0000-00-00" ? 'Unclaimed' : date('m/d/y', strtotime($datereturn))) . "</td>";
				echo "			        	<td>$notes</td>";
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";
    	}
        $stmt->close();
    }

    function listMudderBikeRentals($json, $edit, $history) {
    	// Print all bikes in database
    	// 	json = 1 means skip the table output but just display json script
    	//  edit = 1 means certain boxes appear for editing
    	//  history = 1 means display all returned bikes, 0 means display currently unreturned bikes

    	if ($history == 1)
    		$stmt = $this->db->prepare('SELECT rentid, bikeid, sname, sid, waiver, dateout, keyreturnedto, datein, status, latedays, paidcollectedby, notes FROM mudderbikerentals WHERE (status LIKE \'%return%\' OR status LIKE \'%late%\')ORDER BY rentid DESC');
    	else
    		$stmt = $this->db->prepare('SELECT rentid, bikeid, sname, sid, waiver, dateout, keyreturnedto, datein, status, latedays, paidcollectedby, notes FROM mudderbikerentals WHERE (status NOT LIKE \'%return%\' AND status NOT LIKE \'%late%\') ORDER BY rentid DESC');

        $stmt->execute();
        $stmt->bind_result($rentid, $bikeid, $sname, $sid, $waiver, $dateout, $keyreturnedto, $datein, $status, $latedays, $paidcollectedby, $notes);
        $stmt->store_result(); // store result set into buffer


        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('rentid' => $rentid, 'bikeid' => $bikeid, 'sname' => $sname, 'sid' => $sid, 'waiver' => $waiver, 'dateout' => $dateout, 'keyreturnedto' => $keyreturnedto, 'datein' => $datein, 'status' => $status, 'latedays' => $latedays, 'paidcollectedby' => $paidcollectedby, 'notes' => $notes);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("bikerentals" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

		// Loop through the associative array and output all results.
		if ($stmt->num_rows == 0)
			echo "<h4>No bike rentals currently in the database!</h4>";
		else
		{
			// Print table header
			echo "				<div class=\"table-responsive\">";
			echo "					<table class=\"table table-striped table-hover table-bordered\">";
			echo "			    	<thead>";
			echo "			    	<tr>";
			echo "			        	<th>Rental ID</th>";
			echo "			        	<th>Bike ID</th>";
			echo "			        	<th>Student Name</th>";
			echo "			        	<th>Student ID</th>";
			echo "			        	<th>Waiver?</th>";
			echo "			        	<th>Check Out Date</th>";
			echo "			        	<th>Key Returned To</th>";
			echo "			        	<th>Check In Date</th>";
			echo "			        	<th>Status</th>";
			echo "			        	<th>Late Days</th>";
			echo "			        	<th>Paid/Collected By</th>";
			echo "			        	<th>Notes</th>";
            echo "                      <th>Actions</th>";
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";

			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $checkinButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#bikeCheckin".$bikeid."\">Check In</button>";

				echo "			    	<tr>";
				echo "			        	<td>$rentid</td>";
				echo "			        	<td>$bikeid</td>";
				echo "			        	<td>$sname</td>";
				echo "			        	<td>$sid</td>";
				echo "			        	<td>$waiver</td>";
				echo "			        	<td>" . date('m/d/y', strtotime($dateout)) . "</td>";
				echo "			        	<td>$keyreturnedto</td>";
				echo "			        	<td>" . ($datein == "0000-00-00" ? 'Unclaimed' : date('m/d/y', strtotime($datein))) . "</td>";
				echo "			        	<td>$status</td>";
				echo "			        	<td>$latedays</td>";
				echo "			        	<td>$paidcollectedby</td>";
				echo "			        	<td>$notes</td>";
                echo "                      <td>" . ($history != 1 ? $checkinButton : '');
                echo                        $history != 1 ? $this->printMudderbikeModalWindow($bikeid, 0, $rentid, $sname, $sid, $waiver) : '' . "</td>";
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";
    	}
        $stmt->close();
    }

    function listMudderBikeData($json, $edit) {
    	// Print all bikes in database
    	// 	json = 1 means skip the table output but just display json script
    	//  edit = 1 means certain boxes appear for editing

    	$stmt = $this->db->prepare('SELECT * FROM mudderbikedata');
        $stmt->execute();
        $stmt->bind_result($bikeid, $available, $notes, $dateofbirth, $dateofdeath);
        $stmt->store_result(); // store result set into buffer

        // Push the results into JSON format if requested
        if ($json == 1) {
            // JSON variables - prepare array to encode JSON with
            $outerArray = array();

            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('bikeid' => $bikeid, 'available' => $available, 'notes' => $notes, 'dateofbirth' => $dateofbirth, 'dateofdeath' => $dateofdeath);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("bikerdata" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

		// Loop through the associative array and output all results.
        // If no data exists
		if ($stmt->num_rows == 0)
			echo "<h4>No bike data currently in the database!</h4>";
		// Print all results if data exists
        else
		{
			// Print table header
			echo "				<div class=\"table-responsive\">";
			echo "					<table class=\"table table-striped table-hover table-bordered\">";
			echo "			    	<thead>";
			echo "			    	<tr>";
			echo "			        	<th>Bike ID</th>";
			echo "			        	<th>Availability</th>";
			echo "			        	<th>Notes</th>";
			echo "			        	<th>Date of Birth</th>";
			echo "			        	<th>Date of Death</th>";
			echo "			        	<th>Actions</th>";
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";

			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
	        	// This will loop through all the bikeids and the HTML will have unique identifiers
	        	$checkoutButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#bikeCheckout".$bikeid."\">Check out</button>";

				echo "			    	<tr>";
				echo "			        	<td>$bikeid</td>";
				echo "			        	<td>" . ($available == 1 ? 'Yes' : 'No') . "</td>";
				echo "			        	<td>$notes</td>";
				echo "			        	<td>" . ($dateofbirth == "0000-00-00" ? 'Unclaimed' : date('m/d/y', strtotime($dateofbirth))) . "</td>";
				echo "			        	<td>" . ($dateofdeath == "0000-00-00" ? 'Unclaimed' : date('m/d/y', strtotime($dateofdeath))) . "</td>";
				echo "			        	<td>" . ($available == 1 ? $checkoutButton : '');
				echo 			        	$available == 1 ? $this->printMudderbikeModalWindow($bikeid, 1) : '' . "</td>";
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";
    	}
        $stmt->close();
    }

    function checkInMudderBike($rentid, $bikeid, $datein, $status, $keyreturnedto, $late, $paid, $notes) {
        // Check out the associated bike id with a student
        //  $rentid = need this since it's primary key
        //  $bikeid = needed for setting bike data back to available
        //  $datein = return date

        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 1 in the bike data
            $stmt = $this->db->prepare('UPDATE mudderbikedata SET available = 1 WHERE bikeid=?');
            $stmt->bind_param("i", $bikeid);
            $stmt->execute();
            $stmt->close();

            // Then we add an entry to the bike rentals table
            $stmt = $this->db->prepare('UPDATE mudderbikerentals SET keyreturnedto = ?, datein = ?, status = ?, latedays = ?, paidcollectedby = ?, notes = ? WHERE rentid = ?');
            $stmt->bind_param("sssissi", $keyreturnedto, $datein, $status, $late, $paid, $notes, $rentid);
            $stmt->execute();
            $stmt->close();

            // We commit the transaction because nothing has failed
            $this->db->commit();
            $this->db->autocommit(TRUE); // end transaction
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $db->rollback();
            $this->db->autocommit(TRUE); // end transaction
        }
    }

    function checkOutMudderBike($bikeid, $sname, $sid, $waiver, $notes, $status="") {
    	// Check out the associated bike id with a student
    	// 	$bikeid = grabbed from the list of bikes avialable after clicking on a button
    	//  $sname = student name
    	//  $sid = student id
    	//  $waiver = yes/no
    	//  $dateout = today's date
    	//  $notes = any notes the lac staff wants to add
        //  $status = returned or late, empty string is default

    	// Get today's date
    	$dateout = date('y/m/d');

        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 0 in the bike data
            $stmt = $this->db->prepare('UPDATE mudderbikedata SET available = 0 WHERE bikeid=?');
            $stmt->bind_param("i", $bikeid);
            $stmt->execute();
            $stmt->close();

            // Then we add an entry to the bike rentals table
            $stmt = $this->db->prepare('INSERT INTO mudderbikerentals (bikeid, sname, sid, waiver, dateout, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param("isissss", $bikeid, $sname, $sid, $waiver, $dateout, $status, $notes);
            $stmt->execute();
            $stmt->close();

            // We commit the transaction because nothing has failed
            $this->db->commit();
            $this->db->autocommit(TRUE); // end transaction
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $db->rollback();
            $this->db->autocommit(TRUE); // end transaction
        }
    }

    // $available = 1 means display CHECK OUT
    // $available = 0 means display CHECK IN
    function printMudderbikeModalWindow($bikeid, $available, $rentid = -1, $sname = "", $sid = -1, $waiver = "") {
        if ($available == 1) {
    		return '	<div class="modal fade" id="bikeCheckout'.$bikeid.'" tabindex="-1" role="dialog" aria-labelledby="bikeCheckout'.$bikeid.'" aria-hidden="true">
    				<div class="modal-dialog">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    							<h4 class="modal-title" id="bikeLabel">Mudder Bike Checkout Form</h4>
    						</div> <!-- end modal header -->
    						<form action="checkout.php?mode=mudderbike" method="post" class="form-horizontal" role="form">
    							<div class="modal-body">

    									<div class="form-group">
    									    <label for="bikeid" class="col-sm-2 control-label">Bike ID</label>
    									    <div class="col-sm-10">
    									       <p class="form-control-static">'.$bikeid.'</p>
    								    	</div>
                                            <input type="hidden" name="bikeid" value="'.$bikeid.'" />
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputName" class="col-sm-2 control-label">Name</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Name">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputSID" class="col-sm-2 control-label">ID #</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputSID" id="inputSID" placeholder="Student ID#">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputWaiver" class="col-sm-2 control-label">Waiver</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputWaiver" id="inputWaiver" placeholder="Waiver">
    								    	</div>
    								  	</div><!--
    								  	<div class="form-group">
    								    	<label for="inputNotes" class="col-sm-2 control-label">Notes</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
    								    	</div>
    								  	</div>-->

    							</div> <!-- end modal-body -->
    							<div class="modal-footer">
    								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    								<button type="submit" name="save" class="btn btn-primary">Save changes</button>
    							</div> <!-- end modal-footer -->
    						</form>
    					</div> <!-- end modal content -->
    				</div> <!-- end modal dialog -->
    			</div> <!-- end my myModal -->';
            } else {
                return '   <div class="modal fade" id="bikeCheckin'.$bikeid.'" tabindex="-1" role="dialog" aria-labelledby="bikeCheckin'.$bikeid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="bikeLabel">Mudder Bike Checkin Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkin.php?mode=mudderbike" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="rentid" class="col-sm-4 control-label">Rent ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$rentid.'</p>
                                            </div>
                                            <input type="hidden" name="rentid" value="'.$rentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="bikeid" class="col-sm-4 control-label">Bike ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$bikeid.'</p>
                                            </div>
                                            <input type="hidden" name="bikeid" value="'.$bikeid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="sname" class="col-sm-4 control-label">Name</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$sname.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sid" class="col-sm-4 control-label">ID #</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$sid.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waiver" class="col-sm-4 control-label">Waiver</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$waiver.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateIn" class="col-sm-4 control-label">Date In</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputDateIn" id="inputDateIn" placeholder="Date of Return, format: yyyy/mm/dd">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputStatus" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputStatus" id="inputStatus" placeholder="i.e. Returned">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputkeyreturnedto" class="col-sm-4 control-label">Key Returned To</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputkeyreturnedto" id="inputkeyreturnedto" placeholder="LAC Staff Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputLate" class="col-sm-4 control-label">Late</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputLate" id="inputLate" placeholder="Manual Entry of days late">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPaid" class="col-sm-4 control-label">Paid / Collected By</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputPaid" id="inputPaid" placeholder="LAC Staff Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-4 control-label">Notes</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
                                            </div>
                                        </div>

                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
            }
    }

    function listEquipmentData($json, $edit) {
        // Print all existing equipment in database
        //  json = 1 means skip the table output but just display json script
        //  edit = 1 means certain boxes appear for editing

        $stmt = $this->db->prepare('SELECT * FROM equipmentdata');
        $stmt->execute();
        $stmt->bind_result($equipmentid, $name, $qtyleft, $notes, $ownerid);
        $stmt->store_result(); // store result set into buffer

        // Push the results into JSON format if requested
        if ($json == 1) {
            // JSON variables - prepare array to encode JSON with
            $outerArray = array();

            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('equipmentid' => $equipmentid, 'name' => $name, 'qtyleft' => $qtyleft, 'notes' => $notes, 'ownerid' => $ownerid);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("equipmentdata" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

        // Loop through the associative array and output all results.
        // If no data exists
        if ($stmt->num_rows == 0)
            echo "<h4>No equipment data currently in the database!</h4>";
        // Print all results if data exists
        else
        {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Equipment ID</th>";
            echo "                      <th>Equipment Name</th>";
            echo "                      <th>Quantity Left</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Owner ID</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $checkoutButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#bikeCheckout".$equipmentid."\">Check out</button>";

                echo "                  <tr>";
                echo "                      <td>$equipmentid</td>";
                echo "                      <td>$name</td>";
                echo "                      <td>$qtyleft</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>$ownerid</td>";
                echo "                      <td>" . ($qtyleft >= 1 ? $checkoutButton : '');
                echo                        $qtyleft >= 1 ? $this->printMudderbikeModalWindow($equipmentid, 1) : '' . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }
        $stmt->close();
    }

    function listEquipmentRentals($json, $edit, $history) {
        // Print all equipment rentals in database
        //  json = 1 means skip the table output but just display json script
        //  edit = 1 means certain boxes appear for editing
        //  history = 1 means display all returned bikes, 0 means display currently unreturned bikes

        if ($history == 1)
            $stmt = $this->db->prepare('SELECT rentid, equipmentid, sname, sid, dateout, datein, school, timeout, timein, notes FROM equipmentrentals WHERE (datein IS NULL) ORDER BY rentid DESC');
        else
            $stmt = $this->db->prepare('SELECT rentid, equipmentid, sname, sid, dateout, datein, school, timeout, timein, notes FROM equipmentrentals WHERE (datein IS NOT NULL) ORDER BY rentid DESC');

        $stmt->execute();
        $stmt->bind_result($rentid, $equipmentid, $sname, $sid, $dateout, $datein, $school, $timeout, $timein, $notes);
        $stmt->store_result(); // store result set into buffer


        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('rentid' => $rentid, 'equipmentid' => $equipmentid, 'sname' => $sname, 'sid' => $sid, 'dateout' => $dateout, 'datein' => $datein, 'school' => $school, 'timeout' => $timeout, 'timein' => $timein, 'notes' => $notes);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("equipmentrentals" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

        // Loop through the associative array and output all results.
        if ($stmt->num_rows == 0)
            echo "<h4>No bike rentals currently in the database!</h4>";
        else
        {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Rental ID</th>";
            echo "                      <th>Equipment ID</th>";
            echo "                      <th>Student Name</th>";
            echo "                      <th>Student ID</th>";
            echo "                      <th>Check Out Date</th>";
            echo "                      <th>Check In Date</th>";
            echo "                      <th>School</th>";
            echo "                      <th>Check Out Time</th>";
            echo "                      <th>Check In Time</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $checkinButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#bikeCheckin".$rentid."\">Check In</button>";

                echo "                  <tr>";
                echo "                      <td>$rentid</td>";
                echo "                      <td>$equipmentid</td>";
                echo "                      <td>$sname</td>";
                echo "                      <td>$sid</td>";
                echo "                      <td>" . date('m/d/y', strtotime($dateout)) . "</td>";
                echo "                      <td>" . date('m/d/y', strtotime($datein)) . "</td>";
                echo "                      <td>$school</td>";
                echo "                      <td>" . ($timeout == "0000-00-00" ? 'Unclaimed' : date('m/d/y h:i:s', strtotime($timeout))) . "</td>";
                echo "                      <td>$timein</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>" . ($history != 1 ? $checkinButton : '');
                echo                        $history != 1 ? $this->printMudderbikeModalWindow($equipmentid, 0, $rentid, $sname, $sid, $waiver = 0) : '' . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }
        $stmt->close();
    }
    /*

     function listCheckedRooms($json, $edit, $history) {
    	// Print all items in database
    	// 	json = 1 means skip the table output but just display json script
    	//  edit = 1 means certain boxes appear for editing
    	//  history = 1 means display all returned bikes, 0 means display currently unreturned bikes

    	if ($history == 1)
    		$stmt = $this->db->prepare('SELECT * FROM rooms WHERE (status LIKE \'%return%\' OR status LIKE \'%late%\')ORDER BY rentid DESC');
    	else
    		$stmt = $this->db->prepare('SELECT * FROM rooms WHERE (status NOT LIKE \'%return%\' AND status NOT LIKE \'%late%\') ORDER BY rentid DESC');

        $stmt->execute();
        $stmt->bind_result($rid, $name, $status, $reservDate, $duration, $contactName, $description);
        $stmt->store_result(); // store result set into buffer


        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('rid' => $rid, 'name' => $name, 'status' => $status, 'reservDate' => $reservDate, 'duration' => $duration, 'contactName' => $contactName, 'description' => $description);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("Room Reservation" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

		// Loop through the associative array and output all results.
		if ($stmt->num_rows == 0)
			echo "<h4>No room for reservation currently in the database!</h4>";
		else
		{
			// Print table header
			echo "				<div class=\"table-responsive\">";
			echo "					<table class=\"table table-striped table-hover table-bordered\">";
			echo "			    	<thead>";
			echo "			    	<tr>";
			echo "			        	<th>Room ID</th>";
			echo "			        	<th>Room Name</th>";
			echo "			        	<th>Status</th>";
			echo "			        	<th>Reservation Date</th>";
			echo "			        	<th>Duration</th>";
			echo "			        	<th>Contact Name</th>";
			echo "			        	<th>Description</th>";
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";

			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $checkinButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#bikeCheckin".$bikeid."\">Check In</button>";

				echo "			    	<tr>";
				echo "			        	<td>$rid</td>";
				echo "			        	<td>$name</td>";
				echo "			        	<td>$status</td>";
				echo "			        	<td>" . date('m/d/y', strtotime($reservDate)) . "</td>";
				echo "			        	<td>$duration</td>";
				echo "			        	<td>$contactName</td>";
				echo "			        	<td>$description</td>";
                echo "                      <td>" . ($history != 1 ? $checkinButton : '');
                //echo                        $history != 1 ? $this->printMudderbikeModalWindow($bikeid, 0, $rentid, $sname, $sid, $waiver) : '' . "</td>";
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";
    	}
        $stmt->close();
    }*/

} //end Records

class Users {
    private $db;

    // Constructor - opens DB connection
	function __construct() {
		if (!$this->db instanceof mysqli) {
			$this->db = new mysqli('mysql.claremontbooks.com', 'byaz', 'Zonkey9387!$', 'zonkey');
        	$this->db->autocommit(FALSE);
		}

  	    if ($this->db->connect_errno) {
    	   	printf("Connection failed: %s \n", $this->db->connect_error);
			exit();
		}
    }

    // Destructor - close DB connection
    function __destruct() {
        $this->db->close();
    }

    // Check for existing user for registering (TO DO)

    // Check if the user input valid login information
    // sha256 hash calculator for "lacdb123456rocks!"
    // http://www.xorbin.com/tools/sha256-hash-calculator
    function checkLoginInfo($inputEmail, $inputPassword) {
        // Prepare to access
        $tempEmail = $this->db->escape_string($inputEmail);
        $tempPassword = $this->db->escape_string($inputPassword);
        $salt1 = "lacdb";
        $salt2 = "rocks!";
        $tempPasswordCombined = $salt1.$tempPassword.$salt2;


        $rehashedPassword = hash('sha256', $tempPasswordCombined);

        // Search for matching email and password in database
        $stmt = $this->db->prepare('SELECT uid, level, name, email, password, profile FROM users WHERE email = ? AND password = ?');
        $stmt->bind_param("ss", $tempEmail, $rehashedPassword);
        $stmt->execute();
        $stmt->bind_result($userid, $level, $name, $email, $password, $profile);
        $stmt->fetch();

        if($rehashedPassword == $password) {
            return array('uid' => $userid,
            			'level' => $level,
            			'name' => $name,
            			'email' => $email,
            			'password' => $password,
            			'profile' => $profile
        				);
        } else {
            return NULL;
        }
    }

}


?>
