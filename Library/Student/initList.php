<?php
//-------------------------------------------------------
// Purpose: To store the records of subject to class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    define('MODULE','COMMON');
	define('ACCESS','view');

    $studentManager = StudentManager::getInstance();
	if($REQUEST_DATA['listStudent'])
	{
		$showTitle = "";
		$showData  = "";
		$showPrint = "";
	}

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
    
    if(UtilityManager::notEmpty($REQUEST_DATA['listStudent'])) 
	{
 
		// to limit records per page    
		/*
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		//////
		
		/// Search filter /////  
		if(UtilityManager::notEmpty($REQUEST_DATA['classId'])) {
			$filter .= ' AND (stu.classId = '.add_slashes($REQUEST_DATA['classId']).')';         
		}

		if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
		   $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
		}

		if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
		   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
		}
		////////////
		$orderBy	= $REQUEST_DATA['orderBy'];

		$totalArray   = $studentManager->getTotalStudent($filter);
		$studentRecordArray = $studentManager->getStudentList($filter,$limit,$orderBy,$classId);
		*/

		foreach($REQUEST_DATA as $key => $values) {
		$$key = $values;
	}

	$conditionsArray = array();
	
	$qryString = "";

		//Roll Number
	$rollNo = $REQUEST_DATA['rollNo'];
	if (!empty($rollNo)) {
		$conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
		$qryString.= "&rollNo = $rollNo";
	}

	//Student Name
	$studentName = $REQUEST_DATA['studentName'];
	if (!empty($studentName)) {
		//$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
		 $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
		$qryString.= "&studentName = $studentName";
	}

	//Student Gender
	$gender = $REQUEST_DATA['gender'];
	if (!empty($gender)) {
		$conditionsArray[] = " a.studentGender = '$gender' ";
		$qryString .= "&gender = $gender";
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
		$qryString.= "&dateOfBirth = $thisDate";
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
		$qryString.= "&dateOfBirth = $thisDate";
	}

	//degree
	$degs = $REQUEST_DATA['degreeId'];
	if (!empty($degs)) {
		$conditionsArray[] = " b.degreeId in ($degs) ";
		$qryString.= "&degreeId = $degs";
	}

	//branch
	$brans = $REQUEST_DATA['branchId'];
	if (!empty($brans)) {
		$conditionsArray[] = " b.branchId in ($brans) ";
		$qryString.= "&branchId = $brans";
	}

	//periodicity
	$periods = $REQUEST_DATA['periodicityId'];
	if (!empty($periods)) {
		$conditionsArray[] = " b.studyPeriodId IN ($periods) ";
		$qryString.= "&periodicityId = $periods";
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
		$conditionsArray[] = " a.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
		$qryString .= "&groupId=$groupId";
	}

	//university
	$univs = $REQUEST_DATA['universityId'];
	if (!empty($univs)) {
		$conditionsArray[] = " b.universityId IN ($univs) ";
		$qryString .= "&universityId = $univs";
	}

	//city
	$citys = $REQUEST_DATA['cityId'];
	if (!empty($citys)) {
		$conditionsArray[] = " a.corrCityId IN ($citys) ";
		$qryString .= "&cityId = $citys";
	}

	//states
	$states = $REQUEST_DATA['stateId'];
	if (!empty($states)) {
		$conditionsArray[] = " a.corrStateId IN ($states) ";
		$qryString .= "&stateId = $states";
	}

	//country
	$cnts = $REQUEST_DATA['countryId'];
	if (!empty($cnts)) {
		$conditionsArray[] = " a.corrCountryId IN ($cnts) ";
		$qryString .= "&countryId = $cnts";
	}

	//management category
	$categoryId = $REQUEST_DATA['categoryId'];
	if (!empty($categoryId)) {
		$conditionsArray[] = " a.managementCategory = $categoryId ";
		$qryString .= "&categoryId = $categoryId";
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
		$qryString.= "&admissionFrom = $thisDate";
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
		$qryString.= "&admissionFrom = $thisDate";
	}

	//hostel
	$hostels = $REQUEST_DATA['hostelId'];
	if (!empty($hostels)) {
		$conditionsArray[] = " a.hostelId IN ($hostels) ";
		$qryString .= "&hostelId = $hostels";
	}

	//bus stop
	$buss = $REQUEST_DATA['busStopId'];
	if (!empty($buss)) {
		$conditionsArray[] = " a.busStopId IN ($buss) ";
		$qryString .= "&busStopId = $buss";
	}

	//bus route
	$routs = $REQUEST_DATA['busRouteId'];
	if (!empty($routs)) {
		$conditionsArray[] = " a.busRouteId IN ($routs) ";
		$qryString .= "&busRouteId = $routs";
	} 
	
	//quota
	$quotaId = $REQUEST_DATA['quotaId'];
	if (!empty($quotaId)) {
		$conditionsArray[] = " a.quotaId IN ($quotaId) ";
		$qryString .= "&quotaId = $quotaId";
	}
	
	$bloodGroup = $REQUEST_DATA['bloodGroup'];
	if (!empty($bloodGroup)) {
		$conditionsArray[] = " a.studentBloodGroup = $bloodGroup";
		$qryString .= "&bloodGroup=$bloodGroup";
	}

	$conditions = '';
	if (count($conditionsArray) > 0) {
		$conditions = ' AND '.implode(' AND ',$conditionsArray);
	}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	/*$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';

	$orderBy="a.$sortField $sortOrderBy"; 

	if($sortField=="studyPeriod")
		$orderBy="b.studyPeriodId $sortOrderBy"; */

	$orderBy	= $REQUEST_DATA['orderBy'];
	/* END: search filter */

	$totalArray = $studentManager->getTotalStudent($conditions);
    $studentRecordArray = $studentManager->getStudentList($conditions,$limit,$orderBy);


    }
// VSS comments
// $History: initList.php $	
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Library/Student
//Added group filter in student search
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Library/Student
//updated functionality as per CC
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Library/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Library/Student
//updated back button with class
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Library/Student
//updated as respect to subject centric
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:03p
//Updated in $/Leap/Source/Library/Student
//updated with print button functioanlity with $showPrint variable
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Library/Student
//updated formatting and print reports
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Updated in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/08    Time: 10:27a
//Updated in $/Leap/Source/Library/Student
//added VSS comment variable
?>