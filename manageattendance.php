<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="manageattendance.html";

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

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
if (isset($_POST['loopcount'])) $totalloopcount = $_POST['loopcount'];

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
if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

if(isset($submit))
{
	switch($submit)
	{
		case _oscatt_addatttocart:
		//Add selected attendance to cart
			for($i=0;$i<$totalloopcount+1;$i++)
			{
				if (isset($_POST['chk' . $i]))
				{
					$id=$_POST['chk' . $i];
					$uid=$xoopsUser->getVar('uid');
					$attendance=$attendance_handler->get($id);
					$attendance_handler->addtoCart($attendance,$uid);	
				}
			}
			//redirect to view cart
			redirect_header(XOOPS_URL . "/modules/oscmembership/viewcart.php",3,_oscmem_addedtocart);
		
		break;
		
		case _oscatt_delete:
			for($i=0;$i<$totalloopcount+1;$i++)
			{
				if (isset($_POST['chk' . $i]))
				{
					$id=$_POST['chk' . $i];
					$attendance=$attendance_handler->get($id);
					$attendance_handler->delete($attendance);	
				}
			}
			
			redirect_header("manageattendance.php",3,_oscatt_delete_success);
	}
}


$results = $attendanceev_handler->search($searcharray, $sort);
$xoopsTpl->assign("title",_oscatt_manageattendance_list);

$xoopsTpl->assign("oscatt_eventname",_oscatt_eventname);
$xoopsTpl->assign('oscatt_applyfilter',_oscatt_applyfilter);
$xoopsTpl->assign('oscatt_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscatt_view',_oscmem_view);
$xoopsTpl->assign('oscatt_edit',_oscmem_edit);
$xoopsTpl->assign("oscatt_count",_oscatt_count);
$xoopsTpl->assign('attendanceevents',$results);
$xoopsTpl->assign('oscatt_addatttocart',_oscatt_addatttocart);
$xoopsTpl->assign('oscatt_date',_oscatt_date);
$xoopsTpl->assign('oscatt_delete',_oscatt_delete);

$attendanceevent=$results[0];
if(isset($attendanceevent))
{
$totalloopcount=$attendanceevent->getVar('totalloopcount');
$xoopsTpl->assign('loopcount', $totalloopcount);
}
else $xoopsTpl->assign('loopcount',0);


include(XOOPS_ROOT_PATH."/footer.php");

?>