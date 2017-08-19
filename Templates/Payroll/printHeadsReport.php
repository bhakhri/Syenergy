 <?php 
//This file is used as printing salary heads report.
//
// Author :Abhiraj
// Created on : 12-April-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Payroll');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
     $orderBy = " $sortField $sortOrderBy";
     
	$headsArray = $payrollManager->getHeadList($filter,'',$orderBy);

		$recordCount = count($headsArray);
		
		$headPrintArray[] =  Array();
		if($recordCount >0 && is_array($headsArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
                $dedAccountId=$headsArray[$i]['dedAccountId'];
                $dedAccount=$payrollManager->getDedAccount('where dedAccountId='.$dedAccountId);
                if($dedAccount[0]['accountName']=="")
                {
                    $dedAccountArray=array('deductionAccountName'=>'--');
                }
                else
                {
                    $dedAccountArray=array('deductionAccountName'=>$dedAccount[0]['accountName'].'('.$dedAccount[0]['accountNumber'].')');
                }
                if($headsArray[$i]['headDesc']=="")
                {
                  $headsArray[$i]=array_merge($headsArray[$i],array('headDesc'=>'--'));  
                }
                if($headsArray[$i]['headType']==1)
                {
                  $headsArray[$i]=array_merge($headsArray[$i],array('headType'=>'Deduction'));  
                }
                else
                {
                  $headsArray[$i]=array_merge($headsArray[$i],array('headType'=>'Earning'));  
                }
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$headsArray[$i],$dedAccountArray);
			
			}
		}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Salary Heads Report ');
	if($search != '') {
		$reportManager->setReportInformation("Search By : $search");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['headName']			=    array('Head Name ',' width=20% align="left" ','align="left" ');
    $reportTableHead['headAbbr']            =    array('Head Abbr.',' width=10% align="left" ','align="left" ');
    $reportTableHead['headType']            =    array('Head Type',' width="20%" align="left" ','align="left"');
    $reportTableHead['deductionAccountName']=    array('Deduction Account',' width="30%" align="left" ','align="left"');
    $reportTableHead['headDesc']=    array('Description',' width="40%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>