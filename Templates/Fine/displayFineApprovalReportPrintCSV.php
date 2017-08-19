 <?php 
//This file is used as printing version for display test type category.
//
// Author :Jaineesh
// Created on : 25.07.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
define('MODULE','COMMON');
define('ACCESS','view');
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
	   return $comments.chr(160); 
	 }
	}

	/// Search filter /////  
	$fineManager = FineManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
	$orderBy = " $sortField $sortOrderBy";

    $condition = '';
    $timeTableLabelId  = trim($REQUEST_DATA['timeTable']); 
    $classId   = trim($REQUEST_DATA['classId']);    
	$status	= $REQUEST_DATA['status'];
	$rollNo	= $REQUEST_DATA['rollNo'];
	$startDate	= $REQUEST_DATA['startDate'];
	$toDate	= $REQUEST_DATA['toDate'];
	$fineCategoryId	= $REQUEST_DATA['fineCategory'];

    
    if($timeTableLabelId=='') {
       $timeTableLabelId=0; 
    }
    
    if($classId=='') {
      $classId=0;  
    }
    
    if($classId!='all') {  
       $condition .= " AND c.classId=".$classId;  
    }
    
    $condition .=" AND ttc.timeTableLabelId = $timeTableLabelId AND fs.status= $status";        
          
	if ($rollNo != "") {
		$condition .= " AND st.rollNo='".$rollNo."'";
	}

	if ($fineCategoryId != "") {
		$condition .= " AND fs.fineCategoryId=".$fineCategoryId."";
	}
    
    $reportHead ='';
	if ($startDate != "" && $toDate != "") {
		$condition .= " AND fs.fineDate BETWEEN '".$startDate."' AND '".$toDate."'";
        $reportHead = "Date,".UtilityManager::formatDate($startDate).",to,".UtilityManager::formatDate($toDate);
	}

	$studentFineArray = $fineManager->getStudentFineList($condition,$orderBy);
    $recordCount = count($studentFineArray);
    $valueArray = array();

    
    $csvData ='';    
    
    // Findout Class Name
    if($timeTableLabelId!='') {
       $classNameArray = $studentManager->getSingleField("time_table_labels", "labelName AS labelName", "WHERE timeTableLabelId  = $timeTableLabelId");
       $labelName = $classNameArray[0]['labelName'];
       $csvData .= "Time Table, ".$labelName."\n";
    }
    
    if($classId!='all') {
       $classNameArray = $studentManager->getSingleField("class", "SUBSTRING_INDEX(className,'-',-3) AS className", "WHERE classId  = $classId");
       $className = $classNameArray[0]['className'];
       $className2 = str_replace("-",' ',$className);
       $csvData .= "Class, ".$className2."\n";
    }
    
    if($rollNo!='') {
       $csvData .= "Roll No., ".$rollNo."\n";
    }
    
    if($reportHead!='') {
     $csvData .= $reportHead."\n";
    }
    
    global $statusCategoryArr;
    $csvData .= "Fine Status, ".$statusCategoryArr[$status]."\n";
    
    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);     
    $csvData .= "As On,".$formattedDate."\n";
    
    $csvData .="Sr No.,Roll No.,Class,Name,Fine Category,Date,Fined By,Reason,Status Reason,Amount";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
      if ($studentFineArray[$i]['employeeName'] == NOT_APPLICABLE_STRING) {
        $studentFineArray[$i]['employeeName'] = 'Admin';
      }
	  $studentFineArray[$i]['fineDate'] = UtilityManager::formatDate($studentFineArray[$i]['fineDate']);
	  $csvData .= ($i+1).",";
	  $csvData .= $studentFineArray[$i]['rollNo'].",";
	  $csvData .= $studentFineArray[$i]['className'].",";
	  $csvData .= $studentFineArray[$i]['studentName'].",";
	  $csvData .= $studentFineArray[$i]['fineCategoryName'].",";
	  $csvData .= $studentFineArray[$i]['fineDate'].",";
	  $csvData .= $studentFineArray[$i]['employeeName'].",";
	  $csvData .= parseCSVComments($studentFineArray[$i]['reason']).",";
	  $csvData .= parseCSVComments($studentFineArray[$i]['statusReason']).",";
	  $csvData .= $studentFineArray[$i]['amount'].",";
	  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'fineApprovalReportPrint.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
