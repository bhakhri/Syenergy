 <?php
//This file is used as printing version for group.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classesManager = ClassesManager::getInstance();

	/// Search filter /////
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = '  (degreeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR batchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR branchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR studentCount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR degreeDuration LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR periodName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';

    $orderBy = " $sortField $sortOrderBy";

	$classesRecordArray = $classesManager->getClassesList($filter,'',$orderBy);

	$recordCount = count($classesRecordArray);

                            //$designationPrintArray[] =  Array();
                            if($recordCount >0 && is_array($classesRecordArray) ) {

                                for($i=0; $i<$recordCount; $i++ ) {

                                    $bg = $bg =='row0' ? 'row1' : 'row0';

                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$classesRecordArray[$i]);

                                }
                            }

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Classes Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['degreeCode']			=    array('Degree',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['batchName']			=    array('Batch',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['branchName']			=    array('Branch',        ' width="20%" align="left" ','align="left"');
	$reportTableHead['degreeDuration']		=    array('Duration (Yrs.)',        ' width="5%" align="right" ','align="right"');
	$reportTableHead['Active']				=    array('Active Classes',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['studentCount']        =    array('Student',        ' width="5%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>