 <?php 
//This file is used as printing version for display attendance report in parent module.
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

    require_once(MODEL_PATH . "/RoomManager.inc.php");
    $roomManager = RoomManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=="laboratory") {
				   $type="Laboratory";
		   }
		   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=="theory") {
			   $type="Theory";
		   }
        else{
             $type=-1;
		}
       $filter = ' AND (roomName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR capacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomType LIKE "%'.$type.'%" OR blockName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR buildingName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR examCapacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
    $orderBy = " $sortField $sortOrderBy";

	$roomArray = $roomManager->getRoomList($filter,'',$orderBy);
    
                           
                            $recordCount = count($roomArray);
                            
                            $marksPrintArray[] =  Array();
                            if($recordCount >0 && is_array($roomArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                    
                                   
                                    $j=$i+1;
                                    
									$valueArray[] = array_merge(array('srNo' => ($i+1) ),$roomArray[$i]);
                                                                         
                                }
                            }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Room Report');
	if($search!='') {
		$reportManager->setReportInformation("Search by: $search");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['roomName']			=    array('Room Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['roomAbbreviation']	=    array('Abbr.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['roomType']			=    array('Room Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['buildingName']		=    array('Building',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['blockName']			=    array('Block Name',     ' width="15%" align="left" ','align="left"');
	$reportTableHead['capacity']			=    array('Capacity',     ' width="15%" align="right" ','align="right"');
    $reportTableHead['examCapacity']		=    array('Examroom Capacity',        'width="12%" align="right"','align="right"');
    

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
