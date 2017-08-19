<?php
//-----------------------------------------------------------------------------------------------
// Purpose: To display student fine along with add,edit,delete,sorting and paging facility
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
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

    global $statusCategoryArr;
    
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    
    if($roleId!='1') {
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
    }
     
    $rollNo       = trim($REQUEST_DATA['rollNo']);
    $fineCategoryId    = $REQUEST_DATA['fineCategory'];
    $startDate = $REQUEST_DATA['startDate'];
    $toDate       = $REQUEST_DATA['toDate'];
    $status       = trim($REQUEST_DATA['status']);
    $paymentStatus   = $REQUEST_DATA['paymentStatus'];
    $searchClassStatus = $REQUEST_DATA['searchClassStatus'];
    $studentName       = trim($REQUEST_DATA['studentName']);
    
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
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

  
    $totalArray             = $fineStudentManager->getTotalFineStudentNew($filter);
    $fineStudentRecordArray = $fineStudentManager->getFineStudentListNew($filter,$limit,$orderBy);
    $cnt = count($fineStudentRecordArray);

	
	
    for($i=0;$i<$cnt;$i++) {
       $fineStudentId = strip_slashes($fineStudentRecordArray[$i]['fineStudentId']);
       $checkall = "<input type='checkbox' name='chb[]' id='chb".$id."' value='".$fineStudentId."'>"; 
       
	   $fineStudentRecordArray[$i]['fineDate'] = UtilityManager::formatDate($fineStudentRecordArray[$i]['fineDate']);
	   $fineStudentRecordArray[$i]['status'] = $statusCategoryArr[$fineStudentRecordArray[$i]['status']];
	   $showlink = "<a href='#' onClick='editWindow(".$fineStudentRecordArray[$i]['fineStudentId'].",\"EditFineStudent\",360,250)' alt='Edit' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>&nbsp;&nbsp;
                    <a href='#' onClick='deleteFineCategory(".$fineStudentRecordArray[$i]['fineStudentId'].")' alt='Delete' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' /></a>";
	   $valueArray = array_merge(array('action1' => $showlink , 
                                       'checkAll' => $checkall, 
                                       'srNo' => ($records+$i+1) ),$fineStudentRecordArray[$i]);
       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

?>
