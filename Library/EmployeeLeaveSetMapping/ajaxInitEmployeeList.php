<?php
//-------------------------------------------------------
// Purpose: To store the records of Employee in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
    $employeeLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();

  
  
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 99999;
    $limit      = ' LIMIT '.$records.',99999';
  
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
 
 
    // Set leave sets
    $leaveSetArray = $commonQueryManager->getLeaveSessionSetAdvData(' AND s.active=1 AND ls.isActive=1');
    
    // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      echo ACTIVE_LEAVE_SESSION;    
      die;  
    }
    
    // leave employee
    $leaveCondition =' AND s.active=1';
    $leaveOrderBy ='emp.employeeId';
    $leaveEmployeeArray = $employeeLeaveSetMappingManager->getEmployeeLeaveSetMappingList($leaveCondition,'',$leaveOrderBy);
    
    
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
    
    $employeeRoleId='';     
    if(!empty($roleName)) { 
       $roleIdsArray = $employeeManager->getEmployeeRoleId($roleName);
       for($i=0;$i<count($roleIdsArray);$i++) {
          if($employeeRoleId=='') {
            $employeeRoleId = $roleIdsArray[$i]['employeeId'];    
          } 
          else { 
            $employeeRoleId .=",".$roleIdsArray[$i]['employeeId'];  
          }
       }   
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
    
    if($employeeRoleId!='') {
      $conditions .= " AND emp.employeeId IN ($employeeRoleId) ";
    }
    

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";         

    $totalArray = $employeeManager->getTotalIcardEmployeeList($conditions);
    $employeeRecordArray = $employeeManager->getIcardEmployeeList($conditions,$limit,$orderBy);  
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $id = $employeeRecordArray[$i]['employeeId'];
       
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00') {
          $employeeRecordArray[$i]['dateOfJoining'] = NOT_APPLICABLE_STRING;
       }
       else {
          $employeeRecordArray[$i]['dateOfJoining'] = UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']); 
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeName']) == '') {
         $employeeRecordArray[$i]['employeeName']  = NOT_APPLICABLE_STRING;
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
       
       $findLeaveSetId = '-1';
       $leaveEmployeeId ='-1';
       for($j=0;$j<count($leaveEmployeeArray);$j++) {
         if($leaveEmployeeArray[$j]['employeeId']==$id) {  
            $findLeaveSetId = $leaveEmployeeArray[$j]['leaveSetId'];  
            $leaveEmployeeId = $leaveEmployeeArray[$j]['leaveEmployeeId'];  
            break;
         }
       }
       
       $inputEmployee = "<input type='hidden' class='inputBox' readonly='readonly' name='employeeIds[]' id='employeeIds".$id."' value='".$id."' >";
       $leaveSet = "<select size='1' style='width:260px;z-index:100;' class='selectfield' name='leaveSet[]' id='leaveSet".$id."'>
                       <option value=''>Select</option>";
       for($j=0; $j<count($leaveSetArray); $j++) {
          if($leaveEmployeeId!=-1) {
             if($leaveSetArray[$j]['leaveSetId']==$findLeaveSetId && $leaveSetArray[$j]['leaveSessionId']== $leaveSessionId) { 
               $leaveSet = $leaveSetArray[$j]['leaveSetName']; 
               break;
             }
             else {
               $leaveSet .= '<option SELECTED="SELECTED"  value="'.$leaveSetArray[$j]['leaveSetId'].'">'.$leaveSetArray[$j]['leaveSetName'].'</option>';   
             }  
          }
          else { 
             if($leaveSetArray[$j]['leaveSetId']==$findLeaveSetId) { 
               $leaveSet .= '<option  SELECTED="SELECTED" value="'.$leaveSetArray[$j]['leaveSetId'].'">'.$leaveSetArray[$j]['leaveSetName'].'</option>'; 
             }
             else {
               $leaveSet .= '<option value="'.$leaveSetArray[$j]['leaveSetId'].'">'.$leaveSetArray[$j]['leaveSetName'].'</option>';   
             }
          }
       }
       
       
       if($leaveId==-1) {
          $leaveSet .="</select>";        
       }
       
       $leaveSet .= $inputEmployee;
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1), 'leaveSet' => $leaveSet),
                                       $employeeRecordArray[$i]);     
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
