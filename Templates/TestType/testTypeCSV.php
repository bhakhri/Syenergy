<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testTypeManager = TestTypeManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions = ' WHERE (tt.testTypeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.testTypeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.testTypeAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR tt.weightageAmount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR eve.evaluationCriteriaName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR un.universityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR deg.degreeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    
    //$conditions = '';
    //if (count($conditionsArray) > 0) {
        //$conditions = ' AND '.implode(' AND ',$conditionsArray);
    //}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testTypeName';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


    $totalArray  = $testTypeManager->getTotalTestType($conditions);
    $recordArray = $testTypeManager->getTestTypeList($conditions,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
	//$csvData .= "Sr, Name, Code, Abbr, Weightage.Amt, Weightage.Per, Eva.Criteria , University, Degree \n";
    $csvData .= "Sr, Name, Code, Abbr, Weightage Amt., Eva.Criteria , University, Degree \n";
	foreach($valueArray as $record) {
		//$csvData .= $record['srNo'].', '.parseCSVComments($record['testTypeName']).', '.parseCSVComments($record['testTypeCode']).', '.parseCSVComments($record['testTypeAbbr']).','.$record['weightageAmount'].','.$record['weightagePercentage'].','.$record['evaluationCriteriaName'].','.$record['universityName'].','.$record['degreeCode'];
        $csvData .= $record['srNo'].', '.parseCSVComments($record['testTypeName']).', '.parseCSVComments($record['testTypeCode']).', '.parseCSVComments($record['testTypeAbbr']).','.$record['weightageAmount'].','.$record['evaluationCriteriaName'].','.$record['universityName'].','.$record['degreeCode'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="testType.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: testTypeCSV.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 5  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 12:55
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs -----issues.doc.
//Bug ids-1,2,3
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Templates/TestType
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Created in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print and export to csv
?>