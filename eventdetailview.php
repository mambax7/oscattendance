<?php
// $Id: eventdetailview.php, 2007/05/17 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Steve McAtee                                          //
// URL: http://www.churchledger.com, http://www.xoops.org/
// Project: The XOOPS Project, The Open Source Church project (OSC)
// ------------------------------------------------------------------------- //
include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");
//include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscatt_accessdenied);
}


if(!hasPerm("oscattendance_view",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscatt_accessdenied);

//determine action
$op = '';
$confirm = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $eventid=$_GET['id'];
if (isset($_POST['id'])) $eventid=$_POST['id'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];

$myts = &MyTextSanitizer::getInstance();
$eventdetail_handler = &xoops_getmodulehandler('event', 'oscattendance');
    
    
    if(isset($eventid))
    {
	$event = $eventdetail_handler->get($eventid);  //only one     
    }
    else
    {
	$event = $eventdetail_handler->create();    	
	
    }


$eventname_text = new XoopsFormLabel(_oscatt_eventname, $event->getVar('event_Name'));

$datelastedited_label = new XoopsFormLabel(_oscatt_datelastedited, $event->getVar('datelastedited'));

$user=new XoopsUser();

if($event->getVar('editedby')<>'' && $event->getVar('editedby')>0)
{
	$user = $member_handler->getUser($event->getVar('editedby'));
}
else $user=null;


if(isset($user))
{ $editedby_label = new XoopsFormLabel(_oscatt_editedby, $user->getVar('uname')); }
else $editedby_label=new XoopsFormLabel(_oscatt_editedby,null);

$dateentered_label = new XoopsFormLabel(_oscatt_dateentered, $event->getVar('dateentered'));

$user=new XoopsUser();
if($event->getVar('enteredby')<>'')
{
	$user = $member_handler->getUser($event->getVar('enteredby'));
}

$enteredby_label = new XoopsFormLabel(_oscatt_enteredby,
 $user->getVar('uname'));

$id_hidden = new XoopsFormHidden("id",$event->getVar('id'));

$removeid_hidden = new XoopsFormHidden("removeid",'');

$op_hidden = new XoopsFormHidden("op", "save");  //save operation

$form = new XoopsThemeForm(_oscmatt_eventdetail_TITLE, "eventdetailform", "eventdetailform.php", "post", true);
$form->addElement($eventname_text);
$form->addElement($datelastedited_label);
$form->addElement($editedby_label);
$form->addElement($dateentered_label);
$form->addElement($enteredby_label);

$form->addElement($op_hidden);
$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($eventname_text);

//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>

