<?php
//-------------------------------------------------------
// Purpose: To add in lecture
// Author : Jaineesh
// Created on : (30.03.2009 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/LecturePercentManager.inc.php");   
	define('MODULE','LecturePercent');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
    $percentManager  = LecturePercentManager::getInstance();

    
	$errorMessage ='';
    
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));    
    
    if($attendanceSetId == '') {  
      echo SELECT_ATTENDANCE_SET;
	  die;
    }
    
    //$foundArray = $percentManager->getActiveTimeTableLabelId();
    //$activeTimeTableLabelId  = $foundArray[0]['timeTableLabelId'];

/*
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$lectureAttendedFrom = $REQUEST_DATA['lectureAttendedFrom'];
	$lectureAttendedTo = $REQUEST_DATA['lectureAttendedTo'];
	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$degreeId = $REQUEST_DATA['degree'];

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
*/
	
	if (trim($errorMessage) == '') {
	
        $totalValues = count($REQUEST_DATA['lectureDelivered']);
		
		if($totalValues == 0 ) {
			//$str = ' WHERE subjectTypeId='.$subjectTypeId.' AND timeTableLabelId='.$timeTableLabelId.' AND instituteId = '.$instituteId.' AND degreeId = '.$degreeId;
            $str = ' WHERE attendanceSetId='.$attendanceSetId.' AND instituteId = '.$instituteId;       
			$returnStatus = $percentManager->deleteLecturePercent($str);
//			echo ('Please Enter Lecture Delievered & Attended');
			echo SLAB_DELETE_SUCCESSFULLY;
			die;
		}

        // Check Validations
        for($i = 0; $i < $totalValues; $i++) {
              if($REQUEST_DATA['lectureDelivered'][$i] == '' || $REQUEST_DATA['lectureAttendedFrom'][$i] == '' || $REQUEST_DATA['lectureAttendedTo'][$i] == '' || $REQUEST_DATA['marksScored'][$i] == '') {
                  echo "Please fill the value of empty box"; 
                  die;   
              }

			  if($REQUEST_DATA['lectureAttendedFrom'][$i] > $REQUEST_DATA['lectureAttendedTo'][$i] ) {
                  echo "Lecture Attended From cannot be greater than Lecture Attended To";          
                  die;
              }

			  if($REQUEST_DATA['lectureAttendedFrom'][$i] > $REQUEST_DATA['lectureDelivered'][$i] ) {
                  echo "Lecture Attended From cannot be more than Lecture Delivered";          
                  die;
              }

              if($REQUEST_DATA['lectureAttendedTo'][$i] > $REQUEST_DATA['lectureDelivered'][$i] ) {
                  echo "Lecture Attended To cannot be more than Lecture Delivered";          
                  die;
              }
          }


			for($k = 0; $k < $totalValues; $k++) {
			$kLectureDelivered = $REQUEST_DATA['lectureDelivered'][$k];
            $kLectureAttendedFrom = $REQUEST_DATA['lectureAttendedFrom'][$k];
            $kLectureAttendedTo =	$REQUEST_DATA['lectureAttendedTo'][$k];
				for($l = $k+1; $l < $totalValues; $l++) {
					$lLectureDelivered = $REQUEST_DATA['lectureDelivered'][$l];
					$lLectureAttendedFrom = $REQUEST_DATA['lectureAttendedFrom'][$l];
					$lLectureAttendedTo = $REQUEST_DATA['lectureAttendedTo'][$l];
					if (($kLectureDelivered == $lLectureDelivered && $lLectureAttendedFrom >= $kLectureAttendedFrom && $lLectureAttendedFrom <= $kLectureAttendedTo) || ($kLectureDelivered == $lLectureDelivered && $lLectureAttendedTo >= $kLectureAttendedFrom && $lLectureAttendedTo <= $kLectureAttendedTo)) {
						echo "The range of Lecture Attended From and To has already given";
						die;
					}
				}
            }
			
        // Delete all Records
        //$str = ' WHERE subjectTypeId='.$subjectTypeId.' AND timeTableLabelId='.$timeTableLabelId.' AND instituteId = '.$instituteId.' AND degreeId = '.$degreeId;
        $str = ' WHERE attendanceSetId='.$attendanceSetId.' AND instituteId = '.$instituteId;  
        $returnStatus = $percentManager->deleteLecturePercent($str);
        if($returnStatus === false) {
           $errorMessage = FAILURE;
        }
        $str = '';
        for($i = 0; $i < $totalValues; $i++) {
			$lectureDelivered = $REQUEST_DATA['lectureDelivered'][$i];
            $lectureAttendedFrom = $REQUEST_DATA['lectureAttendedFrom'][$i];
			$lectureAttendedTo = $REQUEST_DATA['lectureAttendedTo'][$i];
            $marksScored = $REQUEST_DATA['marksScored'][$i];
			if($lectureAttendedFrom >= 0) {
				if($lectureAttendedFrom <= $lectureAttendedTo) {
					for($lectureAttendedFrom;$lectureAttendedFrom<=$lectureAttendedTo;$lectureAttendedFrom++) {
						if(!empty($str)) {
							$str .= ',';
						}
                        $str .= "($lectureDelivered, $lectureAttendedFrom, $marksScored, $instituteId, $attendanceSetId)";
						//$str .= "($lectureDelivered, $lectureAttendedFrom, $marksScored, $subjectTypeId, $timeTableLabelId, $instituteId, $degreeId)";
					}
				}
			}
        }
	
		$returnStatus = $percentManager->addLecturePercent($str);
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			echo SUCCESS;
		}
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitLecturePercent.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 12/29/09   Time: 6:52p
//Updated in $/LeapCC/Library/LecturePercent
//attendance Set Id base code updated
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Library/LecturePercent
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/18/09   Time: 3:33p
//Updated in $/LeapCC/Library/LecturePercent
//Add Time Table Label dropdown and change in interface of attendance
//marks slabs. Now user can add the marks between the range for Lecture
//attended. 
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/LecturePercent
//changed queries to add instituteId
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 4/22/09    Time: 10:51a
//Updated in $/LeapCC/Library/LecturePercent
//remove condition if lecture delivered  already exist
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/31/09    Time: 11:42a
//Updated in $/LeapCC/Library/LecturePercent
//modified in messaging
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/31/09    Time: 11:19a
//Updated in $/LeapCC/Library/LecturePercent
//modified code to make it working even better
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/31/09    Time: 10:21a
//Updated in $/LeapCC/Library/LecturePercent
//modified to check some validations
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/30/09    Time: 3:58p
//Updated in $/LeapCC/Library/LecturePercent
//modified for delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/30/09    Time: 1:43p
//Created in $/LeapCC/Library/LecturePercent
//

?>