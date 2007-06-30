<?php
/*
 *  Copyright 2007, Steve McAtee, Open Source Church Project
 ******************************************************************************/
include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/event.php";

if(!hasPerm("oscatt_view",$xoopsUser)) exit(_oscatt_access_denied);

$GLOBALS['xoopsOption']['template_main'] ="atthistory.html";

// Set the page title and include HTML header
//$sPageTitle = gettext("Directory reports");
//require "Include/Header.php";
include(XOOPS_ROOT_PATH."/header.php");

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$xoopsTpl->assign('title',_oscatt_eventhistoryattreporttitle);

$event_handler=&xoops_getmodulehandler('event','oscattendance');
$events=$event_handler->getall();

$form = new XoopsThemeForm("", "atthistoryform", "atthistoryreport_pdf.php", "post", true);

$event_select = new XoopsFormSelect(_oscatt_eventname,'sevent',"",1,false, 'class');
$event=$event_handler->create();

foreach($events as $event)
{
	$event_select->addOption($event->getVar('event_id'), $event->getVar('event_Name'));
}

$form->addElement($event_select);
$form->setRequired($event_select);

$submit_button = new XoopsFormButton("", "submit", _oscatt_submit, "submit");

$form->addElement($submit_button);

$rform= $form->render();

$xoopsTpl->assign('form',$rform);

include(XOOPS_ROOT_PATH."/footer.php");
?>
