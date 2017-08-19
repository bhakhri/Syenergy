<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','SendMessageToEmployees');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();

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
		$conditionsArray[] = " u.roleId in ($roleName) 
		UNION 
		SELECT	e.employeeId, 
				e.employeeName,
				e.employeeCode,
				e.employeeAbbreviation,
                IF(e.isTeaching=1,'YES','NO') AS isTeaching,e.qualification,
                e.dateOfJoining,
                d.designationName,br.branchCode,r.roleName, e.userId, e.emailAddress, e.mobileNumber 
		FROM	employee e,designation d,`user` u,`role` r,branch br,user_role ur
		WHERE	e.designationId=d.designationId
        AND		e.isActive=1
        AND		e.branchId=br.branchId
        AND		e.instituteId=".$instituteId."
        AND		e.userId=u.userId 
		AND		u.roleId=r.roleId
		AND		ur.userId = e.userId
		AND		ur.roleId in ($roleName)";
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
    
    //$totalArray = $sendMessageManager->getTotalEmployee($conditions);
	$totalArray = $sendMessageManager->getEmployeeList($conditions,'',$orderBy);
	$count = count($totalArray);
    $employeeRecordArray = $sendMessageManager->getEmployeeList($conditions,$limit,$orderBy);
    $cnt = count($employeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00' or $employeeRecordArray[$i]['dateOfJoining']==''){
           $employeeRecordArray[$i]['dateOfJoining']=NOT_APPLICABLE_STRING;
       }
       else{
           $employeeRecordArray[$i]['dateOfJoining']=UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']);
       } 
        
       $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps\" id=\"emps\" value=\"".$employeeRecordArray[$i]['employeeId'] ."\">")
        , $employeeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxAdminEmployeeMessageList.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 23/01/10   Time: 11:09
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//Bug ids---0002690 to 0002698
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminMessage
//Added Role Permission Variables
//
//*****************  Version 5  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Library/AdminMessage
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
//*****************  Version 4  *****************
//User: Administrator Date: 14/05/09   Time: 18:15
//Updated in $/LeapCC/Library/AdminMessage
// Corrected "Query Parameter"
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 17:15
//Updated in $/LeapCC/Library/AdminMessage
//Modified "Send Message to Employees" module and incorporated "Advanced"
//employee filter
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/15/08   Time: 5:40p
//Updated in $/LeapCC/Library/AdminMessage
//added define('MANAGEMENT_ACCESS',1) Parameter
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/08/08    Time: 4:05p
//Updated in $/Leap/Source/Library/AdminMessage
//Updated according to Kabir Sir's suggestion
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/05/08    Time: 12:11p
//Updated in $/Leap/Source/Library/AdminMessage
//Added employee search filter
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:30p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:04p
//Created in $/Leap/Source/Library/AdminMessage
?>
