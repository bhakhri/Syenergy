<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Jaineesh
// Created on : 02.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','FrozenTimeTableToClass');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FrozenClassManager.inc.php");
    $frozenClassManager  = FrozenClassManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
	$orderBy = " $sortField $sortOrderBy";
	
	$labelId	= $REQUEST_DATA['labelId'];

		$frozenToClassArray = $frozenClassManager->getFrozenClasses($labelId,$filter,$limit,$orderBy);
		$cnt = count($frozenToClassArray);
		for($i=0;$i<$cnt;$i++) {
			$checkTotalMarksTransferredArray = $frozenClassManager->getUseClassMarksTransferred($frozenToClassArray[$i]['classId']);
			if ($checkTotalMarksTransferredArray[0]['cnt'] == 0 OR $checkTotalMarksTransferredArray[0]['cnt'] == '' ) {
				$marksTransferred = 'No';
				$checkall = NOT_APPLICABLE_STRING;
			}
			else {
				$marksTransferred = 'Yes';
				$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($frozenToClassArray[$i]['classId']).'">';
			}
			
			//$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($frozenToClassArray[$i]['classId']).'">';

			if ($frozenToClassArray[$i]['isFrozen']==1) {
				$imgActive =  '<img src='.IMG_HTTP_PATH.'/tick.gif border="0" alt="Active" title="Freeze" width="18" height="18" style="cursor:default">';	
			}
			else {
				$imgActive= '<img src='.IMG_HTTP_PATH.'/cross.gif border="0" alt="Deactive" title="Unfreeze" width="18" height="18" style="cursor:default">';	
			}

			// add subjectId in actionId to populate edit/delete icons in User Interface   
			$valueArray = array_merge(array('checkAll' => $checkall, 'marksTransferred' => $marksTransferred, 'frozen' => $imgActive, 'srNo' => ($records+$i+1) ),$frozenToClassArray[$i]);
		 
		   if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
		   }
		   else {
				$json_val .= ','.json_encode($valueArray);           
		   }
		}
	
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:32p
//Updated in $/LeapCC/Library/FrozenClass
//fixed issue no. 0002671
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/21/10    Time: 1:59p
//Updated in $/LeapCC/Library/FrozenClass
//fixed bug nos. 0002672, 0002660, 0002657, 0002656, 0002658, 0002659,
//0002661, 0002662
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:49p
//Created in $/LeapCC/Library/FrozenClass
//put new ajax files for time table to class
//
?>