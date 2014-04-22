<?php
/*
 *
 * Main page File
 *
 *
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular main page file for things to display on home page
 *
 */
 ?>
<div class="page-header">
	<h2>Overdues <small>Current overdues</small></h2>
	<p class="lead">
		Current overdues are listed here in reverse chronological order--meaning the most overdue items are always shown at the top.  Clicking on any of the columns will auto-sort the overdues accordingly.
	</p>
</div>
<div class="container">
	<h3> Display Check in Code here </h3>

<?php /*
<!-- Button trigger modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#bikeCheckout1234">
  Checkout
</button>
<!-- Button trigger modal -->
<button class="btn btn-warning" data-toggle="modal" data-target="#bikeCheckin1234">
  Checkin
</button>
<?php
$bikeid = 1234;


echo'	<div class="modal fade" id="bikeCheckout'.$bikeid.'" tabindex="-1" role="dialog" aria-labelledby="bikeCheckout'.$bikeid.'" aria-hidden="true">
    				<div class="modal-dialog">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    							<h4 class="modal-title" id="bikeLabel">Mudder Bike Checkout Form</h4>
    						</div> <!-- end modal header -->
    						<form action="checkout.php?mode=mudderbike" method="post" class="form-horizontal" role="form">
    							<div class="modal-body">

    									<div class="form-group">
    									    <label for="bikeid" class="col-sm-2 control-label">Bike ID</label>
    									    <div class="col-sm-10">
    									       <p class="form-control-static">'.$bikeid.'</p>
    								    	</div>
                                            <input type="hidden" name="bikeid" value="'.$bikeid.'" />
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputName" class="col-sm-2 control-label">Name</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Name">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputSID" class="col-sm-2 control-label">ID #</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputSID" id="inputSID" placeholder="Student ID#">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputWaiver" class="col-sm-2 control-label">Waiver</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputWaiver" id="inputWaiver" placeholder="Waiver">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputNotes" class="col-sm-2 control-label">Notes</label>
    								    	<div class="col-sm-10">
    								      		<input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
    								    	</div>
    								  	</div>

    							</div> <!-- end modal-body -->
    							<div class="modal-footer">
    								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    								<button type="submit" name="save" class="btn btn-primary">Save changes</button>
    							</div> <!-- end modal-footer -->
    						</form>
    					</div> <!-- end modal content -->
    				</div> <!-- end modal dialog -->
    			</div> <!-- end my myModal -->';


echo'   <div class="modal fade" id="bikeCheckin'.$bikeid.'" tabindex="-1" role="dialog" aria-labelledby="bikeCheckin'.$bikeid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="bikeLabel">Mudder Bike Checkin Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=mudderbike" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="rentid" class="col-sm-4 control-label">Rent ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$rentid.'</p>
                                            </div>
                                            <input type="hidden" name="rentid" value="'.$rentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="bikeid" class="col-sm-4 control-label">Bike ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$bikeid.'</p>
                                            </div>
                                            <input type="hidden" name="bikeid" value="'.$bikeid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="sname" class="col-sm-4 control-label">Name</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$sname.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sid" class="col-sm-4 control-label">ID #</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$sid.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waiver" class="col-sm-4 control-label">Waiver</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$waiver.'</p>
                                            </div>
                                        </div>
    								  	<div class="form-group">
    								    	<label for="inputDateIn" class="col-sm-4 control-label">Date In</label>
    								    	<div class="col-sm-8">
    								      		<input type="text" class="form-control" name="inputDateIn" id="inputDateIn" placeholder="Date of Return, format: mm/dd/yy">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputStatus" class="col-sm-4 control-label">Status</label>
    								    	<div class="col-sm-8">
    								      		<input type="text" class="form-control" name="inputStatus" id="inputStatus" placeholder="i.e. Returned">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputkeyreturnedto" class="col-sm-4 control-label">Key Returned To</label>
    								    	<div class="col-sm-8">
    								      		<input type="text" class="form-control" name="inputkeyreturnedto" id="inputkeyreturnedto" placeholder="LAC Staff Name">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputLate" class="col-sm-4 control-label">Late</label>
    								    	<div class="col-sm-8">
    								      		<input type="text" class="form-control" name="inputLate" id="inputLate" placeholder="Manual Entry of days late">
    								    	</div>
    								  	</div>
    								  	<div class="form-group">
    								    	<label for="inputPaid" class="col-sm-4 control-label">Paid / Collected By</label>
    								    	<div class="col-sm-8">
    								      		<input type="text" class="form-control" name="inputPaid" id="inputPaid" placeholder="LAC Staff Name">
    								    	</div>
    								  	</div>
                                        <div class="form-group">
                                            <label for="inputNotes" class="col-sm-4 control-label">Notes</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="inputNotes" id="inputNotes" placeholder="Notes">
                                            </div>
                                        </div>

                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';
                ?>
*/ ?>
</div> <!-- end container -->
