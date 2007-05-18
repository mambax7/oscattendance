<?php
/************************************************************************* *
 *  filename    : DonationEditor.php
 *  last change : 2003-03-21
 *
 *  http://osc.sourceforge.net
 *
 *  This product is based upon work previously done by Infocentral (infocentral.org)
 *  on their PHP version Church Management Software that they discontinued
 *  and we have taken over.  We continue to improve and build upon this product
 *  in the direction of excellence.
 * 
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2003 Chris Gebhardt
 *  Copyright 2007 Steve McAtee
*********************************************************************/

include("../../mainfile.php");
//$GLOBALS['xoopsOption']['template_main'] ="donationeditor.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscgiv_access_denied);
}


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include(XOOPS_ROOT_PATH."/header.php");
include(XOOPS_ROOT_PATH."/modules/" . $xoopsModule->getVar('dirname') . "/class/oscgivradio.php");

if (isset($_GET['Batch']))  $batchMode = $_GET['Batch'];

if(isset($_POST['defaultdate']))
	$sdefaultdate=$_POST['defaultdate'];
else
	$sdefaultdate=Date("Y-m-d");

$iDefaultFundID=0;
$defaultpaymenttype=0;
if (isset($_POST['DefaultFundID'])) $iDefaultFundID=$_POST['DefaultFundID'];
if (isset($_POST['DefaultPaymentType'])) $defaultpaymenttype=$_POST['DefaultPaymentType'];

if(hasPerm("oscgiving_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}


$donations=array();
$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');


$smode="";

for($i=0;$i<=3;$i++)
{
	$donation=$donation_handler->create(false);
	$donation->assignVar('iteration',$i);


	//Pull post information
	if (isset($_POST['postaction']))
	{
		$smode=$_POST['postaction'];
				
		if(isset($_POST['donationdate' . $i]))
		$donation->assignVar('don_Date',$_POST['donationdate' . $i]);
		
		if(isset($_POST['fund' . $i])) $donation->assignVar('dna_fun_id',$_POST['fund' . $i]);

	
		if(isset($_POST['paymenttype' . $i]))
		$donation->assignVar('don_PaymentType',$_POST['paymenttype' . $i]);
	
		if(isset($_POST['donationamount' . $i]))
		$donation->assignVar('dna_Amount',$_POST['donationamount' . $i]);
	
		if(isset($_POST['donationenvorname' . $i]))
		{
			$donation->assignVar('searchvalue',$_POST['donationenvorname' . $i]);
		}
			
		if(isset($_POST['checknumber' . $i]) && is_numeric($_POST['checknumber' . $i]))
		{
			$donation->assignVar('don_CheckNumber',$_POST['checknumber' . $i]);
		}
		else
		{
			$donation->assignVar('don_CheckNumber',"");
		}
		
		if(isset($_POST['personid' . $i]))
		{
			$donation->assignVar('personid',$_POST['personid' . $i]);
		}
		
		if(isset($_POST['envelope' . $i]))
		{
			$donation->assignVar('don_Envelope',$_POST['envelope' . $i]);
		}
		
		if(isset($_POST['display' . $i]))
		{
			$donation->assignVar('displayaddress',$_POST['display' . $i]);
		}
		
	}
	
	$donations[$i]=$donation;

}

switch ($smode)
{
	case "lookup":
	
		$lookupval="";
		//iterate thru donations
		$i=0;
		$persons=array();
		foreach($donations as $donation )
		{
			//lookup donator
			if($donation->getVar('searchvalue')=="")
			{
				$persons=array();
			}
			else
			{
				$persons=$donation_handler->lookupDonator($donation->getVar('searchvalue'));
			}

			$donation->searchpersons=$persons;
//			$donation->assignVar('searchpersons',$persons);
			$donations[$i]=$donation;
			
			$i++;
		}
	
	break;
	
	case "applydefaults":
		$i=0;

		foreach($donations as $donation )
		{

			$donation->assignVar('don_Date',$sdefaultdate);
			$donation->assignVar('dna_fun_id',$iDefaultFundID);
			$donation->assignVar('don_PaymentType',$defaultpaymenttype);
			
			$donations[$i]=$donation;
		
			$i++;		
		}
	
	break;
	
	case "submit":
		foreach($donations as $donation)
		{

			if($donation->getVar('personid')>0)
			{	

				$donationid=$donation_handler->submitDonation($donation);
			
				if($donationid>0)
				{	
					//recreate donation for display
					$i=$donation->getVar('iteration');
					$donation=$donation_handler->create(false);
					$donation->assignVar('iteration',$i);
					//apply defaults
					$donation->assignVar('don_Date',$sdefaultdate);
					$donation->assignVar('dna_fun_id',$iDefaultFundID);
					$donation->assignVar('don_PaymentType',$defaultpaymenttype);
		
					$donations[$i]=$donation;
				}
			}

		}
	
}

$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');

$funds=$fund_handler->getFundlist();


$donation_dt=array();
$donor_txt=array();
$donor_amount=array();
$donor_lbl=array();

$defaultdate_dt= new XoopsFormTextDateSelect(_oscgiv_defaultdate,'defaultdate', 15, $sdefaultdate);

$oscgiv_sdefaultdate="";

for($i=0;$i++;$i<3)
{

	$hold=new XoopsFormTextDateSelect(_oscgiv_defaultdate,'donationdate' . $i, 15, $sdefaultdate);	
	
	$donation_dt[$i]=$hold->render();
/*
	$donor_txt[$i]= new XoopsFormText(_oscgiv_envelopenumorlastname, "donor" . $i, 30, 50, null);
	
	$donor_lbl[$i]=new XoopsFormLabel("","item found here");

	$donor_amount[$i]= new XoopsFormText("", "amount" . $i, 30, 50, null);
*/
}

echo "<h2 class=comTitle>" . _oscgiv_donationbatchentry_title . "</h2>";
echo "<form name='DonationForm' method='post' action='DonationEditor.php?'>";
echo "<input type=hidden name=postaction>" ;
echo "<table align=center cellpadding=3 border=1>";
echo "<tr>";
echo "<td class='TextColumn'>";
echo "<b>" . _oscgiv_defaultdate . "</b><br>";
echo $defaultdate_dt->render();
echo "</td>";
echo "<td class='TextColumn'>";
echo "<b>" . _oscgiv_defaultfund. "</b><br>";
echo "<select name='DefaultFundID'>";
//echo "<option value='0'>" . _oscgiv_none . "</option>";

foreach($funds as $fund)
{
	echo "<option value='" . $fund->getVar('fund_id') . "'";
	if($fund->getVar('fund_id')==$iDefaultFundID)
	{
		echo " selected ";
	}
	echo ">" . $fund->getVar('fund_Name') . "</option>";
}

echo "</select>";

echo "</td>";
echo "<td class='TextColumn'>";
echo "<b>" . _oscgiv_defaulttype . "</b><br>";
echo "<select name='DefaultPaymentType'>";
echo "<option ";
if($defaultpaymenttype==1)
{ 
	echo " selected ";
}
echo " value='1'>" . _oscgiv_cash . "</option>";

echo "<option ";
if($defaultpaymenttype==2)
{ 
	echo " selected ";
}
echo " value='2'>" . _oscgiv_check . "</option>";
echo "<option ";
if($defaultpaymenttype==3)
{
	echo " selected ";
}
echo " value='3'>" . _oscgiv_credit . "</option>";
echo " </select>";
echo "</td>";

echo "<td align=right valign=bottom>";
echo "<br><input type=submit class='icButton' value='" . _oscgiv_applydefaults . "' Name='applydefaults' id='applydefaults' onclick=\"DonationForm.postaction.value='applydefaults';\">";
echo "</td>";

echo "</tr>";
echo "</table>";
echo "<br>";

echo "<table class='outer' align='center' width='40%'>";
echo "<tr>";
echo "<th colspan=6>";
echo "<input type='submit' class='icButton' value='" . _oscgiv_lookup . "'  Name='LookupDonor' id='LookupDonor' onclick=\"DonationForm.postaction.value='lookup';\">";

echo "&nbsp;&nbsp;<input type=reset value='" . _oscgiv_clear . "'>";
echo "&nbsp;&nbsp;<input type=submit value='" . _oscgiv_submitdonations . "' Name=\"submitdonations\" id=\"submitdonations\" onclick=\"DonationForm.postaction.value='submit';\">";
echo "</th></tr>";
echo "<tr>";
echo "<th>";
echo _oscgiv_donationdate;
echo "</th>";
echo "<th>";
echo _oscgiv_envelopenumorlastname;
echo "</th>";
echo "<th>" . _oscgiv_fund;
echo "</th>";
echo "<th>" . _oscgiv_amount . "</th>";
echo "<th>" . _oscgiv_paymenttype . "</th>";
echo "<th width='15'>" . _oscgiv_checknumber . "</th>";
echo "</tr>";
foreach($donations as $donation)
{	
	echo "<tr class='even'>";
	echo "<td>";
	echo "<input type='text' name='donationdate" . $donation->getVar('iteration') . "' .  id='donationdate" . $donation->getVar('iteration') . "' size='10' maxlength='25' value='" . $sdefaultdate . "' />";
	echo "<input type='reset' value=' ... ' onclick='return showCalendar(\"donationdate" . $donation->getVar('iteration') . "\");'>";
	echo "</td>";
	echo "<td nowrap>";
	echo "<input type='text' name='donationenvorname" . $donation->getVar('iteration') . "' id='donationenvorname" . $donation->getVar('iteration') . "'  size='30' maxlength='50' value='" . $donation->getVar('searchvalue') . "'>";

//	$persons=$donation->getVar('searchpersons');	
	$persons=$donation->searchpersons;
	$radio= new OscgivRadio("", "personid" . $donation->getVar('iteration'), "");
	
	if(isset($persons))
	{

		if(count($persons)==1)
		{
			$person=$persons[0];
			$displayradio=$person->getVar('lastname') . ", " . $person->getVar('firstname') . "&nbsp;" . $person->getVar('address1');
			$selectradio=$person->getVar('id');
			$radio->addOption($person->getVar('id'), $displayradio);
			$radio->setValue($person->getVar('id'));
		}
		else
		{
			foreach($persons as $person)
			{
				$displayradio=$person->getVar('lastname') . ", " . $person->getVar('firstname') . "&nbsp;" . $person->getVar('address1');
				$radio->addOption($person->getVar('id'), $displayradio);
			} 
		}
		echo "<br>" . $radio->renderbreak();

	}
	echo "</td>";
	echo "<td>";
	echo "<select name='fund" . $donation->getVar('iteration') . "'  id='fund" . $donation->getVar('iteration') . "'>";
//	echo "<option value='0'>" . _oscgiv_none . "</option>";
	foreach($funds as $fund)
	{
		echo "<option value=\"" . $fund->getVar('fund_id') . "\"";
		if($donation->getVar('dna_fun_id')==$fund->getVar('fund_id'))
		{
			echo " selected ";
		}
		echo ">";
		echo $fund->getVar('fund_Name');
		echo "</option>";
	}
	echo "</select>";
	echo "</td>";
	echo "<td>";
	echo "<input type='text' name='donationamount" . $donation->getVar('iteration') . "' id='donation" . $donation->getVar('iteration') . "' size='8' maxlength='8' value='" . $donation->getVar('dna_Amount') . "'>";
	echo "</td>";
	echo "<TD>";
	echo "<select name='paymenttype" . $donation->getVar('iteration') . "'>";
	echo "<option ";
	if($donation->getVar('don_PaymentType')==1)
	{
		echo " selected ";
	}
	echo " value='1'>" . _oscgiv_cash . "</option>";
	echo "<option ";
	if($donation->getVar('don_PaymentType')==2)
	{
		echo " selected ";
	}
	echo " value='2'>" . _oscgiv_check . "</option>";
	echo "<option ";
	if($donation->getVar('don_PaymentType')==3)
	{
		echo " selected ";
	}
	echo " value='3'>" . _oscgiv_credit . "</option>";
	echo "</select>";
	echo "</TD>";
	echo "<td><input size='5' type=text  name=\"checknumber" . $donation->getVar('iteration') . "\" id=\"checknumber" . $donation->getVar('iteration') . "\" value=\"" . $donation->getVar("don_CheckNumber") . "\">";
		
	echo "<input type=hidden name=\"envelope" . $donation->getVar('iteration') . "\" id=\"envelope" . $donation->getVar('iteration') . "\" value=\"" . $donation->getVar('don_Envelope') . "\">";
	echo "</td>";
	echo "</tr>";
}

echo "</tr>";
echo "</table>";
echo "</form>";
echo "<p><p><P><P>";
/*
$xoopsTpl->assign('donations',$donations);
$xoopsTpl->assign('lookuppersons',$lookuppersons);
$xoopsTpl->assign('donation_dt',$donation_dt);
$xoopsTpl->assign('donor_txt',$donor_txt);
$xoopsTpl->assign('donor_lbl',$donor_lbl);
$xoopsTpl->assign('donor_amount',$donor_amount);

$xoopsTpl->assign('title',_oscgiv_donationbatchentry_title);
$xoopsTpl->assign('personid',1);
$xoopsTpl->assign('donationid',1);
$xoopsTpl->assign('defaultdate_dt',$defaultdate_dt->render());
$xoopsTpl->assign('sdefaultdate',$sdefaultdate);
$xoopsTpl->assign('funds',$funds);

$xoopsTpl->assign('oscgiv_batchentrymode',_oscgiv_batchentrymode);
$xoopsTpl->assign('oscgiv_batchentrymode',_oscgiv_batchentrymode);
$xoopsTpl->assign('oscgiv_defaultdate',_oscgiv_defaultdate);
$xoopsTpl->assign('oscgiv_sdefaultdate',$oscgiv_sdefaultdate);
$xoopsTpl->assign('oscgiv_defaultfund',_oscgiv_defaultfund);
$xoopsTpl->assign('oscgive_none',_oscgive_none);
$xoopsTpl->assign('oscgiv_defaulttype',_oscgiv_defaulttype);
$xoopsTpl->assign('oscgiv_paymenttype',_oscgiv_paymenttype);
$xoopsTpl->assign('oscgiv_cash',_oscgiv_cash);
$xoopsTpl->assign('oscgiv_check',_oscgiv_check);
$xoopsTpl->assign('oscgiv_credit',_oscgiv_credit);

$xoopsTpl->assign('oscgiv_clear',_oscgiv_clear);
$xoopsTpl->assign('oscgiv_delete',_oscgiv_delete);

$xoopsTpl->assign('oscgiv_receiptnumber',_oscgiv_receiptnumber);

$xoopsTpl->assign('oscgiv_donationdate',_oscgiv_donationdate);

$xoopsTpl->assign('oscgiv_dateerror',_oscgiv_dateerror);

$xoopsTpl->assign('oscgiv_envelopenumorlastname',_oscgiv_envelopenumorlastname);

$xoopsTpl->assign('oscgiv_submitdonations',_oscgiv_submitdonations);
$xoopsTpl->assign('oscgiv_lookup',_oscgiv_lookup);
$xoopsTpl->assign('oscgiv_selecteddonor',_oscgiv_selecteddonor);
$xoopsTpl->assign('oscgiv_applydefaults',_oscgiv_applydefaults);
$xoopsTpl->assign('oscgiv_fund',_oscgiv_fund);
$xoopsTpl->assign('oscgiv_amount',_oscgiv_amount);
$xoopsTpl->assign('oscgiv_checknumber',_oscgiv_checknumber);
*/
include(XOOPS_ROOT_PATH."/footer.php");

?>
