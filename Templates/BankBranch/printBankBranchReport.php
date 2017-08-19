 <?php 
//This file is used as printing version for Bank Branch.
//
// Author :Jaineesh
// Created on : 20.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BankBranchMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  AND (a.branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.branchAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR a.accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.bankName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';
    
     $orderBy = " $sortField $sortOrderBy";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = " $sortField $sortOrderBy";
     
	$bankBranchArray = $bankBranchManager->getBankBranchList($filter,'',$orderBy);

		$recordCount = count($bankBranchArray);
		
		$hostelPrintArray[] =  Array();
		if($recordCount >0 && is_array($bankBranchArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$bankBranchArray[$i]);
			}
		}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Bank Branch Report ');
	if ($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['bankName']			=    array('Bank',' width=15% align="left" ','align="left" ');
    $reportTableHead['branchName']			=    array('Branch',' width="20%" align="left" ','align="left"');
	$reportTableHead['branchAbbr']			=    array('Abbr.',' width="15%" align="left" ','align="left"');
    $reportTableHead['accountType']         =    array('Account Type',' width="18%" align="left" ','align="left"');
    $reportTableHead['accountNumber']       =    array('Account Number',' width="18%" align="left" ','align="left"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>
