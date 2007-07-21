<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="eventview.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscatt_accessdenied);
}



include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";


if(hasPerm("oscattendance_view",$xoopsUser)) $ispermview=true;
if(hasPerm("oscattendance_modify",$xoopsUser)) $ispermmodify=true;

if(!($ispermmodify==true || $ispermview==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscatt_accessdenied);
}



if (isset($_POST['submit'])) $submit = $_POST['submit'];

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$sort="";
$filter="";
if (isset($_GET['sort'])) $sort = $_GET['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];


include(XOOPS_ROOT_PATH."/header.php");

$event_handler = &xoops_getmodulehandler('event', 'oscattendance');

if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

if(isset($submit))
{
	switch($submit)
	{
	case _oscattendance_addevent:
		redirect_header("eventdetailform.php?action=create", 2, _oscatt_addevent_redirect);
		
		//do nothing
		break;
	}
}

$results = $event_handler->search($searcharray, $sort);
$xoopsTpl->assign("title",_oscatt_event_list);

$xoopsTpl->assign('oscatt_applyfilter',_oscatt_applyfilter);
$xoopsTpl->assign('oscatt_eventname',_oscatt_eventname);
$xoopsTpl->assign('oscatt_clearfilter',_oscatt_clearfilter);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscatt_view',_oscatt_view);
$xoopsTpl->assign('oscatt_edit',_oscatt_edit);
$xoopsTpl->assign('oscatt_addevent',_oscattendance_addevent);

$xoopsTpl->assign('events',$results);

$event=$results[0];

$totalloopcount=$event->getVar('totalloopcount');

$xoopsTpl->assign('loopcount', $totalloopcount);

include(XOOPS_ROOT_PATH."/footer.php");

?>