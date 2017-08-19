<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','ADVFB_AssignSurveyMasterLabelWiseReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
$fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();


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
    
  if($type==1){  

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
    
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $conditions .=' AND fs.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $orderBy = " $sortField $sortOrderBy";
    
     
    ////////////
    //fetch the users
    $userIdArray=$fbManager->fetchStudentFeedbackUserList($conditions);
    $totalRecords = count($userIdArray);
    if(count($userIdArray)>0 and is_array($userIdArray)){
        $userIds=UtilityManager::makeCSList($userIdArray,'userId');
    }
    else{
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
      die;   
    }                                                             
    $userIdArray=$fbManager->fetchStudentFeedbackUserList($conditions,$orderBy,$limit);  
   
    for($j=0;$j<count($userIdArray);$j++) {
       $userId = $userIdArray[$j]['userId']; 
       
       $conditions =' AND fs.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;     
       $studentRecordArray = $fbManager->displayStudentFeedbackLabelWiseList($conditions,$userId,$labelId);
       $i=0; 
       $actionString='';
       if($type==1){ //for students
          if($studentRecordArray[$i]['dateOverFlag']==1){
             if($studentRecordArray[$i]['isCompletedFlag']!=1){
                if($studentRecordArray[$i]['currentStatus']==FEEDBACK_STUDENT_BLOCKED){
                   $studentRecordArray[$i]['currentStatus']='Blocked';
                   $actionString='<a href="javascript:void(0);" title="Unblock this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/selected.gif" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_FORCED_UNBLOCKED.')" ></a>';
                }
                else if($studentRecordArray[$i]['currentStatus']==FEEDBACK_STUDENT_UNBLOCKED){
                   $studentRecordArray[$i]['currentStatus']='<b>Not Blocked</b>';
                   $actionString='<a href="javascript:void(0);" title="Block this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/block_user.png" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_BLOCKED.');" ></a>';
                }
                else{
                   $studentRecordArray[$i]['currentStatus']='UBA*'; 
                   $actionString='<a href="javascript:void(0);" title="Block this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/block_user.png" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_BLOCKED.');" ></a>';
                }
             }
             else{
                $studentRecordArray[$i]['currentStatus']=NOT_APPLICABLE_STRING;
                $actionString=NOT_APPLICABLE_STRING; 
             }  
           }
           else{
              $studentRecordArray[$i]['currentStatus']=NOT_APPLICABLE_STRING;
              $actionString=NOT_APPLICABLE_STRING;
           }
           $valueArray = array_merge(array('srNo' => ($records+$j+1),'actionString'=>$actionString ),$studentRecordArray[$i]); 
        }
        if(trim($json_val)=='') {
           $json_val = json_encode($valueArray);
        }
        else {
           $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    die;
  }
  else if($type==2){
      //***********EMPLOYEE SEARCH FILTER************
    if (!empty($employeeName)) {
        $conditionsArray[] = " (
                                  TRIM(e.employeeName) LIKE '".add_slashes(trim($employeeName))."%'
                               )";
    }
    if (!empty($employeeCode)) {
        $conditionsArray[] = " (
                                  TRIM(e.employeeCode) LIKE '".add_slashes(trim($employeeCode))."%'
                               )";
    }
    if (!empty($designationId)) {
        $conditionsArray[] = " e.designationId in ($designationId) ";
    }
    if (!empty($genderRadio)) {
        $conditionsArray[] = " e.gender in ('".$genderRadio."') ";
    }
    if (!empty($qualification)) {
        $conditionsArray[] = " e.qualification LIKE '".$qualification."%'";
    }
    if (!empty($isMarried)) {
        $conditionsArray[] = " e.isMarried = $isMarried";
    }
    if (!empty($teachEmployee)) {
        $conditionsArray[] = " e.isTeaching = $teachEmployee";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " e.cityId in ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " e.stateId in ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " e.countryId in ($countryId) ";
    }
    if (!empty($instituteId)) {
        $conditionsArray[] = " e.instituteId in ($instituteId) ";
    }
   /*DOB--Start*/
    if (!empty($birthDateF) and $birthDateF != '--') {
        $fromDateAM = intval($birthMonthF);
        $fromDateAD = intval($birthDateF);
        $fromDateAY = intval($birthYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfBirth >= '$thisDate' ";
        }
    }
    if (!empty($birthDateT) and $birthDateT != '--') {
        $fromDateAM = intval($birthMonthT);
        $fromDateAD = intval($birthDateT);
        $fromDateAY = intval($birthYearT);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfBirth <= '$thisDate' ";
        }
    }
   /*DOB--End*/ 
   /*DOJ--Start*/
    if (!empty($joiningDateF) and $joiningDateF != '--') {
        $fromDateAM = intval($joiningMonthF);
        $fromDateAD = intval($joiningDateF);
        $fromDateAY = intval($joiningYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfJoining >= '$thisDate' ";
        }
    }
    if (!empty($joiningDateT) and $joiningDateT != '--') {
        $fromDateAM = intval($joiningMonthT);
        $fromDateAD = intval($joiningDateT);
        $fromDateAY = intval($joiningYearT);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfJoining <= '$thisDate' ";
        }
    }
  /*DOB--End*/  
  /*DOL--Start*/
    if (!empty($leavingDateF) and $leavingDateF != '--') {
        $fromDateAM = intval($leavingMonthF);
        $fromDateAD = intval($leavingDateF);
        $fromDateAY = intval($leavingYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfLeaving >= '$thisDate' ";
        }
    }
    if (!empty($leavingDateT) and $leavingDateT != '--') {
        $fromDateAM = intval($leavingMonthT);
        $fromDateAD = intval($leavingDateT);
        $fromDateAY = intval($leavingYearT);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfLeaving <= '$thisDate' ";
        }
    }
  /*DOL--End*/
  //***********EMPLOYEE SEARCH FILTER ENDS************
  
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    $conditions .=' AND fs.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    
    
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $orderBy = " $sortField $sortOrderBy";
    
    

    ////////////
    
    $totalArray          = $fbManager->displayEmployeFeedbackLabelWiseTotal($conditions);
    $employeeRecordArray = $fbManager->displayEmployeeFeedbackLabelWiseList($conditions,$labelId,$limit,$orderBy);
    $cnt = count($employeeRecordArray);
    $totalRecords=count($totalArray);
  }
   
   // require_once(MODEL_PATH . "/FeedBackProvideAdvancedManager.inc.php");
    //$fbMgr2 = FeedBackProvideAdvancedManager::getInstance();
    
    for($i=0;$i<$cnt;$i++) {
      $actionString='';
      if($type==1){ //for students
       if($studentRecordArray[$i]['dateOverFlag']==1){
         if($studentRecordArray[$i]['isCompletedFlag']!=1){
            if($studentRecordArray[$i]['currentStatus']==FEEDBACK_STUDENT_BLOCKED){
               $studentRecordArray[$i]['currentStatus']='Blocked';
               $actionString='<a href="javascript:void(0);" title="Unblock this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/selected.gif" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_FORCED_UNBLOCKED.')" ></a>';
            }
            else if($studentRecordArray[$i]['currentStatus']==FEEDBACK_STUDENT_UNBLOCKED){
               $studentRecordArray[$i]['currentStatus']='<b>Not Blocked</b>';
               $actionString='<a href="javascript:void(0);" title="Block this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/block_user.png" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_BLOCKED.');" ></a>';
            }
            else{
               $studentRecordArray[$i]['currentStatus']='UBA*'; 
               $actionString='<a href="javascript:void(0);" title="Block this student"><img style="height:20px;" src="'.IMG_HTTP_PATH.'/block_user.png" onclick="changeStatus('.$studentRecordArray[$i]['userId'].','.FEEDBACK_STUDENT_BLOCKED.');" ></a>';
            }
         }
         else{
            $studentRecordArray[$i]['currentStatus']=NOT_APPLICABLE_STRING;
            $actionString=NOT_APPLICABLE_STRING; 
         }  
       }
       else{
          $studentRecordArray[$i]['currentStatus']=NOT_APPLICABLE_STRING;
          $actionString=NOT_APPLICABLE_STRING;
       }
        
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'actionString'=>$actionString ),$studentRecordArray[$i]); 
      }
      else if($type==2){ //for employees
        if($employeeRecordArray[$i]['employeeName']==''){
            $employeeRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        if($employeeRecordArray[$i]['employeeCode']==''){
            $employeeRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        //now check for completed/incompleted/partial status of users corresponding to selected label
        /*
        $allTotalQuestionsArray=$fbMgr2->totalFetchMappedQuestionsForTeachers($labelId,$employeeRecordArray[$i]['userId'],2);
        $allTotalQuestionCount=intval($allTotalQuestionsArray[0]['cnt']);
        
        $allTotalQuestionsAnsweredArray=$fbMgr2->totalAnsweresForMappedQuestionsForStudents($labelId,$employeeRecordArray[$i]['userId'],2);
        $allTotalQuestionAnsweredCount=intval($allTotalQuestionsAnsweredArray[0]['cnt']);
        
        if($allTotalQuestionAnsweredCount==0){
           $employeeRecordArray[$i]['isCompleted']='No';
        }
        else if($allTotalQuestionCount!=$allTotalQuestionAnsweredCount){
           $employeeRecordArray[$i]['isCompleted']='Partial';
        }
        else if($allTotalQuestionCount==$allTotalQuestionAnsweredCount){
           $employeeRecordArray[$i]['isCompleted']='Completed';
        }
        else{
           $employeeRecordArray[$i]['isCompleted']=NOT_APPLICABLE_STRING;  
        }
        */
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$employeeRecordArray[$i]);   
      }
      
      if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
      }
      else {
            $json_val .= ','.json_encode($valueArray);           
      }
    }
   
  // echo '<pre>';
  // print_r($json_val);
  // die;
    
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
   
// $History: ajaxStudentFeedbackStatus.php $
?>