<?php
include("../../mainfile.php");
//$GLOBALS['xoopsOption']['template_main'] ="eventview.html";

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

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];

if (isset($_POST['submit'])) $submit = $_POST['submit'];

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

include(XOOPS_ROOT_PATH."/header.php");

$person_handler= &xoops_getmodulehandler('person', 'oscmembership');

$event_handler = &xoops_getmodulehandler('event', 'oscattendance');
$attendance_handler = &xoops_getmodulehandler('attendance', 'oscattendance');

if(isset($attendanceid))
{
$attendance = $attendance_handler->get($attendanceid);  //only one     
}
else
{
$attendance = $attendance_handler->create();
}

if(isset($_POST['event'])) $attendance->assignVar('event_id',$_POST['event']);

if(isset($_POST['attendancedate']))
$attendance->assignVar('att_Date',$_POST['attendancedate']);

switch (true) 
{
	case($op=="save"):
//		$familydetail_handler->update($family);
//		$message=_oscmem_UPDATESUCCESS;
//		redirect_header("familydetailform.php?id=" . $familyid, 3, $message);

//		break;
	case($op=="create"):
		$attendance->assignVar('dateentered',date('y-m-d g:i:s'));
		$attendance->assignVar('enteredby',$xoopsUser->getVar('uid'));
		$uid=$xoopsUser->getVar('uid');
		$attendanceid= $attendance_handler->insert($attendance, $uid);	
		$message=_oscatt_CREATESUCCESS_attendance;
		redirect_header("index.php", 3, $message);
		break;
}

$attendance_dt= new XoopsFormTextDateSelect(_oscatt_date,'attendancedate', 15, '');

$events=$event_handler->getall();

$event_select = new XoopsFormSelect(_oscatt_eventname,'event','',1,false, 'event');

$events_array=array();
foreach($events as $event)
{
	$events_array[$event->getVar('event_id')]=$event->getVar('event_Name');

}

$event_select->addOptionArray($events_array);

$cartpersons = $person_handler->getCartc($xoopsUser->getVar('uid'));

$cart_tray = new XoopsFormElementTray('', '&nbsp;');
$memberresult="<table><th>" . _oscatt_lastname . "</th><th>" . _oscatt_firstname . "</th><th>" . _oscatt_address . "</th>";

$i=0;
foreach($cartpersons as $person)
{
	$memberresult .= "<tr><td>" . $person->getVar('lastname') . "</td>";
	$memberresult .= "<td>" . $person->getVar('firstname') . "</td>";
	$memberresult .="<td>" . $person->getVar('address1') . "&nbsp;" . $person->getVar('city') . ",&nbsp;" . $person->getVar('state') . "</td>";
	$memberresult .= "</tr>";
	$i++;
}
$memberresult .="<tr><td colspan=3><hr><br>" . _oscatt_totalcountis . "&nbsp;" . $i . "</td></tr>";
$memberresult .="</table>";

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "recordattendancesubmit", _osc_save, "submit");

$form = new XoopsThemeForm(_oscatt_recordattendance_TITLE, "recordattendanceform", "recordattendance.php", "post", true);
$form->addElement($attendance_dt);
$form->addElement($event_select);
$form->setRequired($attendance_dt);
$form->setRequired($event_select);

$member_label = new XoopsFormLabel(_oscatt_cartcontents, $memberresult);
$form->addElement($member_label);

$form->addElement($submit_button);

if(isset($action))
{
	if($action=="create")
	{
		$op_hidden = new XoopsFormHidden("op", "create");  //save operation
		$submit_button = new XoopsFormButton("", "recordattendancesubmit", _oscatt_create, "submit");
	}
	else
	{
		$op_hidden = new XoopsFormHidden("op", "save");  //save operation
		$submit_button = new XoopsFormButton("", "recordattendancesubmit", _oscatt_save, "submit");
	}
}
else $action="";

$form->addElement($op_hidden);

//xoops_cp_header();
$form->display();

/*
$xoopsTpl->assign("title",_oscatt_recordattendance_title);

$xoopsTpl->assign('oscatt_event_name',_oscatt_eventname);
$xoopsTpl->assign('oscatt_clearfilter',_oscatt_clearfilter);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscatt_view',_oscatt_view);
$xoopsTpl->assign('oscatt_edit',_oscatt_edit);
$xoopsTpl->assign('oscatt_addevent',_oscattendance_addevent);

$xoopsTpl->assign('events',$results);
$event=$results[0];

$totalloopcount=$event['totalloopcount'];

$xoopsTpl->assign('loopcount', $totalloopcount);
*/

include(XOOPS_ROOT_PATH."/footer.php");

?>