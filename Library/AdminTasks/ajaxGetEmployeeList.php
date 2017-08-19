<?php 
//-------------------------------------------------------
//  This File contains Download Images
//
// Author :Parveen Sharma
// Created on : 03-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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

    /////////////////////////
    // to limit records per page    
    //$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    // to limit records per page    
    if($REQUEST_DATA['page1']==$REQUEST_DATA['page']) {
       $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page1']) || UtilityManager::isEmpty($REQUEST_DATA['page1']) ) ? 1 : $REQUEST_DATA['page1'];
    }
    else {
       $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];        
    }
    $records = ($page-1)* 20;
    $limit   = ' LIMIT '.$records.',20';
    
    
    /// Search filter //  
    if($REQUEST_DATA['sortOrderBy1']==$REQUEST_DATA['sortOrderBy']) {
      $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy1'])) ? $REQUEST_DATA['sortOrderBy1'] : 'ASC'; 
    }
    else {
      $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';        
    }
    
    if($REQUEST_DATA['sortField1']==$REQUEST_DATA['sortField']) {
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField1'])) ? $REQUEST_DATA['sortField1'] : 'employeeName';
    }
    else {
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';  
    }
    
 
    foreach($REQUEST_DATA as $key => $values) {
        $$key = add_slashes($values);
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
    
    //if (!empty($departmentId)) {
    //    $conditionsArray[] = " emp.departmentId IN ($departmentId) ";
    //}

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
    
   /* if($sortField=="employeeName")
        $sortField= "IF(IFNULL(employeeName,'')='',employeeId,employeeName)"; 
        
    else if($sortField=="employeeCode")
        $sortField= "IF(IFNULL(employeeName,'')='',employeeId,employeeId,employeeCode)"; 
        
    else if($sortField=="designationName")
        $sortField= "IF(IFNULL(designationName,'')='',employeeId,employeeId,designationName)"; 
        
    else
        $sortField= "IF(IFNULL(employeeName,'')='',employeeId,employeeId,employeeName)";     
   */ 
    $orderBy=" $sortField $sortOrderBy";   

    $totalArray = $employeeManager->getTotalIcardEmployeeList($conditions);
    $employeeRecordArray = $employeeManager->getIcardEmployeeList($conditions,$limit,$orderBy);  
    $cnt = count($employeeRecordArray);

    for($i=0;$i<$cnt;$i++) {
       $imgSrc = "";
       $upload = "";
       $checkall = "";
         
       $employeeId = $employeeRecordArray[$i]['employeeId'];

       if(strip_slashes($employeeRecordArray[$i]['employeeName']) == '') {
         $employeeRecordArray[$i]['employeeName']  = NOT_APPLICABLE_STRING;
       }

       if(strip_slashes($employeeRecordArray[$i]['employeeCode']) == '') {
         $employeeRecordArray[$i]['employeeCode']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['designationName']) == '') {
         $employeeRecordArray[$i]['designationName']  = NOT_APPLICABLE_STRING;
       }
       
       $employeeNames = trim($employeeRecordArray[$i]['employeeImage']);
        
        // Employee Photo    --Start--        
        if($employeeRecordArray[$i]['employeeImage'] != ''){ 
            $File = STORAGE_PATH."/Images/Employee/".$employeeRecordArray[$i]['employeeImage'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Employee/'.$employeeRecordArray[$i]['employeeImage'].'?x='.rand(0,150)*rand(0,150);
               $checkall = '<input type="checkbox" name="chb2[]"  value="'.$employeeId.'">';
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg?y=".rand(0,150)*rand(0,150);
               $checkall = NOT_APPLICABLE_STRING;
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg?y=".rand(0,150)*rand(0,150);
          $checkall = NOT_APPLICABLE_STRING;
        }

        if($checkall==NOT_APPLICABLE_STRING) {
           $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='employeeImageId' class='imgLinkRemove11' />";
        }
        else {
           $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='employeeImageId' class='imgLinkRemove11' />&nbsp;
                      <a onclick='deleteEmployeeImage(".$employeeId."); return false;'>
           <img src='".IMG_HTTP_PATH."/delete.gif?rand=".rand(0,1000)."' style='margin-bottom-4px' alt='Delete Photo' title='Delete Photo'></a>"; 
        }
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        // Student Photo    --END--
        
        $upload = '<input type="file" class="inputbox" name="empFileId[]" id="empFileId" style="width:200px">
                   <input readonly type="hidden" name="eEmployeeId[]" id="eEmployeeId" value="'.$employeeId.'">
                   <input readonly type="hidden"  name="empEmployeeNames[]" id="empEmployeeNames" value="'.$employeeNames.'">';        
        
        $valueArray = array_merge(array(
                              'checkAll' =>  $checkall, 
                              'upload' =>  $upload, 
                              'imgSrc' =>  $imgSrc, 
                              'srNo' => ($records+$i+1)), $employeeRecordArray[$i] );     
                              
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
     
?>