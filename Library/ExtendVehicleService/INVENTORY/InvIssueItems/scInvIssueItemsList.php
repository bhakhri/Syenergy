<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Jaineesh
// Created on : 02.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','InvIssueItems');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	
    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $issueItemsManager = IssueItemsManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subItemCode';
	$temp = $sortField;
	if($sortField == "subItemCode") {
		$sortField = " LENGTH(subItemCode)+0 $sortOrderBy,subItemCode";
	}
	//echo($sortField);
	$orderBy = " $sortField $sortOrderBy";
	
	$store = $REQUEST_DATA['store'];
	$itemCategoryId = $REQUEST_DATA['itemCategory'];
	$itemId = $REQUEST_DATA['itemName'];
	
	$issueFromItemsArray = $issueItemsManager->getIssueFromItemsList($conditions,$store);
	$issueItemsList = UtilityManager::makeCSList($issueFromItemsArray,'subItemId',',');
	$issueFromArray = explode(',',$issueItemsList);
	
	$latestDepttArray = $issueItemsManager->getLatestDepttList();
	$countDeptt = count($latestDepttArray);

	$conditions = " AND ins.invDepttId = '".$store."' AND ins.itemId = '".$itemId."' AND ic.itemCategoryId = '".$itemCategoryId."'";
	$condition = " AND isi.itemId = ".$itemId."";
	$issueItemsArray = $issueItemsManager->getItemsList($conditions,$store,$condition,$orderBy);
	$sortField = $temp;

	$cnt = count($issueItemsArray);

	for($i=0;$i<$cnt;$i++) {
		for($j=0;$j<$countDeptt;$j++) {
			if($issueItemsArray[$i]['subItemId'] == $latestDepttArray[$j]['subItemId']) {
				$depttName = $latestDepttArray[$j]['latestUser'];
					if($latestDepttArray[$j]['latestUser'] == '') {
						$depttName = NOT_APPLICABLE_STRING;
					}
			  }
		}
		if($issueItemsArray[$i]['mode'] == 2) {
			if(in_array($issueItemsArray[$i]['subItemId'],$issueFromArray)) {
				$checkall = NOT_APPLICABLE_STRING;
				//$issueItemsArray[$i]['invDepttAbbr'] = $latestDepttArray[$i]['latestUser'];
			}
			else {
				$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($issueItemsArray[$i]['subItemId']).'">';
				$issueItemsArray[$i]['status'] = 'Available';
				//$issueItemsArray[$i]['invDepttAbbr'] = NOT_APPLICABLE_STRING;	
			}
		}
		else if ($issueItemsArray[$i]['mode'] == 1) {
			if($issueItemsArray[$i]['status'] == 'Issued' OR $issueItemsArray[$i]['status'] == 'Transferred') {
				$checkall = NOT_APPLICABLE_STRING;
				//$issueItemsArray[$i]['invDepttAbbr'] = $issueItemsArray[$i]['invDepttAbbr'];
			}
			else {
				$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($issueItemsArray[$i]['subItemId']).'">';
				//$issueItemsArray[$i]['invDepttAbbr'] = NOT_APPLICABLE_STRING;
			}
		}

		// add subjectId in actionId to populate edit/delete icons in User Interface   
		$valueArray = array_merge(array('checkAll' => $checkall, 'deptName' => $depttName,'srNo' => ($records+$i+1) ),$issueItemsArray[$i]);
	 
	   if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
	   }
	   else {
			$json_val .= ','.json_encode($valueArray);           
	   }
	}
	
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: scInvIssueItemsList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:07a
//Created in $/Leap/Source/Library/INVENTORY/InvIssueItems
//new files for inventory issue items
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/09    Time: 12:09p
//Created in $/Leap/Source/Library/FrozenClass
//new ajax files for frozen class
//
?>