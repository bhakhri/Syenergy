<?php
//-------------------------------------------------------
// Purpose: To delete designation detail
//
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DesignationMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['designationId']) || trim($REQUEST_DATA['designationId']) == '') {
        $errorMessage = INVALID_DESIGNATION;
    }

	
	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/DesignationManager.inc.php");
		$designationManager =  DesignationManager::getInstance();

		$designationInsituteArray = $designationManager->getCheckDesignationInstitute("AND des.designationId =".$REQUEST_DATA['designationId']);
        if ($designationInsituteArray[0]['designationId'] > 0) {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
		$designationUniversityArray = $designationManager->getCheckDesignationUniversity("AND des.designationId =".$REQUEST_DATA['designationId']);
        if ($designationUniversityArray[0]['designationId'] > 0) {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
		$designationArray = $designationManager->getCheckDuplicateDesignation("AND des.designationId =".$REQUEST_DATA['designationId']);
        if ($designationArray[0]['designationId'] > 0) {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
		else { 
			if ($designationManager->deleteDesignation($REQUEST_DATA['designationId']) ) {
					echo DELETE;
				}
		}
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/22/09    Time: 11:28a
//Updated in $/LeapCC/Library/Designation
//fixed bug no.0000610
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/24/09    Time: 4:10p
//Updated in $/LeapCC/Library/Designation
//fixed bug no.0000225
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Designation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:34p
//Updated in $/Leap/Source/Library/Designation
//define access values
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/26/08    Time: 3:58p
//Updated in $/Leap/Source/Library/Designation
//modified message 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:21p
//Created in $/Leap/Source/Library/Designation
//include ajax delete function
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:59p
//Created in $/Leap/Source/Library/Periods
//function is used for delete period
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>