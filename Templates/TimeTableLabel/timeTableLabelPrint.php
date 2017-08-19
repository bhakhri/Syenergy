<?php 
//This file is used as printing version for Time Table Label
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
    $timeTableLabelManager = TimeTableLabelManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    


    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
           $active=1;
       }
       else if(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
           $active=0;
       }
       else{
           $active=-1;
       }
	 /*  if(strtoupper(trim($REQUEST_DATA['searchbox']))=='WEEKLY' ){
           $timeTableType=1;
       }
       else if(strtoupper(trim($REQUEST_DATA['searchbox']))=='DAILY'){
           $timeTableType=2;
       }  */
 
	     $filter .=' AND (labelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttl.isActive LIKE "'.$active.'" OR 
		 IF(ttl.timeTableType=1,"WEEKLY","DAILY")  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.startDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.endDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';   
       
       $search=trim($REQUEST_DATA['searchbox']);
    }


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'labelName';
    
    $orderBy = " $sortField $sortOrderBy"; 


    $recordArray = $timeTableLabelManager->getTimeTableLabelList($filter,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['isActive']==1){
            $recordArray[$i]['isActive']='Yes';
        }
        else if($recordArray[$i]['isActive']==0){
            $recordArray[$i]['isActive']='No';
        }
		//$timeTableLabelRecordArray[$i]['typeOf']==$timeTableLabelRecordArray[$i]['typeOf'];
	/*	if($recordArray[$i]['timeTableType']==WEEKLY_TIMETABLE){
            $recordArray[$i]['timeTableType']='Weekly';
        }
        else if($recordArray[$i]['timeTableType']==DAILY_TIMETABLE){
            $recordArray[$i]['timeTableType']='Daily';
        } */
        $recordArray[$i]['startDate'] =strip_slashes($recordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($recordArray[$i]['startDate']);
        
        $recordArray[$i]['endDate'] = strip_slashes($recordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($recordArray[$i]['endDate']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Time Table Label Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=   array('#','width="2%"', "align='left' ");
    $reportTableHead['labelName']           =   array('Name','width=32% align="left"', 'align="left"');
	$reportTableHead['startDate']		    =   array('From Date','width=20% align="center"', 'align="center"');
	$reportTableHead['endDate']		        =   array('To Date','width="20%" align="center" ', 'align="center"');
    $reportTableHead['isActive']            =   array('Active','width="20%" align="left" ', 'align="left"');
	$reportTableHead['typeOf']       =   array('Type','width="20%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: timeTableLabelPrint.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Templates/TimeTableLabel
//done changes as per FCNS No. 1625
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/07/09   Time: 14:58
//Updated in $/LeapCC/Templates/TimeTableLabel
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/07/09   Time: 13:59
//Created in $/LeapCC/Templates/TimeTableLabel
//Added "print and export to excel" facility for time table label module
//
//*****************  Version 7  *****************
//User: Administrator Date: 12/06/09   Time: 10:55
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug ids---0000046,0000048,0000050
//
//*****************  Version 6  *****************
//User: Administrator Date: 11/06/09   Time: 12:13
//Updated in $/LeapCC/Templates/TestType
//Corrected spelling mistakes
//
//*****************  Version 5  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 12:55
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs -----issues.doc.
//Bug ids-1,2,3
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Templates/TestType
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Created in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print and export to csv
?>