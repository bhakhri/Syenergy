<?php 
//This file is used as printing version for Evaluation Criteria.
//
// Author :Rajeev Aggarwal
// Created on : 16-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/EvaluationCriteriaManager.inc.php");

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
	$EvaluationCriteriaManager = EvaluationCriteriaManager::getInstance();
    $totalArray = $EvaluationCriteriaManager->getTotalEvaluationCriteriaType($filter);
	$orderBy = " evaluationCriteriaId";
    $recordArray = $EvaluationCriteriaManager->getEvaluationCriteriaList($filter,$limit,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {

		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(600);
	$reportManager->setReportInformation("As On $formattedDate ");
	$reportManager->setReportHeading("Evaluation Criteria Report");
	 

	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', 'align="left"');
	$reportTableHead['evaluationCriteriaName']			=	array('Name','width=97% align="left"', 'align="left"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listEvaluationPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:13p
//Updated in $/LeapCC/Templates/Evaluation
//Gurkeerat: resolved issue 1273,1275
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/10/09    Time: 12:29p
//Created in $/LeapCC/Templates/Evaluation
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/16/09    Time: 1:10p
//Created in $/Leap/Source/Templates/Evaluation
//Intial checkin
?>