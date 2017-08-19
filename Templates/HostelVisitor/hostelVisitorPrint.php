<?php 
//This file is used as printing version for payment history.
//
// Author :Gurkeerat Sidhu
// Created on : 20-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
    $hostelVisitorManager = HostelVisitorManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        if(strtolower(trim($search))=='father'){
           $rel=1;
       }
       elseif(strtolower(trim($search))=='mother'){
           $rel=2;
       }
       elseif(strtolower(trim($search))=='sister'){
           $rel=3;
       }
       elseif(strtolower(trim($search))=='brother'){
           $rel=4;
       }
       elseif(strtolower(trim($search))=='others'){
           $rel=5;
       }
       else{
           $rel=-1;
       } 
        $conditions =' WHERE (visitorName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR toVisit LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR address LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"
       OR purpose LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR contactNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR relation LIKE "'.$rel.'%")';          
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visitorName';

	 
    $orderBy="$sortField $sortOrderBy"; 

    $recordArray = $hostelVisitorManager->getHostelVisitorList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $recordArray[$i]['relation']=$hostelVisitorRelArr[$recordArray[$i]['relation']];
        $recordArray[$i]['dateOfVisit']=UtilityManager::formatDate($recordArray[$i]['dateOfVisit']);  
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Hostel Visitor Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				    =	array('#','width=3% align="left"','align="left"' );
    $reportTableHead['visitorName']             =   array('Visitor Name','width=15% align="left"', 'align="left"');
	$reportTableHead['toVisit']			        =	array('To Visit','width=15% align="left"', 'align="left"');
	$reportTableHead['address']			        =	array('Address','width="15%" align="left" ', 'align="left"');
    $reportTableHead['dateOfVisit']             =   array('Date of Visit','width="15%" align="center" ', 'align="center"');
    $reportTableHead['timeOfVisit']             =   array('Time','width="10%" align="center" ', 'align="center"');
    $reportTableHead['purpose']                 =   array('Purpose','width="15%" align="left" ', 'align="left"');
    $reportTableHead['contactNo']               =   array('Contact No.','width="25%" align="right" ', 'align="right"');
    $reportTableHead['relation']                =   array('Relation','width="15%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();


?>