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

if(isset($_POST['barcodes'])) $barcodes=$_POST['barcodes'];

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
		$resultbarcodes= $attendance_handler->insertbarcodes($attendance, $uid,$barcodes);	
		
		if(isset($resultbarcodes))
		{
			$alert=_oscatt_invalidbarcodes;
			$barcodes=$resultbarcodes;
	//		redirect_header("index.php", 3, $message);
		}
		else
		{		
			$message=_oscatt_CREATESUCCESS_attendance;
		}
			
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

$barcode_text = new XoopsFormTextArea(_oscatt_barcodes . "<br>" . $alert, "barcodes",$barcodes,$rows=10,$cols=15);

$op_hidden = new XoopsFormHidden("op", "create");  //save operation
$submit_button = new XoopsFormButton("", "barcodeattendancesubmit", _oscatt_create, "submit");

$form = new XoopsThemeForm(_oscatt_barcodeattendance_TITLE, "barcodeattendanceform", "barcodeattendance.php", "post", true);
$form->addElement($attendance_dt);
$form->addElement($event_select);
$form->addElement($barcode_text);
$form->setRequired($attendance_dt);
$form->setRequired($event_select);
$form->setRequired($barcode_text);


$form->addElement($submit_button);

$op_hidden = new XoopsFormHidden("op", "create");  //save operation
$submit_button = new XoopsFormButton("", "barcodeattendancesubmit", _oscatt_create, "submit");


$form->addElement($op_hidden);

//xoops_cp_header();
if(strlen($message)>0)
	echo $message;
	
$form->display();


include(XOOPS_ROOT_PATH."/footer.php");

?>