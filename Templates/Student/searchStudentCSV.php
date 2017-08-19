<?php 
//This file is used as csv output of student list.
//
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
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

		$genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}

	global $sessionHandler;
	$userId= $sessionHandler->getSessionVariable('UserId');
    
    $showAlumnniStudent=trim($REQUEST_DATA['alumniStudent']);
    if($showAlumnniStudent==''){
        $showAlumnniStudent=1;
    }

	//Roll Number
	$rollNo = trim($REQUEST_DATA['rollNo']);
	if (!empty($rollNo)) {
		$conditionsArray[] = " (a.rollNo LIKE '$rollNo%' OR a.regNo LIKE '$rollNo%' OR a.universityRollNo LIKE '$rollNo%')  ";
		$qryString.= "&rollNo=$rollNo";
	}

	//Student Name
	$studentName = trim($REQUEST_DATA['studentName']);
	if (!empty($studentName)) {
		//$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
		$qryString.= "&studentName=$studentName";
	}

	//Student Gender
	$gender = $REQUEST_DATA['gender'];
	if (!empty($gender)) {
		$conditionsArray[] = " a.studentGender = '$gender' ";
		$qryString .= "&gender=$gender";
	}

	//From Date of birth
	$birthDateF = $REQUEST_DATA['birthDateF'];
	$birthMonthF = $REQUEST_DATA['birthMonthF'];
	$birthYearF = $REQUEST_DATA['birthYearF'];

	if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

		if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
			$thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
			$conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
		}
		$qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
	}

	//To Date of birth
	$birthDateT = $REQUEST_DATA['birthDateT'];
	$birthMonthT = $REQUEST_DATA['birthMonthT'];
	$birthYearT = $REQUEST_DATA['birthYearT'];

	if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

		if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
			$thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
			$conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
		}
		$qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
	}

	//fee receipt Number
	$feeReceiptNo = $REQUEST_DATA['feeReceiptNo'];
	if (!empty($feeReceiptNo)) {
		$conditionsArray[] = " a.feeReceiptNo LIKE '$feeReceiptNo%' ";
		$qryString.= "&feeReceiptNo=$feeReceiptNo";
	}

	//registration Number
	$instRegNo = $REQUEST_DATA['regNo'];
	if (!empty($instRegNo)) {
		$conditionsArray[] = " a.regNo LIKE '$instRegNo%' ";
		$qryString.= "&regNo=$instRegNo";
	}
	//degree
	$degs = $REQUEST_DATA['degs'];
	if (!empty($degs)) {
		$conditionsArray[] = " b.degreeId in ($degs) ";
		$qryString.= "&degreeId=$degs";
	}

	//branch
	$brans = $REQUEST_DATA['branchId'];
	if (!empty($brans)) {
		$conditionsArray[] = " b.branchId in ($brans) ";
		$qryString.= "&branchId=$brans";
	}

	//periodicity
	$periods = $REQUEST_DATA['periods'];
	if (!empty($periods)) {
		$conditionsArray[] = " b.studyPeriodId IN ($periods) ";
		$qryString.= "&periodicityId=$periods";
	}
	
	//course
	$course = $REQUEST_DATA['course'];
	if (!empty($course)) {
		$conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
		$qryString.= "&subjectId=$course";
	} 

	//groups
	$groupId = $REQUEST_DATA['groupId'];
	if (!empty($groupId)) {
		$conditionsArray[] = " a.studentId IN (SELECT DISTINCT studentId from student_groups WHERE groupId IN ($groupId) UNION
                                               SELECT DISTINCT studentId FROM student_optional_subject WHERE  groupId IN ($groupId))"; 
		$qryString .= "&groupId=$groupId";
	}
	 

	//university
	$univs = $REQUEST_DATA['univs'];
	if (!empty($univs)) {
		$conditionsArray[] = " b.universityId IN ($univs) ";
		$qryString .= "&universityId=$univs";
	}

	//city
	$citys = $REQUEST_DATA['citys'];
	if (!empty($citys)) {
		$conditionsArray[] = " (a.corrCityId IN ($citys)  OR  a.permCityId IN ($citys))";
		$qryString .= "&cityId=$citys";
	}

	//states
	$states = $REQUEST_DATA['states'];
	if (!empty($states)) {
		$conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
		$qryString .= "&stateId=$states";
	}

	//country
	$cnts = $REQUEST_DATA['cnts'];
	if (!empty($cnts)) {
		$conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts))";
		$qryString .= "&countryId=$cnts";
	}

	//management category
	$categoryId = $REQUEST_DATA['categoryId'];
	if (!empty($categoryId)) {
		$conditionsArray[] = " a.managementCategory = $categoryId ";
		$qryString .= "&categoryId=$categoryId";
	}

	//From Admission Date
	$admissionDateF = $REQUEST_DATA['admissionDateF'];
	$admissionMonthF = $REQUEST_DATA['admissionMonthF'];
	$admissionYearF = $REQUEST_DATA['admissionYearF'];

	if (!empty($admissionDateF) && !empty($admissionMonthF) && !empty($admissionYearF)) {

		if (false !== checkdate($admissionMonthF, $admissionDateF, $admissionYearF)) {
			$thisDate = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
			$conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
		}
		$qryString.= "&admissionDateF=$admissionDateF&admissionMonthF=$admissionMonthF&admissionYearF=$admissionYearF";
	}

	//To Admission Date
	$admissionDateT = $REQUEST_DATA['admissionDateT'];
	$admissionMonthT = $REQUEST_DATA['admissionMonthT'];
	$admissionYearT = $REQUEST_DATA['admissionYearT'];

	if (!empty($admissionDateT) && !empty($admissionMonthT) && !empty($admissionYearT)) {

		if (false !== checkdate($admissionMonthT, $admissionDateT, $admissionYearT)) {
			$thisDate = $admissionYearT.'-'.$admissionMonthT.'-'.$admissionDateT;
			$conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
		}
		$qryString.= "&admissionDateT=$admissionDateT&admissionMonthT=$admissionMonthT&admissionYearT=$admissionYearT";
	}

	//hostel
	$hostels = $REQUEST_DATA['hostels'];
	if (!empty($hostels)) {
		$conditionsArray[] = " a.hostelId IN ('$hostels') ";
		$qryString .= "&hostelId=$hostels";
	}

	//bus stop
	$buss = $REQUEST_DATA['buss'];
	if (!empty($buss)) {
		$conditionsArray[] = " a.busStopId IN ('$buss') ";
		$qryString .= "&busStopId=$buss";
	}

	//bus route
	$routs = $REQUEST_DATA['routs'];
	if (!empty($routs)) {
		$conditionsArray[] = " a.busRouteId IN ($routs) ";
		$qryString .= "&busRouteId=$routs";
	} 
	
	//quota
	$quotaId = $REQUEST_DATA['quotaId'];
	if (!empty($quotaId)) {
		$conditionsArray[] = " a.quotaId IN ($quotaId) ";
		$qryString .= "&quotaId=$quotaId";
	}
	
	//blood group
	$bloodGroup = $REQUEST_DATA['bloodGroup'];
 
	if (!empty($bloodGroup)) {
		$conditionsArray[] = " a.studentBloodGroup IN ($bloodGroup) ";
		$qryString .= "&bloodGroup=$bloodGroup";
		 
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
			$conditionsArray[] = " a.studentId IN ($studentIdList) ";
		 }
		 else {
			$conditionsArray[] = " a.studentId IN (0) ";
		 }
	 }
	 ########################################################################	

	$conditions = '';
	if (count($conditionsArray) > 0) {
		$conditions = ' AND '.implode(' AND ',$conditionsArray);
	}

	function parseCSVComments($comments) {
  	  $comments = str_replace('"', '""', $comments);
	  if(eregi(",", $comments) or eregi("\n", $comments)) {
		return '"'.$comments.'"'; 
	  } 
	  else {
	    return $comments; 
	  }
	}


	$conditions = $sessionHandler->getSessionVariable('IdToStudentList');

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';

	$orderBy="a.$sortField $sortOrderBy"; 

	if($sortField=="studyPeriod")
		$orderBy= "b.studyPeriodId $sortOrderBy"; 

	if($sortField=="groupId")
		$orderBy= "sg.groupId $sortOrderBy"; 

	if($sortField=="className")
		$orderBy= "b.className $sortOrderBy"; 

	/* END: search filter */



	if($sessionHandler->getSessionVariable('RoleId')=='9') {
      $userRoleArray = $studentManager->getRoleUser($userId,$showAlumnniStudent);
      $roleCount = $userRoleArray[0]['totalRecords'];
    }

    if ($roleCount > 0) {
        $recordArray = $studentManager->getRoleStudentListFast($conditions,'',$orderBy,$userId,$showAlumnniStudent);
    }
    else {
        $recordArray = $studentManager->getStudentListFast($conditions,'',$orderBy,$showAlumnniStudent);
    }
	 

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr, Student Name, Roll No., Univ. No.,Reg. No., Class,Group, Mobile \n";

    if (count($valueArray) == 0){
		$csvData .= "No Data Found \n";
	}
	else {
		foreach ($valueArray as $record) {
			$csvData .= $record['srNo'].','.parseCSVComments($record['firstName']).','.parseCSVComments($record['rollNo']).','.parseCSVComments($record['universityRollNo']).','.parseCSVComments($record['regNo']).','.parseCSVComments($record['className']).','.parseCSVComments($record['groupId']).','.parseCSVComments($record['studentMobileNo']);
			$csvData .= "\n";
		}
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="allStudentList.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: searchStudentCSV.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 4/02/10    Time: 18:57
//Updated in $/LeapCC/Templates/Student
//resolved issues 0002650,0002620,0002098,0001602,0002788,0002785
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 09-10-24   Time: 2:02p
//Updated in $/LeapCC/Templates/Student
//fixed bug no 0001821,0001880,0001816,0001852,0001851,0001637,0001329
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Templates/Student
//worked on role to class
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/22/09    Time: 3:40p
//Updated in $/LeapCC/Templates/Student
//added Registration No. and Fee receipt no in student filter
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/09/09    Time: 4:15p
//Updated in $/LeapCC/Templates/Student
//Updated issues sent by Sachin sir dated 9thjune
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/14/09    Time: 12:25p
//Updated in $/LeapCC/Templates/Student
//Updated search filter with permanent cityid, stateId and countryid
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Templates/Student
//Added group filter in student search
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:53p
//Created in $/LeapCC/Templates/Student
//intial checkin
?>