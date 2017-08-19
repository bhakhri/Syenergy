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
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
	

    require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
    $hostelRoomManager = HostelRoomManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions = ' AND (hr.roomName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hs.hostelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hr.roomCapacity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR hrt.roomType LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
    $orderBy = "$sortField $sortOrderBy";
	$hostelRoomArray = $hostelRoomManager->getHostelRoomList($conditions,'',$orderBy); 
    
                        
                            $recordCount = count($hostelRoomArray);
                            
                            $hostelRoomPrintArray[] =  Array();
                            if($recordCount >0 && is_array($hostelRoomArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$hostelRoomArray[$i]);
								
                                }
                            }
                           
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hostel Room Report ');
	if ($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}
	
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['roomName']			=    array('Room Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['hostelName']			=    array('Hostel Name',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['roomCapacity']		=    array('Room Capacity',  ' width="10%" align="right" ','align="right"');
	$reportTableHead['roomType']			=    array('Room Type',  ' width="15%" align="left" ','align="left"');
    $reportTableHead['roomFloor']           =    array('Room Floor',  ' width="15%" align="left" ','align="left"');
	   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
