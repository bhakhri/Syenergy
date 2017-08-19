<?php
//-------------------------------------------------------
// This File contains Validation and ajax function used for group change
// Author :Dipanjan Bhattacharjee
// Created on : 07-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdateStudentGroups');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$rollNo = $REQUEST_DATA['rollNo'];

$studentDetailArray = $studentManager->getStudentDetail($rollNo);
if (!isset($studentDetailArray[0]['studentId']) or empty($studentDetailArray[0]['studentId'])) {
	echo INVALID_ROLL_NO;
	echo ' or this student does not belong to current session/institute';
	die;
}
$studentId = $studentDetailArray[0]['studentId'];
$studentName = $studentDetailArray[0]['studentName'];
$classId = $studentDetailArray[0]['classId'];

$groupIds='';
foreach($REQUEST_DATA as $key=> $val){
  if(strpos('"'.$key.'"','chkop_')){
      if($groupIds!=''){
          $groupIds .=',';
      }
      $gr=explode('chkop_',$key);
      $groupIds .=$gr[1]; 
  }    
}

if($groupIds==''){
   echo NO_OPTIONAL_GROUP_DATA_FOUND;
   die;
}

$userGroupArray=explode(',',$groupIds);
$userGrCnt=count($userGroupArray);

//fetch groupShort Names
$newGroupNameArrays=$studentManager->getGroupsInformation(' WHERE groupId IN ('.$groupIds.')');
$newGroupArray=array();
foreach($newGroupNameArrays as $group){
    $newGroupArray[$group['groupId']]=trim($group['groupShort']);
	$groupShort = $newGroupArray[$group['groupId']];
	$newGroupShort .= "$groupShort,";
}
//get subject-group information
$subjectGrArray=$studentManager->getStudentSubjectOptionalGroups($studentId,$classId);
$cnt=count($subjectGrArray);
if($cnt==0){
   echo NO_OPTIONAL_GROUP_DATA_FOUND;
   die; 
}

//this array stores new values of groups
$userSubGroupArray=array();
$k=0;
for($i=0;$i<$cnt;$i++){
    $fl=0;
    $gfl=0;
    $grName='';
    $subjectId=$subjectGrArray[$i]['subjectId'];
    $groups=explode(',',$subjectGrArray[$i]['groupIds']);
    for($j=0;$j<$userGrCnt;$j++){
        if(in_array($userGroupArray[$j],$groups)){
            $gfl=$userGroupArray[$j];
            $fl++;
        }
    }
    if($fl!=1){
        echo INVALID_OPTIONAL_GROUP_COUNT;
        die;
    }
    $userSubGroupArray[$subjectId]=$gfl;
}


//this array stores old assigned values of groups
$dbSubjectGrArray=array();
$dbSubjectGrArray2=$studentManager->getStudentSubjectOptionalGroupsAssigned($studentId,$classId);
$cnt1=count($dbSubjectGrArray2);
$k=0;
for($i=0;$i<$cnt1;$i++){
   $dbSubjectGrArray[$dbSubjectGrArray2[$i]['subjectId']]=$dbSubjectGrArray2[$i]['groupId'];
   $groupShort =  $dbSubjectGrArray2[$i]['groupShort'];
   $oldGroupShort .= "$groupShort,";
}
/*
echo '<pre>';
print_r($dbSubjectGrArray);
echo '***<br/>';
print_r($userSubGroupArray);
die;
*/

$presentArray = $studentManager->getSingleField('attendance_code', 'attendanceCodeId', 'where attendanceCodePercentage = 100');
$presentAttendanceCode = $presentArray[0]['attendanceCodeId'];
$absentArray = $studentManager->getSingleField('attendance_code', 'attendanceCodeId', 'where attendanceCodePercentage = 0');
$absentAttendanceCode = $absentArray[0]['attendanceCodeId'];

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	foreach($dbSubjectGrArray as $key=>$val) {
        $subjectId=$key;
        $oldGroupId=$val;
        $oldGroupName=$dbGroupNameArray[$subjectId];
        $newGroupId=$userSubGroupArray[$subjectId];
        $newGroupName=$newGroupArray[$newGroupId];

		if ($oldGroupId != $newGroupId) {
			$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$newGroupId);
			$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
			$newLectureAttended = $REQUEST_DATA['att_'.$newGroupId.'_'.$subjectId];
			if (!isset($newLectureAttended) or !is_numeric($newLectureAttended)) {
				$newLectureAttended = 0;
			}
            
            if($newLectureAttended>$newLectureDelivered){
                echo MAX_LECTURE_DELIVERED_ERROR;
                die;
            }
         
			if ($newLectureDelivered > 0) {
				$attDetailsArray = $studentManager->getGroupAttendanceDetails($classId,$subjectId,$newGroupId);
				$totalAttended = 0;
				foreach($attDetailsArray as $attRecod) {
					$newEmployeeId = $attRecod['employeeId'];
					$attendanceType = $attRecod['attendanceType'];
					$attendanceCodeId = $attRecod['attendanceCodeId'];
					$periodId = $attRecod['periodId'];
					$attFromDate = $attRecod['fromDate'];
					$attToDate = $attRecod['toDate'];
					$attLectureDelivered = $attRecod['lectureDelivered'];
					$topicsTaughtId = $attRecod['topicsTaughtId'];
					if ($attendanceType == '1' or $attendanceType == 1) {
						if (($totalAttended + $attLectureDelivered) < $newLectureAttended) {
							$totalAttended += $attLectureDelivered;
							$return = $studentManager->addAttendanceInTransaction($classId,$newGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $attLectureDelivered, $topicsTaughtId);
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$newGroupName;
								die;
							}
						}
						else {
							$return = $studentManager->addAttendanceInTransaction($classId,$newGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $totalAttended + $attLectureDelivered - $newLectureAttended, $topicsTaughtId);
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$newGroupName;
								die;
							}
						}
					}
					else {
						if (($totalAttended + 1) <= $newLectureAttended) {
							$totalAttended += 1;
							$return = $studentManager->addAttendanceInTransaction($classId,$newGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $presentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$newGroupName;
								die;
							}
						}
						else {
							$return = $studentManager->addAttendanceInTransaction($classId,$newGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $absentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$newGroupName;
								die;
							}
						}
					}
				}
			}

			$return = $studentManager->quarantineAttendanceInTransaction($classId,$oldGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_.$oldGroupName;
				die;
			}

			$return = $studentManager->deleteAttendanceInTransaction($classId,$oldGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_DELETING_ATTENDANCE_FOR_.$oldGroupName;
				die;
			}

			$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$newGroupId);
			foreach($newTestArray as $newTestRecord) {
				$newTestId = $newTestRecord['testId'];
				$newTestName = $newTestRecord['testName'];
				$newMaxMarks = $newTestRecord['maxMarks'];
				$newMarksScored = $REQUEST_DATA['test_'.$newTestId.'_'.$subjectId];
				if (!isset($newMarksScored) or !is_numeric($newMarksScored)) {
					$newMarksScored = 0;
				}
                if($newMarksScored>$newMaxMarks){
                   echo MAX_MARKS_SCORED_ERROR;
                   die; 
                }
				if ($newMaxMarks > 0) {
					$return = $studentManager->addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, 1, 1);
					if ($return == false) {
						echo ERROR_WHILE_SAVING_MARKS_FOR_.$newGroupName._FOR_TEST_.$newTestName;
						die;
					}
				}
			}
			
			$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $oldGroupId);
			foreach($oldTestArray as $oldTestRecord) {
				$oldTestId = $oldTestRecord['testId'];
				$return = $studentManager->quarantineMarksInTransaction($oldTestId,$studentId, $classId, $subjectId);
				if ($return == false) {
					echo ERROR_WHILE_QUARANTINING_MARKS_FOR_.$oldGroupName._FOR_TEST_.$newTestName;
					die;
				}
				$return = $studentManager->deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $oldGroupId);
				if ($return == false) {
					echo ERROR_WHILE_DELETING_MARKS_FOR_.$oldGroupName._FOR_TEST_.$newTestName;
					die;
				}
			}
          
          if ($newGroupId != 0) {
          $return = $studentManager->updateStudentOptionalGroupInTransaction($classId,$subjectId,$studentId, $oldGroupId, $newGroupId);
          if ($return == false) {
            echo ERROR_WHILE_UPDATING_STUDENT_GROUP_.$newGroupName;
            die;
            }
          }  
       }
	}
	################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	$newGroupShort1 = substr($newGroupShort,0,-1);
	$oldGroupShort1 = substr($oldGroupShort,0,-1);
	$student = $studentName.'('.$rollNo.')';
	$auditTrialDescription = "Following Optional groups have been changed for student $student : Old groups : $oldGroupShort1 New groups : $newGroupShort1" ;
	$type = GROUPS_CHANGED; //Groups Changed
	$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
	if($returnStatus == false) {
		echo  ERROR_WHILE_SAVING_DATA_IN_AUDIT_TRAIL;
		die;
	}
	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo SUCCESS;
	}
	else {
		echo FAILURE;
	}
}
else {
	echo FAILURE;
}


//$History: ajaxInitUpdateStudentGroup.php $
?>