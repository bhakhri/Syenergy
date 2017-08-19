 <?php 
//This file is used as printing version for resource category.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
    $resourceCategoryManager = ResourceCategoryManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (resourceName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'resourceName';
    
    $orderBy = " $sortField $sortOrderBy";

	$resouceCategoryRecordArray = $resourceCategoryManager->getResourceCategoryList($filter,'',$orderBy);
    
	$recordCount = count($resouceCategoryRecordArray);
                            
	//$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($resouceCategoryRecordArray) ) {
		
		for($i=0; $i<$recordCount; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';
		   
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$resouceCategoryRecordArray[$i]);
		
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Subject Resource Category Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['resourceName']		=    array('Category Name',         ' width=90% align="left" ','align="left" ');
  
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>