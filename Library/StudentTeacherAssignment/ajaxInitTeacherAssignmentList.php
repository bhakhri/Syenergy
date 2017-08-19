<?php
//-------------------------------------------------------
// Purpose: To store the records of salary head in array from the database, pagination and search, delete 
// functionality
// Author : Rajeev Aggarwal
// Created on : (25.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;   
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2) {
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();
    
    
    require_once(MODEL_PATH . "/AssignmentReportManager.inc.php");
    $teacherManager = AssignmentReportManager::getInstance();

    
    $timeTableLabelId    = $REQUEST_DATA['timeTableLabelId'];
    $employeeId = $REQUEST_DATA['employeeId'];   
    $classId  = $REQUEST_DATA['classId'];  
    $subjectId   = $REQUEST_DATA['subjectId']; 
    $groupId = $REQUEST_DATA['groupId'];   
    
    $searchDateFilter = $REQUEST_DATA['searchDateFilter'];    
    $searchFromDate = $REQUEST_DATA['searchFromDate'];   
    $searchToDate = $REQUEST_DATA['searchToDate'];   
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    if($timeTableLabelId=='') {
      $timeTableLabelId='0';  
    }
    
    $filter ="";
    
    $filter .=" AND timeTableLabelId = '$timeTableLabelId' ";
    if($employeeId!='') {
      $filter .=" AND aa.employeeId = '$employeeId' ";
    }
    if($classId!='') {
      $filter .=" AND aa.classId = '$classId' ";
    }
    if($subjectId!='') {
      $filter .=" AND aa.subjectId = '$subjectId' ";
    }
    if($groupId!='') {
      $filter .=" AND aa.groupId = '$groupId' ";
    }
    
    if($searchDateFilter!='') {
      if($searchDateFilter=='1') {
        $filter .=" AND (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
      }  
      else if($searchDateFilter=='2') {
        $filter .=" AND (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
      }  
      else if($searchDateFilter=='3') {
        $filter .=" AND (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate') ";    
      }  
      else if($searchDateFilter=='4') {
        $filter .=" AND ( (aa.assignedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR 
                          (aa.tobeSubmittedOn BETWEEN '$searchFromDate' AND '$searchToDate') OR
                          (aa.addedOn BETWEEN '$searchFromDate' AND '$searchToDate')
                        ) ";    
      }  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'assignedOn';
    
     $orderBy = " $sortField $sortOrderBy";         
    ////////////
    
    $totalArray            = $teacherManager->getTeacherAssignmentList($filter,'');
    $salaryHeadRecordArray = $teacherManager->getTeacherAssignmentList($filter,$limit,$orderBy);
    $cnt = count($salaryHeadRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		if($salaryHeadRecordArray[$i]['attachmentFile']){
			$salaryHeadRecordArray[$i]['attachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="'.$salaryHeadRecordArray[$i]['messageSubject'].'" onclick="download(\''.$salaryHeadRecordArray[$i]['attachmentFile'].'\')" />';  
		}
		else{
			$salaryHeadRecordArray[$i]['attachmentFile'] = '--';
		}
		
		$readMore1 = '';
		if(strlen($salaryHeadRecordArray[$i]['topicTitle'])>40){
			$readMore1 = " ...";
		}
		$salaryHeadRecordArray[$i]['topicTitle'] = stripslashes(substr($salaryHeadRecordArray[$i]['topicTitle'],0,40)).$readMore1;
		$readMore = '';
		if(strlen($salaryHeadRecordArray[$i]['topicDescription'])>115){
			$readMore = " ...";
		}
		$salaryHeadRecordArray[$i]['topicDescription'] = stripslashes(substr($salaryHeadRecordArray[$i]['topicDescription'],0,115)).$readMore;

		//assignedOn 	tobeSubmittedOn 	addedOn
		$salaryHeadRecordArray[$i]['assignedOn'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['assignedOn']);
		$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['tobeSubmittedOn']);
		$salaryHeadRecordArray[$i]['addedOn'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['addedOn']);

		$actionStr='<a href="#" title="Detail"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Detail" onclick="showWindow('.$salaryHeadRecordArray[$i]['assignmentId'].',\'StudentAssignmentActionDiv\');return false;"></a>&nbsp;';

        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1) ),$salaryHeadRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>