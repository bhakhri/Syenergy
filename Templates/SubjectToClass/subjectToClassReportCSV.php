<?php 
//This file is used as CSV for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
	$subjecttoclassManager = SubjectToClassManager::getInstance();

	$classId     = $REQUEST_DATA['class'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField   = $REQUEST_DATA['sortField'];
	$className   = $REQUEST_DATA['className'];

	function parseCSVComments($comments) {
		
		$comments = str_replace('"', '""', $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {
        
			return '"'.$comments.'"'; 
		} 
		else {
			return chr(160).$comments; 
		}
	}
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stc.subjectId';
	$orderBy = " $sortField $sortOrderBy";

	$recordArray = array();
	$condition   =" AND subtocls.classId=$classId ";

	if(UtilityManager::notEmpty($REQUEST_DATA['subjectDetail'])) {

       $condition .= ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%"  OR sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%")';
    }

	$recordArray = $subjecttoclassManager->getSubToClassListPrint($condition, $limit = '', $orderBy);
	
	$cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr,Code,Subject Name,Type,Optional,Major/Minor,Offered,Credits, Internal Marks, External Marks \n";

	
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.parseCSVComments($record['subjectCode']).','.parseCSVComments($record['subjectName']).','.parseCSVComments($record['subjectTypeName']).','.parseCSVComments($record['optional']).','.parseCSVComments($record['hasParentCategory']).','.parseCSVComments($record['offered']).','.parseCSVComments($record['credits']).','.parseCSVComments($record['internalTotalMarks']).','.parseCSVComments($record['externalTotalMarks']);
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="'.$className.'.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

	ob_end_clean();
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment; content-length: '.strlen($csvData).' filename="'.$className.'.csv"');
	// The PDF source is in original.pdf
	echo $csvData;
	die;

// $History: subjectToClassReportCSV.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-12-14   Time: 11:58a
//Updated in $/LeapCC/Templates/SubjectToClass
//Changed Label "Has Category" to "Major/Minor"
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:07p
//Updated in $/LeapCC/Templates/SubjectToClass
//Fixed 1090,1089,1088,1058 bugs
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:14a
//Created in $/LeapCC/Templates/SubjectToClass
//Intial checkin
?>