<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
    else if($roleId==3){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else if($roleId==4){
      UtilityManager::ifParentNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineReportManager.inc.php");
    $fineReportManager = FineReportManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    $classId= trim($REQUEST_DATA['classId']);   
    $showPending= trim($REQUEST_DATA['showPending']);  
    $studentName= htmlentities(add_slashes(trim($REQUEST_DATA['studentName'])));
    $rollNo= htmlentities(add_slashes(trim($REQUEST_DATA['rollNo'])));
    $fatherName= htmlentities(add_slashes(trim($REQUEST_DATA['fatherName'])));
    $receiptNo= htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo'])));  
    $fromDate= trim($REQUEST_DATA['fromDate']);  
    $toDate= trim($REQUEST_DATA['toDate']);  
            
    $condition ='';
    $having ='';
    $payCondition ='';
    
    if($classId=='') {
      $classId='0';  
    }
    
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
   
    if($classId!='') {
      $condition .= " AND fs.classId IN ($classId) ";
    }
    
    if($receiptNo!='') {
      $payCondition .= " AND frd.fineReceiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $payCondition = " AND (frd.receiptDate BETWEEN '$fromDate' and '$toDate') ";
    }
    
    if($showPending=='1') { // Paid
      if($having=='') {
        $having .= " HAVING (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) <= 0 ";    
      }  
      else {
        $having .= " AND (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) <= 0 ";      
      }
    }
    else if($showPending=='2') {   // UnPaid
      if($having=='') {
        $having .= " HAVING (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) > 0 ";    
      }  
      else {
        $having .= " AND (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) > 0 ";      
      }
    }
    
    $totalArray = $fineReportManager->getTotalFineStudentNew($condition,$having,$payCondition);
    $fineRecordArray = $fineReportManager->getFineStudentNew($condition,$orderBy,$limit,$having,$payCondition);
    $cnt = count($fineRecordArray);


    for($i=0;$i<$cnt;$i++) {
    	$studentId = $fineRecordArray[$i]['studentId'];
		$ttcondition = "AND s.studentId = '$studentId'";
	 $fineCategoryArray = $fineReportManager->getStudentFineCategory($ttcondition);
	 $fineCategoryName="";
	 if(count($fineCategoryArray)>0){
	 	for($xx=0;$xx<count($fineCategoryArray);$xx++){
	 		if($fineCategoryName!=''){
	 			$fineCategoryName .= ","; 
	 		}
	 		$fineCategoryName .= $fineCategoryArray[$xx]['fineCategoryName'];	
	 	} 
	 }
	
      $str = trim(trim($fineRecordArray[$i]['studentName'])." (".$fineRecordArray[$i]['rollNo'].")");

      $action1 = '<a href="" name="bubble" onclick="showFineDetails('.$fineRecordArray[$i]['studentId'].',\'divMessage\',\''.$str.'\',800,400);return false;" title="'.$title.'" ><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Brief Description" title="Fine Receipt Detail"></a></a>';	
	 
      $valueArray = array_merge(array('action1'=>$action1,'fineCategory'=>$fineCategoryName,'action' => $fineRecordArray[$i]['studentId'] ,
                                       'srNo' => ($records+$i+1) ),
                                       $fineRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
