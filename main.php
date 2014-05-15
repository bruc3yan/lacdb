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
	<h2>Main Page <small>Current List of daily LAC Staff Tasks</small></h2>
	<p class="lead">
		Be sure to check back often for daily tasks!
	</p>
</div>
<div class="container">
<?php /*
	<h3> Testing code </h3>

<!-- Button trigger modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#equipmentCheckout9">
  Checkout
</button>
<!-- Button trigger modal -->
<button class="btn btn-warning" data-toggle="modal" data-target="#equipmentCheckin9">
  Checkin
</button>
<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#equipmentModify9">Modify</button>
<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#equipmentDelete9">Delete</button> */ ?>

<?php /*
$equipmentid = 9;
$rentid = 10;
$sname = "check in name";
$inputDateOut = date('y-m-d');
$inputTimeOut = date('h:i:s');
$inputDateIn = date('y-m-d');
$inputTimeIn = date('h:i:s');
$dateout = "today's date";
$timeout = "check out time";




echo'    <div class="modal fade" id="equipmentCheckout'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentCheckout'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Equipment Rental Checkout Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkout.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-2 control-label">Equipment ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
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
                                            <label for="inputSchool" class="col-sm-2 control-label">School</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputSchool" id="inputSchool" placeholder="School">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateOut" class="col-sm-2 control-label">Check Out Date</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$inputDateOut.'</p>
                                            </div>
                                            <input type="hidden" name="inputDateOut" value="'.$inputDateOut.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeOut" class="col-sm-2 control-label">Check Out Time</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$inputTimeOut.'</p>
                                            </div>
                                            <input type="hidden" name="inputTimeOut" value="'.$inputTimeOut.'" />
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

echo'    <div class="modal fade" id="equipmentModify'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentModify'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Edit Equipment Data</h4>
                            </div> <!-- end modal header -->
                            <form action="modify.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-2 control-label">Equipment ID</label>
                                            <div class="col-sm-10">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentName" class="col-sm-2 control-label">Equipment Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentName" id="inputEquipmentName" placeholder="Equipment Name" value="'.$equipmentName.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputQtyleft" class="col-sm-2 control-label">Quantity</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputQtyleft" id="inputQtyleft" placeholder="Quantity" value="'.$qtyleft.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentOwner" class="col-sm-2 control-label">Owner</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentOwner" id="inputEquipmentOwner" placeholder="Owner" value="'.$equipmentOwner.'">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEquipmentNotes" class="col-sm-2 control-label">Notes</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="inputEquipmentNotes" id="inputEquipmentNotes" placeholder="Notes" value="'.$equipmentNotes.'">
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

echo'    <div class="modal fade" id="equipmentDelete'.$equipmentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentDelete'.$equipmentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Confirm Deletion?</h4>
                            </div> <!-- end modal header -->
                            <form action="delete.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">
                                        <h5>Are you sure you want to delete the following item?</h5>
                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-4 control-label">Equipment ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
                                        </div>
                                </div> <!-- end modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="save" class="btn btn-danger">Delete</button>
                                </div> <!-- end modal-footer -->
                            </form>
                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end my myModal -->';


echo'   <div class="modal fade" id="equipmentCheckin'.$rentid.'" tabindex="-1" role="dialog" aria-labelledby="equipmentCheckin'.$rentid.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="equipmentLabel">Equipment Rental Checkin Form</h4>
                            </div> <!-- end modal header -->
                            <form action="checkin.php?mode=equipment" method="post" class="form-horizontal" role="form">
                                <div class="modal-body">

                                        <div class="form-group">
                                            <label for="rentid" class="col-sm-4 control-label">Rent ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$rentid.'</p>
                                            </div>
                                            <input type="hidden" name="rentid" value="'.$rentid.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="equipmentid" class="col-sm-4 control-label">Equipment ID</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$equipmentid.'</p>
                                            </div>
                                            <input type="hidden" name="equipmentid" value="'.$equipmentid.'" />
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
                                            <label for="school" class="col-sm-4 control-label">School</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$school.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="dateout" class="col-sm-4 control-label">Check Out Date</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$dateout.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="timeout" class="col-sm-4 control-label">Check Out Time</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">'.$timeout.'</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDateIn" class="col-sm-4 control-label">Check In Time</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$inputDateIn.'</p>
                                            </div>
                                            <input type="hidden" name="inputDateIn" value="'.$inputDateIn.'" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inputTimeIn" class="col-sm-4 control-label">Check In Time</label>
                                            <div class="col-sm-8">
                                               <p class="form-control-static">'.$inputTimeIn.'</p>
                                            </div>
                                            <input type="hidden" name="inputTimeIn" value="'.$inputTimeIn.'" />
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
*/
?>

</div> <!-- end container -->
