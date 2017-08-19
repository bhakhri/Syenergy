<?php
//-------------------------------------------------------------------
// This File contains the show details of Student Internal Subject Re-appear detail
// Author :Parveen Sharma
// Created on : 19-01-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','DisplayStudentReappearReport');
   define('ACCESS','view');
   define("MANAGEMENT_ACCESS",1);
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();
   
   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
     
   require_once(MODEL_PATH . "/StudentManager.inc.php");
   $studentManager = StudentManager::getInstance();
   
   
    $conditions = "";
    
    $reappearClassId  = add_slashes($REQUEST_DATA['reappearClassId']);   
    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate = add_slashes($REQUEST_DATA['endDate']);
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
    $subjectId = add_slashes(trim($REQUEST_DATA['subjectId']));
    $reappearStatusId = add_slashes(trim($REQUEST_DATA['reappearStatusId'])); 
    $assignmentChk = add_slashes(trim($REQUEST_DATA['assignmentChk']));
    $midSemesterChk = add_slashes(trim($REQUEST_DATA['midSemesterChk']));
    $attendanceChk = add_slashes(trim($REQUEST_DATA['attendanceChk']));
    $studentDetained = add_slashes(trim($REQUEST_DATA['studentDetained']));

    if($reappearClassId!='') {
       $conditions .= " AND reapperClassId IN ($reappearClassId) ";
    }
    else {
       $conditions .= " AND reapperClassId IN (0) ";  
    }
    
    if($subjectId!='' ) {
      $conditions .= " AND sub.subjectId IN ($subjectId) ";
    }
    
    if($reappearStatusId!='' ) {
      $conditions .= " AND sr.reppearStatus IN ($reappearStatusId) ";
    }
    
    if($startDate!='' && $endDate =='')
       $conditions .= " AND dateOfEntry >='$startDate' ";

    if($startDate=='' && $endDate!='')
        $conditions .= " AND dateOfEntry <='$endDate'";

    if($startDate!='' && $endDate!=''){
       $conditions .= " AND ((dateOfEntry BETWEEN '$startDate' AND '$endDate') OR (dateOfEntry BETWEEN '$startDate' AND '$endDate'))";
    }
    
    if($rollNo!='') {
      $conditions .= " AND rollNo LIKE '$rollNo%' ";
    }
   
    if($assignmentChk!='') {
      $conditions .= " AND sr.assignmentStatus = '$assignmentChk' ";
    } 
    
    if($midSemesterChk!='') {
      $conditions .= " AND sr.midSemesterStatus = '$midSemesterChk'  ";
    }
    
    if($attendanceChk != '') {
      $conditions .= " AND sr.attendanceStatus = '$attendanceChk' ";
    }
    
    if($studentDetained != '') {
      $conditions .= " AND sr.detained = '$studentDetained' ";        
    }

   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = "ORDER BY $sortField $sortOrderBy ";

    $totalRecordArray = $studentManager->getClasswiseReappearCount($conditions);
    $studentRecordArray = $studentManager->getClasswiseReappearDetails($conditions, $orderBy, $limit);
    
    global $reppearStatusArr; 
    
    $cnt = count($studentRecordArray);
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecordArray[0]['cnt'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
<?php    
//$History : ajaxStudentInternalReappear.php $  
//

?>