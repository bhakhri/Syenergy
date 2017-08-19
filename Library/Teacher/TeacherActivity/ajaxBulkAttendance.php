<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
	global $sessionHandler;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    define('MODULE','BulkAttendance');
    define('ACCESS','edit');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	$roleId=$sessionHandler->getSessionVariable('RoleId');
	if($roleId==2) {
       if($sessionHandler->getSessionVariable('BULK_ATTENDANCE_ALLOWED')=='0') {
          echo "You don't have the permission to take bulk attendance"; 
          die; 
       }
    }
	
 //-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------




 
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}
    //if bulk attendance is not allowed
    /*
    if($sessionHandler->getSessionVariable('BULK_ATTENDANCE_ALLOWED')!=1){
        echo 'You do not have the permission to take bulk attendance';
        die;
    }
    */

    //echo "<pre>";
   // print_r($REQUEST_DATA);
    //die;

    $classId=trim($REQUEST_DATA['classId']);
    if($classId==''){
         echo 'Required parameter missing';
         die;
    }
    /*CHECK FOR FROZEN CLASS*/
     $isFrozenArray=CommonQueryManager::getInstance()->checkFrozenClass($classId);
     if($isFrozenArray[0]['isFrozen']==1){
         echo FROZEN_CLASS_RESTRICTION.$isFrozenArray[0]['className'];
         die;
     }
    /*CHECK FOR FROZEN CLASS*/

    $serverDate=explode('-',date('Y-m-d'));
    $sDate0=$serverDate[0].$serverDate[1].$serverDate[2];
    $sDate1=explode('-',$REQUEST_DATA['fromDate']);
    $sDate2=$sDate1[0].$sDate1[1].$sDate1[2];
    if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))!='' and trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT')) !="0"){
        if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))==-1 && trim($sessionHandler->getSessionVariable('RoleId'))==2)
        {
          echo "You can not take attendance because attendance has been freezed by the Admin.";
          die;  
        }
        elseif(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))>0 && trim($sessionHandler->getSessionVariable('RoleId'))==2)
        {
            $threshold=trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'));
            $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
            $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
            $diff=$end_date - $start_date;
            if($diff>$threshold){
                echo "You can not take attendance older than ".$threshold." days";
                die;
            }
        }
        
    }


    //check for conflicting dates entry
    $bulkAttendanceRecordConflictArray=$teacherManager->checkBulkAttendanceConflict($filter);
    $cnt1=count($bulkAttendanceRecordConflictArray);
    $sstr='';
    if($cnt1 > 0 and is_array($bulkAttendanceRecordConflictArray)){
        if($cnt1>1){
           for($i=0;$i<$cnt1;$i++){
               if($sstr!=''){
                   $sstr .=',';
               }
               $sstr .='( '.$bulkAttendanceRecordConflictArray[$i]['fromDate'].' ) To ( '.$bulkAttendanceRecordConflictArray[$i]['toDate'].' )';
           }
           echo BULK_ATTENDANCE_RESTRICTION_EXISTING_SC.' '.$sstr;
           die;
        }
        elseif($bulkAttendanceRecordConflictArray[0]['fromDate']==$REQUEST_DATA['fromDate'] and $bulkAttendanceRecordConflictArray[0]['toDate']==$REQUEST_DATA['toDate']){
        }
        else{
            echo BULK_ATTENDANCE_RESTRICTION_EXISTING_SC.' ( '.$bulkAttendanceRecordConflictArray[0]['fromDate'].' ) To ( '.$bulkAttendanceRecordConflictArray[0]['toDate'].' )';
            die;
        }
    }


    /*********USED TO CHECK DUPLICATE RECORDS**********/
    $duplicateRecordArray=$teacherManager->bulkAttendanceDuplicateCheck($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate']);
    $duplicateStudentId=UtilityManager::makeCSList($duplicateRecordArray,'studentId',',');
    $duplicateStudentIds=explode(',',$duplicateStudentId);
    /*********USED TO CHECK DUPLICATE RECORDS**********/

    $attendanceIds='';
    $attCount=0;
    if(trim($REQUEST_DATA['attendanceIds'])!='') {
      $attendanceIds =split("," , $REQUEST_DATA['attendanceIds']);
    }
    $studentIds =split("," , $REQUEST_DATA['studentIds']);
    $memc =split("," , $REQUEST_DATA['memofclass']);
    $ldel =split("," , $REQUEST_DATA['delivered']);
    $latt =split("," , $REQUEST_DATA['attended']);

    $insQuery="";
    $empId=$sessionHandler->getSessionVariable('EmployeeId');
    $userId=$sessionHandler->getSessionVariable('UserId');
    $attCount = count($studentIds);

    $errorMessage=SUCCESS;

	$fins=1; //flag for insert
    $fup=0;  //flag for update
    $fup2=1; //flag for update(when insert in done for update)
    $topicsTaughtId=0;

	$ret=explode("~",$REQUEST_DATA['subjectTopicId']); //[0]:subjectTopicId,[1]:topicsTaughtId

	$subjectTopicIds=$REQUEST_DATA['subjectTopicId'];
	$taughtId=$REQUEST_DATA['taught'];

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    //$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'isLeet,universityRollNo';

    $sortField = $REQUEST_DATA['sortField'];

    if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="numericRollNo";
    }
    elseif($sortField=="universityRollNo"){
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
    else {
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }


    $orderBy = " $sortField2 $sortOrderBy";


    //used to fetch student's attendance till befor "From Date"
    $searchStr=' AND s.studentId IN ('.$REQUEST_DATA['studentIds'].') AND c.classId='.$REQUEST_DATA['classId'].' AND su.subjectId='.$REQUEST_DATA['subjectId'].' AND att.groupId='.$REQUEST_DATA['groupId'].' AND att.fromDate < "'.$REQUEST_DATA['fromDate'].'"';
    //as this variable name is hard coded in query
    if(trim($REQUEST_DATA['group'])==''){
     $REQUEST_DATA['group']=$REQUEST_DATA['groupId'];
    }
    $prevoiusRecordArray=$teacherManager->getStudentAttendanceTillDate($searchStr,' ',$orderBy);
    $prevoiusRecordCount=count($prevoiusRecordArray);


 //****************************************************************************************************************
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
if(SystemDatabaseManager::getInstance()->startTransaction()) {
  //started insert and update operations

  //if($attCount > 0 && is_array($attendanceIds)){

  if($attCount > 0 ){

    for($i=0; $i <$attCount; $i++ ){

       /***************************
            LOGIC: If prev attendance is 0 or ''(blank) then insert or update as it is
                                           OTHERWISE
            subtract the prev value from user entered value and then insert/updates it
        ***************************/
         $studentId=$studentIds[$i];
         $flk=0;

         for($m=0;$m<$prevoiusRecordCount;$m++){  //as previous and current students can mismatch
            if($studentId==$prevoiusRecordArray[$m]['studentId']){
                 if(abs($prevoiusRecordArray[$m]['attended'])=='' or abs($prevoiusRecordArray[$m]['attended'])==0){
                     $lAttended=$latt[$i];
                 }
                 else{
                     $lAttended=abs($latt[$i]-abs($prevoiusRecordArray[$m]['attended']));
                 }

                 if($prevoiusRecordArray[$m]['delivered']=='' or $prevoiusRecordArray[$m]['delivered']==0){
                     $lDelivered=$ldel[$i];
                 }
                 else{
                     $lDelivered=abs($ldel[$i]-abs($prevoiusRecordArray[$m]['delivered']));
                 }
                 $flk=1;
                 break;
            }
         }
        if(!$flk){
            $lAttended=$latt[$i];
            $lDelivered=$ldel[$i];
        }

        //*****if moc=0, then lecture delivered and attended is also 0 ****
        if($memc[$i]==0){
            $lDelivered=0;
            $lAttended=0;
        }

        if($attendanceIds[$i]!="-1" && $attendanceIds[$i]!='' ){
           if($fup2==1){
             //add topics_taught table
             //$topicsTaughtId=$teacherManager->addTopicsTaught($empId,$ret[0],trim($REQUEST_DATA['comments']));
             $r1=$teacherManager->editTopicsTaught($subjectTopicIds,$taughtId,$empId,trim($REQUEST_DATA['comments']));
             if($r1===false){
                 echo FAILURE;
                 die;
             }
             $fup2=0;
            }

            if($lAttended=='') {
              $lAttended=0;
            }
            if($lDelivered=='') {
              $lDelivered=0;
            }

           //these values are already in attendance_bulk table.so update them [update funtion will be called multiple times]
           //$returnStatus=$teacherManager->editBulkAttendance($attendanceIds[$i],$ldel[$i],$latt[$i],$memc[$i],$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate'],$taughtId);
           $r2=$teacherManager->editBulkAttendance($attendanceIds[$i],$lDelivered,$lAttended,$memc[$i],$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate'],$taughtId);
           if($r2===false){
                 echo FAILURE;
                 die;
           }
        }
       else{

          if(in_array($studentIds[$i],$duplicateStudentIds)){
             continue; //if there is multiple clicks check whether studentIds exists or not.if exists then do not execute insert query
          }
          //these values are not in attendance_bulk table.so insert them
          if($fins==1){
             //add topics_taught table
             //$topicsTaughtId=$teacherManager->addTopicsTaught($empId,$ret[0],trim($REQUEST_DATA['comments']));
             $r3=$teacherManager->addTopicsTaught($empId,$subjectTopicIds,trim($REQUEST_DATA['comments']));
             if($r3===false){
                 echo FAILURE;
                 die;
             }
             $topicsTaughtId=SystemDatabaseManager::getInstance()->lastInsertId();
             if($topicsTaughtId===false){
                 echo FAILURE;
                 die;
             }
             $fins=0;
          }

          if($lAttended=='') {
            $lAttended=0;
          }
          if($lDelivered=='') {
            $lDelivered=0;
          }
          //create multiple insert query
           if($insQuery==""){
              //$insQuery="($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$ldel[$i],$latt[$i],$userId,$topicsTaughtId)";
              $insQuery="($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$lDelivered,$lAttended,$userId,$topicsTaughtId)";
           }
          else{
              //$insQuery=$insQuery." , ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$ldel[$i],$latt[$i],$userId,$topicsTaughtId)";
              $insQuery=$insQuery." , ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$lDelivered,$lAttended,$userId,$topicsTaughtId)";
          }
		  $studentsIdArray[] = $studentIds[$i];
	   }
        //if($returnStatus === false) {  //tracking error in update query
        //      $errorMessage = FAILURE;
        //}
      }
    }

     //insert query will be execute one time but multiple insertions will be done
     if($insQuery!=""){  //check if insertion query should be performed or not
      $returnStatus=$teacherManager->addBulkAttendance($insQuery) ;
      if($returnStatus === false) {  //tracking error in insert query
            echo FAILURE;
            die;
       }
     }

	 if($fup==1 and $fup2==1){
        //$teacherManager->editTopicsTaught($ret[0],$ret[1],trim($REQUEST_DATA['comments']));
		$r4=$teacherManager->editTopicsTaught($subjectTopicIds,$taughtId,$empId,trim($REQUEST_DATA['comments']));
        if($r4===false){
         echo FAILURE;
         die;
        }
  }
  // for sending sms alert to students
  if($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_ATTENDANCE') != ''){
	  if(count($studentsIdArray)> 0 and is_array($studentsIdArray)){
		  if($sessionHandler->getSessionVariable('SMS_ALERT_TO_STUDENTS_WHEN_ATTENDANCE_IS_UPDATED') == 1){
				$studentIdList = implode(",",$studentsIdArray);
				$studentMobileNoArray = CommonQueryManager::getInstance()->getStudentMobileNumber($studentIdList);
				$cnt = count($studentMobileNoArray);
				if($cnt > 0 and is_array($studentMobileNoArray)){
				   for($i=0; $i < $cnt ; $i++){
						if(trim($studentMobileNoArray[$i]['studentMobileNo'])!="" and trim($studentMobileNoArray[$i]['studentMobileNo'])!='NA' and strlen(trim($studentMobileNoArray[$i]['studentMobileNo']))>=10){
                            copyHODSendSMS($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_ATTENDANCE'));
							$ret=sendSMS($studentMobileNoArray[$i]['studentMobileNo'],strip_tags($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_ATTENDANCE')));
							if($ret){
								$sms=1;
							}
							else{
								$sms=0;$smsNotSentStudentArray[]=$studentMobileNoArray[$i]['studentMobileNo'];
							}
					   }
					  else{
						 $smsNotSentStudentArray[]=$studentMobileNoArray[$i]['studentMobileNo']; // this array contain all the mobile numbers which do not have receve sms this array can be used for making sms report in future
					  }
					}
				}
		 }
	}
  }
  ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
	$type = ATTENDANCE_IS_ENTERED_BY_TEACHER_OR_ADMINISTRATOR;
	$className='';
	$groupName ='';
	$subjectCode='';
	$employeeName='';
	if($REQUEST_DATA['classId'] !=''){
		$classNameArray =  $teacherManager->getClass($REQUEST_DATA['classId']);
		if($classNameArray == false){
			 echo FAILURE;
			 die;
		}
		$className = $classNameArray[0]['className'];
	}
	if($REQUEST_DATA['subjectId'] != ''){
		$SubjectNameArray = $teacherManager->getSubject($REQUEST_DATA['subjectId']);
		if($SubjectNameArray == false){
			 echo FAILURE;
			 die;
		}
		$subjectCode = $SubjectNameArray[0]['subjectCode'];
	}
	if($REQUEST_DATA['employeeId'] != ''){
		$teacherNameArray = $teacherManager->getTeacherName($REQUEST_DATA['employeeId']);
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
	if($REQUEST_DATA['groupId']!=''){
		$groupNameArray = $teacherManager->getGroupName($REQUEST_DATA['groupId']);
		if($groupNameArray ==false){
			 echo FAILURE;
			 die;
		}
		$groupName=$groupNameArray[0]['groupName'];
	}
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if($roleId == 1){
		$userName = $sessionHandler->getSessionVariable('UserName');
		$employeeName = "user ".$userName." on the behalf of ".$employeeName ;
	}
	$auditTrialDescription = "Bulk Attendance has been taken by ".$employeeName." for class ".$className.", group ".$groupName.", subject  ".$subjectCode." from ".UtilityManager::formatDate($REQUEST_DATA['fromDate'])." to " .UtilityManager::formatDate($REQUEST_DATA['toDate'])."";

	$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
	if($returnStatus == false) {
		echo  "Error while saving data for audit trail";
		die;
	}

	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################



//*****************************COMMIT TRANSACTION*************************
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo   $errorMessage;
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
// $History: ajaxBulkAttendance.php $
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 21/04/10   Time: 12:47
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected variable name
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:47
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added the code for "Freezed" class
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 20/11/09   Time: 17:43
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified sorting fields logic:sort on university roll no if
//present,else sort on college roll no if present,else sort on student
//names
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 3/11/09    Time: 17:08
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Fixed bugs in bulk attendance module : Mismatched Lecture Delivered and
//attended problem
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/09/09    Time: 17:30
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected function calling
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 7/09/09    Time: 17:16
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected parameters  in editTopicsTaught() function
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/09/09    Time: 16:38
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated editTopicsTaught() function and send employeeId
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 4/09/09    Time: 12:48
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected date checking function for freeze attendance limit
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:51
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected "Overlapping Attendance Problem" in Leap,LeapCC and SNS.
//
//*****************  Version 6  *****************
//User: Administrator Date: 13/06/09   Time: 11:19
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made bulk attendance,duty leaves and grace marks in teacher end
//configurable
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 16/05/09   Time: 15:23
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Transaction Support" For Attendance and Marks Modules in
//Leap,LeapCC ans SNS
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/04/09    Time: 15:10
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added server side checks during attendance and marks entry in
//SNS,LeapCC and Leap
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/04/09    Time: 17:51
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Incorporated new logic for bulk attendance
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified the files for topics taught
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/02/08    Time: 12:37p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Added Null for periodId and AttendanceCodeId for bulk attendance
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:54p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Make modifications for having  daily and bulk attendance in a single
//table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/18/08    Time: 1:16p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:13p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>