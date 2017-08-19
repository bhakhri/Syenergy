 <?php 
//This file is used as export to excel version for display batch.
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AttendanceDeductManager.inc.php");    
    define('MODULE','AttendanceDeductSlab');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    //used to parse csv data
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

    $attendanceDeduct = AttendanceDeductManager::getInstance();   
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
    $csvData = "AS On, ".$formattedDate;
    
    $valueArray = $attendanceDeduct->getAttendanceDeductList();
   
    
    $csvData ='';
    $csvData = "AS On, ".$formattedDate;   
    $csvData .="\n";
    $csvData .="#,Attendance From,Attendance To,Grade Cut Points";
    $csvData .="\n";
    
    for($i=0;$i<count($valueArray);$i++) {
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($valueArray[$i]['minval']).",";
	  $csvData .= parseCSVComments($valueArray[$i]['maxval']).",";
      $csvData .= parseCSVComments($valueArray[$i]['point']).","; 
	  $csvData .= "\n";
    }
    
    if($i==0) {
      $csvData .=",,No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'AttendanceDeduct.csv');     
    die;
?>