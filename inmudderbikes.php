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
			<div class="tabbable">
				<ul class="nav nav-tabs">
			  		<li class="active"><a href="#unreturned" data-toggle="tab">Currently Checked Out</a></li>
			  		<li><a href="#history" data-toggle="tab">History</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="unreturned">
						<?php
				 			// create a new instance of the Records class
							$record = new Records;
							// list all the rentals in tabular format
							$record->listMudderBikeRentals(0, 1, 0);
						?>
					</div> <!-- end tab pane -->
					<div class="tab-pane fade" id="history">
						<?php
							$record->listMudderBikeRentals(0, 1, 1);
						?>
					</div> <!-- end tab pane -->
				</div> <!-- end tab content -->
			</div> <!-- end tabbable -->
	 	</div> <!-- end col 12 -->
	 </div> <!-- end row inmudderbikes -->
</div> <!-- end container -->