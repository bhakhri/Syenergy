<?php
//-------------------------------------------------
//This file returns the load of teacher 
//
// Author :PArveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);  
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");     
    $employeeReportsManager = EmployeeReportsManager::getInstance(); 

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";         


    $lectureGroupType = add_slashes($REQUEST_DATA['groupType']);  
    $lectureGroupTypeArr = explode(',',$lectureGroupType);

    $employeeId=add_slashes($REQUEST_DATA['employeeId']);    
    
    $filter1 = '';
    $filter2 = '';
    
    $cnt = count($lectureGroupTypeArr);
    for($i=0; $i<$cnt; $i++) {
      $filter1 .= " IF(grp.groupTypeId=".trim($lectureGroupTypeArr[$i]).",MAX(att.lectureDelivered),0) AS ss".trim($lectureGroupTypeArr[$i]).", ";
      $filter2 .= " SUM(t.ss".trim($lectureGroupTypeArr[$i]).") AS s".trim($lectureGroupTypeArr[$i]).", ";
    }

    $condition = " AND ttc.timeTableLabelId = ".add_slashes($REQUEST_DATA['timeTableLabelId'])." AND att.employeeId=".add_slashes($employeeId);  
    
    $cnt = 0; 
    $cnt1 = 0;   
    if(add_slashes($REQUEST_DATA['timeTableLabelId'])!='' && add_slashes($employeeId) != '') {   
        $recordArray = $employeeReportsManager->getLectureDeliveredCount($condition,$filter1,$filter2);
        $cnt1 = $recordArray[0]['cnt'];
        
        $recordArray = $employeeReportsManager->getLectureDelivered($condition,$orderBy, $limit,$filter1,$filter2);
        $cnt = count($recordArray);
    }
    
    for($i=0;$i<$cnt;$i++) {
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
        if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

     
// $History:
//
//
?>
