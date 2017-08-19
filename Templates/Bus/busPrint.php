<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BusManager.inc.php");
    $busManager = BusManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
         $inService=1;  
       }
       elseif(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
         $inService=0;  
       }
      else{
          $inService=-1;
      }
      
       $conditions = ' WHERE (bs.busName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.busNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.modelNumber LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.seatingCapacity LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.yearOfManufacturing LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.isActive LIKE "'.$inService.'%")';
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" bs.$sortField $sortOrderBy"; 


	//$totalArray = $busManager->getTotalBus($conditions);
    $recordArray = $busManager->getBusList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['purchaseDate']!='0000-00-00'){
         $recordArray[$i]['purchaseDate']=UtilityManager::formatDate($recordArray[$i]['purchaseDate']);
        }
        else{
            $recordArray[$i]['purchaseDate']=NOT_APPLICABLE_STRING;
        }
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Bus Report');
    $reportManager->setReportInformation("SearchBy: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', 'align="left"');
    $reportTableHead['busName']             =   array('Bus','width=15% align="left"', 'align="left"');
	$reportTableHead['busNo']               =   array('Registration No.','width=8% align="left"', 'align="left"');
    $reportTableHead['modelNumber']         =   array('Model','width=10% align="left"', 'align="left"');
    $reportTableHead['purchaseDate']        =   array('Purchase Date','width=8% align="center"', 'align="center"');
    $reportTableHead['seatingCapacity']     =   array('Capacity','width=5% align="right"', 'align="right"');
	$reportTableHead['yearOfManufacturing']	=	array('Mfd.Year','width="5%" align="right" ', 'align="right"');
    $reportTableHead['isActive']            =   array('In Service','width="5%" align="center" ', 'align="center"');

	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: busPrint.php $
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-09-05   Time: 12:47p
//Updated in $/LeapCC/Templates/Bus
//increased model number fields width
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/27/09    Time: 2:12p
//Updated in $/LeapCC/Templates/Bus
//Gurkeerat: resolved issue 1295
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/07/09   Time: 10:26
//Updated in $/LeapCC/Templates/Bus
//Done bug fixing.
//bug ids---
//0000551,0000552
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 10/07/09   Time: 17:27
//Updated in $/LeapCC/Templates/Bus
//Done bug fixes.
//bug ids---0000538 to 0000543
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Templates/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/Bus
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:36
//Created in $/LeapCC/Templates/Bus
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:03
//Created in $/Leap/Source/Templates/Bus
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:13
//Created in $/SnS/Templates/Bus
?>