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
    define('MODULE','AttendanceIncentiveDetails');    
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    $incentiveDetailId = trim($REQUEST_DATA['incentiveDetailId']);
    if($incentiveDetailId==1){
	$weightageFormat=1;
		
	}
	else
		{
		$weightageFormat=2;
		}
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

   $assignStudentIncentive = AssignFinalGradeManager::getInstance();    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
    $csvData = "AS On, ".$formattedDate;
   
    
    $foundArray =   $assignStudentIncentive->getIncentiveDetailListPrint($weightageFormat);
    
    $csvData ='';
    $csvData = "AS On, ".$formattedDate;   
    $csvData .="\n";
	if($weightageFormat==1){
        $csvData .="#,Attendance % From,Attendance % To,Marks Weightage";
    }
    else{
    	 $csvData .="#,Attendance % From,Attendance % To,Discount Amount";
    }
    $csvData .="\n";
    
    for($i=0;$i<count($foundArray);$i++) {
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($foundArray[$i]['attendancePercentageFrom']).",";
          $csvData .= parseCSVComments($foundArray[$i]['attendancePercentageTo']).",";
	  $csvData .= parseCSVComments($foundArray[$i]['weigthage']).",";
      $csvData .= "\n";
    }
    
    if($i==0) {
      $csvData .=",,No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'AssignIncentiveDetail.csv');     
    die;
?>
