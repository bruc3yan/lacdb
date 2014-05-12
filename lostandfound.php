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
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#lostitems" data-toggle="tab">Lost Items</a></li>
                    <li><a href="#founditems" data-toggle="tab">Found Items</a></li>
                    <li><a href="#history" data-toggle="tab">History</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="lostitems">
                        <?php
                            // create a new instance of the Records class
                            $record = new Records;
                            // list all the items in tabular format
                            $record->listLostAndFound(0, 0);
                        ?>
                    </div> <!-- end tab pane -->
                    <div class="tab-pane fade" id="founditems">
                        <?php
                            $record->listLostAndFound(0, 1);
                        ?>
                    </div> <!-- end tab pane -->
                    <div class="tab-pane fade" id="history">
                        <?php
                            $record->listLostAndFound(0, -1);
                        ?>
                    </div> <!-- end tab pane -->
                </div> <!-- end tab content -->
            </div> <!-- end tabbable -->
        </div> <!-- end col 12 -->
     </div> <!-- end row lostandfound -->
</div> <!-- end container -->
