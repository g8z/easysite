<?php

$manage = true;
include_once( '../config.php' );

$table = MODULECATEGORIES_TABLE;
$itemsTable = REALTYITEMS_TABLE;

$startid = 0;
$startLevel = 0;
$levelLimit = 0;

// determine which module we are editing by analyzing the directory structure

$moduleInfo = $db->getRow( "select * from $table where site_key = '$site' and module_key = 'realty'" );

$add_fields = array( 'site_key', 'module_key' );
$add_values = array( $site, 'realty' );

//include_once( FULL_PATH . 'init_bottom.php' );

// if we are running a report, determine which report we are attempting to run

if ( $type ) {
	// get the filter criteria

	$filters = array( 'id > 0' );

	// get the sort criteria

	$sorts = array();

	if ( $report == 'agent' )
		$sorts [] = 'user_id';
	else if ( $report == 'category' )
		$sorts [] = 'cat_id';

	switch( $type ) {
		case 'summary':

			// get all groups

			$sql1 = 'select id, name from ' . GROUPS_TABLE . " where site_key = '$site' order by name";

			$allMembers = $db->getAll( $sql1 );

			foreach( $allMembers as $index => $data ) {

				$row = array();

				$group = $data[id];

				$active = $db->getOne( 'select count(id) from ' . USERS_TABLE . " where status = 'active' and group_id = '$group' and site_key = '$site'" );

				$pending = $db->getOne( 'select count(id) from ' . USERS_TABLE . " where status = 'pending' and group_id = '$group' and site_key = '$site'" );

				$suspended = $db->getOne( 'select count(id) from ' . USERS_TABLE . " where status = 'suspended' and group_id = '$group' and site_key = '$site'" );

				$terminated = $db->getOne( 'select count(id) from ' . USERS_TABLE . " where status = 'terminated' and group_id = '$group' and site_key = '$site'" );

				$row['name'] = $data[name];
				$row['active'] = $active;
				$row['pending'] = $pending;
				$row['suspended'] = $suspended;
				$row['terminated'] = $terminated;

				$totalActive += $active;
				$totalSuspended += $suspended;
				$totalPending += $pending;
				$totalTerminated += $terminated;

				$allMembers[$index] = $row;
			}

			$t->assign( 'headers', array( 'Membership Type', 'Active', 'Pending', 'Suspended', 'Terminated' ) );

			$t->assign( 'reportData', $allMembers );

			$t->assign( 'title', 'Membership Summary - by Membership Status' );

			// assign totals to template

			$t->assign( 'totalActive', $totalActive );
			$t->assign( 'totalPending', $totalPending );
			$t->assign( 'totalSuspended', $totalSuspended );
			$t->assign( 'totalTerminated', $totalTerminated );

			break;

		case 'detail':
		case 'roster':

			$usersTable = USERS_TABLE;
			$groupsTable = GROUPS_TABLE;

			// sort is pased in by GET

			if ( $type == 'roster' ) {
				$statusOption = " and a.status = 'active' ";
				$sort = 'last_name';
			}
			else
				$statusOption = '';

			if ( $sort == 'group' )
				$sort1 = 'group_name';
			else
				$sort1 = "a.$sort";

			$temp = $db->getAll( "select a.company, a.last_name, a.first_name, a.status, b.name as group_name from ".USERS_TABLE." a, ".GROUPS_TABLE." b where a.group_id = b.id $statusOption order by $sort1" );


			// depending on the type of report, show specific fields only

			$allMembers = array();

			foreach( $temp as $index => $data ) {

/*				$row = array();

				if ( $sort == 'last_name' ) {
					$row[] = $data[last_name] . ', ' . $data[first_name];

					if ( $type != 'roster' )
						$row['status'] = $data[status];

					$row['group_name'] = $data[group_name];
					$row['campany'] = $data[company];
				}
				else if ( $sort == 'company' ) {
					$row[] = $data[company];
					$row[] = $data[last_name] . ', ' . $data[first_name];
					$row[] = $data[group_name];
					$row[] = $data[status];
				}
				else if ( $sort == 'group' ) {
					$row[] = $data[group_name];
					$row[] = $data[status];
					$row[] = $data[last_name] . ', ' . $data[first_name];
					$row[] = $data[company];
				}
				else if ( $sort == 'status' ) {
					$row[] = $data[status];
					$row[] = $data[group_name];
					$row[] = $data[last_name] . ', ' . $data[first_name];
					$row[] = $data[company];
				}*/

				$allMembers[] = $data;
			}

			if ( $sort == 'last_name' ) {

				if ( $type == 'roster' ) {
					$t->assign( 'title', 'Membership Roster (Active Users Only)' );
					$t->assign( 'headers', array( 'Name', 'Membership Type', 'Company Name' ) );
				}
				else {
					$t->assign( 'title', 'Membership Detail - by Last Name' );
					$t->assign( 'headers', array( 'Name', 'Status', 'Membership Type', 'Company Name' ) );
				}
			}
			else if ( $sort == 'company' ) {
				$t->assign( 'title', 'Membership Detail - by Company' );
				$t->assign( 'headers', array( 'Company', 'Name', 'Membership Type', 'Status' ) );
			}
			else if ( $sort == 'group' ) {
				$t->assign( 'title', "Membership Detail - by Membership Type ('Group')" );
				$t->assign( 'headers', array( 'Membership Type', 'Status', 'Name', 'Company' ) );
			}
			else if ( $sort == 'status' ) {
				$t->assign( 'title', 'Membership Detail - by Status' );
				$t->assign( 'headers', array( 'Status', 'Membership Type', 'Name', 'Company' ) );
			}

			$t->assign( 'reportData', $allMembers );

			break;
	}

	$t->assign( 'numCols', sizeof( $row ) );
	$t->assign( 'numRows', sizeof( $allMembers ) );

	// the following headers will ensure that the data is directly downloaded,
	// rather than displayed in the web browser as a page
	/*
	session_cache_limiter("");
	header("Cache-Control: public");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=contacts.$ext");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");
	echo implode( "\n", $downloadData );
	*/

	// insert the body content

	$t->display( 'popupHeader.tpl' );
	$t->display( 'modules/realty/manage/reportDisplay.tpl' );
	$t->display( 'popupFooter.tpl' );
}
else {

	// insert the body content
	$t->assign( 'bodyTemplate', 'modules/realty/manage/report.tpl' );

	$t->display( $templateName );
}

?>