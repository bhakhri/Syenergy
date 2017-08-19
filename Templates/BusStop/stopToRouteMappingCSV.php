<?php 
//This file is used as CSV for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/BusStopManager.inc.php");
	define('MODULE','BusStopRouteMapping');
        define('ACCESS','view');
	$busStopManager = BusStopManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
        function parseCSVComments($comments) {
		
		$comments = str_replace('"', '""', $comments);
		if(eregi(",", $comments) or eregi("\n", $comments)) {
        
			return '"'.$comments.'"'; 
		} 
		else {
			return chr(160).$comments; 
		}
	}
	
	$timeTablelabelId = trim($REQUEST_DATA['labelId']);
        $routeId = trim($REQUEST_DATA['routeId']);
     
	$timeTablelabelName = trim($REQUEST_DATA['labelName']);
        $routeName = trim($REQUEST_DATA['routeName']);
	

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
        $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';
        $orderBy = " $sortField $sortOrderBy";  
	
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$recordArray = array();

	$condition   =" AND IFNULL(rsm.mappingId,'-1') != '-1' ";
	$busStopRecordArray = $busStopManager->getBusRoutMappingList($timeTablelabelId,$routeId,$orderBy,$limit,$condition);

	$reportManager->setReportInformation("For Route ". $routeName." As On $formattedDate ");

	$cnt = count($busStopRecordArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$busStopRecordArray[$i]);
   }


	$csvData = '';
	$csvData .= "Sr,Stop Name,Abbr,Charges \n";

	
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.parseCSVComments($record['stopName']).','.parseCSVComments($record['abbr']).','.parseCSVComments($record['Charges']);
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="'.$routeName.'.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

	ob_end_clean();
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment; content-length: '.strlen($csvData).' filename="'.$routeName.'.csv"');
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
