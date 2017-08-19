 <?php 
//This file is used as printing version for test type category.
//
// Author :Jaineesh
// Created on : 25.07.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testTypeManager = TestTypeManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
       }
	   else {
		   $type=-1;
	   }

       $filter = ' AND (ttc.testTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ttc.testTypeAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ttc.examType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ttc.showCategory LIKE  "%'.$type.'%" OR ttc.isAttendanceCategory LIKE  "%'.$type.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testTypeName';
    
     $orderBy = " $sortField $sortOrderBy";

	$testtypeRecordArray = $testTypeManager->getTestTypeCategoryList($filter,'',$orderBy); 
    
                           
                            $recordCount = count($testtypeRecordArray);
                            
                            //$designationPrintArray[] =  Array();
                            if($recordCount >0 && is_array($testtypeRecordArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$testtypeRecordArray[$i]);
								
                                }
                            }
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Test Type Category Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="center"', "align='center'");
    $reportTableHead['testTypeName']		=    array('Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['testTypeAbbr']		=    array('Abbr.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['examType']			=    array('Exam Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['subjectTypeName']		=    array('Subject Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['showName']			=    array('Show Status',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['isAttendanceCategory']			=    array('Attendance Category',        ' width="15%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
