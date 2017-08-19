<?php
//-------------------------------------------------------
// Purpose: To fetch student marks details
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SearchStudentDisplay');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
   /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
   */ 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studyPeriod';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    if(trim($sessionHandler->getSessionVariable('rStudentId'))!=''){
        $totalArray               = $teacherManager->getTotalStudentMarks($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId']);
        $studentMarksRecordArray = $teacherManager->getStudentMarks($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$orderBy,$limit);
    }
    $cnt = count($studentMarksRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentMarksRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxMarksList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/11/09    Time: 12:30
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Fixed Query Error
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//Corrected Student Tabs
?>
