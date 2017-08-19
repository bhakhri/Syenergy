<?php
//-------------------------------------------------------
// THIS FILE IS USED TO DELETE LECTURE TYPE 
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LectureTypeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['lecturetypeId']) || trim($REQUEST_DATA['lecturetypeId']) == '') {
        $errorMessage = 'Invalid Lecture type';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LectureManager.inc.php");
        $lectureManager =  LectureTypeManager::getInstance();
        //$recordArray = $lectureManager->checkInInstitute($REQUEST_DATA['lecturetypeId']);
        //if($recordArray[0]['found']==0) {
            if($lectureManager->deleteLectureType($REQUEST_DATA['lecturetypeId']) ) {
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
//User: Dipanjan     Date: 8/10/09    Time: 3:15p
//Updated in $/LeapCC/Library/Lecture
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Lecture
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Library/Lecture
//updated formatting
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:36p
//Updated in $/Leap/Source/Library/Lecture
//updated messages
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/08    Time: 6:41p
//Updated in $/Leap/Source/Library/Lecture
//updated dependency constraint on delete function
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/30/08    Time: 3:39p
//Created in $/Leap/Source/Library/Lecture
//updated the ajax code
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/25/08    Time: 4:36p
//Created in $/Leap/Source/Library/Evaluation
//intial checkin
?>