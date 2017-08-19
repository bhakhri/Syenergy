<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to parents
//
//
// Author : Jaineesh
// Created on : (20.01.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendStudentPerformanceMessageToParents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(BL_PATH.'/HtmlFunctions.inc.php');

require_once(MODEL_PATH . "/SendMessageManager.inc.php");
$sendMessageManager = SendMessageManager::getInstance();

require_once(MODEL_PATH."/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

require_once(MODEL_PATH."/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();


global $sessionHandler;

$userId=$sessionHandler->getSessionVariable('UserId');
$instituteId=$sessionHandler->getSessionVariable('InstituteId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

//$teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}

//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (20.01.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------
$smsArr=array();  //will contain smss(each of sms_max_length or less)
function smsCalculation($value,$limit){
 $temp1=$value;
 $nos=1;
 global $smsArr;
 $smsArr[0]=substr($value,0,$limit);
 while(strlen($temp1) > $limit){
     $temp1=substr($temp1,$limit);
     $smsArr[$nos]=substr($temp1,0,$limit);
     $nos=$nos+1;
 }
 return $nos;
}


if ($errorMessage == '' && (!isset($REQUEST_DATA['msgMode']) || trim($REQUEST_DATA['msgMode']) == '')) {
    $errorMessage .= 'Select message type <br/>';
}

$msgMode=trim($REQUEST_DATA['msgMode']);
$attendanceDate=trim($REQUEST_DATA['attendanceUpToDate']);
$dutyLeaves=trim($REQUEST_DATA['dutyLeaves']);

if($msgMode!=1 and $msgMode!=2){
    echo 'Invalid message type';
    die;
}

$smsBody='';
if($msgMode==1){
   $smsBody='Attendance upto '.UtilityManager::formatDate($attendanceDate).' for ';
   $smsSubject='SMS sent for attendance information';
}
else{
   $smsBody='Marks details for ';
   $smsSubject='SMS sent for marks information';
   $testId=trim($REQUEST_DATA['testId']);
   if($testId=='' or $test==-1){
       echo 'Required Parameters Missing';
       die;
   }
   //fetch testTypeCategoryId and testIndex from test table
   $testInfoArray=$sendMessageManager->getTestInfo($testId);
   if(is_array($testInfoArray) and count($testInfoArray)>0){
     $testTypeCategoryId=$testInfoArray[0]['testTypeCategoryId'];
     $testIndex=$testInfoArray[0]['testIndex'];
   }
   else{
       echo 'Test Information Missing';
       die;
   }
}

$insQuery="";
$curDate=date('Y-m-d h:i:s');
$sms=0;
$smsNotSentArrayS=array();
$smsNotSentArrayF=array();
$smsNotSentArrayM=array();
$smsNotSentArrayG=array();


if (trim($errorMessage) == '') {
       //gets student mobile and email
       if(trim($REQUEST_DATA['student'])!=""){
        $studentEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")");
        $cntS=count($studentEmailMobilesArr);
        if($msgMode==1){
            $attendanceRecordStudentArray=$commonQueryManager->getConsolidatedStudentAttendance($REQUEST_DATA['student'],'','',' AND att.toDate <="'.$attendanceDate.'" AND att.classId=s.classId');
            $attCS=count($attendanceRecordStudentArray);
        }
        else{
            $marksRecordStudentArray=$studentManager->getStudentMarks($REQUEST_DATA['student'],'',' studyPeriod','',' AND cl.classId=s.classId AND t.testTypeCategoryId='.$testTypeCategoryId.' AND t.testIndex='.$testIndex);
            $marksCS=count($marksRecordStudentArray);
        }
       }



       //gets father mobile and email
       if(trim($REQUEST_DATA['father'])!=""){
        $fatherEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['father'].")");
        $cntF=count($fatherEmailMobilesArr);
        if($msgMode==1){
            $attendanceRecordFatherArray=$commonQueryManager->getConsolidatedStudentAttendance($REQUEST_DATA['father'],'','',' AND att.toDate <="'.$attendanceDate.'" AND att.classId=s.classId');
            $attCF=count($attendanceRecordFatherArray);
        }
        else{
            $marksRecordFatherArray=$studentManager->getStudentMarks($REQUEST_DATA['father'],'',' studyPeriod','',' AND cl.classId=s.classId AND t.testTypeCategoryId='.$testTypeCategoryId.' AND t.testIndex='.$testIndex);
            $marksCF=count($marksRecordFatherArray);
        }
       }


       //gets mother mobile and email
       if(trim($REQUEST_DATA['mother'])!=""){
        $motherEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['mother'].")");
        $cntM=count($motherEmailMobilesArr);
        if($msgMode==1){
            $attendanceRecordMotherArray=$commonQueryManager->getConsolidatedStudentAttendance($REQUEST_DATA['mother'],'','',' AND att.toDate <="'.$attendanceDate.'" AND att.classId=s.classId');
            $attCM=count($attendanceRecordMotherArray);
        }
        else{
            $marksRecordMotherArray=$studentManager->getStudentMarks($REQUEST_DATA['mother'],'',' studyPeriod','',' AND cl.classId=s.classId');
            $marksCM=count($marksRecordMotherArray);
        }
       }
      //gets guardian mobile and email
       if(trim($REQUEST_DATA['guardian'])!=""){
        $guardianEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['guardian'].")");
        $cntG=count($guardianEmailMobilesArr);
        if($msgMode==1){
            $attendanceRecordGuardianArray=$commonQueryManager->getConsolidatedStudentAttendance($REQUEST_DATA['guardian'],'','',' AND att.toDate <="'.$attendanceDate.'" AND att.classId=s.classId');
            $attCG=count($attendanceRecordGuardianArray);
        }
        else{
            $marksRecordGuardianArray=$studentManager->getStudentMarks($REQUEST_DATA['guardian'],'',' studyPeriod','',' AND cl.classId=s.classId');
            $marksCG=count($marksRecordGuardianArray);
        }
       }


       $errorMessage = SUCCESS;

       //STUDENT SECTION
       if($cntS > 0 and is_array($studentEmailMobilesArr)){   
           $insQuery="";
           for($i=0; $i < $cntS ; $i++){
                $sms=0;
                $smsNo=0;
                $smsAttendance='';
                $smsMarks='';
                if($msgMode==1){
                 for($x=0;$x<$attCS;$x++){
                      if($attendanceRecordStudentArray[$x]['studentId']==$studentEmailMobilesArr[$i]['studentId']){
                        if($smsAttendance!=''){
                            $smsAttendance .=',';
                        }
                        $smsAttendance .=$attendanceRecordStudentArray[$x]['subjectCode'].':'.round($attendanceRecordStudentArray[$x]['attended'],0).'/'.round($attendanceRecordStudentArray[$x]['delivered'],0);
                        if($dutyLeaves==1){
                           $smsAttendance .='-'.round($attendanceRecordStudentArray[$x]['leavesTaken'],0);
                        }
                      }
                   }
                }
                else{
                    for($x=0;$x<$marksCS;$x++){
                     if($marksRecordStudentArray[$x]['studentId']==$studentEmailMobilesArr[$i]['studentId']){
                        if($smsMarks!=''){
                            $smsMarks .=',';
                        }
                        $smsMarks .=$marksRecordStudentArray[$x]['subjectCode'].':'.round($marksRecordStudentArray[$x]['obtainedMarks'],0).'/'.round($marksRecordStudentArray[$x]['totalMarks'],0);
                      }
                    }
                }


                if($msgMode==1){
                   if($smsAttendance!=''){
                    $smsNo=smsCalculation($smsBody.$smsAttendance,SMS_MAX_LENGTH);
                   }
                }
                else{
                   if($smsMarks!=''){
                    $smsNo=smsCalculation($smsBody.$smsMarks,SMS_MAX_LENGTH);
                   }
                }
                if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])!="" and trim($studentEmailMobilesArr[$i]['studentMobileNo'])!='NA' and strlen(trim($studentEmailMobilesArr[$i]['studentMobileNo']))>=10){
					 $ret=false;
					 for($s=0 ; $s < $smsNo ;$s++){
						$ret=sendSMS($studentEmailMobilesArr[$i]['studentMobileNo'],strip_tags($smsArr[$s]));
					 }
					 if($ret){
						$sms=1;
					 }  //if sms sent successful
					else{
						 $sms=0;
						 $smsNotSentArrayS[]=$studentEmailMobilesArr[$i]['studentId'];
					}
					    $visibleFromDate=$curDate;
						$visibleToDate=$curDate;
				 }
              else{
                 $smsNotSentArrayS[]=$studentEmailMobilesArr[$i]['studentId'];
                 $sms=0;
                 $visibleFromDate=$curDate;
                 $visibleToDate=$curDate;
               }
               //build the insert string
               for($s=0 ; $s < $smsNo ;$s++){
                if($insQuery!=""){
                 $insQuery .=" , ";
                }
				$smsStatus = '';
				if (in_array($studentEmailMobilesArr[$i]['studentId'], $smsNotSentArrayS)) {
					$smsStatus = '#0';
				}
                $insQuery .=" ('~".$studentEmailMobilesArr[$i]['studentId'].$smsStatus."~','Student','".$curDate."','".$smsSubject."','".strip_tags($smsArr[$s])."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              }
            }

      //end of forloop
		if($insQuery!=""){
			if(SystemDatabaseManager::getInstance()->startTransaction()){
				$adminMessageEmailSMSRecordArray = $sendMessageManager->insertIntoAdminMessagesInTranscation($insQuery); //add the record in database
				if($adminMessageEmailSMSRecordArray == false){
					echo FAILURE;
					die;
				}
				$insertStatus = $sendMessageManager->insertBulkFailedMessages();
				if($insertStatus == false){
					 echo FAILURE;
					 die;
				 }
				$table ="admin_messages";
				$condition = "SET receiverIds = replace(receiverIds,'#0','')
								WHERE receiverIds like '%#0%'";
				$updateIdInAdminMessages = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessages == false){
					echo FAILURE;
					die;
				}
				$table ="admin_messages_failed";
				$condition = "SET	receiverIds = replace(receiverIds,'#0',''),
									receiverIds = replace(receiverIds,'~','')
							WHERE	receiverIds like '%#0%'";
				$updateIdInAdminMessageFailed = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessageFailed == false){
					return FAILURE;
					die;
				}
				SystemDatabaseManager::getInstance()->commitTransaction();
			}
	  }

  }
//END OF STUDENT SECTION



       //FATHER SECTION
       if($cntF > 0 and is_array($fatherEmailMobilesArr)){
           $insQuery="";
           for($i=0; $i < $cntF ; $i++){
                $sms=0;
                $smsNo=0;
                $smsAttendance='';
                $smsMarks='';
                if($msgMode==1){
                 for($x=0;$x<$attCF;$x++){
                      if($attendanceRecordFatherArray[$x]['studentId']==$fatherEmailMobilesArr[$i]['studentId']){
                        if($smsAttendance!=''){
                            $smsAttendance .=',';
                        }
                        $smsAttendance .=$attendanceRecordFatherArray[$x]['subjectCode'].':'.round($attendanceRecordFatherArray[$x]['attended'],0).'/'.round($attendanceRecordFatherArray[$x]['delivered'],0);
                        if($dutyLeaves==1){
                           $smsAttendance .='-'.round($attendanceRecordFatherArray[$x]['leavesTaken'],0);
                        }
                      }
                   }
                }
                else{
                    for($x=0;$x<$marksCF;$x++){
                     if($marksRecordFatherArray[$x]['studentId']==$fatherEmailMobilesArr[$i]['studentId']){
                        if($smsMarks!=''){
                            $smsMarks .=',';
                        }
                        $smsMarks .=$marksRecordFatherArray[$x]['subjectCode'].':'.round($marksRecordFatherArray[$x]['obtainedMarks'],0).'/'.round($marksRecordFatherArray[$x]['totalMarks'],0);
                      }
                    }
                }


                if($msgMode==1){
                   if($smsAttendance!=''){
                    $smsNo=smsCalculation($smsBody.$smsAttendance,SMS_MAX_LENGTH);
                   }
                }
                else{
                   if($smsMarks!=''){
                    $smsNo=smsCalculation($smsBody.$smsMarks,SMS_MAX_LENGTH);
                   }
                }

                if(trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!="" and trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!='NA' and strlen(trim($fatherEmailMobilesArr[$i]['fatherMobileNo']))>=10){
                 $ret=false;
                 for($s=0 ; $s < $smsNo ;$s++){
                  $ret=sendSMS($fatherEmailMobilesArr[$i]['fatherMobileNo'],strip_tags($smsArr[$s]));
                 }
                  if($ret){
                       $sms=1;
                  }  //if sms sent successful
                  else{
                       $sms=0;
                       $smsNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId'];
                  }
                  $visibleFromDate=$curDate;
                  $visibleToDate=$curDate;
               }
              else{
                 $smsNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId'];
                 $sms=0;
                 $visibleFromDate=$curDate;
                 $visibleToDate=$curDate;
               }
               //build the insert string
               for($s=0 ; $s < $smsNo ;$s++){
                if($insQuery!=""){
                 $insQuery .=" , ";
                 }
				 if (in_array($fatherEmailMobilesArr[$i]['studentId'], $smsNotSentArrayF)) {
					$smsStatus = '#0';
				}
                $insQuery .=" ('~".$fatherEmailMobilesArr[$i]['studentId'].$smsStatus."~','Father','".$curDate."','".$smsSubject."','".strip_tags($smsArr[$s])."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              }
            }

	  //end of forloop
 		if($insQuery!=""){
			if(SystemDatabaseManager::getInstance()->startTransaction()){
				$adminMessageEmailSMSRecordArray = $sendMessageManager->insertIntoAdminMessagesInTranscation($insQuery); //add the record in database
				if($adminMessageEmailSMSRecordArray == false){
					echo FAILURE;
					die;
				}
				$insertStatus = $sendMessageManager->insertBulkFailedMessages();
				if($insertStatus == false){
					 echo FAILURE;
					 die;
				 }
				$table ="admin_messages";
				$condition = "SET receiverIds = replace(receiverIds,'#0','')
								WHERE receiverIds like '%#0%'";
				$updateIdInAdminMessages = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessages == false){
					echo FAILURE;
					die;
				}
				$table ="admin_messages_failed";
				$condition = "SET	receiverIds = replace(receiverIds,'#0',''),
									receiverIds = replace(receiverIds,'~','')
							WHERE	receiverIds like '%#0%'";
				$updateIdInAdminMessageFailed = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessageFailed == false){
					return FAILURE;
					die;
				}
				SystemDatabaseManager::getInstance()->commitTransaction();
			}
	  }
  }
//END OF FATHER SECTION



//MOTHER SECTION
 if($cntM > 0 and is_array($motherEmailMobilesArr)){
           $insQuery="";
           for($i=0; $i < $cntM ; $i++){
                $sms=0;
                $smsNo=0;
                $smsAttendance='';
                $smsMarks='';
                if($msgMode==1){
                 for($x=0;$x<$attCM;$x++){
                      if($attendanceRecordMotherArray[$x]['studentId']==$motherEmailMobilesArr[$i]['studentId']){
                        if($smsAttendance!=''){
                            $smsAttendance .=',';
                        }
                        $smsAttendance .=$attendanceRecordMotherArray[$x]['subjectCode'].':'.round($attendanceRecordMotherArray[$x]['attended'],0).'/'.round($attendanceRecordMotherArray[$x]['delivered'],0);
                        if($dutyLeaves==1){
                           $smsAttendance .='-'.round($attendanceRecordMotherArray[$x]['leavesTaken'],0);
                        }
                      }
                   }
                }
                else{
                    for($x=0;$x<$marksCM;$x++){
                     if($marksRecordMotherArray[$x]['studentId']==$motherEmailMobilesArr[$i]['studentId']){
                        if($smsMarks!=''){
                            $smsMarks .=',';
                        }
                        $smsMarks .=$marksRecordMotherArray[$x]['subjectCode'].':'.round($marksRecordMotherArray[$x]['obtainedMarks'],0).'/'.round($marksRecordMotherArray[$x]['totalMarks'],0);
                      }
                    }
                }

                if($msgMode==1){
                   if($smsAttendance!=''){
                    $smsNo=smsCalculation($smsBody.$smsAttendance,SMS_MAX_LENGTH);
                   }
                }
                else{
                   if($smsMarks!=''){
                    $smsNo=smsCalculation($smsBody.$smsMarks,SMS_MAX_LENGTH);
                   }
                }
                if(trim($motherEmailMobilesArr[$i]['motherMobileNo'])!="" and trim($motherEmailMobilesArr[$i]['motherMobileNo'])!='NA' and strlen(trim($motherEmailMobilesArr[$i]['motherMobileNo']))>=10){
                 $ret=false;
                 for($s=0 ; $s < $smsNo ;$s++){
                  $ret=sendSMS($motherEmailMobilesArr[$i]['motherMobileNo'],strip_tags($smsArr[$s]));
                 }
                  if($ret){
                       $sms=1;
                  }  //if sms sent successful
                  else{
                       $sms=0;
                       $smsNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId'];
                  }
                  $visibleFromDate=$curDate;
                  $visibleToDate=$curDate;
               }
              else{
                 $smsNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId'];
                 $sms=0;
                 $visibleFromDate=$curDate;
                 $visibleToDate=$curDate;
               }
               //build the insert string
               for($s=0 ; $s < $smsNo ;$s++){
                if($insQuery!=""){
                 $insQuery .=" , ";
                 }
				if (in_array($motherEmailMobilesArr[$i]['studentId'], $smsNotSentArrayM)) {
					$smsStatus = '#0';
				}
                $insQuery .=" ('~".$motherEmailMobilesArr[$i]['studentId'].$smsStatus."~','Mother','".$curDate."','".$smsSubject."','".strip_tags($smsArr[$s])."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              }
            }

      //end of forloop
		if($insQuery!=""){
			if(SystemDatabaseManager::getInstance()->startTransaction()){
				$adminMessageEmailSMSRecordArray = $sendMessageManager->insertIntoAdminMessagesInTranscation($insQuery); //add the record in database
				if($adminMessageEmailSMSRecordArray == false){
					echo FAILURE;
					die;
				}
				$insertStatus = $sendMessageManager->insertBulkFailedMessages();
				if($insertStatus == false){
					 echo FAILURE;
					 die;
				 }
				$table ="admin_messages";
				$condition = "SET receiverIds = replace(receiverIds,'#0','')
								WHERE receiverIds like '%#0%'";
				$updateIdInAdminMessages = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessages == false){
					echo FAILURE;
					die;
				}
				$table ="admin_messages_failed";
				$condition = "SET	receiverIds = replace(receiverIds,'#0',''),
									receiverIds = replace(receiverIds,'~','')
							WHERE	receiverIds like '%#0%'";
				$updateIdInAdminMessageFailed = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessageFailed == false){
					return FAILURE;
					die;
				}
				SystemDatabaseManager::getInstance()->commitTransaction();
			}
	  }
  }
//END OF MOTHER SECTION

 //GUARDIAN SECTION
 if($cntG > 0 and is_array($guardianEmailMobilesArr)){
         $insQuery="";
         for($i=0; $i < $cntG ; $i++){
				$sms=0;
                $smsNo=0;
                $smsAttendance='';
                $smsMarks='';
                if($msgMode==1){
                 for($x=0;$x<$attCG;$x++){
                      if($attendanceRecordGuardianArray[$x]['studentId']==$guardianEmailMobilesArr[$i]['studentId']){
						if($smsAttendance!=''){
                            $smsAttendance .=',';
                        }
                        $smsAttendance .=$attendanceRecordGuardianArray[$x]['subjectCode'].':'.round($attendanceRecordGuardianArray[$x]['attended'],0).'/'.round($attendanceRecordGuardianArray[$x]['delivered'],0);
                        if($dutyLeaves==1){
                           $smsAttendance .='-'.round($attendanceRecordGuardianArray[$x]['leavesTaken'],0);
                        }

                      }
                   }
                }
                else{
                    for($x=0;$x<$marksCG;$x++){
                     if($marksRecordGuardianArray[$x]['studentId']==$guardianEmailMobilesArr[$i]['studentId']){
                        if($smsMarks!=''){
                            $smsMarks .=',';
                        }
                        $smsMarks .=$marksRecordGuardianArray[$x]['subjectCode'].':'.round($marksRecordGuardianArray[$x]['obtainedMarks'],0).'/'.round($marksRecordGuardianArray[$x]['totalMarks'],0);
                      }
                    }
                }
                if($msgMode==1){
                   if($smsAttendance!=''){
						$smsNo=smsCalculation($smsBody.$smsAttendance,SMS_MAX_LENGTH);
                   }
                }
                else{
                   if($smsMarks!=''){
						$smsNo=smsCalculation($smsBody.$smsMarks,SMS_MAX_LENGTH);
                   }
                }
                if(trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!="" and trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!='NA' and strlen(trim($guardianEmailMobilesArr[$i]['guardianMobileNo']))>=10){
                 $ret=false;
                 for($s=0 ; $s < $smsNo ;$s++){
                  $ret=sendSMS($guardianEmailMobilesArr[$i]['guardianMobileNo'],strip_tags($smsArr[$s]));
                 }
                  if($ret){
                       $sms=1;
                  }  //if sms sent successful
                  else{
                       $sms=0;
                       $smsNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId'];
                  }
                  $visibleFromDate=$curDate;
                  $visibleToDate=$curDate;
               }
              else{
                 $smsNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId'];
                 $sms=0;
                 $visibleFromDate=$curDate;
                 $visibleToDate=$curDate;
               }
               //build the insert string
               for($s=0 ; $s < $smsNo ;$s++){
                if($insQuery!=""){
                 $insQuery .=" , ";
                 }
				 if (in_array($guardianEmailMobilesArr[$i]['studentId'], $smsNotSentArrayG)) {
					$smsStatus = '#0';
				 }
                $insQuery .=" ('~".$guardianEmailMobilesArr[$i]['studentId'].$smsStatus."~','Guardian','".$curDate."','".$smsSubject."','".strip_tags($smsArr[$s])."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              }
        }
		//end of forloop
		if($insQuery!=""){
			if(SystemDatabaseManager::getInstance()->startTransaction()){
				$adminMessageEmailSMSRecordArray = $sendMessageManager->insertIntoAdminMessagesInTranscation($insQuery); //add the record in database
				if($adminMessageEmailSMSRecordArray == false){
					echo FAILURE;
					die;
				}
				$insertStatus = $sendMessageManager->insertBulkFailedMessages();
				if($insertStatus == false){
					 echo FAILURE;
					 die;
				 }
				$table ="admin_messages";
				$condition = "SET receiverIds = replace(receiverIds,'#0','')
								WHERE receiverIds like '%#0%'";
				$updateIdInAdminMessages = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessages == false){
					echo FAILURE;
					die;
				}
				$table ="admin_messages_failed";
				$condition = "SET	receiverIds = replace(receiverIds,'#0',''),
									receiverIds = replace(receiverIds,'~','')
							WHERE	receiverIds like '%#0%'";
				$updateIdInAdminMessageFailed = $sendMessageManager->updateIdInAdminMessages($table,$condition);
				if($updateIdInAdminMessageFailed == false){
					return FAILURE;
					die;
				}
				SystemDatabaseManager::getInstance()->commitTransaction();
			}
	  }
 }
//END OF GUARDIAN SECTION


 $smsNotSentF=implode(",",$smsNotSentArrayF);
 $smsNotSentM=implode(",",$smsNotSentArrayM);
 $smsNotSentG=implode(",",$smsNotSentArrayG);
 $smsNotSentS=implode(",",$smsNotSentArrayS);
 $emailNotSentF='';
 $emailNotSentM='';
 $emailNotSentG='';
 $emailNotSentS='';

 if($smsNotSentF!='') {
    $sessionHandler->setSessionVariable('smsFatherIds',$smsNotSentF);
    $smsNotSentF ='1';
  }
  else {
    $sessionHandler->setSessionVariable('smsFatherIds','-1');
  }

  if($smsNotSentM!='') {
    $sessionHandler->setSessionVariable('smsMotherIds',$smsNotSentM);
    $smsNotSentM ='1';
  }
  else {
    $sessionHandler->setSessionVariable('smsMotherIds','-1');
  }


  if($smsNotSentG!='') {
    $sessionHandler->setSessionVariable('smsGuardianIds',$smsNotSentG);
    $smsNotSentG ='1';
  }
  else {
    $sessionHandler->setSessionVariable('smsGuardianIds','-1');
  }

  if($smsNotSentS!='') {
    $sessionHandler->setSessionVariable('smsStudentIds',$smsNotSentS);
    $smsNotSentS ='1';
  }
  else {
    $sessionHandler->setSessionVariable('smsStudentIds','-1');
  }

 echo $errorMessage.'!~!~!'.$smsNotSentF.'!~!~!'.$emailNotSentF.'!~!~!'.$smsNotSentM.'!~!~!'.$emailNotSentM.'!~!~!'.$smsNotSentG.'!~!~!'.$emailNotSentG.'!~!~!'.$smsNotSentS.'!~!~!'.$emailNotSentS;
}
else {
       echo $errorMessage;
}
// $History: sendStudentPerformanceMessage.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:51
//Updated in $/LeapCC/Library/AdminMessage
//Modified search filter in "Send student performance message to parents"
//module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:36
//Created in $/LeapCC/Library/AdminMessage
//Created "Sent Student Information Message To Parents" module
?>