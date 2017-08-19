<?php 
// This file is used as printing version for fine categories.
// Author :Rajeev Aggarwal
// Created on : 03.07.2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineStudentMaster');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();  

	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    

    global $statusCategoryArr;
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    
    // Fetch Fine Role 
    $roleFineArray = $fineStudentManager->getSearchFineRole($roleId);
    $roleFineId = '0';
    if(is_array($roleFineArray) && count($roleFineArray)>0) {
      $roleFineId = $roleFineArray[0]['roleFineId'];   
    }  
    
    // Fine Category
    $roleCategoryArray = $fineStudentManager->getSearchFineCategory($roleFineId);
     
    // Fine Class
    $roleClassArray = $fineStudentManager->getSearchFineClass($roleFineId);
     
    // Fine Institute
    $roleInstituteArray = $fineStudentManager->getSearchFineInstitute($roleFineId);
    
     
    $categoryId ='0';
    $classId='0';
    $instituteId='0';
    if(is_array($roleCategoryArray) && count($roleCategoryArray)>0) {
      $categoryId = UtilityManager::makeCSList($roleCategoryArray,'fineCategoryId');
    }
    if(is_array($roleClassArray) && count($roleClassArray)>0) {
      $classId = UtilityManager::makeCSList($roleClassArray,'classId');
    }
    if(is_array($roleInstituteArray) && count($roleInstituteArray)>0) {
      $instituteId = UtilityManager::makeCSList($roleInstituteArray,'instituteId');
    }
    $filter = " AND f.classId IN ($classId) AND f.fineCategoryId IN ($categoryId) ";
     
    $rollNo       = trim($REQUEST_DATA['rollNo']);
    $fineCategoryId    = $REQUEST_DATA['fineCategory'];
    $startDate = $REQUEST_DATA['startDate'];
    $toDate       = $REQUEST_DATA['toDate'];
    $status       = trim($REQUEST_DATA['status']);
    $paymentStatus   = $REQUEST_DATA['paymentStatus'];
    $searchClassStatus = $REQUEST_DATA['searchClassStatus'];
    $studentName       = trim($REQUEST_DATA['studentName']);
    
    
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
         $inService=1;
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
         $inService=0;
       }
       else{
         $inService=-1;
       }
       $approvedKey =  array_search(trim(ucfirst(strtolower ($REQUEST_DATA['searchbox']))),$statusCategoryArr);
       if($approvedKey){
         $approveSearch = " OR status =".$approvedKey;
       }
    }
        
    if ($rollNo != "") {
        $filter .= " AND s.rollNo='".$rollNo."'";
    }
    
    if ($fineCategoryId != "") {
        $filter .= " AND f.fineCategoryId=".$fineCategoryId."";
    }

    if ($startDate != "" && $toDate != "") {
        $filter .= " AND (f.fineDate BETWEEN '".$startDate."' AND '".$toDate."')";
    }
    
    if ($status != "") {
        $filter .= " AND f.status='".$status."'";
    }
    
    if($searchClassStatus=='') {
      $searchClassStatus='-1'; 
    }
    
    if ($studentName != "") {
        $filter .= " AND CONCAT(s.firstName,' ',s.lastName) LIKE '".$studentName."%'";
    }    
    
    if($searchClassStatus==4){
       $filter .= " AND c.isActive IN (1,3) ";
    }
    else {
       $filter .= " AND c.isActive = '$searchClassStatus' ";    
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineDate';
    $orderBy = " $sortField $sortOrderBy";

  
    $fineStudentRecordArray = $fineStudentManager->getFineStudentListNew($filter,'',$orderBy);
    $cnt = count($fineStudentRecordArray);

    
    for($i=0;$i<$cnt;$i++) {
       $fineStudentRecordArray[$i]['fineDate'] = UtilityManager::formatDate($fineStudentRecordArray[$i]['fineDate']);
       $fineStudentRecordArray[$i]['status'] = $statusCategoryArr[$fineStudentRecordArray[$i]['status']];
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$fineStudentRecordArray[$i]);
    }

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['rollNo']			=   array('Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['studentName']		=   array('Student Name','width=15% align="left"', 'align="left"');
    $reportTableHead['className']        =   array('Class Name','width=15% align="left"', 'align="left"');
	$reportTableHead['fineCategoryAbbr']=   array('Fine Category','width=12% align="left"', 'align="left"');
	$reportTableHead['fineDate']		=   array('Fine Date','width=10% align="center"', 'align="center"');
    $reportTableHead['amount']          =   array('Amount','width=10% align="right"', 'align="right"');
	$reportTableHead['issueEmployee']	=   array('Assigned By','width=10% align="left"', 'align="left"');
	$reportTableHead['status']			=   array('Status','width=10% align="left"', 'align="left"');
	$reportTableHead['reason']			=   array('Reason','width=20% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
    
?>