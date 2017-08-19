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
	

    require_once(MODEL_PATH . "/HostelRoomTypeManager.inc.php");
    $hostelRoomTypeManager = HostelRoomTypeManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (roomType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomType';
    
    $orderBy = "$sortField $sortOrderBy";

	$hostelRoomTypeArray = $hostelRoomTypeManager->getHostelRoomTypeList($filter,'',$orderBy); 
    
                        
                            $recordCount = count($hostelRoomTypeArray);
                            
                            $hostelRoomPrintArray[] =  Array();
                            if($recordCount >0 && is_array($hostelRoomTypeArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$hostelRoomTypeArray[$i]);
								
                                }
                            }
                           
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hostel Room Type Report ');
	if ($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}
	
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['roomType']			=    array('Hostel Room Type',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['roomAbbr']			=    array('Abbr.',        ' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
