<?php
// ------------------------------------------------------------------------- //
// oscdir
//Module 
//XOOPS - PHP Content Management System
//http://www.xoops.org/>
//
// Author: Steve McAtee, OSC Project//
// Purpose: Module to wrap html or php-content into nice Xoops design.	     //
// email: steve@churchledger.com
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH . '/modules/oscmembership/class/person.php';

function oscmem_search($queryarray, $andor, $limit, $offset, $userid)
{
	global $xoopsDB;
	$ret = array();
	if ( $userid != 0 ) {
		return $ret;
	}
	
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
	$results = $person_handler->modsearch($queryarray);
	
	
	$result = $xoopsDB->query($sql,$limit,$offset);
	$i = 0;
 	while ( $myrow = $xoopsDB->fetchArray($result) ) {
		$ret[$i]['image'] = "fc.gif";
		$ret[$i]['link'] = "index.php?id=".$myrow['id']."";
		$ret[$i]['title'] = $myrow['title'];
		$ret[$i]['time'] = $myrow['date'];
		$ret[$i]['uid'] = $myrow['submitter'];
		$i++;
	}
	return $ret;
}
?>