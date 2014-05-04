<?php
/*
 *
 * Check out rooms
 * 
 * 
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular check out page file
 * 
 */
 ?>
<div class="page-header">
	<h2>LAC Room Reservations <small>Check out.</small></h2>
	<p class="lead">
		Check out room reservations
	</p>
</div>
<div class="container">
	<h3> Display Check out Code here </h3>
	<div class="row" id="outrooms">
<!-- 	 	<div class="col-12">
			<div class="tabbable">
				<ul class="nav nav-tabs">
			  		<li class="active"><a href="#unreturned" data-toggle="tab">Currently Checked Rooms</a></li>
			  		<li><a href="#history" data-toggle="tab">History</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="unreturned">
						<?php
				 			// create a new instance of the Records class
							$record = new Records;
							// list all the rentals in tabular format
							$record->listCheckedRooms(0, 1, 0);
						?>
					</div> <!-- end tab pane -->
					<!--
					<div class="tab-pane fade" id="history">
						<?php
							$record->listCheckedRooms(0, 1, 1);
						?>

					</div> <!-- end tab pane -->
					<!--
				</div> <!-- end tab content -->
				<!--
			</div> <!-- end tabbable -->
			<!--
	 	</div> <!-- end col 12 -->
	 	<!--
	 </div> <!-- end row inmudderbikes --> -->
</div> <!-- end container -->