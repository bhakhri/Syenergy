<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentConcessionManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentConcessionManager = StudentConcessionManager::getInstance();
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


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

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE;

    //////
    global $sessionHandler;
    $searchCrieria = "";

    $userId= $sessionHandler->getSessionVariable('UserId');

    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
    }
    $conditionsArray = array();
    
    if (!empty($rollNo)) {
        $conditionsArray[] = " s.rollNo LIKE '".add_slashes($rollNo)."%' ";
        $searchCrieria .="<b>Roll No</b>:$rollNo&nbsp;";
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
        $searchCrieria .="<b>Student Name</b>:$studentName&nbsp;";
    }
    if (!empty($degreeId)) {
        $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    $searchCrieria .="<b>Degree</b>:".trim($REQUEST_DATA['degsText'])."&nbsp;";
    if (!empty($branchId)) {
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    $searchCrieria .="<b>Branch</b>:".trim($REQUEST_DATA['bransText'])."&nbsp;";
    if (!empty($periodicityId)) {
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    $searchCrieria .="<b>StudyPeriod</b>:".trim($REQUEST_DATA['periodsText'])."&nbsp;";
    $course = $REQUEST_DATA['courseId'];
    if (!empty($course)) {
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
    }
    $searchCrieria .="<b>Subject</b>:".trim($REQUEST_DATA['courseText'])."&nbsp;";
    if (!empty($groupId)) {
        $conditionsArray[] = " s.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }
    $searchCrieria .="<b>Group</b>:".trim($REQUEST_DATA['grpsText'])."&nbsp;";

    $fromDateA=$REQUEST_DATA['admissionMonthF'].'-'.$REQUEST_DATA['admissionDateF'].'-'.$REQUEST_DATA['admissionYearF'];
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $thisDate1 = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " s.dateOfAdmission >= '$thisDate' ";
        }
        $searchCrieria .="<b>Admission Date</b>:$thisDate";
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
        if($thisDate1){
            $toDate = " To: ";
        }
        else{
            $toDate ="";
        }
        $searchCrieria .="<b>$toDate</b>$thisDate";
    }

    $fromDateD=$REQUEST_DATA['birthMonthF'].'-'.$REQUEST_DATA['birthDateF'].'-'.$REQUEST_DATA['birthYearF'];
    if (!empty($fromDateD) and $fromDateD != '--') {
        $fromDateArr = explode('-',$fromDateD);
        $fromDateDM = intval($fromDateArr[0]);
        $fromDateDD = intval($fromDateArr[1]);
        $fromDateDY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
            $thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $thisDate1 = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $conditionsArray[] = " s.dateOfBirth >= '$thisDate' ";
        }
        $searchCrieria .="<b>Date Of Birth</b>:$thisDate";
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
        if($thisDate1){
            $toDate = " To: ";
        }
        else{
            $toDate ="";
        }
        $searchCrieria .="<b>$toDate</b>$thisDate";
    }
    if (!empty($gender)) {
        $conditionsArray[] = " s.studentGender = '$gender' ";
        $gender1 = $gender=='M' ? "Male" : "Female";
        $searchCrieria .="<b>Gender</b>:$gender1&nbsp;";
    }
    if ($categoryId!='') {
        $conditionsArray[] = " s.managementCategory = $categoryId ";
    }
    if (!empty($quotaId)) {
        $conditionsArray[] = " s.quotaId IN ($quotaId) ";
    }
    if (!empty($hostelId)) {
        $conditionsArray[] = " s.hostelId IN ('$hostelId') ";
    }
    if (!empty($busStopId)) {
        $conditionsArray[] = " s.busStopId IN ('$busStopId') ";
    }
    if (!empty($busRouteId)) {
        $conditionsArray[] = " s.busRouteId IN ($busRouteId) ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " s.permCityId IN ($cityId) ";
    }
    $citysText = $REQUEST_DATA['citysText'];
    $searchCrieria .="&nbsp;<b>City:</b>$citysText";
    if (!empty($stateId)) {
        $conditionsArray[] = " s.permStateId IN ($stateId) ";
    }
    $statesText = $REQUEST_DATA['statesText'];
    $searchCrieria .="&nbsp;<b>State:</b>$statesText";
    if (!empty($countryId)) {
        $conditionsArray[] = " s.permCountryId IN ($countryId) ";
    }
    $cntsText= $REQUEST_DATA['cntsText'];
    $searchCrieria .="&nbsp;<b>Country:</b>$cntsText";
    if (!empty($universityId)) {
        $conditionsArray[] = " cl.universityId IN ($universityId) ";
    }
    $univsText= $REQUEST_DATA['univsText'];
    $searchCrieria .="&nbsp;<b>University:</b>$univsText";
    if (!empty($instituteId)) {
        $conditionsArray[] = " cl.instituteId IN ($instituteId) ";
    }
    
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " s.studentBloodGroup = $bloodGroup";
    }
    
    //fee receipt Number
    $feeReceiptNo = add_slashes(trim($REQUEST_DATA['feeReceiptNo']));
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " s.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo =add_slashes(trim($REQUEST_DATA['regNo']));
    if (!empty($instRegNo)) {
        $conditionsArray[] = " s.regNo LIKE '$instRegNo%' ";
        $qryString.= "&regNo=$instRegNo";
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';
    
    $orderBy = " $sortField $sortOrderBy";      

    ////////////
    $userRoleArray = $studentConcessionManager->getRoleUser($userId);
    if(is_array($userRoleArray) and count($userRoleArray)>0){
        $classIdCondition=' AND c.classId IN ('.UtilityManager::makeCSList($userRoleArray,'classId').')';
        $groupIdCondition=' AND g.groupId IN ('.UtilityManager::makeCSList($userRoleArray,'groupId').')';
        $conditions .=$classIdCondition.$groupIdCondition;
    }
    
    if(trim($REQUEST_DATA['feeCycle'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    $feeCycleId=trim($REQUEST_DATA['feeCycle']);    

    $studentRecordArray = $studentConcessionManager->getStudentList($conditions,' ',$orderBy,$feeCycleId);
    $cnt = count($studentRecordArray);
    
    
    for($i=0;$i<$cnt;$i++) {
       $studentId=$studentRecordArray[$i]['studentId']; 
       $classId=$studentRecordArray[$i]['classId']; 
       //now fetch total fees of this student
       $studentFeesArray = $studentManager->getStudentDetailClass(' AND stu.studentId='.$studentId.' AND cls.classId='.$classId);
       $totalFees = 0;
       $hostelFacility = 0;
       $transportFacility = 0;
       if(is_array($studentFeesArray) and count($studentFeesArray)>0){
           $studentHeadFeesArray = $studentManager->getStudentFeeHeadDetailClass($feeCycleId,$studentFeesArray[0]['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet']);
           $feeCnt = count($studentHeadFeesArray);
           for($lm=0;$lm<$feeCnt;$lm++) {
                $totalFees += $studentHeadFeesArray[$lm]['feeHeadAmount'];
          }
       }
         
        $hostelFacility = $studentRecordArray[$i]['hostelFacility'];
        $transportFacility = $studentRecordArray[$i]['transportFacility'];
        
        $busCondition = "";
        if($studentRecordArray[$i]['busStopId']!=0){
            $busCondition = " and bus.busStopId = ".$studentRecordArray[$i]['busStopId'];
        } 
        $studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);

        $hostelCondition = "";
        if($studentRecordArray[$i]['hostelRoomId']!=0){
            $hostelCondition = "  and hosroom.hostelRoomId = ".$studentRecordArray[$i]['hostelRoomId'];
        } 
        $studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);

        $totalFees+=$studentBusFeesArray[0]['feeHeadAmount'];
        $totalFees+=$studentHostelFeesArray[0]['feeHeadAmount'];

       $studentRecordArray[$i]['totalFees']=$totalFees;
   
       $concessionType=$studentRecordArray[$i]['concessionType']; 
       $concessionValue=$studentRecordArray[$i]['concessionValue'];
       $reason=$studentRecordArray[$i]['reason'];
       
       $percentageSelected='';
       $valueSelected='';
       if($concessionType==1){
           $concessionString='Percentage';
       }
       else if($concessionType==2){
           $concessionString='Amount';
       }
       $studentRecordArray[$i]['concessionType']=$concessionString;
       $studentRecordArray[$i]['srNo']=($i+1);
       //$studentRecordArray[$i]['concessionValue']=$concessionValue;
       //$studentRecordArray[$i]['reason']='<input type="text" name="reason" id="reason_'.$studentId.'_'.$classId.'" class="inputbox" value="'.$reason.'" maxlength="240" />';
       
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student Concession');
    $reportManager->setReportInformation("Search By: $searchCrieria<br/><b>Fee Cycle</b> : ".trim($REQUEST_DATA['feeCycleName']));
	 
	$reportTableHead					 =	array();
    $reportTableHead['srNo']			 =   array('#','width="1%"', "align='left' ");
    $reportTableHead['studentName']      =   array('Name','width=12% align="left"', 'align="left"');
	$reportTableHead['rollNo']		     =   array('Roll No.','width=7% align="left"', 'align="left"');
	$reportTableHead['universityRollNo'] =   array('Univ. Roll No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['className']        =   array('Class','width="12%" align="left" ', 'align="left"');
    $reportTableHead['totalFees']        =   array('Fees','width="4%" align="right" ', 'align="right"');
    $reportTableHead['concessionType']   =   array('Concession Type','width="12%" align="left" ', 'align="left"');
    $reportTableHead['concessionValue']  =   array('Value','width="5%" align="left" ', 'align="left"');
    $reportTableHead['reason']           =   array('Reason','width="10%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $studentRecordArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>