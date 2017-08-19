 <?php
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','HostelMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/BankManager.inc.php");
    $bankManager = BankManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = '';
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE bankName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bankAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';

     $orderBy = " $sortField $sortOrderBy";

	$bankArray = $bankManager->getBankList($filter,'',$orderBy);

		$recordCount = count($bankArray);

		$hostelPrintArray[] =  Array();
		if($recordCount >0 && is_array($bankArray) ) {

			for($i=0; $i<$recordCount; $i++ ) {
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$bankArray[$i]);

			}
		}

    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Bank Report ');
	if($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['bankName']			=    array('Bank Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['bankAbbr']			=    array('Abbr.',' width="15%" align="left" ','align="left"');
    $reportTableHead['bankAddress']            =    array('Address',' width="35%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
//
?>