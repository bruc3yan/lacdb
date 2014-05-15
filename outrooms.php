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
    <div class="row" id="outrooms">
        <div class="col-sm-8">
            <?php
                // create a new instance of the Records class
                $record = new Records;
                // list all the rentals in tabular format
                $record->listRoomsData(0, 1);
            ?>
        </div> <!-- end col 8 -->
     </div> <!-- end row outrooms -->
</div> <!-- end container -->
