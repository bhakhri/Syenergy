<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A TimeTable Label
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
define('MODULE','CreateTimeTableLabels');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if (!isset($REQUEST_DATA['labelName']) || trim($REQUEST_DATA['labelName']) == '') {
        $errorMessage .= ENTER_LABEL_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['fromDate1']) || trim($REQUEST_DATA['fromDate1']) == '')) {
        $errorMessage .= EMPTY_FROM_DATE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['toDate1']) || trim($REQUEST_DATA['toDate1']) == '')) {
        $errorMessage .= EMPTY_TO_DATE."\n";
    }

	/*
	$cntArray = TimeTableLabelManager::getInstance()->countAssociatedTimeTable($REQUEST_DATA['labelId']);
	$cnt = $cntArray[0]['cnt'];
	if ($cnt > 0) {
		echo DEPENDENCY_CONSTRAINT_EDIT;
		die;
	}
	*/

    $fromDate = $REQUEST_DATA['fromDate1'];
    $toDate = $REQUEST_DATA['toDate1'];
    $condition = " WHERE 1=2 AND ( (startDate BETWEEN '$fromDate' AND '$toDate') OR (endDate BETWEEN '$fromDate' AND '$toDate') )
                   AND timeTableLabelId!=".$REQUEST_DATA['labelId'] ;

    if (trim($errorMessage) == '') {
         if(trim($REQUEST_DATA['isActive'])==1){
            $foundArray = TimeTableLabelManager::getInstance()->getTimeTableLabel(' WHERE UCASE(labelName)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'" AND timeTableLabelId!='.$REQUEST_DATA['labelId']);
            if(trim($foundArray[0]['labelName'])=='') {  //DUPLICATE CHECK
             $foundArray1 = TimeTableLabelManager::getInstance()->getTimeTableLabel($condition);
               if(trim($foundArray1[0]['labelName'])=='') {  //DUPLICATE CHECK
                    $returnStatus = TimeTableLabelManager::getInstance()->editTimeTableLabel($REQUEST_DATA['labelId']);
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
                          $timeTableLabelId=$REQUEST_DATA['labelId'];
                          //$activeLabelArray=TimeTableLabelManager::getInstance()->makeAllTimeTableLabelInActive(" AND ttl.timeTableLabelId !=".$timeTableLabelId); //make previous entries inactive
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
       else{
            //check whether any time table label active or not.if not then do not update this label
            $activeCheckArray=TimeTableLabelManager::getInstance()->getTimeTableLabel(' WHERE isActive=1 AND timeTableLabelId!='.$REQUEST_DATA['labelId']);
            if(trim($activeCheckArray[0]['isActive'])!=''){  //if there is a active label other than this
            $foundArray = TimeTableLabelManager::getInstance()->getTimeTableLabel(' WHERE UCASE(labelName)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'" AND timeTableLabelId!='.$REQUEST_DATA['labelId']);
            if(trim($foundArray[0]['labelName'])=='') {  //DUPLICATE CHECK
               $foundArray1 = TimeTableLabelManager::getInstance()->getTimeTableLabel($condition);
               if(trim($foundArray1[0]['labelName'])=='') {  //DUPLICATE CHECK
                $returnStatus = TimeTableLabelManager::getInstance()->editTimeTableLabel($REQUEST_DATA['labelId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
               else{
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
          echo ACTIVE_TIME_TABLE_LABEL_UPDATE;
         }
       }
     }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Library/TimeTableLabel
//done changes as per FCNS No. 1625
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
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/13/08   Time: 11:05a
//Updated in $/Leap/Source/Library/TimeTableLabel
//Corrected problem during editing
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
