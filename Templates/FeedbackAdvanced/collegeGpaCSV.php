<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

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
global $sessionHandler;
  $recordArray=array();
  $csvData = '';
	
    $csvData.=parseCSVComments("Time Table").",".parseCSVComments(trim($REQUEST_DATA['timeTableName']))."\nLabel,".parseCSVComments(trim($REQUEST_DATA['labelName']));
  $csvData.="\n";
    	 
    	 
    	 $optionRecordArray = $sessionHandler->getSessionVariable('IdToFeedbackReportOption');
         $valueArray   = $sessionHandler->getSessionVariable('IdToFeedbackReportData');
         
	//$csvData = '';
        $csvData .= "#,Question";
        $csvData1=',,';
        for($i=0;$i<count($optionRecordArray);$i++) {
           $optText = $optionRecordArray[$i]['optionLabel'];
	   $csvData  .= ",".parseCSVComments(trim($optText));           
	   if($i!=0) {
	    $csvData1 .= ",";
	   }
        }   
        $csvData .= ",Weightage Avg.,Response,GPA";
        $csvData .= "\n";
        
        for($i=0;$i<count($valueArray);$i++) {
          if(($i+1)==count($valueArray)) {
            $csvData .= $csvData1;
          }
          else {
	    $csvData .= ($i+1);
	    $csvData .= ",".parseCSVComments($valueArray[$i]['questionName']);
	    for($ii=0;$ii<count($optionRecordArray);$ii++) {
	      $optText = $optionRecordArray[$ii]['optionLabel'];
	      $csvData .= ",".parseCSVComments(trim($optText));           
	    }   
	  }
                
          $csvData .= ",".parseCSVComments($valueArray[$i]['weightedAvg']);       
          $csvData .= ",".parseCSVComments($valueArray[$i]['response']);    
          $csvData .= ",".parseCSVComments($valueArray[$i]['gpa']);         
          $csvData .= "\n";         
        }	

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="collegeGpa.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

?>
