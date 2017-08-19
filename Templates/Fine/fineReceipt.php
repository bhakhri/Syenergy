<?php 
//This file is used as printing version for payment receipt.
//
// Author :Rajeev Aggarwal
// Created on : 29-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(BL_PATH . '/NumToWord.class.php');
	
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
    UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
    UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();  
     
	/// Search filter /////  
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptId'])) {
	   $condition .= ' AND fr.fineReceiptId ='.$REQUEST_DATA['receiptId'];         
	}
	global $sessionHandler;
	$feeFavourText = $sessionHandler->getSessionVariable('FEE_FAVOUR_TEXT');
    $recordArray = $fineStudentManager->getFineHistoryList($condition);

	 
?>
	<table border="0" cellspacing="0" cellpadding="0" width="650" align="center">
	<tr>
		<td align="left" colspan="1" width="25%"><?php echo $reportManager->showHeader();?></td> 
		<th align="left" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
	</tr>
	<tr><th colspan="2"  <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->getInstituteAddress()."<br>".$reportManager->getInstituteTelephone(); ?></th></tr>
    <tr><td align="center" colspan="2" class = 'headingFont'>Fine Receipt</td></tr>
	 
	</table> <br>
 
	<table cellspacing="0" cellpadding="0" border="0" width="650" align="center">
	<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> align="right" width="2%"><nobr>Receipt No.&nbsp;: </nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?>><B><?php echo $recordArray[0]['fineReceiptNo']?></B></td>
		<td <?php echo $reportManager->getReportDataStyle()?> width="28%" align="right"><nobr>Dated: </nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?> width="10%" ><B><?php echo (UtilityManager::formatDate($recordArray[0]['receiptDate']));?></B></td>
	</tr>
	<tr>
		<td valign="top" colspan="4" height="20"></td>
	</tr>
	<tr>
		<td colspan="4">
		<table cellspacing="0" cellpadding="0" border="0" width="650">
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="20%" nowrap>Received with thanks from</td>
			<td valign="top" class="receiptClass" width="38%">&nbsp;<i><?php echo ucwords(strtolower($recordArray[0]['fullName']))?></i></td>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="4%" align="center"><?php
			if($recordArray[0]['studentGender']=="M") echo "S/o"; else echo "D/o";?></td>
			<td valign="top" class="receiptClass"  width="38%">&nbsp;<i><?php echo ucwords(strtolower($titleResults[$recordArray[0]['fatherTitle']].' '.$recordArray[0]['fatherName']))?></i></td>
		</tr>
		<tr>
			<td valign="top" height="8"></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table cellspacing="0" cellpadding="0" border="0" width="650">
				<tr>
					
					<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="13%">a sum of Rupees</td>
					<td valign="top" class="receiptClass">&nbsp;<i><?php 
					$num = new NumberToWord($recordArray[0]['totalAmount']);
					echo ucwords(strtolower($num->word)).' Only';
					?></i></td>
				</tr>
			</td>
			</table>
		</tr>
		<tr>
			<td valign="top" height="8"></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table cellspacing="0" cellpadding="0" border="0" width="650">
				<tr>
					
					 
					<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width="35%">on account of <B>Fine</B></td>
				</tr>
			</td>
			</table>
		</tr>
		<tr>
			<td valign="top" height="28"></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table cellspacing="0" cellpadding="0" border="0" width="650">
				<tr>
					<td valign="middle" class="receiptBox" width="20%" height="30">&nbsp;<B>Rs.&nbsp;&nbsp;<?php echo $recordArray[0]['totalAmount']?>/-</B></td>
					<td valign="top" width="40%"></td>
					<td valign="top" width="40%" <?php echo $reportManager->getReportDataStyle()?> align="center"><B><?php echo $feeFavourText ?></B></td>
				</tr>
				<tr>
					<td valign="top" height="28"></td>
				</tr>
				<tr>
					<td valign="middle"></td>
					<td valign="top" width="40%"></td>
					<td valign="top" width="40%" <?php echo $reportManager->getReportDataStyle()?> align="center">Authorised Signatory</td>
				</tr>
			</td>
			</table>
		</tr>
		
		</table>
		</td>
	</tr>
	</table>
<?php
// $History: fineReceipt.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 3:32p
//Updated in $/LeapCC/Templates/Fine
//resolved issue 0002216,0002211,0002214,0002215,0002217,0002220,0002221,
//0002222,0002223,0002224,0002225,0002226,0002227,0002218
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Fine
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/09    Time: 6:46p
//Created in $/LeapCC/Templates/Fine
//intial checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/19/08    Time: 4:07p
//Updated in $/Leap/Source/Templates/Student
//changed "cauton money" to "fees"
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:07p
//Created in $/Leap/Source/Templates/Student
//intial checkin

?>    