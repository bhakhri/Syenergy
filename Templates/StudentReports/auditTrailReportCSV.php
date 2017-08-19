 <?php 
//---------------------------------------------------------------
//
// Purpose:This file is used as CSV version for Audit Trail Report
// Author :Kavish Manjkhola
// Created on : 24.03.11
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReportsManager = StudentReportsManager::getInstance();
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . '/ReportManager.inc.php');
$reportManager = ReportManager::getInstance();
$fromDate = add_slashes($REQUEST_DATA['fromDate']);
$toDate= add_slashes($REQUEST_DATA['toDate']);
$auditType =  add_slashes($REQUEST_DATA['auditType']);
$sortField = add_slashes($REQUEST_DATA['sortField']);
$sortOrderBy = add_slashes($REQUEST_DATA['sortOrderBy']);
$conditions = "WHERE (at.auditDateTime BETWEEN '$fromDate' AND '$toDate') AND u.userId = at.userId";
$auditTypeName = ""; 
if ($auditType != '') {
	$conditions .= " ";
	$conditions .= " AND at.auditType = '$auditType'";
	$auditTypeName =  $auditTrailArray[$auditType];
}
else {
	$auditTypeName = "All";
}

$timeFormat = '12';
$orderBy =" $sortField $sortOrderBy";

$auditTrailRecordArray = $studentReportsManager->getAuditTrailDetails($conditions,$orderBy,$limit);
$cnt1 = count($auditTrailRecordArray);

$csvData = "";
$csvData .= "From Date: ".UtilityManager::formatDate($fromDate).", To Date: ".UtilityManager::formatDate($toDate).",";
$csvData .= "Audit Type: ".$auditTypeName."\n";
$csvData .="\n";
$csvData .="#,Audit Trail Type,Audit Description,Audit Date Time,Audit User";
$csvData .="\n";

function parseCSVComments($comments) {
	$comments = str_replace('"', '""', $comments);
	$comments = str_ireplace('<br/>', "\n", $comments);
	if(eregi(",", $comments) or eregi("\n", $comments)) {
		return '"'.$comments.'"'; 
	} 
	else {
		return $comments.chr(160); 
	}
}

for($i=0; $i<$cnt1; $i++) {
	$auditTrailRecordArray[$i]['auditDateTime'] = UtilityManager::formatDate($auditTrailRecordArray[$i]['auditDateTime'],true,$timeFormat);
	$auditTrailRecordArray[$i]['auditType'] = $auditTrailArray[$auditTrailRecordArray[$i]['auditType']];
	$csvData .= ($i+1).",";
	$csvData .= parseCSVComments($auditTrailRecordArray[$i]['auditType']).",";
	$csvData .= parseCSVComments($auditTrailRecordArray[$i]['description']).",";
	$csvData .= parseCSVComments($auditTrailRecordArray[$i]['auditDateTime']).",";
	$csvData .= parseCSVComments($auditTrailRecordArray[$i]['userName']).",";
	$csvData .= "\n";
}

if($cnt1 == 0){
	$csvData .= "No Data Found";
}

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'auditTrailReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>