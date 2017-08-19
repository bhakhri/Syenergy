<?php
//---------------------------------------------------------------------------
// Purpose: To display Attendance Set records along with searches
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (29.12.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AttendanceSetMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AttendanceSetManager.inc.php");
    $attendanceSetManager = AttendanceSetManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $search = trim($REQUEST_DATA['searchbox']);
       if(strtoupper($search)=='PERCENTAGES'){
           $filter =' WHERE at.evaluationCriteriaId='.PERCENTAGES;
       }
       else if(strtoupper($search)=='SLABS'){
           $filter =' WHERE at.evaluationCriteriaId='.SLABS;
       }
       if($filter!=''){
          $filter .= ' OR ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }
       else{
          $filter = ' WHERE ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }

    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'attendanceSetName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray      = $attendanceSetManager->getTotalAttendanceSet($filter);
    $setRecordArray  = $attendanceSetManager->getAttendanceList($filter,$limit,$orderBy);
    $cnt = count($setRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($setRecordArray[$i]['evaluationCriteriaId']==PERCENTAGES){
           $setRecordArray[$i]['evaluationCriteriaId']='Percentages';
       }
       else if($setRecordArray[$i]['evaluationCriteriaId']==SLABS){
           $setRecordArray[$i]['evaluationCriteriaId']='Slabs';
       }
       else{
           $setRecordArray[$i]['evaluationCriteriaId']=NOT_APPLICABLE_STRING;
       } 
       $valueArray = array_merge(array('action' => $setRecordArray[$i]['attendanceSetId'] , 'srNo' => ($records+$i+1) ),$setRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:38
//Created in $/LeapCC/Library/AttendanceSet
//Added  "Attendance Set Module"
?>