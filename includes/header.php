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
 ?>

<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>


		<a class="navbar-brand" href="./"><img src="images/logo.png" alt="LAC Database"></a>
<?php //$page == 'main' ? echo ' class="active">' : echo '>' ?>
		<div class="navbar-collapse collapse" id="navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active">
					<a href="<?php echo "?page=main"; ?>">Overdues</a>
				</li>
				<li>
					<a href="<?php echo "?page=checkin"; ?>">Check In</a>
				</li>
				<li>
					<a href="<?php echo "?page=checkout"; ?>">Check Out</a>
				</li>
				<li>
					<a href="<?php echo "?page=lostandfound"; ?>">Lost &amp; Found</a>
				</li>
			</ul> <!-- end nav -->
			<?php /*
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
						<li>
							<a href="<?php echo "?page=profile"; ?>"><span class="glyphicon glyphicon-wrench"></span> Edit Profile</a>
						</li>
						<li>
							<a href="<?php echo "?page=manageusers"; ?>"><span class="glyphicon glyphicon-pencil"></span> Manage Users</a>
						</li>
						<li>
							<a href="<?php echo "?page=logout"; ?>"><span class="glyphicon glyphicon-off"></span> Logout</a>
						</li>
				</ul> <!-- end dropdown menu -->
				</li>
			</ul> <!-- end nav pull-right -->

		</div> <!-- end nav-collapse -->
	</div> <!-- end container -->
</div> <!-- end navbar -->
