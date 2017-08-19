<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
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
	$searchCrieria = "";
	global $sessionHandler;
	$REQUEST_DATA = $sessionHandler->getSessionVariable('FormData');
	//echo "<pre>";
//	print_r($REQUEST_DATA);die;
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
		$conditionsArray[] = " (a.rollNo LIKE '$rollNo%' OR a.regNo LIKE '$rollNo%' OR a.universityRollNo LIKE '$rollNo%') ";
		$qryString.= "&rollNo=$rollNo";
		$searchCrieria .="<b>Reg. No./ Uni. No./ Roll No.</b>:$rollNo&nbsp; ";
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
		$searchCrieria .="<b>studentName</b>:$studentName&nbsp;";
	}

	//Student Gender
	$gender = $REQUEST_DATA['gender'];
	if (!empty($gender)) {
		$conditionsArray[] = " a.studentGender = '$gender' ";
		$qryString .= "&gender=$gender";
		$gender1 = $gender=='M' ? "Male" : "Female";

		$searchCrieria .="<b>Gender</b>:$gender1&nbsp;";
	}

	//From Date of birth
	$birthDateF = $REQUEST_DATA['birthDateF'];
	$birthMonthF = $REQUEST_DATA['birthMonthF'];
	$birthYearF = $REQUEST_DATA['birthYearF'];

	if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

		if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
			$thisDate = $birthDateF.'-'.$birthMonthF.'-'.$birthYearF;
			$thisDate1 = $birthDateF.'-'.$birthMonthF.'-'.$birthYearF;
			$conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
		}
		$qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
		$searchCrieria .="<b>Date Of Birth</b>:$thisDate";
	}

	//To Date of birth
	$birthDateT = $REQUEST_DATA['birthDateT'];
	$birthMonthT = $REQUEST_DATA['birthMonthT'];
	$birthYearT = $REQUEST_DATA['birthYearT'];

	if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

		if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
			$thisDate = $birthDateT.'-'.$birthMonthT.'-'.$birthYearT;
			$conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
		}
		$qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
		 
		if($thisDate1)
			$toDate = " To: ";
		else
			$toDate ="";
		$searchCrieria .="<b>$toDate</b>$thisDate";
	}

	//fee receipt Number
	$feeReceiptNo = $REQUEST_DATA['feeReceiptNo'];
	if (!empty($feeReceiptNo)) {
		$conditionsArray[] = " a.feeReceiptNo LIKE '$feeReceiptNo%' ";
		//$qryString.= "&feeReceiptNo=$feeReceiptNo";
	}

	//registration Number
	$instRegNo = $REQUEST_DATA['regNo'];
	if (!empty($instRegNo)) {
		$conditionsArray[] = " a.regNo LIKE '$instRegNo%' ";
		//$qryString.= "&regNo=$instRegNo";
	}
	//degree
	$degs = $REQUEST_DATA['degreeId'];
	$degsText = $REQUEST_DATA['degsText'];
	if (!empty($degs)) {
		$conditionsArray[] = " b.degreeId in ($degs) ";
		$qryString.= "&degreeId=$degs";
		
	}
	else{
	
		$degsText =  NOT_APPLICABLE_STRING;
	}
	$searchCrieria .="&nbsp;<b>Degree:</b>$degsText";

	//branch
	$brans = $REQUEST_DATA['branchId'];
	$bransText = $REQUEST_DATA['branText'];
	if (!empty($brans)) {
		$conditionsArray[] = " b.branchId in ($brans) ";
		$qryString.= "&branchId=$brans";
	}
	if($brans=='')
		$bransText =  NOT_APPLICABLE_STRING ;
	$searchCrieria .="&nbsp;<b>Branches:</b>$bransText";

	//periodicity
	$periods = $REQUEST_DATA['periods'];
	$periodsText = $REQUEST_DATA['periodsText'];
	if (!empty($periods)) {
		$conditionsArray[] = " b.studyPeriodId IN ($periods) ";
		$qryString.= "&periodicityId=$periods";
	}
	if($periods =='')
		$periodsText= NOT_APPLICABLE_STRING;
	$searchCrieria .="&nbsp;<b>StudyPeriod:</b>$periodsText";
	
	
	if (!empty($course)) {
		$conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
		//$qryString.= "&subjectId=$course";
	} 
	 if($course=='') {
		$course="ALL";
	 }
	// $searchCrieria .="&nbsp;<b>Course:</b>$course";
	 
	//groups
	$groupId = $REQUEST_DATA['groupId'];
	if (!empty($groupId)) {
		$conditionsArray[] = " a.studentId IN (SELECT DISTINCT studentId from student_groups WHERE groupId IN ($groupId) UNION
                                               SELECT DISTINCT studentId FROM student_optional_subject WHERE  groupId IN ($groupId) )  ";
		$qryString .= "&groupId=$groupId";
	} 
	/* if($groupId=='') {
		$groupId="ALL";
	$searchCrieria .="&nbsp;<b>Group:</b>$groupId";
	}  */
	//university
	$univs = $REQUEST_DATA['univs'];
	$univsText = $REQUEST_DATA['univsText'];
	if (!empty($univs)) {
		$conditionsArray[] = " b.universityId IN ($univs) ";
		$qryString .= "&universityId=$univs";
	}
	if($univs=='')
		$univsText= NOT_APPLICABLE_STRING;
	$searchCrieria .="&nbsp;<b>University:</b>$univsText";

	//city
	$citys = $REQUEST_DATA['citys'];
	$citysText = $REQUEST_DATA['citysText'];
	if (!empty($citys)) {
		$conditionsArray[] = " (a.corrCityId IN ($citys)  OR  a.permCityId IN ($citys))";
		$qryString .= "&cityId=$citys";
	}
	if($citys=='')
		$citysText= NOT_APPLICABLE_STRING;
	$searchCrieria .="&nbsp;<b>City:</b>$citysText";

	//states
	$states = $REQUEST_DATA['states'];
	$statesText = $REQUEST_DATA['statesText'];
	if (!empty($states)) {
		$conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
		$qryString .= "&stateId=$states";
	}
	if($states=='')
		$statesText= NOT_APPLICABLE_STRING;
	$searchCrieria .="&nbsp;<b>State:</b>$statesText";

	//country
	$cnts = $REQUEST_DATA['cnts'];
	$cntsText = $REQUEST_DATA['cntsText'];
	if (!empty($cnts)) {
		$conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts)) ";
		$qryString .= "&countryId=$cnts";
	}
	if($cnts=='')
		$cntsText= NOT_APPLICABLE_STRING;
	$searchCrieria .="&nbsp;<b>Country:</b>$cntsText";

	//management category
	$categoryId = $REQUEST_DATA['categoryId'];
	if (!empty($categoryId)) {
		$conditionsArray[] = " a.managementCategory = $categoryId ";
		$qryString .= "&categoryId=$categoryId";
		
	}
	if($categoryId=="0")
		$searchCrieria .="&nbsp;<b>Management Category:</b>No";
	if($categoryId==1)
		$searchCrieria .="&nbsp;<b>Management Category:</b>Yes";
	if($categoryId=="")
		$searchCrieria .="&nbsp;<b>Management Category:</b>". NOT_APPLICABLE_STRING;

	//From Admission Date
	$admissionDateF = $REQUEST_DATA['admissionDateF'];
	$admissionMonthF = $REQUEST_DATA['admissionMonthF'];
	$admissionYearF = $REQUEST_DATA['admissionYearF'];
	$thisDate1 ="";
	if (!empty($admissionDateF) && !empty($admissionMonthF) && !empty($admissionYearF)) {

		if (false !== checkdate($admissionMonthF, $admissionDateF, $admissionYearF)) {
			$thisDate = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
			$thisDate1 = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
			$conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
		}
		$qryString.= "&admissionDateF=$admissionDateF&admissionMonthF=$admissionMonthF&admissionYearF=$admissionYearF";
		$searchCrieria .="<b>Admission Date</b>:$thisDate";
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
		if($thisDate1)
			$toDate = " To: ";
		else
			$toDate ="";
		$searchCrieria .="<b>$toDate</b>$thisDate";
	}

	//hostel
	$hostels = $REQUEST_DATA['hostels'];
	$hostelsText = $REQUEST_DATA['hostelsText'];
	if (!empty($hostels)) {
		$conditionsArray[] = " a.hostelId IN ('$hostels') ";
		$qryString .= "&hostelId=$hostels";
	}
	if ($hostels == '') {
		$hostelsText = NOT_APPLICABLE_STRING;
	}

	$searchCrieria .="&nbsp;<b>Hostel:</b>$hostelsText";

	//bus stop
	$buss = $REQUEST_DATA['buss'];
	$bussText = $REQUEST_DATA['bussText'];
	if (!empty($buss)) {
		$conditionsArray[] = " a.busStopId IN ('$buss') ";
		$qryString .= "&busStopId=$buss";
	}
	if ($buss == '') {
		$bussText = NOT_APPLICABLE_STRING;
	}
	$searchCrieria .="&nbsp;<b>Bus Stop:</b>$bussText";

	//bus route
	$routs = $REQUEST_DATA['routs'];
	$routsText = $REQUEST_DATA['routsText'];
	if (!empty($routs)) {
		$conditionsArray[] = " a.busRouteId IN ($routs) ";
		$qryString .= "&busRouteId=$routs";
	} 
	if ($routs =='') {
		$routsText = NOT_APPLICABLE_STRING;
	}
	$searchCrieria .="&nbsp;<b>Bus Route:</b>$routsText";

	//quota
	$quotaId = $REQUEST_DATA['quotaId'];
	
	$quotaText ="ALL";
	if (!empty($quotaId)) {
		$conditionsArray[] = " a.quotaId IN ($quotaId) ";
		$qryString .= "&quotaId=$quotaId";
		$quotaText = $REQUEST_DATA['quotaText'];
	}
	if ($quotaId == '') {
		$quotaText = NOT_APPLICABLE_STRING;
	}
	$searchCrieria .="&nbsp;<b>Category:</b>$quotaText";
	
	//blood group
	$bloodGroup = $REQUEST_DATA['bloodGroup'];
	$bloodGroupText ="ALL";
	if (!empty($bloodGroup)) {
		$conditionsArray[] = " a.studentBloodGroup IN ($bloodGroup) ";
		$qryString .= "&bloodGroup=$bloodGroup";
		$bloodGroupText = $REQUEST_DATA['bloodGroupText'];
	}
	if ($bloodGroup =='') {
		$bloodGroupText = NOT_APPLICABLE_STRING;
	}
	$searchCrieria .="&nbsp;<b>Blood Group:</b>$bloodGroupText";
	
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

    // to limit records per page    
    //$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	 
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
      $userRoleArray = $studentManager->getRoleUser($userId);
      $roleCount = $userRoleArray[0]['totalRecords'];
    }


	 
    if ($roleCount > 0) {
        $recordArray = $studentManager->getRoleStudentListFast($conditions,'',$orderBy,$userId,$showAlumnniStudent);
    }
    else {
        $recordArray = $studentManager->getStudentListFast($conditions,'',$orderBy,$showAlumnniStudent);
    }

   
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportInformation("For ".$searchCrieria." As On $formattedDate ");
	$reportManager->setReportHeading("Student List Report");
	 

	$reportTableHead					=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']			=	array('#','width="2%"', "align='center' ");
	$reportTableHead['firstName']		=	array('Student Name','width=15% align="left"', 'align="left"');
	$reportTableHead['rollNo']			=	array('Roll No.','width="10%" align="left" ', 'align="left"');
	$reportTableHead['universityRollNo']=	array('Univ. No.','width="12%" align="left"','align="left"');
	$reportTableHead['regNo']		    =	array('Reg. No.','width="10%" align="left"', 'align="left"');
	$reportTableHead['className']		=	array('Class','width="22%" align="left"', 'align="left"');
	$reportTableHead['groupId']			=	array('Group','width="12%" align="left"', 'align="left"');
	$reportTableHead['studentMobileNo']	=	array('Mobile','width="6%" align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(25);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

	// $History: searchStudentPrint.php $
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 4/02/10    Time: 18:57
//Updated in $/LeapCC/Templates/Student
//resolved issues 0002650,0002620,0002098,0001602,0002788,0002785
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 09-10-24   Time: 2:02p
//Updated in $/LeapCC/Templates/Student
//fixed bug no 0001821,0001880,0001816,0001852,0001851,0001637,0001329
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Templates/Student
//worked on role to class
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 7/22/09    Time: 3:40p
//Updated in $/LeapCC/Templates/Student
//added Registration No. and Fee receipt no in student filter
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/24/09    Time: 1:36p
//Updated in $/LeapCC/Templates/Student
//0000188: Find Student (Admin-CC) > Data is not displaying in correct
//order on “student list report print” window 
//
//0000183: Find Student - Admin > Search is not working properly in IE
//browser 
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/09/09    Time: 4:15p
//Updated in $/LeapCC/Templates/Student
//Updated issues sent by Sachin sir dated 9thjune
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Templates/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/14/09    Time: 12:25p
//Updated in $/LeapCC/Templates/Student
//Updated search filter with permanent cityid, stateId and countryid
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Templates/Student
//Added group filter in student search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Templates/Student
//updated functionality as per CC
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Templates/Student
//updated as respect to subject centric
?>