<?php
//-------------------------------------------------------
// Purpose: To delete hostel detail
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['hostelId']) || trim($REQUEST_DATA['hostelId']) == '') {
        $errorMessage = INVALID_HOSTEL;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelManager.inc.php");
        $hostelManager =  HostelManager::getInstance();
        $recordArray = $hostelManager->checkInHostelRoom($REQUEST_DATA['hostelId']);
		$foundArray = $hostelManager -> checkExistanceHostelRoom('WHERE hostelId='.$REQUEST_DATA['hostelId']);
		if ($foundArray[0]['totalRecords'] > 0 OR $recordArray[0]['found'] > 0) {
			echo DEPENDENCY_CONSTRAINT; 
		}
		else {
            if($hostelManager->deleteHostel($REQUEST_DATA['hostelId'])) {
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
//User: Dipanjan     Date: 5/16/09    Time: 4:17p
//Updated in $/LeapCC/Library/Hostel
//dependency check(for hostelRoom) added by Gurkeerat
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Library/Hostel
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:10a
//Created in $/LeapCC/Library/Hostel
//ajax files include add, delete, edit & list functions made by Jaineesh
//& modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//add define access in module
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:30p
//Updated in $/Leap/Source/Library/Hostel
//modification in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/08    Time: 4:03p
//Updated in $/Leap/Source/Library/Hostel
//modified in message
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/27/08    Time: 2:07p
//Updated in $/Leap/Source/Library/Hostel
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:30p
//Created in $/Leap/Source/Library/Hostel
//ajax functions for add, edit, delete and search
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>

