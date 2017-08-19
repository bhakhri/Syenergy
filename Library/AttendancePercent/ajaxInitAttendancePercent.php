<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AttendancePercentManager.inc.php");
define('MODULE','AttendancePercent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $percentManager  = AttendancePercentManager::getInstance();

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));

    //$timeTableLabelId = add_slashes(trim($REQUEST_DATA['timeTableLabelId']));

    $errorMessage ='';
    if(trim($attendanceSetId) == '') {
        $errorMessage = SELECT_ATTENDANCE_SET;
        die();
    }

	/*
    if(trim($REQUEST_DATA['timeTableLabelId']) == '') {
       $errorMessage = "Please active time table";
       die();
    }
	*/


/* if(trim($REQUEST_DATA['degreeId']) == '') {
        $errorMessage = SELECT_DEGREE;
        die();
    }

    if(trim($REQUEST_DATA['subjectTypeId']) == '') {
        $errorMessage = SELECT_SUBJECT_TYPE;
        die();
    }
*/
    //$foundArray = $percentManager->getActiveTimeTableLabelId();
    //$activeTimeTableLabelId  = $foundArray[0]['timeTableLabelId'];

  /*
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
  */

    if (trim($errorMessage) == '') {
        $totalValues = count($REQUEST_DATA['percentFrom']);
        if($totalValues == 0 ) {
            //$str = ' WHERE subjectTypeId='.$subjectTypeId.' AND timeTableLabelId='.$timeTableLabelId.'
            //         AND instituteId = '.$instituteId.' AND degreeId='.$degreeId;
            $str = ' WHERE attendanceSetId='.$attendanceSetId.' AND instituteId = '.$instituteId;
            $returnStatus = $percentManager->deleteAttendancePercent($str);
            echo SLAB_DELETE_SUCCESSFULLY;
            die;
        }



  /*      // Check Validations
        if($totalValues > 0 ) {
           for($i = 0; $i < $totalValues; $i++) {
              if($REQUEST_DATA['percentFrom'][$i] == '' || $REQUEST_DATA['percentTo'][$i] == '' || $REQUEST_DATA['marksScored'][$i] == '') {
                  echo "Please fill the value of empty box";
                  die;
              }

              if($REQUEST_DATA['percentFrom'][$i] > $REQUEST_DATA['percentTo'][$i] ) {
                  echo "Attendance percent from cannot be more than percent to.";
                  die;
              }
          }

          for($i = 0; $i < $totalValues; $i++) {
            $ifrom =$REQUEST_DATA['percentFrom'][$i];
             $ito =$REQUEST_DATA['percentTo'][$i];
            for($k = $i+1; $k < $totalValues; $k++) {
               $jfrom =$REQUEST_DATA['percentFrom'][$k];
               $jto =$REQUEST_DATA['percentTo'][$k];
               for($j=$ifrom; $j <= $ito; $j++) {
                 if($j == $jfrom || $j == $jto) {
                   echo "Wrong data input";
                   die;
                 }
               }
            }
          }
  */

            // Delete all Records
            //$str = ' WHERE subjectTypeId='.$subjectTypeId.' AND timeTableLabelId='.$timeTableLabelId.'
            //         AND instituteId = '.$instituteId.' AND degreeId='.$degreeId;
            $str = ' WHERE attendanceSetId='.$attendanceSetId.' AND instituteId = '.$instituteId;
            $returnStatus = $percentManager->deleteAttendancePercent($str);
            if($returnStatus === false) {
               $errorMessage = FAILURE;
            }
            $str = '';
            for($i = 0; $i < $totalValues; $i++) {
                $percentFrom = $REQUEST_DATA['percentFrom'][$i];
                $percentTo = $REQUEST_DATA['percentTo'][$i];
                $marksScored = $REQUEST_DATA['marksScored'][$i];
                if(!empty($str)) {
                    $str .= ',';
                }
                //$str .= "($percentFrom, $percentTo, $marksScored, $instituteId, $degreeId)";
                $str .= "($percentFrom, $percentTo, $marksScored, $instituteId, $attendanceSetId)";
            }
            $returnStatus = $percentManager->addAttendancePercent($str);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;
            }
        }
   // }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAttendancePercent.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/21/10    Time: 12:58p
//Updated in $/LeapCC/Library/AttendancePercent
//validation & condition format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/29/09   Time: 2:05p
//Updated in $/LeapCC/Library/AttendancePercent
//new enhancement attendance Set Id base checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/20/09   Time: 12:26p
//Updated in $/LeapCC/Library/AttendancePercent
//degreeId, timeTableLabelId added
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/AttendancePercent
//changed queries to add instituteId
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/09    Time: 12:23p
//Updated in $/LeapCC/Library/AttendancePercent
//code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 12:28p
//Created in $/LeapCC/Library/AttendancePercent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 11:27a
//Created in $/Leap/Source/Library/ScTimeTable
//coursewise time table added
?>