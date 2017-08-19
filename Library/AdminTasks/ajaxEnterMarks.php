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
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');  //for having last insert of a new "test"
    define('MODULE','TestMarks');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();
    
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	
    $queryDescription= '';
    $testMarksIds =split("," , $REQUEST_DATA['testMarksId']);
    $studentIds =split("," , $REQUEST_DATA['studentIds']);
    $memc =split("," , $REQUEST_DATA['memofclass']);
    $present =split("," , $REQUEST_DATA['present']);
    $marks =split("," , $REQUEST_DATA['marks']);
    $hiddenMarks =split("," , $REQUEST_DATA['hiddenMarks']);
    $userId=$sessionHandler->getSessionVariable('UserId');

    $subjectName = '';
    $subjectId = trim($REQUEST_DATA['subjectId']);
    if(trim($REQUEST_DATA['subjectId'])=='') {
      $subjectId ='0';  
    }
    
    $subjectNameArray = $teacherManager->getSubject($subjectId);
    if($subjectNameArray == false){
         echo FAILURE;
         die;
    }
    $subjectName = $subjectNameArray[0]['subjectCode'];

    
    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}
	$flag=0;
	$studentIdsList = implode(',',$studentIds);
	if($studentIdsList != ''){
		if($sessionHandler->getSessionVariable('MESSAGE_TO_PARENTS_FOR_NEW_MARKS') != ''){
		   if($sessionHandler->getSessionVariable('SMS_ALERT_TO_PARENTS_FOR_NEW_MARKS') == 1) {
				require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
				$systemDatabaseManage=SystemDatabaseManager::getInstance();
				$mobileNoArray = $teacherManager->getMobileNumber($studentIdsList);
				$cnt = count($mobileNoArray);
				if($cnt > 0 and is_array($mobileNoArray)){
				   for($i=0; $i < $cnt ; $i++){
						if(trim($mobileNoArray[$i]['mobileNumber'])!="" and trim($mobileNoArray[$i]['mobileNumber'])!='NA' and strlen(trim($mobileNoArray[$i]['mobileNumber']))>=10){
							$ret=sendSMS($mobileNoArray[$i]['mobileNumber'],strip_tags($sessionHandler->getSessionVariable('MESSAGE_TO_PARENTS_FOR_NEW_MARKS')));
							if($ret){
								$sms=1;
							}
							else{
								$sms=0;$smsNotSentArray[]=$mobileNoArray[$i]['mobileNumber'];
							}
					   }
					  else{
						 $smsNotSentArray[]=$mobileNoArray[$i]['mobileNumber']; // this array contain all the mobile numbers which do not have receve sms this array can be used for making sms report in future
					  }
					}
				}
		   }
		}
		if($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_NEW_MARKS') != ''){
		   if($sessionHandler->getSessionVariable('SMS_ALERT_TO_STUDENTS_WHEN_MARKS_UPDATED') == 1){
			   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
				$systemDatabaseManage=SystemDatabaseManager::getInstance();
				$studentMobileNoArray = $teacherManager->getStudentMobileNumber($studentIdsList);
				$cnt = count($studentMobileNoArray);
				if($cnt > 0 and is_array($studentMobileNoArray)){
				   for($i=0; $i < $cnt ; $i++){
						if(trim($studentMobileNoArray[$i]['studentMobileNo'])!="" and trim($studentMobileNoArray[$i]['studentMobileNo'])!='NA' and strlen(trim($studentMobileNoArray[$i]['studentMobileNo']))>=10){
							$ret=sendSMS($studentMobileNoArray[$i]['studentMobileNo'],strip_tags($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENT_FOR_NEW_MARKS')));
							if($ret){
								$sms=1;
							}
							else{
								$sms=0;
								$smsNotSentStudentArray[]=$studentMobileNoArray[$i]['studentMobileNo'];
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
    //if(trim($REQUEST_DATA['classId'])=='' or trim($REQUEST_DATA['employeeId'])=='' or trim($REQUEST_DATA['groupId'])=='' or trim($REQUEST_DATA['marks'])=='' or trim($REQUEST_DATA['maxMarks'])=='' or trim($REQUEST_DATA['memofclass'])=='' or trim($REQUEST_DATA['present'])=='' or trim($REQUEST_DATA['studentIds'])=='' or trim($REQUEST_DATA['subjectId'])==''  or trim($REQUEST_DATA['testAbbr'])=='' or trim($REQUEST_DATA['testDate'])=='' or trim($REQUEST_DATA['testId'])=='' or trim($REQUEST_DATA['testIndex'])=='' or trim($REQUEST_DATA['testMarksId'])=='' or trim($REQUEST_DATA['testTopic'])=='' or trim($REQUEST_DATA['testTypeId'])==''){
    if(trim($REQUEST_DATA['classId'])=='' or trim($REQUEST_DATA['employeeId'])=='' or trim($REQUEST_DATA['groupId'])=='' or trim($REQUEST_DATA['marks'])=='' or trim($REQUEST_DATA['maxMarks'])=='' or trim($REQUEST_DATA['memofclass'])=='' or trim($REQUEST_DATA['present'])=='' or trim($REQUEST_DATA['studentIds'])=='' or trim($REQUEST_DATA['subjectId'])==''  or trim($REQUEST_DATA['testAbbr'])=='' or trim($REQUEST_DATA['testDate'])=='' or trim($REQUEST_DATA['testId'])=='' or trim($REQUEST_DATA['testIndex'])=='' or trim($REQUEST_DATA['testMarksId'])=='' or trim($REQUEST_DATA['testTypeId'])==''){
     echo 'Required Parameters Missing';
     die;
   }

    /*********USED TO CHECK DUPLICATE RECORDS**********/
    //$maxRecordArray=$teacherManager->checkDuplicateMarksEntryNew($REQUEST_DATA['classId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['testTypeId']);
    $classId = trim($REQUEST_DATA['classId'] );
    $testTypeId = trim($REQUEST_DATA['testTypeId'] );
    $subjectId = trim($REQUEST_DATA['subjectId'] );
    $groupId = trim($REQUEST_DATA['groupId'] );
    
     $newTestIndexArray = $studentManager->getNewTestIndexNew($classId, $subjectId, $groupId, $testTypeId); 
     $maxTestIndex='0';
     for($i=0;$i<count($newTestIndexArray);$i++) {
       $ttTestIndex = trim($newTestIndexArray[$i]['testIndex']);  
       if($ttTestIndex > $maxTestIndex) {
         $maxTestIndex= $ttTestIndex; 
       }
     }
    
     //$duplicateTestIndex=UtilityManager::makeCSList($duplicateRecordArray,'testIndex');
     //$duplicateTestIndexArray=explode(",",$duplicateTestIndex);
    /*********USED TO CHECK DUPLICATE RECORDS**********/

//****************************************************************************************************************
//***********************************************START TRANSCATION************************************************
//****************************************************************************************************************

if(SystemDatabaseManager::getInstance()->startTransaction()) {
    //********creates or edits the test********
    
     
    if($REQUEST_DATA['testId']=="NT"){ //if new test is to be created
      if($REQUEST_DATA['testIndex']==($maxTestIndex+1)){  //for 1st time insert user submitted testIndex will greater than db stored testIndex
         $r1 = $teacherManager->addTest();
         if($r1===false){
            echo FAILURE;
            die;
         }
         $testId=SystemDatabaseManager::getInstance()->lastInsertId();
         if($testId==false){
            echo FAILURE;
            die;
          }
         
       
     
      
    }
      else{
         echo "Mismatch of Test Index Value";
         die;
        }
}
    else{

       $r2 = $teacherManager->editTest($REQUEST_DATA['testId']);
       if($r2===false){
            echo FAILURE;
            die;
        }
       $testId=$REQUEST_DATA['testId'];
    }
    //if($returnStatus === false) {
       // echo FAILURE;      //if test can not be created or edited no further operation will happen
       // exit(0);
    //}
    //********creates or edits the test(ends)********


    $attCount=count($testMarksIds);  //count $testMarksIds
    $insQuery="";
    $errorMessage=SUCCESS;
    $zeroCnt=0; //used to count no. of students having "0" marks
    
    $countUpdate=0;  // Count total student whose marks updated
    $queryDescription .="Marks are changed for ";
    

    //fetching student records from test_marks table
    $studentIdArray=$teacherManager->fetchStudentRecordsFromTestMarksTable($testId);
	if(is_array($studentIdArray) and count($studentIdArray)>0){
        $fetchedStudentIdArray = explode(',',UtilityManager::makeCSList($studentIdArray,'studentId'));
    }
    else {
        $fetchedStudentIdArray = array();
    }

    $insertForNewStudents='';
 
  //started insert and update operations
	 if($attCount > 0 && is_array($testMarksIds)){
	
   		 for($i=0; $i <$attCount; $i++ ){
			if(trim($marks[$i])==0){
         	   $zeroCnt++;
        	}
			if(!in_array($studentIds[$i],$fetchedStudentIdArray) and $REQUEST_DATA['testId']=="NT"){
        		 //these values are not in test_marks table.so insert them
          		//create multiple insert query
           		if($insQuery !=""){
            	   $insQuery .=",";
           		}
		        $insQuery .= " ($testId,$studentIds[$i],$REQUEST_DATA[subjectId],$REQUEST_DATA[maxMarks],$marks[$i],$present[$i],$memc[$i]) ";
       		}
       		else{
			    //these values are already in test_marks table.so update them [update funtion will be called multiple times]
           		//$returnStatus=$teacherManager->editTestMarks($testMarksIds[$i],$REQUEST_DATA['maxMarks'],$marks[$i],$present[$i],$memc[$i],$userId);
           		if($testMarksIds[$i]!=-1){
         			$returnStatus=$teacherManager->editTestMarks($testMarksIds[$i],$REQUEST_DATA['maxMarks'],$marks[$i],$present[$i],$memc[$i]);
                    if($returnStatus === false) {  //tracking error in update query
                        echo FAILURE;
                        die;
                    }
 	           }
          	   else{
           	$insertForNewStudents = " ($testId,$studentIds[$i],$REQUEST_DATA[subjectId],$REQUEST_DATA[maxMarks],$marks[$i],$present[$i],$memc[$i]) ";
                $returnStatus=$teacherManager->addTestMarks($insertForNewStudents) ;
                if($returnStatus === false) {  //tracking error in insert query
                        echo FAILURE;
                        die;
                }	
          	}
       	 }
          // Start Change Test Marks in Audit Trail
          if($marks[$i] != $hiddenMarks[$i]){ 
                $countUpdate++;
                $oldMarks = $hiddenMarks[$i];
                $newMarks = $marks[$i];
                $studentId = $studentIds[$i];
                if($studentId != ''){
                   $studentNameArray = $commonQueryManager->getStudentName($studentId);
                   if($studentNameArray == false){
                     echo FAILURE;
                     die;
                   }
                   $studentName = $studentNameArray[0]['studentName'];
                   $queryDescription .= $studentName." (OldMarks:".$oldMarks.", NewMarks:".$newMarks.")";
                }
          }
          // End Change Test Marks in Audit Trail 
      }  // End For Loop
      
      
      
      //insert query will be execute one time but multiple insertions will be done
      if($insQuery!="" and $REQUEST_DATA['testId']=="NT"){  //check if insertion query should be performed or not
      	    $returnStatus=$teacherManager->addTestMarks($insQuery) ;
     	     if($returnStatus === false) {  //tracking error in insert query
          	      echo FAILURE;
          	      die;
       	    }
      }
   }

    
    // ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
    if($REQUEST_DATA['testId']!="NT") {    
       if($countUpdate != 0){
          //$queryDescription= '';
          $type = MARKS_ARE_CHANGED; 
          $testName='';
          if($REQUEST_DATA['testId'] != ''){
            $testNameArray = $teacherManager->getTestDetails($REQUEST_DATA['testId']);
            $testName = $testNameArray[0]['testAbbr'];
          }
          $auditTrialDescription = "Mrks are changed for ".$countUpdate." student for sub. ".$subjectName.", test ".$testName."";
          $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);        
          if($returnStatus === false) {
            echo  "Error while saving data for audit trail";
            die;
          }
       }
    }

    

  //*****************************COMMIT TRANSACTION*************************
    
	 if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        if($zeroCnt==0){
         echo  $errorMessage;
       }
       else{
         echo  $errorMessage.'~!~'.$zeroCnt;
       }
	
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
//ended insert and update operations
 //echo   $errorMessage;
// for VSS
// $History: ajaxEnterMarks.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Library/AdminTasks
//Done llrit enhancements
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/12/09   Time: 16:38
//Updated in $/LeapCC/Library/AdminTasks
//Corrected server side checking on "Test Marks" module data entry
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/11/09   Time: 16:01
//Updated in $/LeapCC/Library/AdminTasks
//Fixed Query Error
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/11/09   Time: 18:21
//Updated in $/LeapCC/Library/AdminTasks
//Corrected query condition columns
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Created in $/LeapCC/Library/AdminTasks
//Created "Test Marks" module in admin section
//
//*****************  Version 6  *****************
//User: Administrator Date: 25/05/09   Time: 15:16
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added the functionality "No of students with zero marks : X"
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 16/05/09   Time: 15:23
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Transaction Support" For Attendance and Marks Modules in
//Leap,LeapCC ans SNS
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/05/09    Time: 12:46
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Duplicate" records preventing logic
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/04/09    Time: 10:48
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Double Clicks" preventing javascript alerts in marks module
//in SNS,Leap and LeapCC
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/04/09    Time: 15:10
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added server side checks during attendance and marks entry in
//SNS,LeapCC and Leap
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/03/08   Time: 5:31p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Modified getLastInserId() to lastInsertId() function in ajax files
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
