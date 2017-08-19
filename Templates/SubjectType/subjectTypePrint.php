<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
    $SubjectTypeManager = SubjectTypeManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    define('MODULE','SubjectTypesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
       $filter = ' AND (sub.subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectTypeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR uni.universityName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

                        

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectTypeName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy = " $sortField $sortOrderBy";       


 
    $recordArray = $SubjectTypeManager->getSubjectTypeList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Subject Type Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				   =   array('#','width="3%" align="left"','align="left"');
    $reportTableHead['subjectTypeName']        =   array('Subject Type','width=25% align="left"', 'align="left"');
	$reportTableHead['subjectTypeCode']		   =   array('Abbr.','width=15% align="left"', 'align="left"');
	$reportTableHead['universityName']		   =   array('University','width="15%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: subjectTypePrint.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/24/09    Time: 11:34a
//Updated in $/LeapCC/Templates/SubjectType
//search in all fields any format type
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Templates/SubjectType
//formatting & role permission added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Templates/SubjectType
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/12/09    Time: 4:26p
//Updated in $/LeapCC/Templates/SubjectType
//search format update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/SubjectType
//list formatting & required field validation added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/SubjectType
//Added "Print" and "Export to excell" in subject and subjectType modules
?>