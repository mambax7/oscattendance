<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="oscattreports.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

/*
//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule)) {
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}
*/

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}


$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$years=$donation_handler->getDonationyears();
include(XOOPS_ROOT_PATH."/header.php");

$xoopsTpl->assign('title',_oscatt_reporttitle); 
$xoopsTpl->assign('oscatt_eventatthistoryreport2year',_oscatt_eventatthistoryreport2year); 
$xoopsTpl->assign('oscatt_membersnoatt6month',_oscatt_membersnoatt6month);
/*
$xoopsTpl->assign('OSCMEM_csvexport',_oscmem_csvexport);
$xoopsTpl->assign('oscmem_csvimport',_oscmem_csvimport);
*/

include(XOOPS_ROOT_PATH."/footer.php");
?>