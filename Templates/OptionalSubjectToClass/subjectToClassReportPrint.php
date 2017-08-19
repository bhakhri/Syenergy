<?php 
//This file is used as printing version for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");

	 
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
    $subjecttoclassManager = SubjectToClassManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	$classId     = $REQUEST_DATA['class'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField   = $REQUEST_DATA['sortField'];
	$className   = $REQUEST_DATA['className'];

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stc.subjectId';
	$orderBy = " $sortField $sortOrderBy";

	$recordArray = array();
	$condition   =" AND subtocls.classId=$classId ";
	If(UtilityManager::notEmpty($REQUEST_DATA['subjectDetail'])) {

        $condition .= ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%"  OR sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%")';
    }
	$recordArray = $subjecttoclassManager->getSubToClassListPrint($condition, $limit = '', $orderBy);
	$reportManager->setReportInformation("For ".$className." As On $formattedDate ");

	$cnt = count($recordArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(600);
	$reportManager->setReportHeading('Subject to Class Report');
	if($reportTableHead['optional'])
		$optional = "Yes";
	else
		$optional = "No";
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']				=	array('#','width="3%"  align="left" ', "align='left' ");
	$reportTableHead['subjectCode']			=	array('Code','width="8%" align="left" ','align="left"');
	$reportTableHead['subjectName']			=	array('Subject','width=25% align="left"','align="left"');
	$reportTableHead['subjectTypeName']		=	array('Type','width="8%" align="left"','align="left"');
	$reportTableHead['optional']			=	array('Optional','width="6%" align="left"', 'align="left"');
	$reportTableHead['hasParentCategory']	=	array('Major/Minor','width="6%" align="left"', 'align="left"');
	$reportTableHead['offered']				=	array('Offered','width="5%" align="left"', 'align="left"');
	$reportTableHead['credits']				=	array('Credits','width="5%" align="right"', 'align="right"');
	$reportTableHead['internalTotalMarks']	=	array('Internal Marks','width="5%" align="right"', 'align="right"');
	$reportTableHead['externalTotalMarks']	=	array('External Marks','width="5%" align="right"', 'align="right"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
