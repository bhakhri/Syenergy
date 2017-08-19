<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Jaineesh
// Created on : (02 Aug 2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','IndentMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['indentId']) || trim($REQUEST_DATA['indentId']) == '') {
        $errorMessage = INVALID_INDENT;
    }

	$indentId = $REQUEST_DATA['indentId'];

    if (trim($errorMessage) == '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
        require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
		$indentManager = IndentManager::getInstance();
		$getIndentArray = $indentManager->getIndentData("WHERE indentId = ".$REQUEST_DATA['indentId']." AND indentStatus = 0");
		$indentId = $getIndentArray[0]['indentId'];
		if($indentId != '') {
			$cancelledIndentTransStatus = $indentManager->cancelledIndentTrans($indentId);
			if($cancelledIndentTransStatus == false) {
				echo FAILURE;
				die;
			}
		}
		else {
			echo ONLY_PENDING_INDENT_CANCELLED;
			die;
		}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DELETE;
				die;
			 }
			 else {
				echo FAILURE;
				die;
			}
		}
		else {
			echo FAILURE;
			die;
		}
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/03/09    Time: 11:20a
//Updated in $/Leap/Source/Library/Department
//fixed bug nos.1214,1215,1219,1220
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/14/09    Time: 11:34a
//Updated in $/Leap/Source/Library/Department
//modified for access
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:49p
//Created in $/Leap/Source/Library/Department
//used for delete data in department table
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:31p
//Updated in $/Leap/Source/Library/Degree
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:22p
//Updated in $/Leap/Source/Library/Degree
//Adding AjaxEnabled Delete functionality
//*********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:55p
//Created in $/Leap/Source/Library/Degree
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:09p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
?>

