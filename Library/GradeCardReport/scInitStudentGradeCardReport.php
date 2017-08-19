<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentGradeCardReport');
    define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
    
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();  
    
    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
    $branchId = add_slashes($REQUEST_DATA['branchId']); 
    $batchId = add_slashes($REQUEST_DATA['batchId']);    
    $rollNo = add_slashes(trim($REQUEST_DATA['rollno']));
    $degreeId = add_slashes($REQUEST_DATA['degreeId']);  
    
    
    if (!empty($degreeId) && !empty($batchId) ) {
       $conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' AND d.degreeId = '$degreeId'";
       //$conditions = " AND CONCAT_WS(d.degreeId,b.batchId,b.branchId) IN ($degreeId,$batchId,$branchId)"; 
    }
    
    if($rollNo!='') {
       $conditions .= " AND a.rollNo LIKE '$rollNo%' "; 
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
                                                                                                        
     /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";    

    $totalRecordsArray = $gradeCardRepotManager->countRecords($conditions);
    $totalRecords = $totalRecordsArray[0]['cnt'];
    
    $conditions .=" GROUP BY ss.studentId";
    $studentRecordArray = $gradeCardRepotManager->getAllDetailsStudentList($conditions, $orderBy, $limit);
    $cnt = count($studentRecordArray);
    
    //'students' => "<input type=\"checkbox\" name=\"studentList\" id=\"studentList\" value=\"".$studentRecordArray[$i]['studentId'] ."\">",
    for($i=0;$i<$cnt;$i++) {
        $checkall = '<input type="checkbox" name="chb[]" id="chb" value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
        
        $permAddress = trim($studentRecordArray[$i]['permAddress']); 
        if($studentRecordArray[$i]['permAddress']=='') {
           $permAddress = NOT_APPLICABLE_STRING; 
        }
        
        $corrAddress = trim($studentRecordArray[$i]['corrAddress']); 
        if($studentRecordArray[$i]['corrAddress']=='') {
           $corrAddress = NOT_APPLICABLE_STRING; 
        }
        
        $valueArray = array_merge(array(
                              'checkAll' => $checkall,
                              'srNo' => ($records+$i+1), 
                              'studentName' => strip_slashes($studentRecordArray[$i]['studentName']),
                              'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
                              'fatherName' => strip_slashes($studentRecordArray[$i]['fatherName']),
                              'DOB' => UtilityManager::formatDate($studentRecordArray[$i]['DOB']),
                              'programme' => strip_slashes($studentRecordArray[$i]['programme']),
                              'studentMobileNo' => strip_slashes($studentRecordArray[$i]['studentMobileNo']),    
                              'permAddress' => strip_slashes($permAddress),
                              'corrAddress' => strip_slashes($corrAddress),
                              'Photo' => strip_slashes($studentRecordArray[$i]['Photo'])));
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 

?>