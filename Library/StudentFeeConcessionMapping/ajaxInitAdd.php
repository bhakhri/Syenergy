<?php
//  This File calls addFunction used in adding Subject Records
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    define('MODULE','StudentFeeConcessionMapping');
    define('ACCESS','add');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 

    require_once(MODEL_PATH . "/StudentFeeConcessionMappingManager.inc.php");
    $studentFeeConcessionManager = StudentFeeConcessionMappingManager::getInstance();  
   
 global $sessionHandler;
$queryDescription =''; 

    $errorMessage ='';
   
   
    if($errorMessage=='') {
        $classId    = $REQUEST_DATA['feeClassId'];
        $studentArray = $REQUEST_DATA['studentId']; 
        $str='';
        
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
            for($i=0;$i<count($studentArray);$i++) {
               $studentId = $studentArray[$i];  
               $categoryArray = $REQUEST_DATA["concessionCategory".$studentId];

               $returnStatus = $studentFeeConcessionManager->deleteFeeStudentCategory($classId,$studentId); 
               if($returnStatus === false) {
                 echo FAILURE;
                 die;
               } 
               $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  

               for($j=0;$j<count($categoryArray);$j++) {
                 $categoryId = $categoryArray[$j];  
                 if($str != '') {
                   $str .=",";   
                 }  
                 $str .= "($classId,$studentId,$categoryId)";     
		$students[] = $studentId;
               }
            }
            
            if($str=='') {
              echo "No Data Update";
              die;
            }
            else {
               $returnStatus = $studentFeeConcessionManager->addFeeStudentCategory($str); 
               if($returnStatus === false) {
                 echo FAILURE;
                 die;
               }
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		$array =  array_unique($students);
		for($i=0;$i<count($array);$i++){
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$auditTrialDescription = "Following student fee Concession mapping has been added: ";
			$auditTrialDescription .= $array[$i];
			$type =STUDENT_FEE_CONCESSION_MAPPING; 
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################		
		}
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                echo SUCCESS;
                die;
            }
            else {
                echo FAILURE;
            }
        }
    }
    else {
       echo $errorMessage;
    } 
   
