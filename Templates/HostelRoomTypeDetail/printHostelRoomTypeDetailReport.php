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
	

    require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");
    $hostelRoomTypeDetailManager = HostelRoomTypeDetailManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
       }
	   else {
		   $type=-1;
	   }
       

       $filter = ' AND (h.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrt.roomType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.Capacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfBeds LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.attachedBath LIKE "%'.$type.'%" OR hrtd.airConditioned LIKE "%'.$type.'%" OR hrtd.internetFacility LIKE "%'.$type.'%" OR hrtd.noOfFans LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hrtd.noOfLights LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
    $orderBy = "$sortField $sortOrderBy";

	$hostelRoomTypeDetailArray = $hostelRoomTypeDetailManager->getHostelRoomTypeDetailList($filter,'',$orderBy); 
                        
                            $recordCount = count($hostelRoomTypeDetailArray);
                            
                            $hostelRoomPrintArray[] =  Array();
                            if($recordCount >0 && is_array($hostelRoomTypeDetailArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$hostelRoomTypeDetailArray[$i]);
								
                                }
                            }
                           
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hostel Room Type Detail Report ');
	if ($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}
	
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['hostelName']			=    array('Hostel Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['roomType']			=    array('Hostel Room Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['Capacity']			=    array('Capacity',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['noOfBeds']			=    array('No. of Beds',        ' width="10%" align="right" ','align="right"');
	$reportTableHead['attachedBath']		=    array('Attached Bathroom',        ' width="5%" align="left" ','align="left"');
	$reportTableHead['airConditioned']		=    array('Air Conditioned',        ' width="5%" align="left" ','align="left"');
	$reportTableHead['internetFacility']	=    array('Internet Facility',        ' width="5%" align="left" ','align="left"');
	$reportTableHead['noOfFans']			=    array('No. of Fans',        ' width="10%" align="right" ','align="right"');
	$reportTableHead['noOfLights']			=    array('No. of Lights',        ' width="10%" align="right" ','align="right"');
	

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
