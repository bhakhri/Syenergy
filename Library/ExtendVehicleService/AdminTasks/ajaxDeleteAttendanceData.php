<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the student exam marks
//
// Author : Dipanjan Bbhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    if(trim($REQUEST_DATA['moduleName'])==''){
        echo 'Access Denied';
        die;
    }
    define('MODULE',trim($REQUEST_DATA['moduleName']));
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
    $attendanceId=trim($REQUEST_DATA['attendanceId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    if($attendanceId=='' or $employeeId==''){
        echo 'Invalid Data';
        die;
    }


    /*************FETCH ATTENDANCE RECORDS*************/
     $attendanceRecordArray=$teacherManager->fetchAttendanceRecords($attendanceId);
     if(count($attendanceRecordArray)>0  and is_array($attendanceRecordArray)){
         $classId=$attendanceRecordArray[0]['classId'];
         $subjectId=$attendanceRecordArray[0]['subjectId'];
         $groupId=$attendanceRecordArray[0]['groupId'];
         $fromDate=$attendanceRecordArray[0]['fromDate'];
         $toDate=$attendanceRecordArray[0]['toDate'];
         $periodId=$attendanceRecordArray[0]['periodId'];
         if($periodId==''){
             $periodId=-1;
         }
         $empId=$attendanceRecordArray[0]['employeeId'];
         /**********THIS CHECK HAS BEEN REMOVED AS INSTRUCTED BY SACHIN SIR AS ON 25.11.2009************
         if($employeeId!=$empId){ //only owner of this attendance record can delete this record
             echo 'You Cannot delete this attendance';
             die;
         }
        */
     }
     else{
         echo 'Invalid Data';
         die;
     }
    /*************FETCH ATTENDANCE RECORDS*************/


//***********************************************STRAT TRANSCATION************************************************
if(SystemDatabaseManager::getInstance()->startTransaction()){

    //quarantine attendance
     $ret1=$teacherManager->quarantineAttendanceRecords($classId,$subjectId,$groupId,$fromDate,$toDate,$periodId,$employeeId);
     if($ret1===false){
        echo FAILURE;
        die;
     }

    //delete attendance
    $ret2=$teacherManager->deleteAttendanceRecords($classId,$subjectId,$groupId,$fromDate,$toDate,$periodId,$employeeId);
    if($ret2===false){
        echo FAILURE;
        die;
    }

     if($periodId!=-1){//for daily attendance,update duty_leave table also
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $ret3=$teacherManager->updateDutyLeave($classId,$subjectId,$groupId,$fromDate,$periodId,$sessionId,$instituteId);
        if($ret3==false){
           die(FAILURE);
         }
     }
	 	########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
	$type = ATTENDANCE_IS_DELETED_BY_TEACHER_OR_ADMINISTRATOR;
	$className='';
	$groupName ='';
	$subjectCode='';
	$employeeName='';
	if($classId !=''){
		$classNameArray =  $teacherManager->getClass($classId);
		if($classNameArray == false){
			 echo FAILURE;
			 die;
		}
		$className = $classNameArray[0]['className'];
	}
	if($subjectId != ''){
		$SubjectNameArray = $teacherManager->getSubject($subjectId);
		if($SubjectNameArray == false){
			 echo FAILURE;
			 die;
		}
		$subjectCode = $SubjectNameArray[0]['subjectCode'];
	}
	if($employeeId != ''){
		$teacherNameArray = $teacherManager->getTeacherName($employeeId);
		if($teacherNameArray == false){
			 echo FAILURE;
			 die;
		}
		$employeeName=$teacherNameArray[0]['employeeName'];
	}
	else{
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if($roleId != 1){
			$employeeName = $sessionHandler->getSessionVariable('EmployeeName');
		}
	}
	if($groupId!=''){
		$groupNameArray = $teacherManager->getGroupName($groupId);
		if($groupNameArray ==false){
			 echo FAILURE;
			 die;
		}
		$groupName=$groupNameArray[0]['groupName'];
	}
	$typeofAttendance = "Bulk";
	$date ="from ".UtilityManager::formatDate($fromDate)." to " .UtilityManager::formatDate($toDate)."";
	if($REQUEST_DATA['moduleName'] == "DailyAttendance"){
		$typeofAttendance = "Daily";
		$date = "for ".UtilityManager::formatDate($fromDate)."";
	}
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if($roleId == 1){
		$userName = $sessionHandler->getSessionVariable('UserName');
		$employeeName = "user ".$userName." on the behalf of ".$employeeName ;
	}
	$auditTrialDescription = $typeofAttendance." Attendance has been deleted by ".$employeeName." for class ".$className.", group  ".$groupName.", subject  ".$subjectCode." ".$date;

	$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
	if($returnStatus == false) {
		echo  "Error while saving data for audit trail";
		die;
	}

	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################

    //*****************************COMMIT TRANSACTION*************************
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo DELETE;
        die;
     }
     else {
        echo FAILURE;
     }

}
else{
      echo FAILURE;
      die;
}
// for VSS
// $History: ajaxDeleteAttendanceData.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:30
//Updated in $/LeapCC/Library/AdminTasks
//Made enhancements in Attendance History : Teacher can now view other
//teachers attendance and also edit & delete them,if they have the same
//time table allocation.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:09
//Created in $/LeapCC/Library/AdminTasks
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
?>