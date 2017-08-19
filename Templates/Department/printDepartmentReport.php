 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/DepartmentManager.inc.php");
    $departmentManager = DepartmentManager::getInstance();
	
	/// Search filter /////  
    $search = $REQUEST_DATA['searchbox'];
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (departmentName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR employeeCount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'departmentName';
    
    $orderBy = " $sortField $sortOrderBy"; 

	$recordArray = $departmentManager->getDepartmentList($filter,'',$orderBy); 
	$cnt = count($recordArray);
				
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
     }
     
     //print_r($recordArray);
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Department Report');
    $reportManager->setReportInformation("Search By: $search");

    $reportTableHead                        =    array();
    $reportTableHead['srNo']				=    array('#', 'width="4%" align="left"', "align='left'");
    $reportTableHead['departmentName']		=    array('Department Name ',  ' width=30% align="left" ','align="left" ');
    $reportTableHead['employeeCount']       =    array('Employees ', ' width=10% align="right" ','align="right" '); 
    $reportTableHead['abbr']				=    array('Abbr.', ' width="25%" align="left" ','align="left"');
    $reportTableHead['description']         =    array('Description',' width="55%" align="left" ','align="left"');
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
