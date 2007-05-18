<?php
$admini=0;

$adminmenu[$admini]['title'] = _oscgiv_admin_permissions;
$adminmenu[$admini]['link'] = "admin/perm.php?id=4";

$admini++;
$adminmenu[$admini]['title'] = _oscgiv_admin_givingsettings;
$adminmenu[$admini]['link'] = "admin/yearendform.php";

$admini++;
$adminmenu[$admini]['title'] = _oscgiv_admin_funds;
$adminmenu[$admini]['link'] = "admin/fundselect.php";

?>