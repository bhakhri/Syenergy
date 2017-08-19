<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (20.01.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
function trim_output($val){
 return (trim($val)!="" ? $val : "Not Present");   
}


    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();
    
//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Jaineesh
//Date:20.01.2009
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
    
    /////search functionility not needed   
    //$filter=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".trim(add_slashes($REQUEST_DATA['studentRollNo']))."' " :" AND  s.classId='".$REQUEST_DATA['class']."' ");
    
    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
    
    if (!empty($rollNo)) {
        $conditionsArray[] = " s.rollNo LIKE '".add_slashes(trim($rollNo))."%' ";
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
    $feeReceiptNo = add_slashes(trim($REQUEST_DATA['feeReceiptNo']));
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " s.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo = add_slashes(trim($REQUEST_DATA['regNo']));
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
			echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}';
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
    
	
    $totalArray = $sendMessageManager->getTotalParentList($conditions);
	
	$studentRecordArray = $sendMessageManager->getParentList($conditions,$limit,$orderBy);

	$cnt = count($studentRecordArray);
	
    
	for($i=0;$i<$cnt;$i++) {
		$fdisable=(trim($studentRecordArray[$i]['fatherUserId'])!="" ? " "   : "disabled"); 
		$mdisable=(trim($studentRecordArray[$i]['motherUserId'])!="" ? " "   : "disabled");
		$gdisable=(trim($studentRecordArray[$i]['guardianUserId'])!="" ? " " : "disabled");
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"fatherName" => "<input type=\"checkbox\" name=\"fathers\" id=\"fathers\" $fdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['fatherName'])),
       "motherName" => "<input type=\"checkbox\" name=\"mothers\" id=\"mothers\" $mdisable  value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['motherName'])),
       "guardianName" => "<input type=\"checkbox\" name=\"guardians\" id=\"guardians\" $gdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['guardianName'])),
        'studentName' =>strip_slashes($studentRecordArray[$i]['studentName']) ,
       'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
       'universityRollNo' =>strip_slashes($studentRecordArray[$i]['universityRollNo'])));

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}'; 
    
// for VSS
// $History: scAjaxAdminParentMessageList.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 20/03/10   Time: 17:34
//Updated in $/LeapCC/Library/AdminMessage
//Created "Sent Student Information Message To Parents" module
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 13/10/09   Time: 13:46
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//Bug ids---
//00001774,00001775,00001776
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:14
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//Bug ids---
//00001201,00001207,00001208,00001216
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/08/09    Time: 17:14
//Updated in $/LeapCC/Library/AdminMessage
//Updated Student Filter as two new seach fields are added
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 12:41
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//Bug ids--1111,1112,1114,1115,1116,1117,1118)
//
//*****************  Version 3  *****************
//User: Administrator Date: 30/05/09   Time: 10:40
//Updated in $/LeapCC/Library/AdminMessage
//Added BloodGroup wise search in messaging and delete/restore student
//modules
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/26/09    Time: 5:44p
//Updated in $/LeapCC/Library/AdminMessage
//Updated with Management access so that it can be accessed from
//management login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:28
//Created in $/LeapCC/Library/AdminMessage
//Created module  "Send Message for Parents"
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/09    Time: 6:16p
//Created in $/Leap/Source/Library/ScAdminMessage
//new library to show parent list or insert into data
//

?>
