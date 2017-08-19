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

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
	
	$roleId = $REQUEST_DATA['id'];
	
	if ($roleId != '') {
		$roleArray = $studentReportManager->getRoleName($roleId);
	}
	if (is_array($roleArray) && count($roleArray) >0) {
		$roleName = $roleArray[0]['roleName'];
	}

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    
    $orderBy = " $sortField $sortOrderBy";

	$roleWiseUserArray = $studentReportManager->getUserData('',$orderBy,$roleId); 

	                       
                            $recordCount = count($roleWiseUserArray);
                            
                            $roleWisePrintArray[] =  Array();
                            if($recordCount >0 && is_array($roleWiseUserArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$roleWiseUserArray[$i]);
								
                                }
                            }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Role Wise User Report');
	$reportManager->setReportInformation("Role Name : $roleName");
    
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['userName']		=    array('User Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['name']		=    array('Name',        ' width="15%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
