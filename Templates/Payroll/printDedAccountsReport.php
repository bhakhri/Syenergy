 <?php 
//This file is used as printing payroll deduction accounts report.
//
// Author :Abhiraj
// Created on : 16-April-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Payroll');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE accountName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR accountNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'accountName';
    
     $orderBy = " $sortField $sortOrderBy";
     
	$dedAccountssArray = $payrollManager->getAccountList($filter,'',$orderBy);

		$recordCount = count($dedAccountssArray);
		
		$dedAccountsPrintArray[] =  Array();
		if($recordCount >0 && is_array($dedAccountssArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
                
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$dedAccountssArray[$i]);
			
			}
		}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Deduction Account Report print');
	if($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['accountName']			=    array('Account Name ',' width=50% align="left" ','align="left" ');
    $reportTableHead['accountNumber']            =    array('Account Number',' width="46%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

?>