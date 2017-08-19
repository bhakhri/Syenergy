<?php
//-------------------------------------------------------
// Purpose: To get values of timetable from the database
//
// Author : Rajeev Aggarwal
// Created on : (31.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    $orderBy = " $sortField $sortOrderBy";  
     
    $totalArray        = TimeTableManager::getInstance()->getTotalClassSubjects(trim($REQUEST_DATA['timeTableLabelId'] ),trim($REQUEST_DATA['classId'] ));
    $classSubjectArray = TimeTableManager::getInstance()->getClassSubjectsList(trim($REQUEST_DATA['timeTableLabelId'] ),trim($REQUEST_DATA['classId']),$orderBy,$limit);
    
    $cnt = count($classSubjectArray);
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$classSubjectArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
    
}
// $History: ajaxGetClassSubjectList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/17/10    Time: 11:12a
//Created in $/LeapCC/Library/TimeTable
//Created Class->Subject display popup in "Create Time Table" module
?>