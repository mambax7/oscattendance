<?php
// $Id: oscmattnav.php,2007/06/01 
//  //                XOOPS - OSC Open Source Church Project//
//                    Copyright (c) 2005 ChurchLedger.com //
//                       <http://www.churchledger.com/>                             //
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
// -------------------------------------------------------------------------// 
// include the default language file for the admin interface



function oscattnav_show($options) 
{
global $xoopsUser;
$content_block="
<table class=navbar >
<tr><TD align=center ><small>
<a class=navbar href='" . XOOPS_URL . "/modules/oscattendance/eventslist.php" . "'>" .  _oscatt_nav_events . "</a>
</small></td><td>|</td><td align=center><small>

<a href='" . XOOPS_URL . "/modules/oscattendance/recordattendance.php?action=create'> " . _oscatt_nav_recordattendance . "</a>
</small></td>
<td>|</td><td align=center><small>

<a href='" . XOOPS_URL . "/modules/oscattendance/manageattendance.php'> " . _oscatt_nav_manageattendance . "</a>
</small></td>
<td>|</td><td align=center><small><a href='" . XOOPS_URL . "/modules/oscattendance/reports.php'>" . _oscatt_nav_reports . "</a></small></td>
</tr>
</table>
";
	
		
	$block['title'] = _oscatt_nav_block_title;
	$block['content'] = $content_block;

        return $block;
}


?>