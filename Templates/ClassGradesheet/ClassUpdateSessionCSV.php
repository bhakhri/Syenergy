 <?php 
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2011 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");   
    $classUpdateManager = ClassUpdateManager::getInstance();
    
    define('MODULE','ClassSessionUpdate');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	
    $batchId = trim($REQUEST_DATA['batchId']);     
    $degreeId = trim($REQUEST_DATA['degreeId']);
    $branchId = trim($REQUEST_DATA['branchId']);
   
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

  
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
 
	//$sortField1= "classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId"; 
    $orderBy = " $sortField $sortOrderBy";
    
   
       
    $foundArray = $classUpdateManager-> getSingleField('class', "WHERE batchId  = $batchId AND degreeId=$degreeId AND branchId=$branchId");
    $cnt = count($foundArray);

    $valueArray = array();

     // Findout Time Table Name
    //$labelArray = $classUpdateManager->getSingleField('reappear_label', 'labelName', "WHERE labelId  = $labelId");
    //$labelName = $labelArray[0]['labelName'];
    
    $csvData ='';
    
    //$csvData .="Label Name,".$labelName;
    //if($batchId!='') {   
    //  $batches =  str_replace(",",", ",$batchId); 
    //  $csvData .="\n";
   //   $csvData .="Batch Year,".parseCSVComments($batches);
  //  }
    $csvData .="\n"; 
    $csvData .="#,Class,Title,Display Order";
    $csvData .="\n";
    for($i=0;$i<$cnt;$i++) {
   
	   $csvData .= ($i+1).",";
	   $csvData .= parseCSVComments($foundArray[$i]['className']).",";
	   $csvData .= parseCSVComments($foundArray[$i]['sessionTitleName']).",";
       $csvData .= parseCSVComments($foundArray[$i]['displayOrder']).",";
       
	   $csvData .= "\n";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'ClassSessionUpdate.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
