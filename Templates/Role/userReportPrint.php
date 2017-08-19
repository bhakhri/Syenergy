<?php 
//This file is used as printing version for role.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();
    
    $roleId=trim($REQUEST_DATA['roleId']);
    if($roleId==''){
      echo 'Role Information Missing';   
      die;
    }
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    $orderBy = " $sortField $sortOrderBy";
    
    $filter =' AND r.roleId='.$roleId;
    
    ////////////
    if($roleId !=3 and $roleId!=4){ //for admin/employee
      $roleRecordArray = $roleManager->getEmployeeRoleList($filter,' ',$orderBy);
    }
    else if($roleId==3){//for parents
      $roleRecordArray = $roleManager->getParentRoleList($filter,' ',$orderBy);
    }
    else if($roleId==4){//for students
      $roleRecordArray = $roleManager->getStudentRoleList($filter,' ',$orderBy);
    }
    else{
        echo 'Invalid Role';
        die;
    }
    
    $cnt = count($roleRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       //employee 
       if($roleRecordArray[$i]['employeeName']==''){
           $roleRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
       }
       //designation
       if($roleRecordArray[$i]['designationName']==''){
           $roleRecordArray[$i]['designationName']=NOT_APPLICABLE_STRING;
       }
       //displayName
       if($roleRecordArray[$i]['displayName']==''){
           $roleRecordArray[$i]['displayName']=NOT_APPLICABLE_STRING;
       }
       //parentName
       if($roleRecordArray[$i]['parentName']==''){
           $roleRecordArray[$i]['parentName']=NOT_APPLICABLE_STRING;
       }
       //isTeaching
       if($roleRecordArray[$i]['isTeaching']==''){
           $roleRecordArray[$i]['isTeaching']=NOT_APPLICABLE_STRING;
       }
       else{
           if($roleRecordArray[$i]['isTeaching']==1){
               $roleRecordArray[$i]['isTeaching']='Yes';
           }
           else{
               $roleRecordArray[$i]['isTeaching']='No';
           }
       }
       //dob
       if($roleRecordArray[$i]['dateOfBirth']=='' OR $roleRecordArray[$i]['dateOfBirth']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfBirth']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfBirth']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfBirth']);
       }
       //doj
       if($roleRecordArray[$i]['dateOfJoining']=='' OR $roleRecordArray[$i]['dateOfJoining']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfJoining']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfJoining']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfJoining']);
       }
       //doa
       if($roleRecordArray[$i]['dateOfAdmission']=='' OR $roleRecordArray[$i]['dateOfAdmission']=='0000-00-00'){
           $roleRecordArray[$i]['dateOfAdmission']=NOT_APPLICABLE_STRING;
       }
       else{
           $roleRecordArray[$i]['dateOfAdmission']=UtilityManager::formatDate($roleRecordArray[$i]['dateOfAdmission']);
       }
    }

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$roleRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('User  Report');
    $reportManager->setReportInformation("SearchBy: Role : ".trim($REQUEST_DATA['roleName']));
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="1%" align="left"', "align='left' ");
    $reportTableHead['userName']            =   array('UserName','width="8%" align="left"', "align='left' ");
    $reportTableHead['displayName']         =   array('Display Name','width="10%" align="left"', "align='left' ");
    
    if($roleId!=3 and $roleId!=4){
        $reportTableHead['employeeName']    =   array('Name','width="20%" align="left"', "align='left' ");
        $reportTableHead['isTeaching']      =   array('Teaching','width="3%" align="left"', "align='left' ");
        $reportTableHead['designationName'] =   array('Designation','width="10%" align="left"', "align='left' ");
        $reportTableHead['dateOfJoining']   =   array('DOJ','width="5%" align="center"', "align='center' ");
        $reportTableHead['dateOfBirth']     =   array('DOB','width="5%" align="center"', "align='center' ");
    }
    else if($roleId==3){//for parents
       $reportTableHead['parentName']      =   array('Name','width="40%" align="left"', "align='left' ");
       $reportTableHead['parent']          =   array('Type','width="20%" align="left"', "align='left' ");
    }
    else if($roleId==4){//for students
       $reportTableHead['studentName']      =   array('Name','width="20%" align="left"', "align='left' ");
       $reportTableHead['dateOfBirth']      =   array('DOB','width="5%" align="center"', "align='center' ");
       $reportTableHead['dateOfAdmission']  =   array('DOA','width="5%" align="center"', "align='center' ");
    }
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: userReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:49
//Created in $/LeapCC/Templates/Role
//Made UI changes in Role Master module
?>