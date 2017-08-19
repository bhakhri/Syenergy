<?php
//-------------------------------------------------------
// Purpose: To show student groups in array from the database, pagination functionality
//
// Author : Rajeev Aggarwal
// Created on : 12-11-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    // to limit records per page 
	
	$studentId  = $REQUEST_DATA['studentId'];
	$classId  = $REQUEST_DATA['rClassId'];
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studyPeriod';
    
     $orderBy = " $sortField $sortOrderBy";   
    
    $orderBy = " $sortField $sortOrderBy";
    //$sectionCondtion = " AND sct.studentId = $studentId";
	$totalArray = $studentManager->getStudentGroupCount($studentId,$classId,$orderBy);
	$count = count($totalArray);
	$studentSectionArray = $studentManager->getStudentGroups($studentId,$classId,$orderBy,$limit);
    $cnt = count($studentSectionArray);
    
    for($i=0;$i<$cnt;$i++) {
       
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentSectionArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';

// $History: ajaxGroup.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:20a
//Created in $/LeapCC/Library/Student
//Intial Checkin
?>