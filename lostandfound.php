<?php
/*
 *
 * Lost and Found page File
 * 
 * 
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular lost and found page file for things to display on home page
 * 
 */

 ?>
<div class="page-header">
	<h2>Lost &amp; Found <small>Items that need to be picked up.</small></h2>
	<p class="lead">
		Current list of items in LAC's "Lost and Found" database.
	</p>
</div>
<div class="container">
	<div class="row" id="lostandfound">
	 	<div class="col-12">
	 		<?php
	 			// create a new instance of the Records class
				$record = new Records;
				// list all the items in tabular format
				$record->listLostAndFound(0);
			?>
	 	</div> <!-- end col 12 -->
	 </div> <!-- end row lostandfound -->
</div> <!-- end container -->