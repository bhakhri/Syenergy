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
	define('MODULE','AllocateAssignment');
	define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    $search=trim($REQUEST_DATA['search']);
    if($search!='') {
       $isVisible=-1;
       if(strtoupper($search)=='YES'){
           $isVisible=1;
       }
       else if(strtoupper($search)=='NO'){
           $isVisible=0;
       }
       else{
           $isVisible=-1;
       }
       $search=add_slashes($search);
       $filter = ' HAVING ( aa.topicTitle LIKE "%'.$search.'%" OR aa.topicDescription LIKE "%'.$search.'%" OR totalAssignment LIKE "%'.$search.'%" OR isVisible2 LIKE "'.$isVisible.'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
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

		$actionStr='<a href="#" title="Detail"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Detail" onclick="showWindow('.$salaryHeadRecordArray[$i]['assignmentId'].',\'StudentAssignmentActionDiv\');return false;"></a>&nbsp;<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$salaryHeadRecordArray[$i]['assignmentId'].',\'StudentTeacherActionDiv\');return false;"></a>';

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
    
// for VSS
// $History: ajaxInitTeacherAssignmentList.php $
?>