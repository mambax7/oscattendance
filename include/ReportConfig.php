<?php
/*******************************************************************************
 *
 *  filename    : Include/ReportsConfig.php
 *  last change : 2003-03-14
 *  description : Configure report generation
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
 ******************************************************************************/

//
// Paper size for all PDF report documents
// Sizes: A3, A4, A5, Letter, Legal, or a 2-element array for custom size
//
$paperFormat = "Letter";

//
// Yearly Donation Report Letter (Exemption Letter)
//

//  You may want to comment this out if you are using custom pre-printed letterhead paper.
$sExemptionLetter_Letterhead = "../Images/church_letterhead.png";

$sExemptionLetter_Intro = "We appreciate your financial support during the past year to Your Organization of City, State. The following is a statement of your donations during the past year." ;
$sExemptionLetter_EndLine = "Thank you for your kind donations and may the Lord reward you.<br><br>" ;
$sExemptionLetter_Closing = "<br>Sincerely,<br>" ;
$sExemptionLetter_Author = "<br>Your Name<br>Treasurer" ;
$sExemptionLetter_FooterLine = "123 Your Address · Your City, ST 12345 · Tel. 555.555.1212 · Fax. 555.555.1212 · http://www.youraddress.com";
$sExemptionLetter_Signature = "../Images/signature.png";

//
// Directory Report default settings.  These can be changed at report-creation time.
//

// Settings for the optional title page
$sChurchName = "Your Church";
$sChurchAddress = "123 Your Address";
$sChurchCity = "Your City";
$sChurchState = "ST";
$sChurchZip = "12345";
$sChurchPhone = "(555) 555-5555";
$sDirectoryDisclaimer = "Every effort was made to insure the accuracy of this directory.  If there are any errors or omissions, please contact the church office.\n\nThis directory is for the use of the people of $sChurchName, and the information contained in it may not be used for business or commercial purposes.";

$bDirLetterHead = "../Images/church_letterhead.png";

// Include only these classifications in the directory, comma seperated
$sDirClassifications = "1,2,4,5";
// These are the family role numbers designated as head of house
$sDirRoleHead = "1,7";
// These are the family role numbers designated as spouse
$sDirRoleSpouse = "2";
// These are the family role numbers designated as child
$sDirRoleChild = "3";

// Donation Receipt
$sDonationReceipt_Thanks = "Thank you for your kind donation to Your Organization of City, State.";
$sDonationReceipt_Closing = "Thank you!";

?>
