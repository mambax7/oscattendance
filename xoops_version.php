<?php
$modversion['name'] = _oscatt_MOD_NAME;
$modversion['version'] = "3.0";
$modversion['description'] = _oscatt_MOD_DESC;
$modversion['credits'] = "Open Source Church Project - http://sourceforge.net/osc";
$modversion['author'] = "Steve McAtee";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 3;
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "oscattendance";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "oscatt_events";
$modversion['tables'][1] = "oscatt_attendance";

// Templates
$modversion['templates'][0]['file'] = 'attendance.html';
$modversion['templates'][0]['description'] = 'Main Attendance Page';
$modversion['templates'][1]['file'] = 'eventview.html';
$modversion['templates'][1]['description'] = 'Events';

$modversion['blocks'][0]['file'] = "oscattnav.php";
$modversion['blocks'][0]['name'] = _oscatt_navigationblock;
$modversion['blocks'][0]['description'] = _oscatt_navigation_description;

$modversion['blocks'][0]['show_func'] = "oscattnav_show";

$modversion['hasSearch'] = 0;
//$modversion['search']['file']="include/search.inc.php";
//$modversion['search']['func']="oscmem_search";
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['hasMain'] = 1;
//$modversion['templates'][1]['file'] = 'cs_index.html';
//$modversion['templates'][1]['description'] = 'cs main template file';
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'id';

?>