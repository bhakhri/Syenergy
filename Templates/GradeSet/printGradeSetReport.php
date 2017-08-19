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

    require_once(MODEL_PATH . "/GradeSetManager.inc.php");
    $GradeSetManager = GradeSetManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE ( GradeSetName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isActive LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'GradeSetName';
    
    $orderBy = " $sortField $sortOrderBy";
     
	$gradeSetArray = $GradeSetManager->getgradeSetName($filter,'',$orderBy);
	/*echo '<pre>';
	print_r($gradeSetArray);
	die;*/

		$recordCount = count($gradeSetArray);
		
       $gradeTypeArray = array( 0=>'No',1=>'Yes');
	   
		
		$GradeSetPrintArray[] =  Array();
		if($recordCount >0 && is_array($gradeSetArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
				$gradeSetArray[$i]['isActive'] = $gradeTypeArray[$gradeSetArray[$i]['isActive']];
				$valueArray[] = array_merge(array('srNo' => ($i+1) ), $gradeSetArray[$i]);
			
			}
		}
	                              
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Grade Set Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['gradeSetName']		=    array('Grade Set Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['isActive']			=    array('is Active.',' width="15%" align="left" ','align="left"');
	//$reportTableHead['offenseDesc']			=    array('Desc.',' width="15%" align="left" ','align="left"');
	//$reportTableHead['studentCount']			= array('Student Count',' width="15%" align="left" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
	   
//modified By satinder
//
?>
