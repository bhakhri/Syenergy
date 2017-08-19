<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php                
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance(); 
    
require_once(BL_PATH . '/ReportManager.inc.php');
$reportManager = ReportManager::getInstance();


require_once(MODEL_PATH . "/FeeCycleClassesManager.inc.php");
$feeCycleClassesManager = FeeCycleClassesManager::getInstance(); 

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
    $feeCycleId = trim($REQUEST_DATA['feeCycleId']);
     
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
    
    $feeCycleArray = $studentManager->getSingleField('`fee_cycle`', 'cycleName, cycleAbbr', "WHERE feeCycleId  = $feeCycleId");
    $cycleName = $feeCycleArray[0]['cycleName'];
    
    if($cycleName=='') {
      $cycleName = NOT_APPLICABLE_STRING;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $sortField1= "classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId"; 
    $orderBy = " $sortField1 $sortOrderBy";
    
    //$totalArray = $smsdetailManager->getTotalSMSFullDetailList($filter);  
    $foundArray = $feeCycleClassesManager->getFeeCycleClasses($feeCycleId,'',$orderBy); 
    $cnt = count($foundArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $foundArray[$i]['feeCycleActive']="No";
       if($foundArray[$i]['feeCycleClassId']!=-1) {
          $foundArray[$i]['feeCycleActive']="Yes";
       }         
       if($foundArray[$i]['mappedFeeCycle']!=NOT_APPLICABLE_STRING) {
         $str = explode('~',$foundArray[$i]['mappedFeeCycle']);
         $foundArray[$i]['mappedFeeCycle'] = $str[1];
       }
       $valueArray[] = array_merge(array( 'srNo' => ($records+$i+1) ),$foundArray[$i]); 
    }
    
    $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);    
    $search  = "Fee Cycle:&nbsp;$cycleName<br>As On $formattedDate";

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Cycle Class Report');
    $reportManager->setReportInformation($search);        
    
    $reportTableHead                    =    array();
                //associated key          col.label,                   col. width,      data align
    $reportTableHead['srNo']            = array('#',                   'width="2%"  align="left"', 'align="left"');
    $reportTableHead['className']       = array('Class Name',          'width="25%" align="left"', 'align="left" style="padding-right:5px"');
    $reportTableHead['mappedFeeCycle']  = array('Mapped To Fee Cycle', 'width="25%" align="left"', 'align="left" style="padding-right:5px"');
    $reportTableHead['classStatus']     = array('Class Status',        'width="15%" align="left"', 'align="left" style="padding-right:5px"');
    $reportTableHead['feeCycleActive']  = array('Fee Cycle Status',    'width=10%   align="center"', 'align="center" style="padding-left:5px"');
    

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

?>
