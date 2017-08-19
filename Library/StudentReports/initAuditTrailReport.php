<?php
//----------------------------------------------------------------------------------
//This file returns the array of Audit Description, Username based on the aduit Type
//
// Author :Kavish Manjkhola
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
	define('MODULE','AuditTrailReport');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$fromDate = add_slashes($REQUEST_DATA['fromDate']);
	$toDate= add_slashes($REQUEST_DATA['toDate']);
	$auditType =  add_slashes($REQUEST_DATA['auditType']);
	$sortField = add_slashes($REQUEST_DATA['sortField']);
	$sortOrderBy = add_slashes($REQUEST_DATA['sortOrderBy']);

	$conditions = "WHERE  ( DATE_FORMAT(at.auditDateTime,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') AND u.userId = at.userId";
	$conditions1 = "WHERE ( DATE_FORMAT(at.auditDateTime,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') AND u.userId = at.userId AND u.roleId=r.roleId";
	if ($auditType != '') {
		$conditions .= " ";
		$conditions .= "AND at.auditType = '$auditType'";
	}
        if ($auditType != '') {
		$conditions1 .= " ";
		$conditions1 .= "AND at.auditType = '$auditType'";
	}
	$timeFormat = '12';

	$orderBy =" $sortField $sortOrderBy";

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$recordArray = $studentReportsManager->getAuditTrailCount($conditions);
	$cnt = $recordArray[0]['cnt'];
	$auditTrailRecordArray = $studentReportsManager->getAuditTrailDetailsNew($conditions1,$orderBy,$limit);
	$cnt1 = count($auditTrailRecordArray);
	$valueArray = array();
	for($i=0; $i<$cnt1; $i++) {
		$auditTrailRecordArray[$i]['auditDateTime'] = UtilityManager::formatDate($auditTrailRecordArray[$i]['auditDateTime'],true,$timeFormat);
		$auditTrailRecordArray[$i]['auditType'] = $auditTrailArray[$auditTrailRecordArray[$i]['auditType']];
		$valueArray = array_merge(array('srNo' => ($records+$i+1)),$auditTrailRecordArray[$i]);
		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);
		}
	}
	echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}';

?>
