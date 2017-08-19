<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeManager = EmployeeReportsManager::getInstance();
	
     foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
             
    if (!empty($employeeCode)) {
        $conditionsArray[] = " e.employeeCode like '$employeeCode%' ";
    }
    
    if (!empty($employeeName)) {
        $conditionsArray[] = " e.employeeName like '%$employeeName%' ";
    }

    if (!empty($branchId)) {
        $conditionsArray[] = " e.branchId in ($branchId) ";
    }

    if (!empty($instituteId)) {
        $conditionsArray[] = " e.instituteId IN ($instituteId) ";
    }
    
    if (!empty($designationId)) {
        $conditionsArray[] = " e.designationId IN ($designationId) ";
    }
    
    // Date of Brith    
    if ($birthDateF != '--' && $birthDateF != '') {
        $conditionsArray[] =  " e.dateOfBirth >= '$birthDateF' ";
    }
    
    if ($birthDateT != '--' && $birthDateT != '') {
       $conditionsArray[] =  " e.dateOfBirth <= '$birthDateT' ";
    }
    
    // Joining Date    
    if ($joiningDateF != '--' && $joiningDateF != '') {
        $conditionsArray[] =  " e.dateOfJoining >= '&joiningDateF' ";
    }

    if ($joiningDateT != '--' && $joiningDateT != '') {
        $conditionsArray[] =  " e.dateOfJoining <= '&joiningDateT' ";
    }
    
    // Leaving Date
    if ($leavingDateF != '--' && $leavingDateF !='') {
        $conditionsArray[] =  " e.dateOfLeaving >= '$leavingDateF' ";
    }

    if ($leavingDateT != '--' && $leavingDateT !='') {
       $conditionsArray[] =  " e.dateOfLeaving <= '$leavingDateT' ";
    }    
        
        
    if (!empty($genderRadio)) {
        $conditionsArray[] = " e.gender = '$genderRadio' ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " e.cityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " e.stateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " e.countryId IN ($countryId) ";
    }
    
    if (!empty($instituteId)) {
        $conditionsArray[] = " e.instituteId IN ($instituteId) ";
    }
      
    if ($isMarried!='') {
        $conditionsArray[] = " e.isMarried IN ($isMarried) ";
    } 
    
    if ($teachEmployee!='') {
        $conditionsArray[] = " e.isTeaching IN ($teachEmployee) ";
    } 
    
    if ($qualification!='') {
        $conditionsArray[] = " e.qualification like '$qualification%' ";
    } 
             
    $conditions = '';        
    
    if (count($conditionsArray) > 0) {
        $conditions .= ' AND '.implode(' AND ',$conditionsArray);
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;



	$totalRecordsArray = $employeeManager->countRecords($conditions);
	$totalRecords = $totalRecordsArray[0]['cnt'];
	$employeeRecordArray = $employeeManager->getAllDetails($conditions, $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'], $limit);

    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$valueArray = array_merge(array('act' =>  $showlink, 'srNo' => ($records+$i+1) ),$employeeRecordArray[$i]);
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);           
		}
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 

//$History: initAllDetailsReport.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 5:21p
//Updated in $/LeapCC/Library/EmployeeReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 4:07p
//Created in $/LeapCC/Library/EmployeeReports
//inital checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/04/08   Time: 11:42a
//Updated in $/Leap/Source/Library/ScEmployeeReports
//filter selection check condition update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/31/08   Time: 12:13p
//Created in $/Leap/Source/Library/ScEmployeeReports
//employee list add
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/26/08    Time: 1:08p
//Updated in $/Leap/Source/Library/ScStudentReports
//fixed minor issue related to DOB


?>