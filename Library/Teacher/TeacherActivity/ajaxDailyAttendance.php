<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	global $sessionHandler;
	$queryDescription='';
    define('MODULE','DailyAttendance');
    define('ACCESS','edit');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
     UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
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

    if(trim($REQUEST_DATA['attendanceCodeIds'])=='' or trim($REQUEST_DATA['attendanceIds'])=='' or trim($REQUEST_DATA['classId'])=='' or trim($REQUEST_DATA['forDate'])=='' or trim($REQUEST_DATA['groupId'])=='' or trim($REQUEST_DATA['memofclass'])=='' or trim($REQUEST_DATA['periodId'])=='' or trim($REQUEST_DATA['studentIds'])=='' or trim($REQUEST_DATA['subjectId'])=='' or trim($REQUEST_DATA['subjectTopicId'])==''){
        echo 'Required parameter missing';
        die;
    }

    /*CHECK FOR FROZEN CLASS*/
     $classId=trim($REQUEST_DATA['classId']);
     $isFrozenArray=CommonQueryManager::getInstance()->checkFrozenClass($classId);
     if($isFrozenArray[0]['isFrozen']==1){
         echo FROZEN_CLASS_RESTRICTION.$isFrozenArray[0]['className'];
         die;
     }
    /*CHECK FOR FROZEN CLASS*/
    
   
       $serverDate=explode('-',date('Y-m-d'));
       $sDate0=$serverDate[0].$serverDate[1].$serverDate[2];
       $sDate1=explode('-',$REQUEST_DATA['forDate']);
       $sDate2=$sDate1[0].$sDate1[1].$sDate1[2];
       if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))!='' and trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT')) !="0"){
            if(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))==-1 && trim($sessionHandler->getSessionVariable('RoleId'))==2)
            {
                echo "You cannot mark attendance as it has been frozen by the administrator. Please contact your administrator if you still need to mark this attendance";
                die;  
            }
            elseif(trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'))>0 && trim($sessionHandler->getSessionVariable('RoleId'))==2)
            {
                $threshold=trim($sessionHandler->getSessionVariable('FREEZE_ATTENDANCE_LIMIT'));
                $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
                $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
                $diff=$end_date - $start_date;
                if($diff>$threshold){
                    echo "You cannot mark attendance older than ".$threshold." days";
                    die;
                }
            }
       }

  

    //attendanceCode from the table "attendance_code" table
    $attCode = CommonQueryManager::getInstance()->getAttendanceCode();
    $cntAttCode=count($attCode);
	$msg_sender = "CHITKARA";

    //generating the dynamic array
    for($i=0;$i<$cntAttCode;$i++){
        $attArray[]=array($attCode[$i]['attendanceCodeId'],$attCode[$i]['attendanceCodeName'],0);
    }

    /*********USED TO CHECK DUPLICATE RECORDS**********/
    $duplicateRecordArray=$teacherManager->dailyAttendanceDuplicateCheck($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['periodId'],$REQUEST_DATA['forDate']);
    $duplicateStudentId=UtilityManager::makeCSList($duplicateRecordArray,'studentId',',');
    $duplicateStudentIds=explode(',',$duplicateStudentId);
    /*********USED TO CHECK DUPLICATE RECORDS**********/

    $attendanceIds =split("," , $REQUEST_DATA['attendanceIds']);
    $studentIds =split("," , $REQUEST_DATA['studentIds']);
    $attendanceCodeIds =split("," , $REQUEST_DATA['attendanceCodeIds']);
    $memc =split("," , $REQUEST_DATA['memofclass']);


    $attCount=count($attendanceIds);  //count attendanceIds

    //this is used to show users attendance summery :Total Present :X,Total Absent : Y etc
    $arrLen=count($attArray);
    for($i=0;$i<$attCount;$i++){
        for($j=0;$j<$arrLen;$j++){
            if($attArray[$j][0]==$attendanceCodeIds[$i]){
              $attArray[$j][2] ++;
            }
        }
    }

    $attSummeryStr='';
    for($i=0;$i<$arrLen;$i++){
        if($attSummeryStr!=''){
            $attSummeryStr .=',';
        }
        $attSummeryStr .= 'Total '. $attArray[$i][1].' :'.$attArray[$i][2];
    }

    $insQuery="";
    if($sessionHandler->getSessionVariable('RoleId')==2){
       $empId=$sessionHandler->getSessionVariable('EmployeeId');
    }
    else{
       $empId=trim($REQUEST_DATA['employeeId']);
    }
    $userId=$sessionHandler->getSessionVariable('UserId');

    $errorMessage=SUCCESS;

	$fins=1; //flag for insert
    $fup=0;  //flag for update
    $fup2=1; //flag for update(when insert in done for update)
    $topicsTaughtId=0;

    $ret=explode("~",$REQUEST_DATA['subjectTopicId']); //[0]:subjectTopicId,[1]:topicsTaughtId

	$subjectTopicIds=$REQUEST_DATA['subjectTopicId'];
	$taughtId=$REQUEST_DATA['taught'];

    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');


//****************************************************************************************************************
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
$studentIdArray = array();
  //started insert and update operations
  if($attCount > 0 && is_array($attendanceIds)){
    for($i=0; $i <$attCount; $i++ ){
        if($attendanceIds[$i]!="-1"){
        //these values are already in attendance_bulk table.so update them [update funtion will be called multiple times]
          if($fup2==1){
              if(SystemDatabaseManager::getInstance()->startTransaction()) {
                  $r1=$teacherManager->editTopicsTaught($subjectTopicIds,$taughtId,$empId,trim($REQUEST_DATA['comments']));
                  if($r1===false){
                     echo FAILURE;
                     die;
                 }
                 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
                 $fup2=0;
                 if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
                     
                 }
              }
          }

          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              // $returnStatus=$teacherManager->editDailyAttendance($attendanceIds[$i],$memc[$i],$attendanceCodeIds[$i]);
              $r2=$teacherManager->editDailyAttendance($attendanceIds[$i],$memc[$i],$attendanceCodeIds[$i],$taughtId);
              if($r2===false){
                echo FAILURE;
                die;
              }
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
              }
          }
        }
       else{
		  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
          if(in_array($studentIds[$i],$duplicateStudentIds)){
             continue; //if there is multiple clicks check whether studentIds exists or not.if exists then do not execute insert query
          }
          //these values are not in attendance_bulk table.so insert them
          //create multiple insert query

         
          if($fins==1){

             if(SystemDatabaseManager::getInstance()->startTransaction()) {  
                 //add topics_taught table
                 $r3=$teacherManager->addTopicsTaught($empId,$subjectTopicIds,trim($REQUEST_DATA['comments']));
                 if($r3===false){
                     echo FAILURE;
                     die;
                 }
                 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
                 $topicsTaughtId=SystemDatabaseManager::getInstance()->lastInsertId();
                 if($topicsTaughtId===false){
                     echo FAILURE;
                     die;
                 }
                 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
                 $fins=0;
                 if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
                 }
             }
         }

           if($insQuery==""){
              $insQuery="($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,2,$attendanceCodeIds[$i],$REQUEST_DATA[periodId],'".$REQUEST_DATA['forDate']."','".$REQUEST_DATA['forDate']."',$memc[$i],1,0,$userId,$topicsTaughtId)";
           }
          else{
              $insQuery=$insQuery." , ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,2,$attendanceCodeIds[$i],$REQUEST_DATA[periodId],'".$REQUEST_DATA['forDate']."','".$REQUEST_DATA['forDate']."',$memc[$i],1,0,$userId,$topicsTaughtId)";
          }
		  $studentIdsArray[] = $studentIds[$i];
		  //Code to gather student details who are absent in a particular lecture. 
		  if($attendanceCodeIds[$i]==2) {
			  $getStudentName = $teacherManager->getStudentName($studentIds[$i]) ;
			  
			if(isset($getStudentName[0]['firstName'])&&isset($getStudentName[0]['fatherMobileNo'])&&$getStudentName[0]['firstName']!=''&&$getStudentName[0]['fatherMobileNo']!=''){
				$studentName= $getStudentName[0]['firstName'];
				
				if($loop==0) {
				  $getSubjectName = $teacherManager->getSubject($REQUEST_DATA['subjectId']);
				  $subjectCode = $getSubjectName[0]['subjectCode'];
				  $loop = 1;
				}
				$messageStatus = $sessionHandler->getSessionVariable('STUDENT_ABSENT_MESSAGE');
				if ($messageStatus == 0) {
				  $customMessage = $sessionHandler->getSessionVariable('CUSTOMISED_MESSAGE');
				  $message = str_ireplace("(studentName)", $studentName, $customMessage);
				  $message = str_ireplace("(subjectCode)", $subjectCode, $message);
				  $message = str_ireplace("(date)", $REQUEST_DATA['forDate'], $message);
					if($message == "" or $message == NULL) {
					  $message = "Dear parent, Please be informed that your ward $studentName did not attend to class of the subject $subjectCode scheduled on $REQUEST_DATA[forDate]";
					}
				}
				else {
				  $message = "Dear parent, Please be informed that your ward $studentName did not attend to class of the subject $subjectCode scheduled on $REQUEST_DATA[forDate]";
				}
				$mobile = trim($getStudentName[0]['fatherMobileNo']);
				$sendMessageArray[] = array('mobile'=>$mobile, 'message'=>$message, 'msg_sender'=>$msg_sender);
			}
		  }
		  $message=" ";
	   }

       //if($returnStatus === false) {  //tracking error in update query
         //       $errorMessage = FAILURE;
       //}
       /***Update Duty Leave Table Also***/
       if(SystemDatabaseManager::getInstance()->startTransaction()) {  
           $ret=$teacherManager->updateDutyLeave($studentIds[$i],$REQUEST_DATA['classId'],$REQUEST_DATA['periodId'],$REQUEST_DATA['forDate'],$instituteId,$sessionId,$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId']);
	       if($ret===false){
             echo FAILURE;  
             die;
           }
           $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
           }
       }
     }
   }
   

   
     //insert query will be execute one time but multiple insertions will be done
     if($insQuery!=""){  //check if insertion query should be performed or not
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
          $returnStatus=$teacherManager->addDailyAttendance($insQuery) ;
          if($returnStatus === false) {  //tracking error in insert query
                echo FAILURE;
                die;
           }
           $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
               
           }
        }
     }

	 if($fup==1 and $fup2==1){
        //$teacherManager->editTopicsTaught($ret[0],$ret[1],trim($REQUEST_DATA['comments']));
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
		    $r4=$teacherManager->editTopicsTaught($subjectTopicIds,$taughtId,$empId,trim($REQUEST_DATA['comments']));
            if($r4===false){
             echo FAILURE;
             die;
            }
            $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
               
            }
        }
     }

	    ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
	    $type = ATTENDANCE_IS_ENTERED_BY_TEACHER_OR_ADMINISTRATOR;
	    $className='';
	    $groupName ='';
	    $subjectName='';
	    $employeeName='';
	    if($REQUEST_DATA['classId'] !=''){
		    $classNameArray =  $teacherManager->getClass($REQUEST_DATA['classId']);
		    if($classNameArray === false){
			     echo FAILURE;
			     die;
		    }
		    $className = $classNameArray[0]['className'];
	    }
	    if($REQUEST_DATA['subjectId'] != ''){
		    $SubjectNameArray = $teacherManager->getSubject($REQUEST_DATA['subjectId']);
		    if($SubjectNameArray === false){
			     echo FAILURE;
			     die;
		    }
		    $subjectName = $SubjectNameArray[0]['subjectCode'];
	    }
	    if($REQUEST_DATA['employeeId'] != ''){
		    $teacherNameArray = $teacherManager->getTeacherName($REQUEST_DATA['employeeId']);
		    if($teacherNameArray === false){
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
		    if($groupNameArray ===false){
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
	    $auditTrialDescription = "Daily Attendance has been taken by ".$employeeName." for class ".$className.", group ".$groupName.", subject  ".$subjectName." for ".UtilityManager::formatDate($REQUEST_DATA['forDate'])."";
    
        $queryDescription='';
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
            $returnStatus =  CommonQueryManager::getInstance()->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
	        if($returnStatus === false) {
		      echo  "Error while saving data for audit trail";
		      die;
	        }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
               
            }
        }

	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
  
      //Code to send SMS_Alert to student's parent is any student is absent.
      $automaticAttendanceAlertStatus = $sessionHandler->getSessionVariable('ATTENDANCE_ALERT_TO_PARENTS');
      $cnt = count($sendMessageArray);
      if($cnt>0 AND $automaticAttendanceAlertStatus==1) {
          for($i=0; $i<$cnt; $i++) {
              $mobile = $sendMessageArray[$i]['mobile'];
              $message = $sendMessageArray[$i]['message'];
              $msg_sender = $sendMessageArray[$i]['msg_sender'];
              //echo $message."\n";
              UtilityManager::sendSMS($mobile, $message, $msg_sender);      
          }
      }
      // for sending sms alert to students
      if($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_ATTENDANCE') != ''){
        if(count($studentIdsArray) > 0 and is_array($studentIdsArray)){
            if($sessionHandler->getSessionVariable('SMS_ALERT_TO_STUDENTS_WHEN_ATTENDANCE_IS_UPDATED') == 1){
                $studentIdList = implode(",",$studentIdsArray);
                $systemDatabaseManage=SystemDatabaseManager::getInstance();
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
  echo $errorMessage.'~'.$attSummeryStr;
  die;


