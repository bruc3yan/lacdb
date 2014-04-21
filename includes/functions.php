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
    	// Print all items in database 
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
			echo "			    	</tr>";
			echo "			    	</thead>";
			echo "			    	<tbody>";
	        
			// Print table data by looping through all the rows
	        while ($stmt->fetch()) {
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
				echo "			    	</tr>";
	        }

	        // Close table
			echo "			    	</tbody>";
			echo "				</table>";
			echo "			</div>";
    	}
        $stmt->close();
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