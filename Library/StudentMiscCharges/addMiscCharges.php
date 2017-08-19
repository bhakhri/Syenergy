<?php
//-------------------------------------------------------
// THIS FILE IS USED TO DO STUDENT CONCESSION ENTRIES
// Author : Dipanjan Bhattacharjee
// Created on : (07.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','StudentMiscCharges');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentMiscChargesManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentMiscChargesManager = StudentMiscChargesManager::getInstance();
$studentManager = StudentManager::getInstance();
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

  global $sessionHandler;
    $queryDescription ='';  

//$feeCycleId=trim($REQUEST_DATA['feeCycle']);
$feeHeadId=trim($REQUEST_DATA['feeHead']);
$studentString=trim($REQUEST_DATA['studentString']);
$userId=$sessionHandler->getSessionVariable('UserId');
$feeClassId=trim($REQUEST_DATA['feeClassId']);  


if($studentString=='' or $feeHeadId=='' or $feeClassId==''){
    echo 'Required Parameters Missing';
    die;
}

//first check total fees and percentage wise restrictions
$studentInfoArray=explode('!~!~!',$studentString);
$studentInfoCount=count($studentInfoArray);
if($studentInfoCount==0){
    echo NO_DATA_FOUND;
    die;
}

$deleteString='';
$insertString='';
$dated=date('Y-m-d h:i:s');

 if(SystemDatabaseManager::getInstance()->startTransaction()) {
    for($i=0;$i<$studentInfoCount;$i++){
        $studentTempArray=explode('_',$studentInfoArray[$i]);
        $classId=trim($studentTempArray[0]);   
        $studentId=trim($studentTempArray[1]);
        $charges=trim($studentTempArray[2]);
        
	    //die();
        if($studentId==''){
            echo 'Student Information Missing';
            die;
        }
        if($classId==''){
            echo 'Class Information Missing';
            die;
        }
        if(trim($charges)!=''){
          if(!is_numeric($charges)){
             echo 'Enter numeric value';
             die;
          }
          //if($charges<0){
            // echo "Please enter values greater than or equal to zero";
             //die;
          //}    
        }
        
	     //building delete part
         $deleteCondition =" feeHeadId = $feeHeadId AND classId = $classId AND studentId = $studentId "; 
        
         //building insert part
          $insertString = "($feeHeadId,$classId,$studentId,$charges,$userId,'".$dated."' )";
        
         //then delete previous records
         $ret=$studentMiscChargesManager->deleteStudentMiscCharges($deleteCondition);
         if($ret==false){
             echo FAILURE;
             die;
         }
         $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');

         if(trim($charges)!='') { 
             //now make fresh insert
             $ret=$studentMiscChargesManager->insertStudentMiscCharges($insertString);
             if($ret==false){
                 echo FAILURE;
                 die;
             } $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');

	         }
         $insertString='';
         $deleteString='';
     } //end ot for loop
      //commit transaction
      if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = "Following student misc charges are added: ";
		$auditTrialDescription .= $studentId   ;
		$type = STUDENT_MISC_CHARGES_ADDED; //Fee Head is created
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
        
	 echo SUCCESS;
         die;
      }
      else {
         echo FAILURE;
         die;
      }
 }
 else {
    echo FAILURE;
    die;
 } 
?>
