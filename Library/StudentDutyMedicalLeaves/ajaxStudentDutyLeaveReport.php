<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");   
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentDutyLeaveReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
    $dutyManager = DutyLeaveManager::getInstance();

    /////////////////////////

	$rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
	$eventId = add_slashes(trim($REQUEST_DATA['eventId']));
	$statusId = add_slashes(trim($REQUEST_DATA['statusId']));
	$classId = add_slashes(trim($REQUEST_DATA['classId']));


	$condition ='';
	if($rollNo==''){
     echo ENTER_ROLL_NO_REG_NO_UNI_NO;
    }

	if($rollNo!=""){
       $condition .=" AND (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo' OR s.universityRollNo LIKE '$rollNo') ";
    }

	if($eventId!="-1"){
       $condition .=" AND dl.eventId = '$eventId' ";
    }

    if($statusId!="-1"){
       $condition .=" AND dl.rejected= '$statusId' ";
    }

	if($classId != "-1") {
		$condition .= " And dl.classId= '$classId' ";
	}


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    $dutyTotalRecordArray = $dutyManager->getStudentDutyLeaveCount($condition);
	$dutyRecordArray = $dutyManager->getStudentDutyLeave($condition,$limit,$orderBy);
	$cnt = count($dutyRecordArray);

	global $globalDutyLeaveStatusArray;

	for($i=0;$i<$cnt;$i++) {
	   $dutyRecordArray[$i]['dutyDate'] = UtilityManager::formatDate($dutyRecordArray[$i]['dutyDate']);
	   if($dutyRecordArray[$i]['rejected'] != -1){
			$dutyRecordArray[$i]['rejected'] = $globalDutyLeaveStatusArray[$dutyRecordArray[$i]['rejected']];
       }
	   else{
			$dutyRecordArray[$i]['rejected']=$globalDutyLeaveStatusArray[DUTY_LEAVE_UNRESOLVED];
	   }
	   $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$dutyRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$dutyTotalRecordArray[0]['cnt'].'","page":"'.$page.'","info" : ['.$json_val.']}';

?>