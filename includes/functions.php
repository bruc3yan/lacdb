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

    /***********************************
    ********** LOST AND FOUND **********
    ***********************************/
    function listLostAndFound($json, $history) {
        // history = 0 = LOST
        // history = 1 = Found
        // history = -1 = History of returned items

        if ($history == 0) {
            // Print all Lost items
            $stmt = $this->db->prepare('SELECT itemid, item, datelost, description FROM lost ORDER BY datelost DESC');
            $stmt->execute();
            $stmt->bind_result($itemid, $item, $datelost, $description);
        } else if ($history == 1) {
            // Print all Found items
            $stmt = $this->db->prepare('SELECT itemid, item, datefound FROM found ORDER BY datefound DESC');
            $stmt->execute();
            $stmt->bind_result($itemid, $item, $datefound);
        } else {
            // Print all history in database
            $stmt = $this->db->prepare('SELECT itemid, item, datefound, returnedto, datereturn, notes, category FROM lostandfound ORDER BY datefound ASC');
            $stmt->execute();
            $stmt->bind_result($itemid, $item, $datefound, $returnedto, $datereturn, $notes, $category);
        }

        $stmt->store_result(); // store result set into buffer

        /*
        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('itemid' => $itemid, 'item' => $item, 'datefound' => $datefound, 'returnedto' => $returnedto, 'datereturn' => $datereturn, 'notes' => $notes, 'category' => $category);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("loan" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }
        */

		// Loop through the associative array and output all results.
		if ($stmt->num_rows == 0)
			echo "<h4>No Lost and Found items currently in the database!</h4>";
		else if ($history == 0) {
			// Print table header
			echo "				<div class=\"table-responsive\">";
			echo "					<table class=\"table table-striped table-hover table-bordered\">";
			echo "			    	<thead>";
			echo "			    	<tr>";
			echo "			        	<th>Item ID</th>";
			echo "			        	<th>Item Name</th>";
			echo "			        	<th>Date Lost</th>";
            echo "                      <th>Description</th>";
			echo "			        	<th>Actions</th>";
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";

			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
                // This will loop through all the ids and the HTML will have unique identifiers
                $claimButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#lostClaim".$itemid."\">Claim</button>&nbsp;";

                $modifyButton = "<button class=\"btn btn-sm btn-warning\" data-toggle=\"modal\" data-target=\"#lostModify".$itemid."\">Edit</button>&nbsp;";

                $deleteButton = "<button class=\"btn btn-sm btn-danger\" data-toggle=\"modal\" data-target=\"#lostDelete".$itemid."\">Delete</button>&nbsp;";

				echo "			    	<tr>";
				echo "			        	<td>$itemid</td>";
				echo "			        	<td>$item</td>";
                echo "                      <td>" . date('m/d/y', strtotime($datelost)) . "</td>";
                echo "                      <td>$description</td>";
                echo "                      <td>" . $claimButton . $modifyButton . $deleteButton;
                echo                        $this->printLostAndFoundModalWindow($itemid, 0, $item, $datelost, $description) . "</td>";
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";

            // Add items to the lost database!
            $addLostButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#lostInsert\">Add Lost items</button>";
            echo '<p>'. $addLostButton;
            echo $this->printAddLostAndFoundModalWindow(0);
            echo '</p>';
    	} else if ($history == 1) {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Item ID</th>";
            echo "                      <th>Item Name</th>";
            echo "                      <th>Date Found</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the ids and the HTML will have unique identifiers
                $claimButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#foundClaim".$itemid."\">Claim</button>&nbsp;";

                $modifyButton = "<button class=\"btn btn-sm btn-warning\" data-toggle=\"modal\" data-target=\"#foundModify".$itemid."\">Edit</button>&nbsp;";

                $deleteButton = "<button class=\"btn btn-sm btn-danger\" data-toggle=\"modal\" data-target=\"#foundDelete".$itemid."\">Delete</button>&nbsp;";

                echo "                  <tr>";
                echo "                      <td>$itemid</td>";
                echo "                      <td>$item</td>";
                echo "                      <td>" . date('m/d/y', strtotime($datefound)) . "</td>";
                echo "                      <td>" . $claimButton . $modifyButton . $deleteButton;
                echo                        $this->printLostAndFoundModalWindow($itemid, 1, $item, $datefound) . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";

            // Add items to the found database!
            $addFoundButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#foundInsert\">Add Found items</button>";
            echo '<p>'. $addFoundButton;
            echo $this->printAddLostAndFoundModalWindow(1);
            echo '</p>';
        } else {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Item ID</th>";
            echo "                      <th>Item Name</th>";
            echo "                      <th>Date Lost/Found</th>";
            echo "                      <th>Date Claimed</th>";
            echo "                      <th>Claim Person</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Category</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the ids and the HTML will have unique identifiers
                $modifyButton = "<button class=\"btn btn-sm btn-warning\" data-toggle=\"modal\" data-target=\"#historyModify".$itemid."\">Edit</button>&nbsp;";

                $deleteButton = "<button class=\"btn btn-sm btn-danger\" data-toggle=\"modal\" data-target=\"#historyDelete".$itemid."\">Delete</button>&nbsp;";

                echo "                  <tr>";
                echo "                      <td>$itemid</td>";
                echo "                      <td>$item</td>";
                echo "                      <td>" . date('m/d/y', strtotime($datefound)) . "</td>";
                echo "                      <td>" . date('m/d/y', strtotime($datereturn)) . "</td>";
                echo "                      <td>$returnedto</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>$category</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }
        $stmt->close();
    }

    // Mode
    //  0 = Lost
    //  1 = Found
    function addLostAndFoundData($mode, $itemName, $date, $itemDescription = "") {
        // Lost
        if ($mode == 0) {
            $stmt = $this->db->prepare('INSERT INTO lost (item, datelost, description) VALUES (?, ?, ?)');
           // Replaces the ? above with the variables passed in, i = integer, s = string
            $stmt->bind_param("sss", $itemName, $date, $itemDescription);
        }
        // Found
        else {
            $stmt = $this->db->prepare('INSERT INTO found (item, datefound) VALUES (?, ?)');
            // Replaces the ? above with the variables passed in, i = integer, s = string
            $stmt->bind_param("ss", $itemName, $date);
        }

        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    // Mode
    //  0 = Lost
    //  1 = Found
    function modifyLostAndFoundData($mode, $itemid, $itemName, $date, $itemDescription = "") {
        // Lost
        if ($mode == 0) {
            // Prepare update modified variables
            $stmt = $this->db->prepare('UPDATE lost SET lost.item=?, lost.datelost=?, lost.description=? WHERE lost.itemid=?');
            $stmt->bind_param("sssi", $itemName, $date, $itemDescription, $itemid);
        }
        // Found
        else {
            // Prepare update modified variables
            $stmt = $this->db->prepare('UPDATE found SET found.item=?, found.datefound=? WHERE found.itemid=?');
            $stmt->bind_param("ssi", $itemName, $date, $itemid);
        }

        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    // Mode
    //  0 = Lost
    //  1 = Found
    function deleteLostAndFoundData($mode, $itemid) {
        // Lost
        if ($mode == 0) {
            // Prepare delete statement
            $stmt = $this->db->prepare('DELETE FROM zonkey.lost WHERE lost.itemid=?');
            $stmt->bind_param("i", $itemid);
        }
        // Found
        else {
            // Prepare delete statement
            $stmt = $this->db->prepare('DELETE FROM zonkey.found WHERE found.itemid=?');
            $stmt->bind_param("i", $itemid);
        }
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    // Mode
    //  0 = Lost
    //  1 = Found
    function claimLostAndFoundData($mode, $itemid, $itemname, $date, $dateclaim, $notes, $returnto) {
        // Lost
        if ($mode == 0) {
            $category = "Lost";
            try {
                // Begin a transaction
                $this->db->autocommit(FALSE);

                // Prepare lost delete statement
                $stmt = $this->db->prepare('DELETE FROM zonkey.lost WHERE lost.itemid=?');
                $stmt->bind_param("i", $itemid);
                $stmt->execute();
                $stmt->close();

                // Then we add an entry to the lostandfound history table
                $stmt = $this->db->prepare('INSERT INTO lostandfound (item, datefound, returnedto, datereturn, notes, category) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param("ssssss", $itemname, $date, $returnto, $dateclaim, $notes, $category);
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
        // Found
        else if ($mode == 1){
            $category = "Found";
            try {
                // Begin a transaction
                $this->db->autocommit(FALSE);

                // Prepare found delete statement
                $stmt = $this->db->prepare('DELETE FROM zonkey.found WHERE found.itemid=?');
                $stmt->bind_param("i", $itemid);
                $stmt->execute();
                $stmt->close();

                // Then we add an entry to the lostandfound history table
                $stmt = $this->db->prepare('INSERT INTO lostandfound (item, datefound, returnedto, datereturn, notes, category) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param("ssssss", $itemname, $date, $returnto, $dateclaim, $notes, $category);
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
    }


    // For all the Pop up functions like Claim, Edit, and Delete
    function printLostAndFoundModalWindow($itemid, $mode, $itemname, $date, $itemDescription = "") {
        // Lost
        if ($mode == 0) {
                $claim = '    <div class="modal fade" id="lostClaim'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="lostClaim'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="lostandfoundLabel">Lost Claim Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=lost" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-2 control-label">Item ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="itemname" class="col-sm-2 control-label">Item Name</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemname.'</p>
                                            </div>
                                            <input type="hidden" name="itemname" value="'.$itemname.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="datelost" class="col-sm-2 control-label">Date Lost</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$date.'</p>
                                            </div>
                                            <input type="hidden" name="datelost" value="'.$date.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputReturnTo" class="col-sm-2 control-label">Claimed By</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputReturnTo" id="inputReturnTo" placeholder="Claimed By">
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

                $modify = '    <div class="modal fade" id="lostModify'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="lostModify'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="lostandfoundLabel">Edit Lost items</h4>
                            </div> <!-- end modal header -->
                            <form action="modify.php?mode=lost" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-2 control-label">Item ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputItemName" class="col-sm-2 control-label">Item Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputItemName" id="inputItemName" placeholder="Item Name" value="'.$itemname.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateLost" class="col-sm-2 control-label">Date Lost</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputDateLost" id="inputDateLost" placeholder="Date Lost" value="'.$date.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputItemDescription" class="col-sm-2 control-label">Item Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputItemDescription" id="inputItemDescription" placeholder="Item Description" value="'.$itemDescription.'">
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

                $delete = '    <div class="modal fade" id="lostDelete'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="lostDelete'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Confirm Deletion?</h4>
                            </div> <!-- end modal header -->
                            <form action="delete.php?mode=lost" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">
                                        <h5>Are you sure you want to delete the following item?</h5>
                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-4 control-label">Item ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-danger">Delete</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
            return $claim . $modify . $delete;
        }
        // Found
        else if ($mode == 1) {
                $claim = '    <div class="modal fade" id="foundClaim'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="foundClaim'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="lostandfoundLabel">Found Claim Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=found" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-2 control-label">Item ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="itemname" class="col-sm-2 control-label">Item Name</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemname.'</p>
                                            </div>
                                            <input type="hidden" name="itemname" value="'.$itemname.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="datelost" class="col-sm-2 control-label">Date Found</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$date.'</p>
                                            </div>
                                            <input type="hidden" name="datefound" value="'.$date.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputReturnTo" class="col-sm-2 control-label">Claimed By</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputReturnTo" id="inputReturnTo" placeholder="Claimed By">
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

                $modify = '    <div class="modal fade" id="foundModify'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="foundModify'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Edit Found items</h4>
                            </div> <!-- end modal header -->
                            <form action="modify.php?mode=found" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-2 control-label">Item ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputItemName" class="col-sm-2 control-label">Item Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputItemName" id="inputItemName" placeholder="Item Name" value="'.$itemname.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateFound" class="col-sm-2 control-label">Date Lost</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputDateFound" id="inputDateFound" placeholder="Date Lost" value="'.$date.'">
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

                $delete = '    <div class="modal fade" id="foundDelete'.$itemid.'" tabindex="-1" role="dialog" aria-labelledby="foundDelete'.$itemid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Confirm Deletion?</h4>
                            </div> <!-- end modal header -->
                            <form action="delete.php?mode=found" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">
                                        <h5>Are you sure you want to delete the following item?</h5>
                                        <div class="form-group">
                                            <label for="itemid" class="col-sm-4 control-label">Item ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$itemid.'</p>
                                            </div>
                                            <input type="hidden" name="itemid" value="'.$itemid.'" />
                                        </div>
                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-danger">Delete</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
            return $claim . $modify . $delete;
        }
        // History (we are done!)
        else {
            // Nothing to do here
        }
    }

    // For adding Lost (mode == 0) and Found (mode == 1) items to the database
    function printAddLostAndFoundModalWindow($mode) {
        // Lost
        if ($mode == 0) {
            return '    <div class="modal fade" id="lostInsert" tabindex="-1" role="dialog" aria-labelledby="lostInsert" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="lostLabel">Add Lost Items To the Database</h4>
                                </div> <!-- end modal header -->
                                <form action="insert.php?mode=lost" method="post" class="form-horizontal" role="form">
                                    <div class="modal-body">

                                            <div class="form-group">
                                                <label for="inputItemName" class="col-sm-2 control-label">Item Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="inputItemName" id="inputItemName" placeholder="Item Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputDateLost" class="col-sm-2 control-label">Date Lost</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="inputDateLost" id="inputDateLost" placeholder="Date Lost (yyyy/mm/dd)">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputItemDescription" class="col-sm-2 control-label">Description</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="inputItemDescription" id="inputItemDescription" placeholder="Item Contact Description">
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
        // Found
        else if ($mode == 1) {
            return '    <div class="modal fade" id="foundInsert" tabindex="-1" role="dialog" aria-labelledby="foundInsert" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="foundLabel">Add Found Items To the Database</h4>
                            </div> <!-- end modal header -->
                            <form action="insert.php?mode=found" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="inputItemName" class="col-sm-2 control-label">Item Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputItemName" id="inputItemName" placeholder="Item Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateFound" class="col-sm-2 control-label">Date Lost</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputDateFound" id="inputDateFound" placeholder="Date Found (yyyy/mm/dd)">
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


    /***********************************
    ********** MUDDER BIKES   **********
    ***********************************/
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

            // Then we add an entry to the customers table (but first we check to see if user exists already or not)

            // Checking if user exists
            $stmt = $this->db->prepare('SELECT customerid, sid, name, email, phone, school FROM customers WHERE sid = ?');
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $stmt->bind_result($customerid, $sid, $sname, $customeremail, $customerphone, $school);
            $stmt->store_result(); // store result set into buffer

            // This means that no such user by the `sid` exists, we insert him in
            $school = "HMC"; // Since mudder bike is HMC only!
            if ($stmt->num_rows == 0) {
                $stmt = $this->db->prepare('INSERT INTO customers (sid, name, school) VALUES (?, ?, ?)');
                $stmt->bind_param("iss", $sid, $sname, $school);
                $stmt->execute();
                $stmt->close();
            }

            // If the above != 0, it means that _some_ user with that sid
            // must exist, we just proceed with inserting into equipment
            // rentals

            // Finally we add an entry to the bike rentals table
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


    /***********************************
    ********** EQUIPMENTS     **********
    ***********************************/
    function addEquipmentData($equipmentName = "Untitled", $qtyleft = 999, $equipmentNotes = "", $equipmentOwner = 0) {
        $stmt = $this->db->prepare('INSERT INTO equipmentdata (name, qtyleft, notes, ownerid) VALUES (?, ?, ?, ?)');
        // Replaces the ? above with the variables passed in, i = integer, s = string
        $stmt->bind_param("sisi", $equipmentName, $qtyleft, $equipmentNotes, $equipmentOwner);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    function modifyEquipmentData($equipmentid, $equipmentName, $qtyleft, $equipmentNotes, $equipmentOwner) {
        // Prepare update modified variables
        $stmt = $this->db->prepare('UPDATE equipmentdata SET name=?, qtyleft=?, notes=?, ownerid=? WHERE equipmentid=?');
        $stmt->bind_param("sisii", $equipmentName, $qtyleft, $equipmentNotes, $equipmentOwner, $equipmentid);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    function deleteEquipmentData($equipmentid) {
        // Prepare delete statement
        $stmt = $this->db->prepare('DELETE FROM zonkey.equipmentdata WHERE equipmentdata.equipmentid=?');
        $stmt->bind_param("i", $equipmentid);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    // Helper function to Grab equipment data first based on equipmentid attribute
    // (all attributes except equipmentid is passed in by reference)
    function getEquipmentDataByID($equipmentid, &$equipmentName, &$qtyleft, &$equipmentNotes, &$equipmentOwner) {
        $stmt = $this->db->prepare('SELECT equipmentid, name, qtyleft, notes, ownerid FROM equipmentdata WHERE equipmentid = ?');
        $stmt->bind_param("i", $equipmentid);
        $stmt->execute();
        $stmt->bind_result($equipmentid, $equipmentName, $qtyleft, $equipmentNotes, $equipmentOwner);
        $stmt->store_result(); // store result set into buffer
        $stmt->fetch(); //fetch the result into variables

        $stmt->close();
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
            echo "                      <th>Owner ID</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the ids and the HTML will have unique identifiers
                $checkoutButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#equipmentCheckout".$equipmentid."\">Check out</button>&nbsp;";

                $modifyButton = "<button class=\"btn btn-sm btn-warning\" data-toggle=\"modal\" data-target=\"#equipmentModify".$equipmentid."\">Edit</button>&nbsp;";

                $deleteButton = "<button class=\"btn btn-sm btn-danger\" data-toggle=\"modal\" data-target=\"#equipmentDelete".$equipmentid."\">Delete</button>&nbsp;";

                echo "                  <tr>";
                echo "                      <td>$equipmentid</td>";
                echo "                      <td>$name</td>";
                echo "                      <td>$qtyleft</td>";
                echo "                      <td>$ownerid</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>" . ($qtyleft >= 1 ? $checkoutButton . $modifyButton . $deleteButton : $modifyButton . $deleteButton);
                echo                        $qtyleft >= 1 ? $this->printEquipmentModalWindow($equipmentid, $qtyleft) : $this->printEquipmentModalWindow($equipmentid, 1) . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }

        // Add items to the database!
        $addButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#equipmentInsert\">Add items</button>";
        echo '<p>'. $addButton;
        echo $this->printAddEquipmentModalWindow();
        echo '</p>';

        $stmt->close();
    }

    function listEquipmentRentals($json, $edit, $history) {
        // Print all equipment rentals in database
        //  json = 1 means skip the table output but just display json script
        //  edit = 1 means certain boxes appear for editing
        //  history = 1 means display all returned bikes, 0 means display currently unreturned bikes

        if ($history == 1)
            $stmt = $this->db->prepare('SELECT rentid, equipmentid, sname, sid, dateout, datein, school, timeout, timein, notes FROM equipmentrentals WHERE (datein IS NOT NULL) ORDER BY rentid DESC');
        else
            $stmt = $this->db->prepare('SELECT rentid, equipmentid, sname, sid, dateout, datein, school, timeout, timein, notes FROM equipmentrentals WHERE (datein IS NULL) ORDER BY rentid DESC');

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
            echo "<h4>No equipment rentals currently in the database!</h4>";
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
            echo "                      <th>School</th>";
            echo "                      <th>Check Out Date</th>";
            echo "                      <th>Check Out Time</th>";
            echo "                      <th>Check In Date</th>";
            echo "                      <th>Check In Time</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $checkinButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#equipmentCheckin".$rentid."\">Check In</button>";

                echo "                  <tr>";
                echo "                      <td>$rentid</td>";
                echo "                      <td>$equipmentid</td>";
                echo "                      <td>$sname</td>";
                echo "                      <td>$sid</td>";
                echo "                      <td>$school</td>";
                echo "                      <td>" . date('m/d/y', strtotime($dateout)) . "</td>";
                echo "                      <td>" . ( $timeout == NULL ? $timeout : date('h:i:s', strtotime($timeout)) ) . "</td>";
                echo "                      <td>" . ( $datein == NULL ? $datein : date('m/d/y', strtotime($datein)) ) . "</td>";
                echo "                      <td>" . ( $timein == "0000-00-00 00:00:00" ? "" : date('h:i:s', strtotime($timein)) ) . "</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>" . ($history != 1 ? $checkinButton : '');
                echo                        $history != 1 ? $this->printEquipmentModalWindow($equipmentid, 0, $rentid, $sname, $sid, $school, $dateout, $datein, date('h:i:s', strtotime($timeout)), $timein, $notes) : '' . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }
        $stmt->close();
    }

    function checkInEquipment($rentid, $equipmentid, $datein, $timein, $notes) {
        // Check out the associated bike id with a student
        //  $rentid = need this since it's primary key
        //  $bikeid = needed for setting bike data back to available
        //  $datein = return date

        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 1 in the bike data
            $stmt = $this->db->prepare('UPDATE equipmentdata SET qtyleft = qtyleft+1 WHERE equipmentid=?');
            $stmt->bind_param("i", $equipmentid);
            $stmt->execute();
            $stmt->close();

            // Then we add an entry to the bike rentals table
            $stmt = $this->db->prepare('UPDATE equipmentrentals SET datein = ?, timein = ?, notes = ? WHERE rentid = ?');
            $stmt->bind_param("sssi", $datein, $timein, $notes, $rentid);
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

    function checkOutEquipment($equipmentid, $sname, $sid, $school, $dateout, $timeout) {
        // Check out the associated equipment id with a student
        //  $equipmentid = grabbed from the list of equipment after clicking on a button
        //  $sname = student name
        //  $sid = student id
        //  $school
        //  $dateout = today's date
        //  $timeout = current time


        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 0 in the data
            $stmt = $this->db->prepare('UPDATE equipmentdata SET qtyleft = qtyleft-1 WHERE equipmentid=?');
            $stmt->bind_param("i", $equipmentid);
            $stmt->execute();
            $stmt->close();

            // Then we add an entry to the customers table (but first we check to see if user exists already or not)

            // Checking if user exists
            $stmt = $this->db->prepare('SELECT customerid, sid, name, email, phone, school FROM customers WHERE sid = ?');
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $stmt->bind_result($customerid, $sid, $sname, $customeremail, $customerphone, $school);
            $stmt->store_result(); // store result set into buffer

            // This means that no such user by the `sid` exists, we insert him in
            if ($stmt->num_rows == 0) {
                $stmt = $this->db->prepare('INSERT INTO customers (sid, name, school) VALUES (?, ?, ?)');
                $stmt->bind_param("iss", $sid, $sname, $school);
                $stmt->execute();
                $stmt->close();
            }

            // If the above != 0, it means that _some_ user with that sid
            // must exist, we just proceed with inserting into equipment
            // rentals

            // Finally we add an entry to the rentals table
            $stmt = $this->db->prepare('INSERT INTO equipmentrentals (equipmentid, sname, sid, school, dateout, timeout) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param("isisss", $equipmentid, $sname, $sid, $school, $dateout, $timeout);
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
    function printEquipmentModalWindow($equipmentid, $qtyleft, $rentid = -1, $sname = "", $sid = -1, $school = "", $dateout = "", $datein = "", $timeout = "", $timein = "", $notes = "") {

        // time variables for forms
        $inputDateOut = date('y-m-d');
        $inputTimeOut = date('h:i:s');
        $inputDateIn = date('y-m-d');
        $inputTimeIn = date('h:i:s');

        if ($qtyleft) {
            $checkout = '    <div class="modal fade" id="equipmentCheckout'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentCheckout'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Equipment Rental Checkout Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-2 control-label">Equipment ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
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
                                            <label for="inputSchool" class="col-sm-2 control-label">School</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputSchool" id="inputSchool" placeholder="School">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateOut" class="col-sm-2 control-label">Check Out Date</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$inputDateOut.'</p>
                                            </div>
                                            <input type="hidden" name="inputDateOut" value="'.$inputDateOut.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeOut" class="col-sm-2 control-label">Check Out Time</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$inputTimeOut.'</p>
                                            </div>
                                            <input type="hidden" name="inputTimeOut" value="'.$inputTimeOut.'" />
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

                // Call to helper function for data members based on equipment id
                // For Modify equipment function
                $this->getEquipmentDataByID($equipmentid, $equipmentName, $qtyleft, $equipmentNotes, $equipmentOwner);

                $modify = '    <div class="modal fade" id="equipmentModify'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentModify'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Edit Equipment Data</h4>
                            </div> <!-- end modal header -->
                            <form action="modify.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-2 control-label">Equipment ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentName" class="col-sm-2 control-label">Equipment Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentName" id="inputEquipmentName" placeholder="Equipment Name" value="'.$equipmentName.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputQtyleft" class="col-sm-2 control-label">Quantity</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputQtyleft" id="inputQtyleft" placeholder="Quantity" value="'.$qtyleft.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentOwner" class="col-sm-2 control-label">Owner</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentOwner" id="inputEquipmentOwner" placeholder="Owner" value="'.$equipmentOwner.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentNotes" id="inputEquipmentNotes" placeholder="Notes" value="'.$equipmentNotes.'">
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

                $delete = '    <div class="modal fade" id="equipmentDelete'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentDelete'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Confirm Deletion?</h4>
                            </div> <!-- end modal header -->
                            <form action="delete.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">
                                        <h5>Are you sure you want to delete the following item?</h5>
                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-4 control-label">Equipment ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
                                        </div>
                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-danger">Delete</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
            return $checkout . $modify . $delete;
        } else {
                return '   <div class="modal fade" id="equipmentCheckin'.$rentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentCheckin'.$rentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Equipment Rental Checkin Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkin.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="rentid" class="col-sm-4 control-label">Rent ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$rentid.'</p>
                                            </div>
                                            <input type="hidden" name="rentid" value="'.$rentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-4 control-label">Equipment ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
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
                                            <label for="school" class="col-sm-4 control-label">School</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$school.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="dateout" class="col-sm-4 control-label">Check Out Date</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$dateout.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeout" class="col-sm-4 control-label">Check Out Time</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$timeout.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateIn" class="col-sm-4 control-label">Check In Time</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$inputDateIn.'</p>
                                            </div>
                                            <input type="hidden" name="inputDateIn" value="'.$inputDateIn.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeIn" class="col-sm-4 control-label">Check In Time</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$inputTimeIn.'</p>
                                            </div>
                                            <input type="hidden" name="inputTimeIn" value="'.$inputTimeIn.'" />
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

    // function for adding equipments to the database
    function printAddEquipmentModalWindow() {

        return '    <div class="modal fade" id="equipmentInsert" tabindex="-1" role="dialog" aria-labelledby="equipmentInsert" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Add Equipment To the Database</h4>
                            </div> <!-- end modal header -->
                            <form action="insert.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="inputEquipmentName" class="col-sm-2 control-label">Equipment Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentName" id="inputEquipmentName" placeholder="Equipment Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputQtyleft" class="col-sm-2 control-label">Quantity</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputQtyleft" id="inputQtyleft" placeholder="Quantity">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentOwner" class="col-sm-2 control-label">Owner</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentOwner" id="inputEquipmentOwner" placeholder="Owner">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentNotes" id="inputEquipmentNotes" placeholder="Notes">
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

    /***********************************
    ********** ROOMS     ***************
    ***********************************/

    function addRoomData($roomName) {
        $stmt = $this->db->prepare('INSERT INTO roomsdata (name) VALUES (?)');
        // Replaces the ? above with the variables passed in, i = integer, s = string
        $stmt->bind_param("s", $roomName);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    function modifyRoomData($roomid, $name) {
        // Prepare update modified variables
        $stmt = $this->db->prepare('UPDATE roomsdata SET name=? WHERE roomid=?');
        $stmt->bind_param("si", $name, $roomid);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    function deleteRoomData($roomid) {
        // Prepare delete statement
        $stmt = $this->db->prepare('DELETE FROM zonkey.roomsdata WHERE roomsdata.roomid=?');
        $stmt->bind_param("i", $roomid);
        $stmt->execute();

        $stmt->close();
        $this->db->commit();
    }

    function listRoomsData($json, $edit) {
        // Print all existing equipment in database
        //  json = 1 means skip the table output but just display json script
        //  edit = 1 means certain boxes appear for editing

        $available = 1; // Rooms are always available to check out!

        $stmt = $this->db->prepare('SELECT * FROM roomsdata');
        $stmt->execute();
        $stmt->bind_result($roomid, $name);
        $stmt->store_result(); // store result set into buffer

        // Push the results into JSON format if requested
        if ($json == 1) {
            // JSON variables - prepare array to encode JSON with
            $outerArray = array();

            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('roomid' => $roomid, 'name' => $name);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("roomdata" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

        // Loop through the associative array and output all results.
        // If no data exists
        if ($stmt->num_rows == 0)
            echo "<h4>No room data currently in the database!</h4>";
        // Print all results if data exists
        else
        {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Room ID</th>";
            echo "                      <th>Room Name</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the ids and the HTML will have unique identifiers
                $checkoutButton = "<button class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#roomCheckout".$roomid."\">Check out</button>&nbsp;";

                $modifyButton = "<button class=\"btn btn-sm btn-warning\" data-toggle=\"modal\" data-target=\"#roomModify".$roomid."\">Edit</button>&nbsp;";

                $deleteButton = "<button class=\"btn btn-sm btn-danger\" data-toggle=\"modal\" data-target=\"#roomDelete".$roomid."\">Delete</button>&nbsp;";

                echo "                  <tr>";
                echo "                      <td>$roomid</td>";
                echo "                      <td>$name</td>";
                echo "                      <td>" . $checkoutButton . $modifyButton . $deleteButton . $this->printRoomModalWindow($roomid, $available, $name) . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }

        // Add items to the database!
        $addButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#roomInsert\">Add rooms</button>";
        echo '<p>'. $addButton;
        echo $this->printAddRoomModalWindow();
        echo '</p>';

        $stmt->close();
    }

    function listRoomRentals($json, $edit, $history) {
        // Print all equipment rentals in database
        //  json = 1 means skip the table output but just display json script
        //  edit = 1 means certain boxes appear for editing
        //  history = 1 means display all returned bikes, 0 means display currently unreturned bikes

        if ($history == 1)
            $stmt = $this->db->prepare('SELECT rentid, roomid, sid, checkout, timeout, checkin, timein, notes, status FROM roomrentals WHERE (status IS NOT NULL) ORDER BY rentid DESC');
        else
            $stmt = $this->db->prepare('SELECT rentid, roomid, sid, checkout, timeout, checkin, timein, notes, status FROM roomrentals WHERE (status IS NULL) ORDER BY rentid DESC');

        $stmt->execute();
        $stmt->bind_result($rentid, $roomid, $sid, $checkout, $timeout, $checkin, $timein, $notes, $status);
        $stmt->store_result(); // store result set into buffer


        // JSON variables - prepare array to encode JSON with
        $outerArray = array();

        // Push the results into JSON format if requested

        if ($json == 1) {
            // Loop through each statement to grab columns and data
            while ($stmt->fetch()) {
                $loopArray = array('rentid' => $rentid, 'roomid' => $roomid, 'sid' => $sid, 'checkout' => $checkout, 'timeout' => $timeout, 'checkin' => $checkin, 'timein' => $timein, 'notes' => $notes);
                array_push($outerArray, $loopArray);
            }

            $returnArray = array("equipmentrentals" => $outerArray);

            echo json_encode($returnArray);
            exit;
        }

        // Loop through the associative array and output all results.
        if ($stmt->num_rows == 0)
            echo "<h4>No equipment rentals currently in the database!</h4>";
        else
        {
            // Print table header
            echo "              <div class=\"table-responsive\">";
            echo "                  <table class=\"table table-striped table-hover table-bordered\">";
            echo "                  <thead>";
            echo "                  <tr>";
            echo "                      <th>Rental ID</th>";
            echo "                      <th>Room ID</th>";
            echo "                      <th>Student ID</th>";
            echo "                      <th>Check Out Date</th>";
            echo "                      <th>Check Out Time</th>";
            echo "                      <th>Check In Date</th>";
            echo "                      <th>Check In Time</th>";
            echo "                      <th>Notes</th>";
            echo "                      <th>Actions</th>";
            echo "                  </tr>";
            echo "                  </thead>";
            echo "                  <tbody>";

            // Print table data by looping through all the rows
            while ($stmt->fetch()) {
                // This will loop through all the bikeids and the HTML will have unique identifiers
                $deactivateButton = "<button class=\"btn btn-sm btn-success\" data-toggle=\"modal\" data-target=\"#roomDeactivate".$rentid."\">Release!</button>";

                echo "                  <tr>";
                echo "                      <td>$rentid</td>";
                echo "                      <td>$roomid</td>";
                echo "                      <td>$sid</td>";
                echo "                      <td>" . date('m/d/y', strtotime($checkout)) . "</td>";
                echo "                      <td>" . date('h:i:s', strtotime($timeout)) . "</td>";
                echo "                      <td>" . date('m/d/y', strtotime($checkin)) . "</td>";
                echo "                      <td>" . date('h:i:s', strtotime($timein)) . "</td>";
                echo "                      <td>$notes</td>";
                echo "                      <td>" . ($history != 1 ? $deactivateButton : '');
                echo                        $history != 1 ? $this->printRoomModalWindow($roomid, 0, 0, $rentid, $notes) : '' . "</td>";
                echo "                  </tr>";
            }

            // Close table
            echo "                  </tbody>";
            echo "              </table>";
            echo "          </div>";
        }
        $stmt->close();
    }

    function checkInRoom($rentid, $notes) {

        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 1 in the bike data
            $stmt = $this->db->prepare('UPDATE roomrentals SET status = "" WHERE rentid=?');
            $stmt->bind_param("i", $rentid);
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

    function checkOutRoom($roomid, $sid, $dateout, $timeout, $datein, $timein, $notes) {

        try {
            // Begin a transaction
            $this->db->autocommit(FALSE);

            // First set the availability = 0 in the data
            // We do NOTHING on the Rooms data!!!
            /*
            $stmt = $this->db->prepare('UPDATE equipmentdata SET qtyleft = qtyleft-1 WHERE equipmentid=?');
            $stmt->bind_param("i", $equipmentid);
            $stmt->execute();
            $stmt->close();
            */

            // Then we add an entry to the customers table (but first we check to see if user exists already or not)

            // Checking if user exists
            $stmt = $this->db->prepare('SELECT customerid, sid, name, email, phone, school FROM customers WHERE sid = ?');
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $stmt->bind_result($customerid, $sid, $sname, $customeremail, $customerphone, $school);
            $stmt->store_result(); // store result set into buffer

            // This means that no such user by the `sid` exists, we insert him in
            if ($stmt->num_rows == 0) {
                $stmt = $this->db->prepare('INSERT INTO customers (sid) VALUES (?)');
                $stmt->bind_param("i", $sid);
                $stmt->execute();
                $stmt->close();
            }

            // If the above != 0, it means that _some_ user with that sid
            // must exist, we just proceed with inserting into equipment
            // rentals

            // Finally we add an entry to the rentals table
            $stmt = $this->db->prepare('INSERT INTO roomrentals (roomid, sid, checkout, timeout, checkin, timein, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param("iisssss", $roomid, $sid, $dateout, $timeout, $datein, $timein, $notes);
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
    function printRoomModalWindow($roomid, $available, $name="", $rentid = -1, $notes ="") {

        // time variables for forms
        $inputDateOut = date('y-m-d');
        $inputTimeOut = date('h:i:s');
        $inputDateIn = date('y-m-d');
        $inputTimeIn = date('h:i:s');

        if ($available) {
            $checkout = '    <div class="modal fade" id="roomCheckout'.$roomid.'" tabindex="-1" role="dialog" aria-labelledby="roomCheckout'.$roomid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="roomLabel">Room Rental Checkout Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=room" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="roomid" class="col-sm-2 control-label">Room ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$roomid.'</p>
                                            </div>
                                            <input type="hidden" name="roomid" value="'.$roomid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputSID" class="col-sm-2 control-label">ID #</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputSID" id="inputSID" placeholder="Student ID#">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateOut" class="col-sm-2 control-label">Check Out Date</label>
                                            <div class="col-sm-10">
                                               <input type="text" class="form-control" name="inputDateOut" id="inputDateOut" placeholder="Date of Check out, format: yyyy-mm-dd">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeOut" class="col-sm-2 control-label">Check Out Time</label>
                                            <div class="col-sm-10">
                                               <input type="text" class="form-control" name="inputTimeOut" id="inputTimeOut" placeholder="Time of Check out, format: hh:mm:ss">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateIn" class="col-sm-2 control-label">Check In Date</label>
                                            <div class="col-sm-10">
                                               <input type="text" class="form-control" name="inputDateIn" id="inputDateIn" placeholder="Date of Check in, format: yyyy-mm-dd">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeIn" class="col-sm-2 control-label">Check In Time</label>
                                            <div class="col-sm-10">
                                               <input type="text" class="form-control" name="inputTimeIn" id="inputTimeIn" placeholder="Time of Check In, format: hh:mm:ss">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                               <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes on this reservation.">
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

                $modify = '    <div class="modal fade" id="roomModify'.$roomid.'" tabindex="-1" role="dialog" aria-labelledby="roomModify'.$roomid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="roomLabel">Edit room Data</h4>
                            </div> <!-- end modal header -->
                            <form action="modify.php?mode=room" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="roomid" class="col-sm-2 control-label">Room ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$roomid.'</p>
                                            </div>
                                            <input type="hidden" name="roomid" value="'.$roomid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputroomName" class="col-sm-2 control-label">Room Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputroomName" id="inputroomName" placeholder="room Name" value="'.$name.'">
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

                $delete = '    <div class="modal fade" id="roomDelete'.$roomid.'" tabindex="-1" role="dialog" aria-labelledby="roomDelete'.$roomid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="roomLabel">Confirm Deletion?</h4>
                            </div> <!-- end modal header -->
                            <form action="delete.php?mode=room" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">
                                        <h5>Are you sure you want to delete the following item?</h5>
                                        <div class="form-group">
                                            <label for="roomid" class="col-sm-4 control-label">Room ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$roomid.'</p>
                                            </div>
                                            <input type="hidden" name="roomid" value="'.$roomid.'" />
                                        </div>
                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-danger">Delete</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
            return $checkout . $modify . $delete;
        }
            // currently there's nothing to "check in", just a listing of all
            // current room reservations

        else {
                return '   <div class="modal fade" id="roomDeactivate'.$rentid.'" tabindex="-1" role="dialog" aria-labelledby="roomDeactivate'.$rentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="deactivateLabel">Room Rental Release Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkin.php?mode=room" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="rentid" class="col-sm-2 control-label">Rent ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$rentid.'</p>
                                            </div>
                                            <input type="hidden" name="rentid" value="'.$rentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-2 control-label">Room Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes" value="'.$notes.'">
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

    // function for adding rooms to the database
    function printAddRoomModalWindow() {

        return '    <div class="modal fade" id="roomInsert" tabindex="-1" role="dialog" aria-labelledby="roomInsert" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="roomLabel">Add Rooms To the Database</h4>
                            </div> <!-- end modal header -->
                            <form action="insert.php?mode=room" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="inputRoomName" class="col-sm-2 control-label">Room Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputRoomName" id="inputRoomName" placeholder="Room Name">
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
