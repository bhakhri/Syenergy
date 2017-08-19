 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
    $partyManager = PartyManager::getInstance();

	
    $conditions = ''; 
	$search=$REQUEST_DATA['searchbox'];
   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (partyName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyAddress LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyPhones LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR partyFax LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'partyName';
    
    $orderBy = " $sortField $sortOrderBy";
   
	$partyRecordArray = $partyManager->getPartyList($filter, $orderBy, '');
	$cnt = count($partyRecordArray);
	for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		if($partyRecordArray[$i]['partyAddress'] == ''){
			$partyRecordArray[$i]['partyAddress'] = NOT_APPLICABLE_STRING;
		}
		if($partyRecordArray[$i]['partyPhones'] == ''){
			$partyRecordArray[$i]['partyPhones'] = NOT_APPLICABLE_STRING;
		}
		if($partyRecordArray[$i]['partyFax'] == ''){
			$partyRecordArray[$i]['partyFax'] =  NOT_APPLICABLE_STRING;
		}
	}
	if($cnt >0 && is_array($partyRecordArray) ) { 
		
		for($i=0; $i<$cnt; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$partyRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Supplier Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['partyName']		=    array('Name',' width=12% align="left" ','align="left" ');
    $reportTableHead['partyCode']		=    array('Code',' width="12%" align="left" ','align="left"');
	$reportTableHead['partyAddress']	=    array('Address',' width="20%" align="left" ','align="left"');
    $reportTableHead['partyPhones']		=    array('Phones',' width="15%" align="right" ','align="right"');
    $reportTableHead['partyFax']		=    array('Fax',' width="10%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
