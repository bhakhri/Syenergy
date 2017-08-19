<?php
//-------------------------------------------------------
// Purpose: To fetch student marks details
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////

    $totalArray      = $teacherManager->getTotalStudentMarks($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId']);
    $studentMarksRecordArray = $teacherManager->getStudentMarks($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$orderBy,$limit);
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
// $History: ajaxStudentMarks.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 11:58a
//Created in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Fully Ajax Enabled Student Tabs in Teacher Module
?>
