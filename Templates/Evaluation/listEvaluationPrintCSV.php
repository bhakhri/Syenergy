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


    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr, Name \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['evaluationCriteriaName'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="evaluationCriteria.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: listEvaluationPrintCSV.php $
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