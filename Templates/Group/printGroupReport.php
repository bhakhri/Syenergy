 <?php 
//This file is used as printing version for group.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/GroupManager.inc.php");
	$groupManager = GroupManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.groupName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.groupShort LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gt.groupTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cl.className LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
     $orderBy = " $sortField $sortOrderBy";

	$groupRecordArray = $groupManager->getGroupList($filter,'',$orderBy);
    
	$recordCount = count($groupRecordArray);
                            
                            //$designationPrintArray[] =  Array();
                            if($recordCount >0 && is_array($groupRecordArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    if($groupRecordArray[$i]['parentGroup']==''){
                                       $groupRecordArray[$i]['parentGroup']=NOT_APPLICABLE_STRING;
                                    }
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$groupRecordArray[$i]);
								
                                }
                            }
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Group Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['groupName']			=    array('Group Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['groupShort']			=    array('Short Name',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['parentGroup']			=    array('Parent Group',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['groupTypeName']		=    array('Group Type',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['className']			=    array('Class',        ' width="15%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
