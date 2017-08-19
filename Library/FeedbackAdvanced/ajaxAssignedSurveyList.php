<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
$fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();

$timeTableId=trim($REQUEST_DATA['timeTableLabelId']);
$labelId=trim($REQUEST_DATA['labelId']);
$catId=trim($REQUEST_DATA['catId']);
$questionSetId=trim($REQUEST_DATA['questionSetId']);

if($timeTableId=='' or $labelId=='' or $catId=='' or $questionSetId==''){
    echo 'Required Parameters Missing';
    die;
}

//determine the search mode( Dynamically!!!)
$searchModeArray=$fbManager->getLabelApplicableTo($timeTableId,$labelId);
if(count($searchModeArray)>0 and is_array($searchModeArray)){
    $applicableRoleId=$searchModeArray[0]['roleId'];
    if($applicableRoleId == 3){
      $applicableTo='PARENT';
    }
    else if($applicableRoleId == 4){
      $applicableTo='STUDENT';
    }
    else{
      $applicableTo='EMPLOYEE';   
    }
}
else{
    echo 'Required Parameters Missing************';
    die;
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


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    $conditionsArray = array();

    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
   
  if($applicableTo=='EMPLOYEE'){
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
        /*
        $fromDateArr = explode('-',$birthDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
        $fromDateAM = intval($birthMonthF);
        $fromDateAD = intval($birthDateF);
        $fromDateAY = intval($birthYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfBirth >= '$thisDate' ";
        }
    }
    if (!empty($birthDateT) and $birthDateT != '--') {
        /*
        $fromDateArr = explode('-',$birthDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
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
        /*
        $fromDateArr = explode('-',$joiningDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
        $fromDateAM = intval($joiningMonthF);
        $fromDateAD = intval($joiningDateF);
        $fromDateAY = intval($joiningYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfJoining >= '$thisDate' ";
        }
    }
    if (!empty($joiningDateT) and $joiningDateT != '--') {
        /*
        $fromDateArr = explode('-',$joiningDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
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
        /*
        $fromDateArr = explode('-',$leavingDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
        $fromDateAM = intval($leavingMonthF);
        $fromDateAD = intval($leavingDateF);
        $fromDateAY = intval($leavingYearF);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfLeaving >= '$thisDate' ";
        }
    }
    if (!empty($leavingDateT) and $leavingDateT != '--') {
        /*
        $fromDateArr = explode('-',$leavingDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        */
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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray          = $fbManager->getTotalEmployee($conditions,$labelId,$catId,$questionSetId);
    $employeeRecordArray = $fbManager->getEmployeeList($conditions,$limit,$orderBy,$labelId,$catId,$questionSetId);
    $cnt = count($employeeRecordArray);
    $totalRecords=count($totalArray);
    
    $selectedEmp=explode(",",$REQUEST_DATA['selectedEmp']);
    $len=count($selectedEmp);
   
    $onclick='onclick="checkUncheckEmployee(this.value,this.checked);"';   //adding onclick handler
   
    for($i=0;$i<$cnt;$i++) {

      if($len>1 and is_array($selectedEmp)){  //check for initial values
        if(in_array($employeeRecordArray[$i]['userId'],$selectedEmp)){
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" checked=\"checked\" value=\"".$employeeRecordArray[$i]['userId'] ."\" $onclick>")
        , $employeeRecordArray[$i]);
        }
        else{
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" value=\"".$employeeRecordArray[$i]['userId'] ."\" $onclick>")
        , $employeeRecordArray[$i]);
        }
      }
     else{
       if($employeeRecordArray[$i]['empAssigned']==1){ 
        $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" checked=\"checked\" value=\"".$employeeRecordArray[$i]['userId'] ."\" $onclick>")
         , $employeeRecordArray[$i]);
       }
       else{
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" value=\"".$employeeRecordArray[$i]['userId'] ."\" $onclick>")
         , $employeeRecordArray[$i]);
       }
     } 

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.'],"employeeInfo" : '.json_encode($totalArray).'}';   
  }
  
  
 else if($applicableTo=='STUDENT'){
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
    $classCondition   = -1;
    $subjectCondition = -1;
    if (!empty($degreeId)) {
       if(trim($REQUEST_DATA['fbFlag'])!=4){ //if not for subject wise display
          $conditionsArray[] = " cl.degreeId in ($degreeId) ";
          $classCondition   = -1;
          $subjectCondition = -1;
       }
       else{
          $conditionsArray[] = " cl.classId in ($degreeId) ";
          $classCondition=$degreeId;
          $subjectCondition=$course;
       }
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
        if(trim($REQUEST_DATA['fbFlag'])==2 or trim($REQUEST_DATA['fbFlag'])==3){ //for hostel/transport
         $conditionsArray[] = " s.hostelFacility=1";
        }
        $conditionsArray[] = " s.hostelId IN ('$hostelId') ";
    }
    $transportFlag=0;
    if (!empty($busStopId)) {
        if(trim($REQUEST_DATA['fbFlag'])==2 or trim($REQUEST_DATA['fbFlag'])==3){ //for hostel/transport
          $transportFlag=1;
          $conditionsArray[] = " s.transportFacility = 1 ";
        }
        $conditionsArray[] = " s.busStopId IN ($busStopId) ";
    }
    if (!empty($busRouteId)) {
        if(trim($REQUEST_DATA['fbFlag'])==2 or trim($REQUEST_DATA['fbFlag'])==3){ //for hostel/transport
          if($transportFlag==0){ //so that this condition does not append twice
           $conditionsArray[] = " s.transportFacility = 1 ";
          }
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
    
    $totalArray         = $fbManager->getTotalStudent($conditions,$labelId,$catId,$questionSetId,$classCondition,$subjectCondition);
    $studentRecordArray = $fbManager->getStudentList($conditions,$limit,$orderBy,$labelId,$catId,$questionSetId,$classCondition,$subjectCondition);
    $cnt = count($studentRecordArray);
    $totalRecords=count($totalArray);
    
    $selectedStudent=explode(",",trim($REQUEST_DATA['selectedStudent']));
    $len=count($selectedStudent); 
   
    $onclick='onclick="checkUncheckStudent(this.value,this.checked);"';  //adding onclick handler 
    
    for($i=0;$i<$cnt;$i++) {
       if($len>1 and is_array($selectedStudent)){  //check for initial values
        if(in_array($studentRecordArray[$i]['userId'],$selectedStudent)){
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" checked=\"checked\" value=\"".$studentRecordArray[$i]['userId'] ."\" $onclick>")
        , $studentRecordArray[$i]);
        }
        else{
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['userId'] ."\" $onclick>")
        , $studentRecordArray[$i]);
        }
      }
     else{
       if($studentRecordArray[$i]['studentAssigned']=='1'){ 
        $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" checked=\"checked\" value=\"".$studentRecordArray[$i]['userId'] ."\" $onclick>")
         , $studentRecordArray[$i]);
       }
       else{
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['userId'] ."\" $onclick>")
         , $studentRecordArray[$i]);
       }
     } 

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}'; 
  }  

    


   
// $History: ajaxAssignedSurveyList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/02/10    Time: 10:37
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Corrected bug in Assign Survey module when search in made on bus
//stops/bus routes or hostels.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>