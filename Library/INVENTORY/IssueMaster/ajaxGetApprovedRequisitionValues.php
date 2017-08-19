<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionIssueMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['requisitionId'] ) != '') {
	require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
	$issueManager = IssueManager::getInstance();
	$mainArray = array();
	
	$approvedRequisitionArray = $issueManager->getApprovedItemsList(" AND irm.requisitionId=".$REQUEST_DATA['requisitionId']);
	
	$requisitionDetailArray = $issueManager->getRequisition(" AND irm.requisitionId=".$REQUEST_DATA['requisitionId']);
	
	if(is_array($approvedRequisitionArray) && count($approvedRequisitionArray)>0 ) {
		$approvedRequisitionArray[0]['requisitionDate'] = UtilityManager::formatDate($approvedRequisitionArray[0]['requisitionDate']);
		$approvedRequisitionDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			$approvedRequisitionDiv .= '<input type="hidden" name="requisitionId" id="requisitionId" value='.$REQUEST_DATA['requisitionId'].' />';
			$approvedRequisitionDiv .= '<tr><td class="contenttab_internal_rows"><b>Requisition No.</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['requisitionNo'].'</td></tr>
			<tr><td class="contenttab_internal_rows"><b>Employee Name</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['employeeName'].'</td></tr>
			<tr><td class="contenttab_internal_rows"><b>Requisition Date</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['requisitionDate'].'</td></tr>
			<tr><td colspan="3"><table border="0" width="100%" class="contenttab_internal_rows">
				<tr class="rowheading"><td class="contenttab_internal_rows"><b>#</b></td><td class="contenttab_internal_rows"><b>Item Category</b></td><td class="contenttab_internal_rows"><b>Item Code</b></td><td class="contenttab_internal_rows"><b>Quantity</b></td><td class="contenttab_internal_rows"><b>Available Qty</b></td></tr>';
			$s=1;
			$cnt=count($requisitionDetailArray);
		
			for($j=0;$j<$cnt;$j++) {
				$bg = $bg =='row0' ? 'row1' : 'row0';
				$approvedRequisitionDiv .= '<input type="hidden" name="requisitionTransId_'.$j.'" value='.$requisitionDetailArray[$j]['requisitionTransId'].' />';
				$approvedRequisitionDiv .= '<tr class="'.$bg.'"><td>'.$s.'</td><td width="20%">'.$requisitionDetailArray[$j]['categoryCode'].'</td><td width="20%">'.$requisitionDetailArray[$j]['itemCode'].'</td><td width="20%">'.$requisitionDetailArray[$j]['quantityRequired'].'</td><td width="20%">'.$requisitionDetailArray[$j]['balance'].'</td></tr>';
				$s++;
			}
			$approvedRequisitionDiv .= '</table></td></tr></table>';

		//$mainArray['requisitionDetail'] = $foundArray;
		//echo json_encode($mainArray);
	}
	$mainArray['approvedRequisitionDiv'] = $approvedRequisitionDiv;

	echo(json_encode($mainArray));

	
	/*else {
		echo 0;  //no record found
	}*/
	}
// $History: $
//
?>