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
define('MODULE','ApprovedRequisitionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['requisitionId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
    $requisitionManager = RequisitionManager::getInstance();
	$mainArray = array();
	
    $approvedRequisitionArray = $requisitionManager->getApprovedRequisitionDetail(" AND irm.requisitionId=".$REQUEST_DATA['requisitionId']);
	
	$requisitionDetailArray = $requisitionManager->getRequisition(" AND irm.requisitionId=".$REQUEST_DATA['requisitionId']);
	//print_r($requisitionDetailArray);
	//die;
	
	if(is_array($approvedRequisitionArray) && count($approvedRequisitionArray)>0 ) {
		$approvedRequisitionArray[0]['requisitionDate'] = UtilityManager::formatDate($approvedRequisitionArray[0]['requisitionDate']);
		$approvedRequisitionDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			$approvedRequisitionDiv .= '<input type="hidden" name="requisitionId" id="requisitionId" value='.$REQUEST_DATA['requisitionId'].' />';
			$approvedRequisitionDiv .= '<tr><td class="contenttab_internal_rows"><b>Requisition No.</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['requisitionNo'].'</td></tr>
			<tr><td class="contenttab_internal_rows"><b>Requesting User</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['userName'].'</td></tr>
			<tr><td class="contenttab_internal_rows"><b>Requisition Date</b></td><td class="padding2"><b>:</b>&nbsp;'.$approvedRequisitionArray[0]['requisitionDate'].'</td></tr>
			<tr class="rowheading"><td colspan="3"><table border="0" width="100%" class="contenttab_internal_rows">
				<tr><td class="contenttab_internal_rows" width="15%"><b>Sr. No.</b></td><td width="25%" class="contenttab_internal_rows"><b>Item Category</b></td><td class="contenttab_internal_rows" width="20%"><b> Item </b></td><td width="20%" class="contenttab_internal_rows"><b>Quantity</b></td><td><b>Available</b></td></tr>';
			$s=1;
			for($j=0;$j<count($requisitionDetailArray);$j++) {
				$bg = $bg =='row0' ? 'row1' : 'row0';
				$approvedRequisitionDiv .= '<input type="hidden" name="requisitionTransId_'.$j.'" value='.$requisitionDetailArray[$j]['requisitionTransId'].' />';
				$approvedRequisitionDiv .= '<tr class="'.$bg.'"><td>'.$s.'</td><td width="20%">'.$requisitionDetailArray[$j]['categoryName'].'</td><td width="20%">'.$requisitionDetailArray[$j]['itemName'].'('.$requisitionDetailArray[$j]['itemCode'].')'.'</td><td><input name="quantityRequired_'.$j.'" id="quantityRequired" class="inputbox" maxlength="6" value='.$requisitionDetailArray[$j]['quantityRequired'].'></input></td><td width="20%">'.$requisitionDetailArray[$j]['balance'].'</td></tr>';
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
