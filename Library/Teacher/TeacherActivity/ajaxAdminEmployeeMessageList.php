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
	define('MODULE','EmployeeMessageMaster');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();
    
    $empId=$sessionHandler->getSessionVariable('EmployeeId');

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE;

    //////
    
    /////search functionility not needed  
    
    //*************************************Building The QUERY*********************************
    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
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
    
    if (!empty($departmentId)) {
        $conditionsArray[] = " e.departmentId in ($departmentId) ";
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
    $conditions .=' AND e.employeeId!='.$empId;
    
    $totalArray          = $sendMessageManager->getTotalEmployee($conditions);
    $employeeRecordArray = $sendMessageManager->getEmployeeList($conditions,$limit,$orderBy);
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
       if(trim($employeeRecordArray[$i]['dateOfJoining'])=='' or trim($employeeRecordArray[$i]['dateOfJoining'])=='0000-00-00'){
           $employeeRecordArray[$i]['dateOfJoining']=NOT_APPLICABLE_STRING;
       }
       else{
           $employeeRecordArray[$i]['dateOfJoining']=UtilityManager::formatDate(trim($employeeRecordArray[$i]['dateOfJoining']));
       }
       
       if(trim($employeeRecordArray[$i]['qualification'])==''){
           $employeeRecordArray[$i]['qualification']=NOT_APPLICABLE_STRING;
       }
        
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps\" id=\"emps\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\">")
        , $employeeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAdminEmployeeMessageList.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 4  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 18:15
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
// Corrected "Query Parameter"
//
//*****************  Version 2  *****************
//User: Administrator Date: 14/05/09   Time: 17:15
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified "Send Message to Employees" module and incorporated "Advanced"
//employee filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:49
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added the functioanality "Send Message to Colleagues" In Teacher
//Section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:14
//Created in $/SnS/Library/Teacher/TeacherActivity
//Added the functionaility of send message from teacher end
?>
