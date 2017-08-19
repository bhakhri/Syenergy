<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGeneratePO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

//if(trim($REQUEST_DATA['indentId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();
	$mainArray = array();
	
	$indentDetailArray = $poManager->getIndentDetail();
	
	if(is_array($indentDetailArray) && count($indentDetailArray)>0 ) {
		$indentDetailArray[0]['indentDate'] = UtilityManager::formatDate($indentDetailArray[0]['indentDate']);
		$approvedIndentDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			$approvedIndentDiv .= '<tr class="rowheading"><td colspan="2"><table border="0" width="100%" class="contenttab_internal_rows">
				<tr><td class="contenttab_internal_rows" width="15%"><b>Sr. No.</b></td><td width="25%" class="contenttab_internal_rows"><b>Indent No.</b></td><td width="25%" class="contenttab_internal_rows"><b>Category Name</b></td><td class="contenttab_internal_rows" width="20%"><b>Item Name</b></td><td width="20%" class="contenttab_internal_rows"><b>Quantity</b></td></tr>';
			$s=1;
			for($j=0;$j<count($indentDetailArray);$j++) {
				$bg = $bg =='row0' ? 'row1' : 'row0';
				$approvedIndentDiv .= '<tr class="'.$bg.'"><td>'.$s.'</td><td width="20%">'.$indentDetailArray[$j]['indentNo'].'</td><td width="20%">'.$indentDetailArray[$j]['categoryName'].'</td><td width="20%">'.$indentDetailArray[$j]['itemName'].'</td><td width="20%" style="text-align:right;">'.$indentDetailArray[$j]['quantityRequired'].'</input></td></tr>';
				$s++;
			}
			$approvedIndentDiv .= '</table></td></tr></table>';

	}
	$mainArray['indentDiv'] = $approvedIndentDiv;

	echo(json_encode($mainArray));

	
	/*else {
		echo 0;  //no record found
	}*/
	//}


	


// $History: $
//
?>