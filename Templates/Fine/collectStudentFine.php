<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Country Print
//
// Author : Saurabh Thukral
// Created on : (13.8.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CollectFine');
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
?>
<?php
	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();

	$rollNo= $REQUEST_DATA['rollNo'];

	/* START: function to fetch student details along with class */
    $studentFeesArray = $fineStudentManager->getStudentDetail("  AND st.rollNo='".trim(add_slashes($rollNo))."'");

    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/studentFineReceiptPrint.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
    
    for($i=0;$i<count($studentFeesArray);$i++) {
      $dataContent = str_replace('<date>',date('d-M-y'),$dataContent); 	  
	  //$dataContent = str_replace('<bankName>',$studentFeesArray[$i]['rollNo'],$dataContent);  
	  //$dataContent = str_replace('<acNo>',$studentFeesArray[$i]['studentName'],$dataContent);  
	  $dataContent = str_replace('<studentName>',$studentFeesArray[$i]['studentName'],$dataContent);  
	  $dataContent = str_replace('<fatherName>',$studentFeesArray[$i]['fatherName'],$dataContent);  
	  $dataContent = str_replace('<studentClass>',$studentFeesArray[$i]['className'],$dataContent);  	  
	  $dataContent = str_replace('<regNo>',$studentFeesArray[$i]['regNo'],$dataContent);  
	  $dataContent = str_replace('<rollNo>',$studentFeesArray[$i]['rollNo'],$dataContent);     
    }
    
	$str='';
	$str.="<table><tr>";
	for($i=0;$i<3;$i++){
		 $str.="<td>$dataContent</td>";
				
	}
    $str.="</tr></table>";
    echo $str;
?>

