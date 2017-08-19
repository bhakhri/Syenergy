<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance(); 

require_once(MODEL_PATH . "/FeeCycleClassesManager.inc.php");
$feeCycleClassesManager = FeeCycleClassesManager::getInstance(); 
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
  
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

    
    $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);    
    $csvData  = "";
    $csvData  = "Fee Cycle,".parseCSVComments($cycleName)."\n";
    $csvData .= "As On,$formattedDate\n";
    $csvData .= "#,Class Name,Mapped To Fee Cycle,Class Status,Fee Cycle Status\n";
    
    $foundArray = $feeCycleClassesManager->getFeeCycleClasses($feeCycleId,'',$orderBy); 
    $cnt = count($foundArray);
    
    for($i=0;$i<$cnt;$i++) {
       $check="No";
       if($foundArray[$i]['feeCycleClassId']!=-1) {
          $check="Yes";
       }
       if($foundArray[$i]['mappedFeeCycle']!=NOT_APPLICABLE_STRING) {
         $str = explode('~',$foundArray[$i]['mappedFeeCycle']);
         $foundArray[$i]['mappedFeeCycle'] = $str[1];
       }
       $csvData .= ($i+1).",".parseCSVComments($foundArray[$i]['className']);
       $csvData .= ",".parseCSVComments($foundArray[$i]['mappedFeeCycle']);
       $csvData .= ",".parseCSVComments($foundArray[$i]['classStatus']).",".parseCSVComments($check);
       $csvData .="\n";
    }
    
    if($i==0) {
      $csvData .=",,No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'FeeCycleClassReport.csv');
    die;    
?>