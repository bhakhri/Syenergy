 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
    $inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='issue/transfer') {
           $type=1;
		}
		elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issuing authority') {
           $type=2;
		}
		elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='end user') {
           $type=3;
		}
		else {
		   $type=-1;
		}

		$filter = ' AND (invd.invDepttName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.invDepttAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.depttType LIKE "'.$type.'" OR emp.employeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'invDepttName';
    
    $orderBy = " $sortField $sortOrderBy";
     
	$inventoryDepartmentArray = $inventoryDeptartmentManager->getInventoryDepartmentList( $filter, $orderBy, $limit);

	$recordCount = count($inventoryDepartmentArray);
	
	if($recordCount >0 && is_array($inventoryDepartmentArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$inventoryDepartmentArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Deptt./Store Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['invDepttName']			=    array('Deptt./Store Name',' width=15% align="left" ','align="left" ');
    $reportTableHead['invDepttAbbr']			=    array('Abbreviation',' width="15%" align="left" ','align="left"');
	$reportTableHead['departmentType']			=    array('Type',' width="15%" align="left" ','align="left"');
    $reportTableHead['employeeName']          =    array('Incharge',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>
