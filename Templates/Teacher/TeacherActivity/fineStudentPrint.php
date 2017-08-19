<?php 
// This file is used as printing version for fine categories.
// Author :Rajeev Aggarwal
// Created on : 03.07.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();

	 function parseName($value){
		
		$name=explode(' ',$value);
	    $genName="";
		$len= count($name);
		if($len > 0){
			
			for($i=0;$i<$len;$i++){
			
			if(trim($name[$i])!=""){
            
				if($genName!=""){
					
					$genName =$genName ." ".$name[$i];
				}
				else{

					$genName =$name[$i];
				} 
			}
		}
    }
    if($genName!=""){

		$genName=" OR CONCAT(TRIM(firstName),' ',TRIM(lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}
	$parsedName=parseName(trim($REQUEST_DATA['searchbox']));    //parse the name for compatibality
       
     //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
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

	  $approvedKey =  array_search(trim(ucfirst(strtolower ($REQUEST_DATA['searchbox']))),$statusCategoryArr);
	   if($approvedKey){

			$approveSearch = " OR status =".$approvedKey;
	   }
       $filter = ' AND ( rollNo LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR fineCategoryAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR firstName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR amount LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR noDues LIKE "'.$inService.'%" OR paid LIKE "'.$inService.'%" OR lastName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"'.$parsedName.' '.$approveSearch.'  )';
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineCategoryName';

    $orderBy=" $sortField $sortOrderBy"; 
	global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');

	$filter .="  AND fs.userId = $userId ";

    $recordArray = $fineCategoryManager->getFineStudentList($filter,'',$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {

		$recordArray[$i]['fineDate'] = UtilityManager::formatDate($recordArray[$i]['fineDate']);
		$recordArray[$i]['status'] = $statusCategoryArr[$recordArray[$i]['status']]; 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student Fine Report');
    //$reportManager->setReportInformation("Search By: $search");
	
	 	 	 	 	 	 	 	  
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="2%"', "align='center' ");
    $reportTableHead['rollNo']			=   array('Roll No.','width=10% align="left"', 'align="left"');
	$reportTableHead['fullName']		=   array('Student Name','width=20% align="left"', 'align="left"');
	$reportTableHead['fineCategoryAbbr']=   array('Fine Category','width=15% align="left"', 'align="left"');
	$reportTableHead['amount']			=   array('Amount','width=4% align="right"', 'align="right"');
	$reportTableHead['fineDate']		=   array('Fine Date','width=6% align="center"', 'align="center"');
	$reportTableHead['noDues']			=   array('Fine Due?','width=8% align="left"', 'align="left"');
	$reportTableHead['paid']			=   array('Is Paid?','width=8% align="left"', 'align="left"');
	$reportTableHead['status']			=   array('Status','width=4% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: fineStudentPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:22p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:29p
//Created in $/LeapCC/Templates/Fine
//Intial checkin for fine student
?>