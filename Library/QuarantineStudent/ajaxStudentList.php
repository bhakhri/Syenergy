<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To show student list for quarantine upon selection criteria
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (06.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','QuarantineStudentMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/QuarantineStudentManager.inc.php");
    $quarantineStudentManager = QuarantineStudentManager::getInstance();
    
//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:06.11.2008
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
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    
    /////search functionility not needed   
    //$filter=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".trim(add_slashes($REQUEST_DATA['studentRollNo']))."' " :" AND  s.classId='".$REQUEST_DATA['class']."' ");
    
    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
    
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
    if (!empty($degreeId)) {
        $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    if (!empty($branchId)) {
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    if (!empty($periodicityId)) {
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    $course = $REQUEST_DATA['courseId'];
    if (!empty($course)) {
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
     
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

	############ FOR ATTENDANCE FROM / TO ##############################
	 $attendanceFrom = $REQUEST_DATA['attendanceFrom'];
	 if (!empty($attendanceFrom) or !empty($attendanceTo)) {
		 require_once(MODEL_PATH . "/StudentManager.inc.php");
		 $studentManager = StudentManager::getInstance();

		 $having = "";
		 if (!empty($attendanceFrom)) {
			  $having = " having percentage >= $attendanceFrom";
			  $qryString .= "&attendanceFrom=$attendanceFrom";
		 }
		 $attendanceTo = $REQUEST_DATA['attendanceTo'];
		 if (!empty($attendanceTo)) {
			  if ($having != '') {
					$having .= " and ";
			  }
			  else {
				  $having .= " having ";
			  }
			  $having .= " percentage <= $attendanceTo";
			  $qryString .= "&attendanceTo=$attendanceTo";
		 }
		 
		 $studentListArray = $studentManager->getShortAttendanceStudents($having);
		 $studentIdList = UtilityManager::makeCSList($studentListArray, 'studentId');
		 if($studentIdList != '') {
			$conditionsArray[] = " s.studentId IN ($studentIdList) ";
		 }
		 else {
			echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
			die;
		 }
	 }
	 ########################################################################

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray         = $quarantineStudentManager->getTotalStudent($conditions);
    $studentRecordArray = $quarantineStudentManager->getStudentList($conditions,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($studentRecordArray[$i]['dateOfBirth']!=NOT_APPLICABLE_STRING){
        $studentRecordArray[$i]['dateOfBirth']=UtilityManager::formatDate($studentRecordArray[$i]['dateOfBirth']); 
       }
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId'] ."\">")
        , $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxStudentList.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Library/QuarantineStudent
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Library/QuarantineStudent
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/08/09    Time: 17:14
//Updated in $/LeapCC/Library/QuarantineStudent
//Updated Student Filter as two new seach fields are added
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 12:59
//Updated in $/LeapCC/Library/QuarantineStudent
//Corrected bugs related to "find student"
//
//*****************  Version 3  *****************
//User: Administrator Date: 30/05/09   Time: 10:40
//Updated in $/LeapCC/Library/QuarantineStudent
//Added BloodGroup wise search in messaging and delete/restore student
//modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/08   Time: 11:47
//Updated in $/LeapCC/Library/QuarantineStudent
//Added subject and group dropdown in student filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 6:48p
//Created in $/LeapCC/Library/QuarantineStudent
//Created quarantine student module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/21/08   Time: 10:24a
//Updated in $/Leap/Source/Library/ScQuarantineStudent
//Added Course wise search functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:47p
//Updated in $/Leap/Source/Library/ScQuarantineStudent
//Removed "management access" from module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:12p
//Created in $/Leap/Source/Library/ScQuarantineStudent
//Created Quarantine(delete) Student Module
?>
