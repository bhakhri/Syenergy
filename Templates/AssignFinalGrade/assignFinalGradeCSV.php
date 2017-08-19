 <?php 
//This file is used as export to excel version for display batch.
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");     
    define('MODULE','AssignFinalGrade');    
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

    $assignFinalGrade = AssignFinalGradeManager::getInstance();   
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
    $csvData = "AS On, ".$formattedDate;
    
    $valueArray = $assignFinalGrade->getAssignFinalGradeList();  
   
    
    $csvData ='';
    $csvData = "AS On, ".$formattedDate;   
    $csvData .="\n";
    $csvData .="#,Grade Point From,Grade Point To,Grade,Grade Points";
    $csvData .="\n";
    
    for($i=0;$i<count($valueArray);$i++) {
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($valueArray[$i]['minval']).",";
	  $csvData .= parseCSVComments($valueArray[$i]['maxval']).",";
      $csvData .= parseCSVComments($valueArray[$i]['grade']).",";
      $csvData .= parseCSVComments($valueArray[$i]['point']).","; 
	  $csvData .= "\n";
    }
    
    if($i==0) {
      $csvData .=",,No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'AssignFinalGrade.csv');     
    die;
?>