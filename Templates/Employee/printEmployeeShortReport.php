 <?php 
//This file is used as printing version for display detial of exmployee.
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

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
	
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
   // Search filter /////  
   $search = trim($REQUEST_DATA['searchbox']);
   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   
        if(strtolower(trim($REQUEST_DATA['searchbox']))=='no' || strtolower(trim($REQUEST_DATA['searchbox']))=='n') {
            $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='yes' || strtolower(trim($REQUEST_DATA['searchbox']))=='ye') {
            $type=1;
       }
       else {
            $type=-1;
       }
       
        $filter = ' AND (emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         br.branchCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.contactNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.mobileNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.emailAddress LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         emp.isTeaching LIKE "%'.$type.'%" OR d.abbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )' ;
	   
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = "$sortField $sortOrderBy"; 

	$employeeArray = $employeeManager->getShortEmployeeList($filter,'',$orderBy);
	
                           
                            $recordCount = count($employeeArray);
                            
                            $marksPrintArray[] =  Array();
                            if($recordCount >0 && is_array($employeeArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                    
                                   
                                    $j=$i+1;
                                    
                                    /*if($roomArray[$i]['examCapacity'] is NULL){
										$capacity = "NULL";
									}                                    
									else {
										$capacity = $roomArray[$i]['examCapacity'];
									}*/
                                   /* $marksPrintArray[$i]['srNo']   =$j;
                                    $marksPrintArray[$i]['employeeName']=$employeeArray[$i]['employeeName'];
                                    $marksPrintArray[$i]['employeeCode']=$employeeArray[$i]['employeeCode'];  
									$marksPrintArray[$i]['isTeaching']=$employeeArray[$i]['isTeaching'];  
									$marksPrintArray[$i]['branchCode']=$employeeArray[$i]['branchCode'];
                                    $marksPrintArray[$i]['contactNumber']=$employeeArray[$i]['contactNumber'];  
									$marksPrintArray[$i]['emailAddress']=$employeeArray[$i]['emailAddress'];*/

									$valueArray[] = array_merge(array('srNo' => ($i+1) ),$employeeArray[$i]);
                                                                         
                                }
                            }
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Employee Report (Guest Faculty)');
	if ($search != '') {
		$reportManager->setReportInformation("SearchBy: $search");
	}
 
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['employeeName']		=    array('Name ',         ' width=20% align="left" ','align="left" ');
    $reportTableHead['employeeCode']		=    array('Emp. Code',        ' width="12%" align="left" ','align="left"');
    //$reportTableHead['employeeAbbreviation']  =    array('Abbr.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['isTeaching']			=    array('Teaching',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['departmentAbbr']		=    array('Department',     ' width="12%" align="left" ','align="left"');
    $reportTableHead['contactNumber']		=    array('Contact No.',        'width="15%" align="left"','align="left"');
	$reportTableHead['mobileNumber']		=    array('Mobile No.',        'width="15%" align="left"','align="left"');
	$reportTableHead['emailAddress']		=    array('Email',        'width="15%" align="left"','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
