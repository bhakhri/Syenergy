<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeHierarchy');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Appraisal/HierarchyManager.inc.php");
    $hierarchyManager = HierarchyManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.',5000';
    
    $superiorEmpId=trim($REQUEST_DATA['supEmployeeId']);
    if($superiorEmpId==''){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
      die;  
    }
    
     //////
    
    /////search functionility not needed  
    
    //*************************************Building The QUERY*********************************
    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
    }
	//die('line'.__LINE__);
    $conditionsArray = array();
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');

    if (!empty($employeeName)) {
        //$conditionsArray[] = " CONCAT(s.firstName, ' ', s.lastName) like '%$studentName%' ";
        $conditionsArray[] = " (
                                  TRIM(e.employeeName) LIKE '".add_slashes(trim($employeeName))."%'
                               )";
        
    }
    
    if (!empty($employeeCode)) {
        //$conditionsArray[] = " CONCAT(s.firstName, ' ', s.lastName) like '%$studentName%' ";
        $conditionsArray[] = " (
                                  TRIM(e.employeeCode) LIKE '".add_slashes(trim($employeeCode))."%'
                               )";
        
    }
    
    if (!empty($designationId)) {
        $conditionsArray[] = " e.designationId in ($designationId) ";
    }
    if (!empty($genderRadio)) {
        $conditionsArray[] = " e.gender in ('".$genderRadio."') ";
    }
    if (!empty($qualification)) {
        $conditionsArray[] = " e.qualification LIKE '".$qualification."%'";
    }
    if (!empty($isMarried)) {
        $conditionsArray[] = " e.isMarried = $isMarried";
    }
    
    if (!empty($teachEmployee)) {
        $conditionsArray[] = " e.isTeaching = $teachEmployee";
    }
    
    if (!empty($cityId)) {
        $conditionsArray[] = " e.cityId in ($cityId) ";
    }
    
    if (!empty($stateId)) {
        $conditionsArray[] = " e.stateId in ($stateId) ";
    }
    
    if (!empty($countryId)) {
        $conditionsArray[] = " e.countryId in ($countryId) ";
    }
    
    if (!empty($instituteId)) {
        $conditionsArray[] = " e.instituteId in ($instituteId) ";
    }
    
    if (!empty($departmentId)) {
        $conditionsArray[] = " e.departmentId in ($departmentId) ";
    }

	if (!empty($roleName)) {
		$conditionsArray[] = " u.roleId in ($roleName) " ;
	}

   /*DOB--Start*/
    if (!empty($birthDateF) and $birthDateF != '--') {
        $fromDateArr = explode('-',$birthDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfBirth >= '$thisDate' ";
        }
    }
    
    if (!empty($birthDateT) and $birthDateT != '--') {
        $fromDateArr = explode('-',$birthDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfBirth <= '$thisDate' ";
        }
    }
   /*DOB--End*/ 
   
   /*DOJ--Start*/

    if (!empty($joiningDateF) and $joiningDateF != '--') {
        $fromDateArr = explode('-',$joiningDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfJoining >= '$thisDate' ";
        }
    }
    
    if (!empty($joiningDateT) and $joiningDateT != '--') {
        $fromDateArr = explode('-',$joiningDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfJoining <= '$thisDate' ";
        }
    }
  /*DOB--End*/  
  
  /*DOL--Start*/
    
    if (!empty($leavingDateF) and $leavingDateF != '--') {
        $fromDateArr = explode('-',$leavingDateF);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfLeaving >= '$thisDate' ";
        }
    }
    
    if (!empty($leavingDateT) and $leavingDateT != '--') {
        $fromDateArr = explode('-',$leavingDateT);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " e.dateOfLeaving <= '$thisDate' ";
        }
    }
  /*DOL--End*/
    

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    //$totalArray = $hierarchyManager->getTotalEmployee($conditions);
	$totalArray = $hierarchyManager->getEmployeeList($superiorEmpId,$conditions,'',$orderBy);
	$count = count($totalArray);
    $employeeRecordArray = $hierarchyManager->getEmployeeList($superiorEmpId,$conditions,$limit,$orderBy);
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $checked=''; 
       if($employeeRecordArray[$i]['empUsed']==1){
           $checked='checked="checked"';
       }
       if($employeeRecordArray[$i]['superiorEmployee']==''){
           $employeeRecordArray[$i]['superiorEmployee']=NOT_APPLICABLE_STRING;
       }
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps\" id=\"emps\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\" $checked >"), $employeeRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAdminEmployeeMessageList.php $
?>