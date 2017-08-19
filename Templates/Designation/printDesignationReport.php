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

    require_once(MODEL_PATH . "/DesignationManager.inc.php");
    $designationManager = DesignationManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (designationName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR designationCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR employeeCount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'designationName';
    
    $orderBy = " $sortField $sortOrderBy";

	$designationArray = $designationManager->getDesignationList($filter,'',$orderBy); 
    
                           
                            $recordCount = count($designationArray);
                            
                            $designationPrintArray[] =  Array();
                            if($recordCount >0 && is_array($designationArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$designationArray[$i]);
								
                                }
                            }
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Designation Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="center"', "align='center'");
    $reportTableHead['designationName']		=    array('Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['designationCode']		=    array('Designation Code',        ' width="15%" align="left" ','align="left"');
    $reportTableHead['employeeCount']       =    array('Employees',        ' width="15%" align="right" ','align="right"');
    $reportTableHead['description']         =    array('Description',        ' width="15%" align="left" ','align="left"');  
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
