 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");
    $leaveSetMappingManager = LeaveSetMappingManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (TRIM(ls.leaveSetName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lt.leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lsm.leaveValue) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveSetName';
    
    $sortField2=$sortField;    
    if($sortField=='leaveTypeName'){
        $sortField =' LENGTH(leaveTypeName)+0,leaveTypeName';
    }
    $orderBy = " $sortField $sortOrderBy";
    $sortField=$sortField2;

	$leaveSetMappingRecordArray = $leaveSetMappingManager->getLeaveSetMappingList($filter,'',$orderBy);
                               
	$recordCount = count($leaveSetMappingRecordArray);
	
	$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($leaveSetMappingRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';
		   
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$leaveSetMappingRecordArray[$i]);
		
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Leave Set Mapping Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="2%" align="left"', "align='left'");
    $reportTableHead['leaveSetName']		=    array('Leave Set',         ' width=40% align="left" ','align="left" ');
    $reportTableHead['leaveTypeName']		=    array('Leave Type',        ' width="40%" align="left" ','align="left"');
	$reportTableHead['leaveValue']			=    array('Leave Value',        ' width="15%" align="right" ','align="right"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
