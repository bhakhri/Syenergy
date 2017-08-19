<?php
//-------------------------------------------------------
// Purpose: To delete group type detail
//
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupTypeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['groupTypeId']) || trim($REQUEST_DATA['groupTypeId']) == '') {
        $errorMessage = INVALID_GROUPTYPE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
        $groupTypeManager =  GroupTypeManager::getInstance();
        
            if($groupTypeManager->deleteGroupType($REQUEST_DATA['groupTypeId']) ) {
                echo DELETE;
			}
			else {
				echo DEPENDENCY_CONSTRAINT;
			}
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 1:09p
//Updated in $/LeapCC/Library/GroupType
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/GroupType
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 2:58p
//Updated in $/Leap/Source/Library/GroupType
//modification in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/26/08    Time: 4:01p
//Updated in $/Leap/Source/Library/GroupType
//modified in message
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:22p
//Created in $/Leap/Source/Library/GroupType
//function added for delete value
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

