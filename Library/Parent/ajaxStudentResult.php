<?php
//-------------------------------------------------------
// Purpose: To store the records of student result from the database, pagination and search
// functionality
//
// Author : Parveen Sharma
// Created on : 16-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);  
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    

    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    //$studentRecordArray = $studentInformationManager->getScStudentMarks();
    // to limit records per page    
    $page    = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodName';
    
    $orderBy = " $sortField $sortOrderBy";

    $studentId= $sessionHandler->getSessionVariable('StudentId');
    $classId = $REQUEST_DATA['rClassId'];    
    
    if($studentId=='') {
      $studentId=0;  
    }
    

    $studentRecordArray = $studentInformationManager->getStudentFinalResultListAdv($studentId,$classId,$orderBy,'');
    $totalRecords = COUNT($studentRecordArray);
    
    $studentRecordArray = $studentInformationManager->getStudentFinalResultListAdv($studentId,$classId,$orderBy,$limit);
    $cnt = count($studentRecordArray);

    for($i=0;$i<$cnt;$i++) {
        $valueArray = array_merge(
            array(
                        'srNo' => ($records+$i+1)), 
                        $studentRecordArray[$i]
                 );

         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    //print_r($valueArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// $History: ajaxStudentResult.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/04/10    Time: 11:07a
//Updated in $/LeapCC/Library/Parent
//changes in code to show final result tab in find student & parent 
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/16/08   Time: 10:44a
//Updated in $/LeapCC/Library/Parent
//Intial Checkin 
//

?>