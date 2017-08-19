<?php
//--------------------------------------------------------
//This file returns the array of of Percentage Wise attendance report
// Author :Aditi Miglani
// Created on : 8-Nov-2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);

    require_once(MODEL_PATH . "/StreamWiseAttendanceManager.inc.php");
    $streamWiseAttendanceManager = StreamWiseAttendanceManager::getInstance();
  
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    global $sessionHandler;

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    
    //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments; 
       }
    }

    $labelId = $REQUEST_DATA['labelId'];
    $fromDate = $REQUEST_DATA['fromDate'];  
    $toDate = $REQUEST_DATA['toDate'];  


    //Fetch Report Header Details
    $timeTableNameArray =  $studentManager->getSingleField('time_table_labels','labelName',"WHERE timeTableLabelId = $labelId");
    $labelName = $timeTableNameArray[0]['labelName'];
      
    $headArray = $sessionHandler->getSessionVariable('IdToStreamStudentHeader');
    $valueArray = array_merge($sessionHandler->getSessionVariable('IdToStreamStudentData'));


    $csvData = "For Time Table,".$labelName;
    $csvData .= "\n";
    $csvData .= "#";
    for($i=1;$i<count($headArray);$i++) {
       $headLabel = $headArray[$i]['headLabel'];
       $headName = $headArray[$i]['headName'];
       $csvData .=",$headLabel";
    }
    $csvData .= "\n";
    
    if(count($valueArray) > 0) {
        for($i=0;$i<count($valueArray);$i++) {
          $csvData .= $valueArray[$i]['srNo'];
          for($j=1;$j<count($headArray);$j++) {
            $headName = $headArray[$j]['headName'];
            $csvData .= ",".parseCSVComments($valueArray[$i][$headName]);
          }
          $csvData .= "\n"; 
        }
    }
    else {
       $csvData .= ",,No Data Found\n";  
    }
  
    UtilityManager::makeCSV($csvData,'StreamWiseAttendanceReport.csv');         
?>    
 
