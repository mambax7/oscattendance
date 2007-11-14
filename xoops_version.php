<?php
$modversion['name'] = _oscatt_MOD_NAME;
$modversion['version'] = "3.09";
$modversion['description'] = _oscatt_MOD_DESC;
$modversion['credits'] = "Open Source Church Project - http://sourceforge.net/osc";
$modversion['author'] = "Steve McAtee";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 3.09;
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "oscattendance";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "oscatt_events";
$modversion['tables'][1] = "oscatt_attendance";
$modversion['tables'][2] = "oscatt_attendance_person";

// Templates
$modversion['templates'][0]['file'] = 'attendance.html';
$modversion['templates'][0]['description'] = 'Main Attendance Page';
$modversion['templates'][1]['file'] = 'eventview.html';
$modversion['templates'][1]['description'] = 'Events';
$modversion['templates'][2]['file'] = 'manageattendance.html';
$modversion['templates'][2]['description'] = 'Manage Attendance';
$modversion['templates'][3]['file'] = 'oscattreports.html';
$modversion['templates'][3]['description'] = 'Attendance Reports';
$modversion['templates'][4]['file'] = 'atthistory.html';
$modversion['templates'][4]['description']='Attendance History Report';
$modversion['templates'][5]['file'] = 'noattendance_report.html';
$modversion['templates'][5]['description']='No Attendance Report';

$modversion['blocks'][1]['file'] = "oscattnav.php";
$modversion['blocks'][1]['name'] = "Attendance";
$modversion['blocks'][1]['description'] = "Attendance";
$modversion['blocks'][1]['show_func'] = "oscattnav_show";

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