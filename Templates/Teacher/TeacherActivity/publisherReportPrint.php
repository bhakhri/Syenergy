<?php 
//This file is used as CSV format for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    define('MODULE','EmployeeInformation');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $publishingManager = EmployeeManager::getInstance();

    /////////////////////////
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');              

     global $publisherScopeArr;
         
      
    // Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       foreach($publisherScopeArr as $key=>$value)
       {
          if(stristr($value,add_slashes($REQUEST_DATA['searchbox']))) {  
           $scopeId = " OR scopeId LIKE '%$key%' ";
           break;
         }
       }      
       $condition = ' AND (p.type LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.publishedBy LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR p.description LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$scopeId.')';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'type';
    
    $orderBy = " $sortField $sortOrderBy";         

    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');                    
    $condition .= ' AND p.employeeId = '.add_slashes($employeeId);
     
    ////////////
    $publishingRecordArray = $publishingManager->getPublishingList($condition,$orderBy);
    $cnt = count($publishingRecordArray);
    
    $reportHead = "";
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];

    for($i=0;$i<$cnt;$i++) {
        if($publishingRecordArray[$i]['publishOn']=='0000-00-00') {
           $publishingRecordArray[$i]['publishOn'] = NOT_APPLICABLE_STRING;
        }
        else {
           $publishingRecordArray[$i]['publishOn'] = UtilityManager::formatDate($publishingRecordArray[$i]['publishOn']);
        }
        if($publishingRecordArray[$i]['scopeId']==0 || $publishingRecordArray[$i]['scopeId']=="") {
          $publishingRecordArray[$i]['scopeId'] = NOT_APPLICABLE_STRING;
        }
        else {
          $publishingRecordArray[$i]['scopeId'] = $publisherScopeArr[$publishingRecordArray[$i]['scopeId']];      
        }
        $valueArray[$i] = array_merge(array('srNo' => ($records+$i+1) ),$publishingRecordArray[$i]);
        $reportHead  = "Employee Name&nbsp;:&nbsp;".$publishingRecordArray[$i]['employeeName'];
        $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$publishingRecordArray[$i]['employeeCode'];
    }  
    $reportHead .= "<br>SearchBy&nbsp;:&nbsp;".$REQUEST_DATA['searchbox'];
 
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Publishing Report ');
	if ($REQUEST_DATA['searchbox'] != '') {
    $reportManager->setReportInformation($reportHead); 
	}
    $reportTableHead                        =    array();
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']             =    array('#',                'width="2%"  align="left"', 'align="left" valign="top"');
    $reportTableHead['type']                =    array('Type',          'width=15% align="left" ','valign="top" align="left" ');
    $reportTableHead['scopeId']             =    array('Scope',         'width=15% align="left" ','valign="top" align="left" ');
    $reportTableHead['publishOn']           =    array('Publish On',    'width="10%" align="center" ','valign="top" align="center"');
    $reportTableHead['publishedBy']         =    array('Published By',  'width="15%" align="left" ','valign="top" align="left"');
    $reportTableHead['description']         =    array('Description',   'width="40%" align="left" ','valign="top" align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: publisherReportPrint.php $
//
//*****************  Version 11  *****************
//User: Parveen      Date: 9/01/09    Time: 5:06p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//search condition updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/21/09    Time: 12:18p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//function added stristr
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/20/09    Time: 1:27p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/19/09    Time: 11:59a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue 1131
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/06/09    Time: 12:57p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//correct name publish on instead of publisher date
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//record limits remove,format & new enhancements added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//report Heading updated (employeeName, employeeCode Show)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 3:23p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//


?>