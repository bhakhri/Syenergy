<?php
//-------------------------------------------------------
// Purpose: To delete TimeTable detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateTimeTableLabels');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '') {
        $errorMessage = LABEL_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
        $timeTableLabelManager =  TimeTableLabelManager::getInstance();

		  $cntArray = $timeTableLabelManager->countAssociatedClasses($REQUEST_DATA['labelId']);
		  $cnt = $cntArray[0]['cnt'];
		  if ($cnt > 0) {
          echo DEPENDENCY_CONSTRAINT;
          die;
		  }


        $foundArray2 = $timeTableLabelManager->checkInTimeTable(' AND ttl.timeTableLabelId='.$REQUEST_DATA['labelId']);
        if($foundArray2[0]['timeTableLabelId']!=''){
          echo DEPENDENCY_CONSTRAINT;
          die;  
        }
        $foundArray = $timeTableLabelManager->getTimeTableLabel(' WHERE timeTableLabelId='.$REQUEST_DATA['labelId']);
        if($foundArray[0]['isActive']==0){
            if($timeTableLabelManager->deleteTimeTableLabel($REQUEST_DATA['labelId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
       else{
           echo ACTIVE_TIME_TABLE_LABEL_DELETE;
       }  
    }
   else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Library/TimeTableLabel
//done changes for new Session End Activities
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 21/07/09   Time: 15:54
//Updated in $/LeapCC/Library/TimeTableLabel
//Added the check : Those time table label cannot be deleted which are
//used in time table
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTableLabel
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:46p
//Updated in $/Leap/Source/Library/TimeTableLabel
//applied role level access
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/01/08   Time: 11:01a
//Updated in $/Leap/Source/Library/TimeTableLabel
//Added database checkings
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:34p
//Created in $/Leap/Source/Library/TimeTableLabel
//Created TimeTable Labels
?>

