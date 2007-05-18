<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="attendance.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

include XOOPS_ROOT_PATH."/include/cp_functions.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/oscmembership/class/person.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';


include(XOOPS_ROOT_PATH."/header.php");

/*if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}
*/

$xoopsTpl->assign('title',_oscatt_indextitle); 


include(XOOPS_ROOT_PATH."/footer.php");

?>