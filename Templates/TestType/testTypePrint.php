<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
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

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Test Type Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				   =   array('#','width="2%"', "align='center' ");
    $reportTableHead['testTypeName']           =   array('Name','width=20% align="left"', 'align="left"');
	$reportTableHead['testTypeCode']		   =   array('Code','width=15% align="left"', 'align="left"');
	$reportTableHead['testTypeAbbr']		   =   array('Abbr.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['weightageAmount']        =   array('Weightage Amt.','width="15%" align="right" ', 'align="right"');
    //$reportTableHead['weightagePercentage']    =   array('Weightage.Per','width="15%" align="right" ', 'align="right"');
    $reportTableHead['evaluationCriteriaName'] =   array('Eva. Criteria','width="12%" align="left" ', 'align="left"');
    $reportTableHead['universityName']         =   array('University','width="25%" align="left" ', 'align="left"');
    $reportTableHead['degreeCode']             =   array('Degree','width="20%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 7  *****************
//User: Administrator Date: 12/06/09   Time: 10:55
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug ids---0000046,0000048,0000050
//
//*****************  Version 6  *****************
//User: Administrator Date: 11/06/09   Time: 12:13
//Updated in $/LeapCC/Templates/TestType
//Corrected spelling mistakes
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