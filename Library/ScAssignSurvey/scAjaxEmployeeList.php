<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','AssignSurveyMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ScAssignSurveyManager.inc.php");
    $assignSurveyManager = ScAssignSurveyManager::getInstance();

    /////////////////////////
    
    
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    //////
    
    /////search functionility not needed   
    //$filter=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".trim(add_slashes($REQUEST_DATA['studentRollNo']))."' " :" AND  s.classId='".$REQUEST_DATA['class']."' ");
    
    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    //print_r($REQUEST_DATA);
    $conditionsArray = array();
    
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
    
    $totalArray          = $assignSurveyManager->getTotalEmployee($conditions);
    $employeeRecordArray = $assignSurveyManager->getEmployeeList($conditions,$limit,$orderBy);
    $cnt = count($employeeRecordArray);


   $selectedEmp=explode(",",$REQUEST_DATA['selectedEmp']);
   $len=count($selectedEmp);
   
   $onclick='onclick="checkUncheckEmployee(this.value,this.checked);"';   //adding onclick handler
   
    for($i=0;$i<$cnt;$i++) {
      if($len>1 and is_array($selectedEmp)){  //check for initial values
        if(in_array($employeeRecordArray[$i]['employeeId'],$selectedEmp)){
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" checked=\"checked\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\" $onclick>")
        , $employeeRecordArray[$i]);
        }
        else{
          $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\" $onclick>")
        , $employeeRecordArray[$i]);
        }
      }
     else{
       if($employeeRecordArray[$i]['empAssigned']=='Yes'){ 
        $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" checked=\"checked\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\" $onclick>")
         , $employeeRecordArray[$i]);
       }
       else{
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"employees\" id=\"employees\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\" $onclick>")
         , $employeeRecordArray[$i]);
       }
     } 

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.'],"employeeInfo" : '.json_encode($totalArray).'}';   
    
// for VSS
// $History: scAjaxEmployeeList.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 14:41
//Created in $/LeapCC/Library/ScAssignSurvey
//Copied "Assign Survey" module from Leap to LeapCC
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/01/09    Time: 13:26
//Updated in $/Leap/Source/Library/ScAssignSurvey
//Crrected pagination related problem
//[maintained checkbox state in pagination]
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/01/09    Time: 18:12
//Updated in $/Leap/Source/Library/ScAssignSurvey
//Corrected Access Codes
?>
