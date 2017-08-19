<?php
//-------------------------------------------------------
// Purpose: To add in lecture
// Author : Jaineesh
// Created on : (30.03.2009 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
	define('MODULE','EmployeeMaster');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
    $employeeManager  = EmployeeManager::getInstance();

    
	$errorMessage ='';
	global $sessionHandler;
	
	//print_r($REQUEST_DATA);
	//die;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));    
	$fromDateString = $REQUEST_DATA['fromDateString'];
	$toDateString = $REQUEST_DATA['toDateString'];
	$serverDate=date('Y-m-d');

	function dateDiff($dformat, $endDate, $beginDate) {
		
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
		$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
		return $end_date - $start_date;
	}
	
	function checkDuplicateDate($value){
		$fl=1;
		global $dtArr;
		$len=count($dtArr);
		for($i=0;$i<$len;$i++){
			if($dtArr[$i]==$value){
				$fl=0;
				break;
			}
		}
		
		if($fl==1){
			$dtArr[]=$value;
		}
		
		return $fl;
	}

	if (trim($errorMessage) == '') {

		$totalOrganisation = count($REQUEST_DATA['organisation']);
		$totalDesignation = count($REQUEST_DATA['designation']);
		$employeeId = $REQUEST_DATA['employeeId'];
		$fromEmployeeDate = explode(',',$fromDateString);
		//print_r($fromEmployeeDate);
		$toEmployeeDate = explode(',',$toDateString);
		$fromDateLen = count($fromEmployeeDate);
		$toDateLen = count($toEmployeeDate);
		//echo($fromDateLen);
		if($fromDateLen >0 && $fromEmployeeDate[0] != '') {		
			for($j=0; $j<$fromDateLen; $j++) {
				if(!checkDuplicateDate($fromEmployeeDate[$j])){
					echo DUPLICATE_FROM_DATE_RESTRICTION;
					die;
				}
				if(dateDiff('-',$serverDate,$fromEmployeeDate[$j]) < 0){
					echo DATE_RESTRICTION;
					die;
				}
			}
		}
		if($toDateLen > 0 && $toEmployeeDate[0] != '') {
			for($m=0; $m<$toDateLen; $m++) {
				if(!checkDuplicateDate($toEmployeeDate[$m])){
					echo DUPLICATE_TO_DATE_RESTRICTION;
					die;
				}
				if(dateDiff('-',$serverDate,$toEmployeeDate[$m]) < 0){
					echo DATE_RESTRICTION;
					die;
				}
			}
		}
		
	
		// Check Validations
        for($i = 0; $i < $totalOrganisation; $i++) {
			if(trim($REQUEST_DATA['organisation'][$i]) == '' && trim($REQUEST_DATA['designation'][$i]) == '') {
				echo "Please fill the value of empty box"; 
				die;   
			}
				
          }

		
					
        // Delete all Records
        $returnDeleteStatus = $employeeManager->deleteEmployeeExperience($employeeId);
        if($returnDeleteStatus === false) {
           $errorMessage = FAILURE;
        }
        $str = '';
		if($totalOrganisation > 0 ) {
        for($i = 0; $i < $totalOrganisation; $i++) {
			
			$organisation = addslashes($REQUEST_DATA['organisation'][$i]);
            $designation = addslashes($REQUEST_DATA['designation'][$i]);
			$fromDate = $fromEmployeeDate[$i];
			$toDate = $toEmployeeDate[$i];
			$experience = addslashes($REQUEST_DATA['experience'][$i]);
			$expCertificate = addslashes($REQUEST_DATA['expCertificate'][$i]);
			

			//if($lectureAttendedFrom <= $lectureAttendedTo) {
				if(!empty($str)) {
					$str .= ',';
				}
				$str .= "($employeeId,'$fromDate','$toDate','$organisation','$designation',$experience,$expCertificate)";
				//$str .= "($lectureDelivered, $lectureAttendedFrom, $marksScored, $subjectTypeId, $timeTableLabelId, $instituteId, $degreeId)";
			//}
        }
	
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$returnStatus = $employeeManager->addExperience($str);
			if($returnStatus === false) {
				$errorMessage = FAILURE;
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
	  }
	  else {
		echo SUCCESS;
		die;
	  }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitExperienceAdd.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:32p
//Created in $/LeapCC/Library/Employee
//new files for employee experience, education & gap analysis
//
?>