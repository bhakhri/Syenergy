<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AssignmentReport');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn();
    }
    else{
    UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $groupId=trim($REQUEST_DATA['groupId']);
    
    if($timeTableLabelId=='' or $classId==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
       die; 
    }

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    if($roleId==2){
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    }
    else{
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    }
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $filter =' AND asg.classId='.$classId.' AND ttc.timeTableLabelId='.$timeTableLabelId;
    
    if($subjectId!='' and $subjectId!=-1){
        $filter .=' AND asg.subjectId='.$subjectId;
    }
    
    if($groupId!='' and $groupId!=-1){
        $filter .=' AND asg.groupId='.$groupId;
    }
    
    if($roleId==2){
       $filter .=' AND asg.employeeId='.$sessionHandler->getSessionVariable('EmployeeId');    
    }
    $totalArray = $teacherManager->getAssignmentList($filter);
    $assignmentRecordArray = $teacherManager->getAssignmentList($filter,$limit,$orderBy);
    $cnt = count($assignmentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(strlen($assignmentRecordArray[$i]['topicDescription'])>100){
           $assignmentRecordArray[$i]['topicDescription']=substr($assignmentRecordArray[$i]['topicDescription'],0,97).'...'; 
        }
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$assignmentRecordArray[$i]);

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
?>