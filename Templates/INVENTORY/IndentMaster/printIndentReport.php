 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
    $indentManager = IndentManager::getInstance();

	$search=$REQUEST_DATA['searchbox'];
    $conditions = ''; 

     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=1;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='generatedpo') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (iim.indentNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR iim.indentStatus  LIKE "'.$type.'%" OR iim.indentDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR totalCount = "'.$REQUEST_DATA['searchbox'].'")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'indentNo';
    
    $sortField1 = $sortField;
	if($sortField=='indentNo') {
	   //$sortField1 = "LENGTH(indentNo)+0,indentNo";
	   $orderBy = " LENGTH(indentNo)+0 $sortOrderBy, indentNo $sortOrderBy";
	}
	else {
		$orderBy = " $sortField1 $sortOrderBy";
	}
     
	$indentRecordArray  = $indentManager->getIndentList($filter,$limit,$orderBy);

	$recordCount = count($indentRecordArray);
	
	if($recordCount >0 && is_array($indentRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$indentRecordArray[$i]['indentDate'] = UtilityManager::formatDate($indentRecordArray[$i]['indentDate']);  
			$indentRecordArray[$i]['indentStatus'] = $indentStatusArray[$indentRecordArray[$i]['indentStatus']];
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$indentRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Indent Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['indentNo']		=    array('Indent No.',' width=20% align="left" ','align="left" ');
    $reportTableHead['indentDate']		=    array('Indent Date',' width="20%" align="center" ','align="center"');
	$reportTableHead['indentStatus']	=    array('Status',' width="15%" align="left" ','align="left"');
	$reportTableHead['totalCount']		=    array('Items Count',' width="10%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>