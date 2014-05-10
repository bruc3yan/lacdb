<?php
/*
 *
 * Check out equipment
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular check out page file
 *
 */
 ?>
<div class="page-header">
	<h2>Equipment <small>Check out.</small></h2>
	<p class="lead">
		View current and past list of LAC &amp; Club Equipment.
	</p>
</div>
<div class="container">
    <div class="row" id="outequipments">
        <div class="col-sm-8">
            <?php
                // create a new instance of the Records class
                $record = new Records;
                // list all the rentals in tabular format
                $record->listEquipmentData(0, 1);
            ?>
        </div> <!-- end col 8 -->
     </div> <!-- end row outequipments -->
</div> <!-- end container -->
