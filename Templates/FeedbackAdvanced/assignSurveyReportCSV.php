<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    $conditionsArray = array();
    $qryString = "";
    
require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$studentMgr=StudentInformationManager::getInstance();

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
$fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();    
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
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

//this function is used to detemine whether student is blocked or not for incomplete feedback
function isStudentBlocked($userId,$roleId){
    
    $returnValue=0;
    global $studentMgr;
    
    $feedbackStudentStatusArray=$studentMgr->checkFeedbackStudentStatus($userId);
    if(is_array($feedbackStudentStatusArray)>0 and count($feedbackStudentStatusArray)>0){
    $feedbackStudentStatus=$feedbackStudentStatusArray[0]['status'];
    if($feedbackStudentStatus==FEEDBACK_STUDENT_BLOCKED){
        //get the total labels applicable to this user
        $labelArray=CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId,1);
        $labelCnt=count($labelArray);
        $labelString='';
        for($i=0;$i<$labelCnt;$i++){
          if($labelString!=''){
              $labelString .=',';
          }
          $labelString .=$labelArray[$i]['feedbackSurveyId'];
        }
       if($labelString!=''){
           //get the maximum visible date of these labels
           $maxDateArray=$studentMgr->getMaxDateOfFeedbackLabels($labelString);
           if(is_array($maxDateArray) and count($maxDateArray)>0){
               $maxDate   = strtotime($maxDateArray[0]['maxDate']);
               $logInDate = strtotime(date('Y-m-d'));
               if($logInDate > $maxDate){
                   $returnValue=1;
               }
               else{
                   $returnValue=0;
               }
           }
       } 
    }
  }
  
  return $returnValue;
}


    //////
    $conditionsArray = array();

    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
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
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";         
     
    ////////////
    
    $studentRecordArray = $fbManager->displayStudentFeedbackStatusList($conditions,' ',$orderBy);
    $cnt = count($studentRecordArray);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $status=$studentRecordArray[$i]['status'];
        $studentUserId=$studentRecordArray[$i]['userId'];
        $studentRoleId=4;
        $blocked='No';
        if($status==FEEDBACK_STUDENT_BLOCKED){
          //check for partial completion
          $partialArray=$fbManager->totalAnsweresForMappedQuestionsForStudents($studentUserId,$studentRoleId);
          if($partialArray[0]['cnt']==0){ 
            $studentRecordArray[$i]['status']='No';
          }
          else{
            $studentRecordArray[$i]['status']='Partial';  
          }
        }
        else{
           if($status==FEEDBACK_STUDENT_UNBLOCKED){ 
             $studentRecordArray[$i]['status']='Yes';
           }
           else{
             $studentRecordArray[$i]['status']=NOT_APPLICABLE_STRING;
           }
        }
        if($status==FEEDBACK_STUDENT_FORCED_UNBLOCKED){
           $blocked='UBA*';
        }
        else if($status==FEEDBACK_STUDENT_UNBLOCKED){
           $blocked=NOT_APPLICABLE_STRING; 
        }
        else{
            //here goes the algo for determining blocked/unblocked status
            $studentUserId=$studentRecordArray[$i]['userId'];
            $studentRoleId=4;
            $isStudentBlocked=isStudentBlocked($studentUserId,$studentRoleId);
            if($isStudentBlocked==1){
              $blocked='Yes';
            }
            else{
              $blocked='No';
            }
        }
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1),'isBlocked'=>$blocked ),$studentRecordArray[$i]);
    }

	$csvData = '';
    $csvData .= " , UBA : Unblocked By Administrator \n";
    $csvData .= "#, Name, Roll No., Class, Completed, Blocked \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['studentName']).', '.parseCSVComments($record['rollNo']).', '.parseCSVComments($record['className']).','.$record['status'].','.$record['isBlocked'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="assignSurveyReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: assignSurveyReportCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/02/10   Time: 12:20
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done modifications :
//1.Showing Yes/No/Partial status for student feedback status in report.
//2.Highlight tabs and questions when NA is selected.
//3.Changed status message when partial feedback is given
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:06
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created the repoort for showing student status for feedbacks
?>