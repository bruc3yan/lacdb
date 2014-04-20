<?php
/*
 *
 * Check in mudder bikes
 * 
 * 
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular check in page file
 * 
 */

 ?>
<div class="page-header">
	<h2>Mudder Bikes <small>Check in.</small></h2>
	<p class="lead">
		Check in mudder bikes and make comments.
	</p>
</div>
<div class="container">
	<div class="row" id="inmudderbikes">
	 	<div class="col-12">
	 		<?php
	 			// create a new instance of the Records class
				$record = new Records;
				// list all the rentals in tabular format
				$record->listMudderBikeRentals(0, 1);
			?>
	 	</div> <!-- end col 12 -->
	 </div> <!-- end row inmudderbikes -->
</div> <!-- end container -->