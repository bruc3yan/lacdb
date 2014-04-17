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

class Navigation {

	private $allowedPages = array();	// List of allowed pages
	private $requestedPage;		// Page that's requested by URL

	function __construct($rp) {
		$this->setRequestedPage($rp); // get the reques
		$this->setAllowedPages(); // set the available pages
	}

	// returns the requested page with the correct file extension
	public function fetchPage() {
		return $this->getAllowedPages($this->getRequestedPage());
	}

	// returns the pages allowed by the list, 
	// if not found, go back to home
	public function getAllowedPages($request) {
		if (isset($this->allowedPages[$request])) {
			return $this->allowedPages[$request];
		} else {
			return $this->allowedPages['main'];
		}		
	}

	// return the requested page
	public function getRequestedPage() {
		return $this->requestedPage;
	}

	// Initialize our array
	public function setAllowedPages() {
		$this->allowedPages = array (
	        'main' => './main.php',
	        'checkin' => './checkin.php',
	        'checkout' => './checkout.php',
	        'lostandfound' => './lostandfound.php',
	        'profile' => './profile.php',
	        'manageusers' => './manageusers.php',
	        'logout' => './logout.php'
		);
	}

	// Sets the page
	public function setRequestedPage($rp) {
		if (isset($rp)) {
			$this->requestedPage = trim(strtolower($rp));
		} else {
			$this->requestedPage = 'main';
		}
		
	}

} // end navigation

?>