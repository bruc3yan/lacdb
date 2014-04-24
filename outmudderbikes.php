<?php
/*
 *
 * Check out mudder bikes
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular check out page file
 *
 */

 ?>
<div class="page-header">
	<h2>Mudder Bikes <small>Check out.</small></h2>
	<p class="lead">
		Check Mudder bikes status and edit notes!
	</p>
</div>
<div class="container">
	<div class="row" id="outmudderbikes">
	 	<div class="col-sm-8">
	 		<?php
	 			// create a new instance of the Records class
				$record = new Records;
				// list all the rentals in tabular format
				$record->listMudderBikeData(0, 1);
			?>
	 	</div> <!-- end col 12 -->
	 </div> <!-- end row inmudderbikes -->
</div> <!-- end container -->
