<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Time Table
//
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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['labelName']) || trim($REQUEST_DATA['labelName']) == '') {
        $errorMessage .= ENTER_LABEL_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['fromDate']) || trim($REQUEST_DATA['fromDate']) == '')) {
        $errorMessage .= EMPTY_FROM_DATE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['toDate']) || trim($REQUEST_DATA['toDate']) == '')) {
        $errorMessage .= EMPTY_TO_DATE."\n";
    }

    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];
    $condition = " WHERE 1=2  ";//WHERE ( (startDate BETWEEN '$fromDate' AND '$toDate') OR (endDate BETWEEN '$fromDate' AND '$toDate') )";
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
        require_once(DA_PATH . '/SystemDatabaseManager.inc.php');  
        $foundArray = TimeTableLabelManager::getInstance()->getTimeTableLabel(' WHERE UCASE(labelName)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'"');
        if(trim($foundArray[0]['labelName'])=='') {  //DUPLICATE CHECK
           $foundArray1 = TimeTableLabelManager::getInstance()->getTimeTableLabel($condition);
           if(trim($foundArray1[0]['labelName'])=='') {  //DUPLICATE CHECK
                $returnStatus = TimeTableLabelManager::getInstance()->addTimeTableLabel();
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
               else {
                    if(trim($REQUEST_DATA['isActive'])==1){
                      $timeTableLabelId=SystemDatabaseManager::getInstance()->lastInsertId();   
                     // $activeLabelArray=TimeTableLabelManager::getInstance()->makeAllTimeTableLabelInActive(" AND ttl.timeTableLabelId !=".$timeTableLabelId); //make previous entries inactive
                    }
                   echo SUCCESS;           
                }
        }
        else {
            echo FROM_TO_ALREADY_EXIST;
        }
      }
      else {
            echo LABEL_ALREADY_EXIST;
      }
   }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 2/16/10    Time: 12:15p
//Updated in $/LeapCC/Library/TimeTableLabel
//done changes FCNS No. 1296
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/10/09    Time: 2:35p
//Updated in $/LeapCC/Library/TimeTableLabel
//start and end date for fields added
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