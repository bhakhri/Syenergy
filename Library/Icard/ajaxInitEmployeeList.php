<?php
//-------------------------------------------------------
// Purpose: To store the records of Employee in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','EmployeeIcardReport');
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
 
    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
             
    if (!empty($employeeCode)) {
        $conditionsArray[] = " emp.employeeCode LIKE '$employeeCode%' ";
    }
    
    if (!empty($employeeName)) {
        $conditionsArray[] = " emp.employeeName LIKE '%$employeeName%' ";
    }

    if (!empty($branchId)) {
        $conditionsArray[] = " emp.branchId IN ($branchId) ";
    }
    
    if (!empty($departmentId)) {
        $conditionsArray[] = " emp.departmentId IN ($departmentId) ";
    }

    if (!empty($instituteId)) {
        $conditionsArray[] = " emp.instituteId IN ($instituteId) ";
    }
    
    if (!empty($designationId)) {
        $conditionsArray[] = " emp.designationId IN ($designationId) ";
    }
    
    
    // Date of Birth (From - To)
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];
    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {
        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] =  " emp.dateOfBirth >= '$thisDate' ";
        }
    }
    
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];
    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {
        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] =  " (emp.dateOfBirth > '0000-00-00' AND emp.dateOfBirth <= '$thisDate') ";
        }
    }

    
    // Joining Date (From - To)
    $joiningDateF = $REQUEST_DATA['joiningDateF'];
    $joiningMonthF = $REQUEST_DATA['joiningMonthF'];
    $joiningYearF = $REQUEST_DATA['joiningYearF'];
    if (!empty($joiningDateF) && !empty($joiningMonthF) && !empty($joiningYearF)) {
        if (false !== checkdate($joiningMonthF, $joiningDateF, $joiningYearF)) {
            $thisDate = $joiningYearF.'-'.$joiningMonthF.'-'.$joiningDateF;
            $conditionsArray[] =  " emp.dateOfJoining >=  '$thisDate' ";
        }
    }
    
    $joiningDateT = $REQUEST_DATA['joiningDateT'];
    $joiningMonthT = $REQUEST_DATA['joiningMonthT'];
    $joiningYearT = $REQUEST_DATA['joiningYearT'];
    if (!empty($joiningDateT) && !empty($joiningMonthT) && !empty($joiningYearT)) {
        if (false !== checkdate($joiningMonthT, $joiningDateT, $joiningYearT)) {
            $thisDate = $joiningYearT.'-'.$joiningMonthT.'-'.$joiningDateT;
            $conditionsArray[] =  " (emp.dateOfJoining > '0000-00-00' AND emp.dateOfJoining <=  '$thisDate') ";
        }
    }
 
    
    // Leaving Date  (From - To)
    $leavingDateF = $REQUEST_DATA['leavingDateF'];
    $leavingMonthF = $REQUEST_DATA['leavingMonthF'];
    $leavingYearF = $REQUEST_DATA['leavingYearF'];
    if (!empty($leavingDateF) && !empty($leavingMonthF) && !empty($leavingYearF)) {
        if (false !== checkdate($leavingMonthF, $leavingDateF, $leavingYearF)) {
            $thisDate = $leavingYearF.'-'.$leavingMonthF.'-'.$leavingDateF;
            $conditionsArray[] =  " emp.dateOfLeaving >=  '$thisDate' ";   
        }
    }
    
    $leavingDateT = $REQUEST_DATA['leavingDateT'];
    $leavingMonthT = $REQUEST_DATA['leavingMonthT'];
    $leavingYearT = $REQUEST_DATA['leavingYearT'];
    if (!empty($leavingDateT) && !empty($leavingMonthT) && !empty($leavingYearT)) {
        if (false !== checkdate($leavingMonthT, $leavingDateT, $leavingYearT)) {
            $thisDate = $leavingYearT.'-'.$leavingMonthT.'-'.$leavingDateT;
            $conditionsArray[] =  " (emp.dateOfLeaving > '0000-00-00' AND emp.dateOfLeaving <=  '$thisDate') ";
        }
    }
        
    if (!empty($genderRadio)) {
        $conditionsArray[] = " emp.gender = '$genderRadio' ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " emp.cityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " emp.stateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " emp.countryId IN ($countryId) ";
    }
    
    if ($isMarried!='') {
        $conditionsArray[] = " emp.isMarried IN ($isMarried) ";
    } 
    
    if ($teachEmployee!='') {
        $conditionsArray[] = " emp.isTeaching IN ($teachEmployee) ";
    } 
    
    if ($qualification!='') {
        $conditionsArray[] = " emp.qualification LIKE '$qualification%' ";
    } 
             
    $conditions = '';        
    
    if (count($conditionsArray) > 0) {
        $conditions .= ' WHERE '.implode(' AND ',$conditionsArray);
    }

    if($conditions != "") {
       $conditions .= ' AND isActive = 1 ';
    }
    else {
       $conditions .= ' WHERE isActive = 1 ';
    }
    

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";         

    $totalArray = $employeeManager->getTotalIcardEmployeeList($conditions);
    $employeeRecordArray = $employeeManager->getIcardEmployeeList($conditions,$limit,$orderBy);  
    $cnt = count($employeeRecordArray);

    for($i=0;$i<$cnt;$i++) {
       $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($employeeRecordArray[$i]['employeeId']).'">';
       
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00') {
          $doj = NOT_APPLICABLE_STRING;
       }
       else {
          $doj = UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']); 
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeCode']) == '') {
         $employeeRecordArray[$i]['employeeCode']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['departmentAbbr']) == '') {
         $employeeRecordArray[$i]['departmentAbbr']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['designationName']) == '') {
         $employeeRecordArray[$i]['designationName']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['contactNumber']) == '') {
         $employeeRecordArray[$i]['contactNumber']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['permAddress']) == '') {
         $employeeRecordArray[$i]['permAddress']  = NOT_APPLICABLE_STRING;
       }
       
       if($employeeRecordArray[$i]['issueDate'] == '0000-00-00') {
          $issueDate  = NOT_APPLICABLE_STRING;
       }
       else {                                
          $issueDate = UtilityManager::formatDate($employeeRecordArray[$i]['issueDate']);
       }
       
       $valueArray = array_merge(array(
                              'checkAll' => $checkall,
                              'srNo' => ($records+$i+1), 
                              'employeeName' => strip_slashes($employeeRecordArray[$i]['employeeName']),
                              'employeeCode' => strip_slashes($employeeRecordArray[$i]['employeeCode']),
                              'departmentAbbr' => strip_slashes($employeeRecordArray[$i]['departmentAbbr']),
                              'dateOfJoining' => $doj,
                              'designationName' => strip_slashes($employeeRecordArray[$i]['designationName']), 
                              'contactNumber' => strip_slashes($employeeRecordArray[$i]['contactNumber']),
                              'permAddress' => strip_slashes($employeeRecordArray[$i]['permAddress']),
                              'issueDate' =>$issueDate,
                              'employeePhoto' => strip_slashes($employeeRecordArray[$i]['employeePhoto'])));     
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitEmployeeList.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/14/09   Time: 6:22p
//Updated in $/LeapCC/Library/Icard
//validation format updated (null value checks updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/14/09   Time: 3:39p
//Updated in $/LeapCC/Library/Icard
//date checks updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/14/09   Time: 2:41p
//Updated in $/LeapCC/Library/Icard
//isActive checks updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:08p
//Created in $/LeapCC/Library/Icard
//initial checkin
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/Employee
//role permission,alignment, new enhancements added 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/07/09    Time: 9:48a
//Updated in $/LeapCC/Library/Employee
//alignment, formatting, conditions updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/30/09    Time: 5:36p
//Updated in $/LeapCC/Library/Employee
//validation, conditions, personal information formatting updated 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Employee
//formatting, conditions, validations updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Library/Employee
//validation, formatting, themes base css templates changes
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Library/Employee
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 11:58a
//Updated in $/LeapCC/Library/Employee
//fixed bug send by sachin sir
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Employee
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/21/08   Time: 7:22p
//Updated in $/Leap/Source/Library/Employee
//modified for active or deactive
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:40p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/25/08    Time: 6:08p
//Updated in $/Leap/Source/Library/Employee
//fixed bug
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:00p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Library/Employee
//modification in employee in templates & functions
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:01p
//Created in $/Leap/Source/Library/Employee
//add ajax files for list of employee & delete employee
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>