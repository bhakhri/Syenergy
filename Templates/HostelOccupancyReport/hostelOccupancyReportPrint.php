<?php
//This file is used as printing version for hostel room.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/HostelManager.inc.php");
	$hostelManager = HostelManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = "$sortField $sortOrderBy"; 
	
	$hostelRoomTypeId = $REQUEST_DATA['hostelRoomType'];
	$conditions = "AND hr.hostelRoomTypeId = $hostelRoomTypeId";

	$hostelRoomArray = $hostelManager->getHostelRoomList($conditions,$orderBy,'',$hostelRoomTypeId);
	//print_r($hostelRoomArray);
	$cnt = count($hostelRoomArray);

	$roomType = $hostelRoomArray[0]['roomType'];
	
	if($cnt >0 && is_array($hostelRoomArray) ) { 
		for($i=0;$i<$cnt;$i++) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$hostelRoomArray[$i]);
		}
	}

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hostel Room Detail Report');
	$reportManager->setReportInformation("Hostel Room Type : $roomType");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					'width="4%"', "align='right' ");
	$reportTableHead['hostelName']			=	array('Hostel Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['roomTotal']			=	array('Nos. of Rooms',	'width="14%" align="right"','align="right"');
	$reportTableHead['capacity']			=	array('Capacity',		'width="8%" align="right"','align="right"');
	$reportTableHead['vacant']			=	array('Vacant',		'width="5%" align="right"','align="right"');
	$reportTableHead['noOfBeds']			=	array('Nos. of Beds',		'width="8%" align="right"','align="right"');
	$reportTableHead['attachedBath']		=	array('Attached Bath',		'width="10%" align="left"','align="left"');
	$reportTableHead['airConditioned']		=	array('AC',		'width="10%" align="left"','align="left"');
	$reportTableHead['internetFacility']	=	array('Internet',		'width="10%" align="left"','align="left"');
	$reportTableHead['noOfFans']			=	array('Fan(s)',		'width="8%" align="right"','align="right"');
	$reportTableHead['noOfLights']			=	array('Light(s)',		'width="8%" align="right"','align="right"');
	$reportTableHead['Fee']					=	array('Fee',		'width="10%" align="right"','align="right"');


	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

////$History: hostelRoomReportPrint.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/18/09    Time: 7:16p
//Updated in $/Leap/Source/Templates/Hostel
//fixed bug during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/22/09    Time: 7:24p
//Updated in $/Leap/Source/Templates/Hostel
//changes to fix bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:19p
//Created in $/Leap/Source/Templates/Hostel
//new print file to show hostel room detail
//
//
?>