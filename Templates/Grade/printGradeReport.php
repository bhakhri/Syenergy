 <?php 
//This file is used as printing version for offense.
//
// Author :Jaineesh
// Created on : 05-10-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/GradeManager.inc.php");
    $gradeManager = GradeManager::getInstance();

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE gradeSetName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gradeLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gradePoints LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'gradeLabel';
    
    $orderBy = " $sortField $sortOrderBy";         

    $totalArray = $gradeManager->getTotalGrade($filter);
    $gradeRecordArray = $gradeManager->getGradeList($filter,$limit,$orderBy);
	
    $cnt = count($gradeRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$gradeRecordArray[$i]);
    }
	
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Grade Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['gradeSetName']		=    array('Grade Set Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['gradeLabel']			=    array('Grade Name',' width="15%" align="left" ','align="left"');
	$reportTableHead['gradePoints']         =    array('Grade Points','width="15%" align="left" ','align="left"');
	//$reportTableHead['offenseDesc']			=    array('Desc.',' width="15%" align="left" ','align="left"');
	//$reportTableHead['studentCount']			= array('Student Count',' width="15%" align="left" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
	   
//modified By satinder
//
?>
