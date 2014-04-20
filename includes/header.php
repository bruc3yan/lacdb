<?php
/*
 *
 * Header File
 * 
 * 
 * Author:  Bruce Yan
 * Updated: April 2014
 * Notes:	Modular Header file include
 * 
 */

// Checks to see if the global page is set
$page = (isset($_GET['page']) ? htmlentities($_GET['page']) : 'main');

?>

<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>


		<a class="navbar-brand" href="./"><img src="images/logo.png" alt="LAC Database"></a>

		<div class="navbar-collapse collapse" id="navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li<?php echo ($page == 'main' ? ' class="active">' : ($page == '' ? ' class="active">' : '>')) ?>
					<a href="<?php echo "?page=main"; ?>">Overdues</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Check In <span class="caret"></a>
					<ul class="dropdown-menu">
						<li<?php echo ($page == 'inmudderbikes' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=inmudderbikes"; ?>"><span class="glyphicon glyphicon-plus"></span> Mudder Bikes</a>
						</li>
						<li<?php echo ($page == 'inequipment' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=inequipment"; ?>"><span class="glyphicon glyphicon-plus"></span> Equipment</a>
						</li>
						<li<?php echo ($page == 'inrooms' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=inrooms"; ?>"><span class="glyphicon glyphicon-plus"></span> Rooms</a>
						</li>
					</ul> <!-- end dropdown menu -->
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Check Out <span class="caret"></a>
					<ul class="dropdown-menu">
						<li<?php echo ($page == 'outmudderbikes' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=outmudderbikes"; ?>"><span class="glyphicon glyphicon-minus"></span> Mudder Bikes</a>
						</li>
						<li<?php echo ($page == 'outequipment' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=outequipment"; ?>"><span class="glyphicon glyphicon-minus"></span> Equipment</a>
						</li>
						<li<?php echo ($page == 'outrooms' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=outrooms"; ?>"><span class="glyphicon glyphicon-minus"></span> Rooms</a>
						</li>
					</ul> <!-- end dropdown menu -->
				</li>
				<li<?php echo ($page == 'lostandfound' ? ' class="active">' : '>') ?>
					<a href="<?php echo "?page=lostandfound"; ?>">Lost &amp; Found</a>
				</li>
			</ul> <!-- end nav -->
			<?php /* removed from nav bar
			<form class="navbar-form navbar-left">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search for items..." id="searchItemInput">
					<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
				</div>
			</form> <!-- end navbar-form -->
			*/ ?>	
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> My Account <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li<?php echo ($page == 'profile' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=profile"; ?>"><span class="glyphicon glyphicon-wrench"></span> Edit Profile</a>
						</li>
						<li<?php echo ($page == 'manageusers' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=manageusers"; ?>"><span class="glyphicon glyphicon-pencil"></span> Manage Users</a>
						</li>
						<li<?php echo ($page == 'logout' ? ' class="active">' : '>') ?>
							<a href="<?php echo "?page=logout"; ?>"><span class="glyphicon glyphicon-off"></span> Logout</a>
						</li>
					</ul> <!-- end dropdown menu -->
				</li>
			</ul> <!-- end nav pull-right -->

		</div> <!-- end nav-collapse -->
	</div> <!-- end container -->
</div> <!-- end navbar -->
