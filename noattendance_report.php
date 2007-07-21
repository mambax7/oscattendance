<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="noattendance_report.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscatt_accessdenied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/attendanceevent.php";

if(hasPerm("oscattendance_view",$xoopsUser)) $ispermview=true;
if(hasPerm("oscattendance_modify",$xoopsUser)) $ispermmodify=true;

if(!($ispermmodify==true || $ispermview==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscatt_accessdenied);
}

$days=90;  //set default
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_POST['loopcount'])) $totalloopcount = $_POST['loopcount'];
if (isset($_POST['days'])) $days=$_POST['days'];


if (isset($_POST['submit'])) $submit = $_POST['submit'];

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

$sort="";
$filter="";
if (isset($_GET['sort'])) $sort = $_GET['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];


include(XOOPS_ROOT_PATH."/header.php");

$attendance_handler = &xoops_getmodulehandler('attendance','oscattendance');
$attendanceev_handler = &xoops_getmodulehandler('attendanceevent', 'oscattendance');
$attperson_handler = &xoops_getmodulehandler('attperson','oscattendance');


if(isset($submit))
{
	switch($submit)
	{
		case _oscatt_addtocart:
		//Add selected attendance to cart
			for($i=0;$i<$totalloopcount+1;$i++)
			{
				if (isset($_POST['chk' . $i]))
				{
					$id=$_POST['chk' . $i];
					$uid=$xoopsUser->getVar('uid');
					$attperson_handler->addtoCart($id,$uid);
				}
			}
			//redirect to view cart
			redirect_header(XOOPS_URL . "/modules/oscmembership/viewcart.php",3,_oscatt_addedtocart);
		
		break;

		case _oscatt_clearfilter:
			$filter="";
		break;
		
	}
}


if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

$persons=$attperson_handler->getNoAttendees($days,$searcharray,$sort);

//$results = $attendanceev_handler->search($searcharray, $sort);
$xoopsTpl->assign("title",_oscatt_noattendance_list);

$xoopsTpl->assign("oscatt_name",_oscatt_name);
$xoopsTpl->assign("oscatt_address",_oscatt_address);
$xoopsTpl->assign("oscatt_phone",_oscatt_phone);
$xoopsTpl->assign("oscatt_eventname",_oscatt_eventname);
$xoopsTpl->assign('oscatt_applyfilter',_oscatt_applyfilter);
$xoopsTpl->assign('oscatt_clearfilter',_oscatt_clearfilter);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscatt_view',_oscmem_view);
$xoopsTpl->assign('oscatt_edit',_oscmem_edit);
$xoopsTpl->assign("oscatt_count",_oscatt_count);
$xoopsTpl->assign('persons',$persons);
$xoopsTpl->assign('oscatt_addtocart',_oscatt_addtocart);
$xoopsTpl->assign('oscatt_lasteventdate',_oscatt_lasteventdate);
$xoopsTpl->assign('oscatt_applydays',_oscatt_applydays);
$xoopsTpl->assign('days',$days);

$xoopsTpl->assign('filter',$filter);

if(count($persons)>0)
{
	$firstitem=$persons[0];
	$totalloopcount=$firstitem->getVar('totalloopcount');
	$xoopsTpl->assign('loopcount', $totalloopcount);
}


include(XOOPS_ROOT_PATH."/footer.php");

?>