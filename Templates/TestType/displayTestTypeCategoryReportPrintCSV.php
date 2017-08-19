 <?php 
//This file is used as printing version for display test type category.
//
// Author :Jaineesh
// Created on : 25.07.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testTypeManager = TestTypeManager::getInstance();
    
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

    $cnt=count($testtypeRecordArray);
    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Name,Abbr.,Exam Type,Subject Type,Show Status,Attendance Category";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $testtypeRecordArray[$i]['testTypeName'].",";
		  $csvData .= $testtypeRecordArray[$i]['testTypeAbbr'].",";
		  $csvData .= $testtypeRecordArray[$i]['examType'].",";
		  $csvData .= $testtypeRecordArray[$i]['subjectTypeName'].",";
		  $csvData .= $testtypeRecordArray[$i]['showName'].",";
		  $csvData .= $testtypeRecordArray[$i]['isAttendanceCategory'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'testTypeCategoryReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
