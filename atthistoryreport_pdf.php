<?php
/*********************************************************************** * 
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2007, Steve McAtee
 ******************************************************************************/

include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscgiv_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/html2fpdf/html2fpdf.php");

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscatt_view",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscatt_accessdenied);
}


if(isset($_GET['year'])) $year=$_GET['year'];
if(isset($_POST['sevent'])) $eventid=$_POST['sevent'];

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

// Avoid a bug in FPDF..
setlocale(LC_NUMERIC,'C');

$attendanceev_handler = &xoops_getmodulehandler('attendanceevent','oscattendance');

//get attendance count for last 90 days

$attendance90 = $attendanceev_handler->getAttcount($eventid,90);

$att_table="<table border=1><tr bgcolor=#C0C0C0>";
$att_table.="<th>" . _oscatt_date . "</th><th>" . _oscatt_eventname . "</th><th>" . _oscatt_count . "</th></tr>";

$ae=$attendanceev_handler->create();

foreach($attendance90 as $ae)
{
$att_table.="<tr><td>" . $ae->getVar('att_Date');
$att_table.="</td><td>" . $ae->getVar('event_Name');
$att_table.="</td><td>" . $ae->getVar('attendancecount');
$att_table.="</td></tr>";
} 
$att_table.="</table>";
$att_table_90=$att_table;

$attendanceYTD = $attendanceev_handler->getAttcountYTD($eventid);

$att_table="<table border=1><tr bgcolor=#C0C0C0>";
$att_table.="<th>" . _oscatt_eventname . "</th><th>" . _oscatt_month . "</th><th>" . _oscatt_year . "</th><th>" . _oscatt_count . "</th></tr>";


foreach($attendanceYTD as $ae)
{
	$att_table.="<tr><td>" . $ae->getVar('event_Name');
	$att_table.="</td><td>" . $ae->getVar('attmonth');
	$att_table.="</td><td>" . $ae->getVar('attyear');
	
	$att_table.="</td><td>" . $ae->getVar('attendancecount');
	$att_table.="</td></tr>";
} 
$att_table.="</table>";

$att_table_YTD=$att_table;


$churchdetail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');
	
$churchdetail=$churchdetail_handler->get();
		
class PDF extends HTML2FPDF
{

	//Page header
	function Header()
	{
		$t=getdate();
   
    		$today=date('Y-m-d h:m',$t[0]);
		
		$header="Generated: " . $today;
		
		//Select Arial bold 15
//		$this->SetFont($this->_Font,'B',9);
		//Line break
		$this->Ln(3);
		//Move to the right
//		$this->Cell(10);
		//Framed title
		$this->Cell(190,10,$header,'T',0,'R');
		$this->SetLineWidth(0.5);
	}

	//Page footer
	function Footer()
	{
		//global $sExemptionLetter_FooterLine;

		global $churchdetail;
		$footer=$churchdetail->getVar('churchname') . " " . $churchdetail->getVar('address1') . " " . $churchdetail->getVar('city') . ", " . $churchdetail->getVar('state') . " " . $churchdetail->getVar('zip') . "  " . _oscatt_phone . ":" . $churchdetail->getVar('phone') . "  " . _oscatt_fax . ":" . $churchdetail->getVar('fax') . "  " . _oscatt_website . ":" . $churchdetail->getVar('website');
		
		// if ($this->PageNo() == 1){
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','',9);
		$this->SetLineWidth(0.5);
		$this->Cell(0,10,$footer,'T',0,'C');
	}

	function CreateReport($table1, $table2)
	{
	
		$title="<h1>" . _oscatt_attendancereport_title . "</h1>";
		
		$this->WriteHTML($title);
		$this->WriteHTML($table1);
		$this->WriteHTML("<br>");
		$this->WriteHTML($table2);
		
		
	}
}


// Main
$today = date("F j, Y");

$pdf=new PDF('P','mm',$paperFormat);
$pdf->Open();
$pdf->AddPage();
//$pdf->SetFont('Arial','',11); 
$pdf->AliasNbPages();
$pdf->CreateReport($att_table_90, $att_table_YTD);
$pdf->Output();
?>
