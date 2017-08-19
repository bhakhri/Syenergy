<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMasterLabelWiseReport');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager1.inc.php");
$fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();

global $sessionHandler; 

$doneByUserId=$sessionHandler->getSessionVariable('UserId');  

$reason=trim(add_slashes($REQUEST_DATA['reason']));
if($reason==''){
    die("Enter reason for unblocking");
}


//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}


    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $type=trim($REQUEST_DATA['type']);
    
    if($labelId=='' or $timeTableLabelId=='' or $type==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
       die; 
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    $conditionsArray = array();

    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
    }
    

    //***********STUDENT SEARCH FILTER************
    if (!empty($rollNo)) {
        $conditionsArray[] = " s.rollNo LIKE '$rollNo%' ";
    }
    if (!empty($studentName)) {
         $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        //$conditionsArray[] = " CONCAT(s.firstName, ' ', s.lastName) like '%$studentName%' ";
        $conditionsArray[] = " (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
        
    }
    
    $course = $REQUEST_DATA['courseId'];

    if (!empty($degreeId)) {
          $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    
    if (!empty($course)) {
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
     
    }
    
    if (!empty($branchId)) {
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    if (!empty($periodicityId)) {
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    if (!empty($groupId)) {
        $conditionsArray[] = " s.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }

    $fromDateA=$REQUEST_DATA['admissionMonthF'].'-'.$REQUEST_DATA['admissionDateF'].'-'.$REQUEST_DATA['admissionYearF'];
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " s.dateOfAdmission >= '$thisDate' ";
        }
    }
    $toDateA=$REQUEST_DATA['admissionMonthT'].'-'.$REQUEST_DATA['admissionDateT'].'-'.$REQUEST_DATA['admissionYearT'];
    if (!empty($toDateA) and $toDateA != '--') {
        $toDateArr = explode('-',$toDateA);
        $toDateAM = intval($toDateArr[0]);
        $toDateAD = intval($toDateArr[1]);
        $toDateAY = intval($toDateArr[2]);
        if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
            $thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
            $conditionsArray[] = " s.dateOfAdmission <= '$thisDate' ";
        }
    }

    $fromDateD=$REQUEST_DATA['birthMonthF'].'-'.$REQUEST_DATA['birthDateF'].'-'.$REQUEST_DATA['birthYearF'];
    if (!empty($fromDateD) and $fromDateD != '--') {
        $fromDateArr = explode('-',$fromDateD);
        $fromDateDM = intval($fromDateArr[0]);
        $fromDateDD = intval($fromDateArr[1]);
        $fromDateDY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
            $thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $conditionsArray[] = " s.dateOfBirth >= '$thisDate' ";
        }
    }

    $toDateD=$REQUEST_DATA['birthMonthT'].'-'.$REQUEST_DATA['birthDateT'].'-'.$REQUEST_DATA['birthYearT'];
    if (!empty($toDateD) and $toDateD != '--') {
        $toDateArr = explode('-',$toDateD);
        $toDateDM = intval($toDateArr[0]);
        $toDateDD = intval($toDateArr[1]);
        $toDateDY = intval($toDateArr[2]);
        if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
            $thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
            $conditionsArray[] = " s.dateOfBirth <= '$thisDate' ";
        }
    }

    if (!empty($gender)) {
        $conditionsArray[] = " s.studentGender = '$gender' ";
    }
    if ($categoryId!='') {
        $conditionsArray[] = " s.managementCategory = $categoryId ";
    }
    if (!empty($quotaId)) {
        $conditionsArray[] = " s.quotaId IN ($quotaId) ";
    }
    if (!empty($hostelId)) {
         $conditionsArray[] = " s.hostelFacility=1";
         $conditionsArray[] = " s.hostelId IN ('$hostelId') ";
    }
    $transportFlag=0;
    if (!empty($busStopId)) {
         $transportFlag=1;
         $conditionsArray[] = " s.transportFacility = 1 ";
         $conditionsArray[] = " s.busStopId IN ($busStopId) ";
    }
    if (!empty($busRouteId)) {
          if($transportFlag==0){ //so that this condition does not append twice
           $conditionsArray[] = " s.transportFacility = 1 ";
         }
         $conditionsArray[] = " s.busRouteId IN ($busRouteId) ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " s.permCityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " s.permStateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " s.permCountryId IN ($countryId) ";
    }
    if (!empty($universityId)) {
        $conditionsArray[] = " cl.universityId IN ($universityId) ";
    }
    if (!empty($instituteId)) {
        $conditionsArray[] = " cl.instituteId IN ($instituteId) ";
    }
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " s.studentBloodGroup = $bloodGroup";
    }
    
    //fee receipt Number
    $feeReceiptNo = $REQUEST_DATA['feeReceiptNo'];
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " s.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo = $REQUEST_DATA['regNo'];
    if (!empty($instRegNo)) {
        $conditionsArray[] = " s.regNo LIKE '$instRegNo%' ";
        $qryString.= "&regNo=$instRegNo";
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    //$conditions .=' AND fss.feedbackSurveyId='.$labelId.' AND fss.timeTableLabelId='.$timeTableLabelId; 
    
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $orderBy = " $sortField $sortOrderBy";
    
     
       //fetch the users
       $studentRecordArray=$fbManager->getStudentsFeedbackStatus($conditions,$labelId,'','',$timeTableLabelId);
       $cnt = count($studentRecordArray);
       if($cnt==0){
          die("No students found for blocking");
       }
        
       $strQuery=''; 
       $unBlockStudent='';
       $userIds= array();
       $j=0;
       for($i=0;$i<$cnt;$i++) {
          if(strtolower(trim($studentRecordArray[$i]['isCompleted'])) != 'completed') {
              $pStudentId = $studentRecordArray[$i]['studentId'];
              $pClassId = $studentRecordArray[$i]['classId'];  
              $isPendingStatus =2;
              $isBlockStatus = 1;   // 0 => Unblock Student, 1=> Block Student, 2=> Unblock By Admin  
              if($studentRecordArray[$i]['feedbackStudentId'] == "-1") {
                 if($strQuery!='') {
                   $strQuery .=",";  
                 }
                 $strQuery .= "($labelId,$pClassId,$pStudentId,$isPendingStatus,$isBlockStatus)";
              }
              
              if($studentRecordArray[$i]['dateOverFlag']==1){
                if(strtolower(trim($studentRecordArray[$i]['isCompleted'])) != 'completed') {
                   $currentStatus = $studentRecordArray[$i]['currentStatus']; 
                   $isCompleted = strtolower(trim($studentRecordArray[$i]['isCompleted']));
                   if($currentStatus==1 || $isCompleted=='partial' || $isCompleted=='pending') {  
                      if($unBlockStudent!='') {
                        $unBlockStudent .= ",";  
                      } 
                      $unBlockStudent.=$studentRecordArray[$i]['studentId'];
                      $userIds[$j]= $studentRecordArray[$i]['userId'];
                      $j++;
                   }
                 }
               }
           }
       }
       
       if($unBlockStudent==''){
           die("No students found for unblocking");
       }
       
       if($unBlockStudent=='') {
         $unBlockStudent=0;  
       }
       
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
     
      if($strQuery!='') { 
          $returnStatus=$fbManager->addPendingStudents($strQuery);  
          if($returnStatus === false) {
             echo FAILURE;
             die;
          }   
      }
       
      //unblock student 
      $condition =  " studentId IN ($unBlockStudent) AND surveyId = $labelId "; 
      $returnStatus=$fbManager->changeStudentStatusInBlock1($condition,2);
      if($returnStatus === false) {
        echo FAILURE;
         die;
      } 
      
      $insertString='';
      $cnt = count($userIds);
      for($i=0;$i<$cnt;$i++) {
          if($insertString!='') {
              $insertString .=",";
          }
          $insertString .=" ($userIds[$i],'$reason',".FEEDBACK_STUDENT_COMPLETE.",CURDATE(),$doneByUserId) ";
          if(($i % 20)==0){
            //insert new row in log table
            $ret=$fbManager->doStatusLogEntryInBulk($insertString);
            if($ret==false){
             echo FAILURE;
             die;
            }
            $insertString='';        
          }
      }
      if($insertString!=''){
          $ret=$fbManager->doStatusLogEntryInBulk($insertString);
          if($ret==false){
            echo FAILURE;
            die;
          }
      }
      if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo SUCCESS;
        die;
      }
      else {
        echo FAILURE;
        die;
      }
   }
   else {
      echo FAILURE;
      die;
   }
?>
