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
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    $quotaManager = QuotaManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        /// Search filter /////  
       $conditions = ' WHERE (qt.quotaName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR qt.quotaAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR p.quotaName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'quotaName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


	$totalArray = $quotaManager->getTotalQuota($conditions);
    $recordArray = $quotaManager->getQuotaList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        if(trim($recordArray[$i]['parentQuota'])==''){
            $recordArray[$i]['parentQuota']=NOT_APPLICABLE_STRING;
        }
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Quota Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['quotaName']            =   array('Quota Name','width=35% align="left"', 'align="left"');
	$reportTableHead['quotaAbbr']			=	array('Quota Abbr.','width=35% align="left"', 'align="left"');
	$reportTableHead['parentQuota']			=	array('Parent Quota','width="25%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: quotaPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/06/09   Time: 15:24
//Updated in $/LeapCC/Templates/Quota
//Done bug fixing.
//bug ids---00000113,00000114,00000115,00000141,00000142,
//00000143,00000144,00000146,00000147
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Quota
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:17p
//Updated in $/Leap/Source/Templates/Quota
//modified search criteria
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:11p
//Created in $/Leap/Source/Templates/Quota
//Added functionality for quota report print
?>