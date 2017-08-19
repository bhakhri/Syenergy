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
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
     require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
     $commonQueryManager = CommonQueryManager::getInstance();

    define('MODULE','StudentAdhocConcession');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentAdhocConcessionManager.inc.php");
    $studentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();
         
    
    global $sessionHandler;
    $queryDescription ='';  
    
    $userId = $sessionHandler->getSessionVariable('UserId');        
            
    $studentString=trim($REQUEST_DATA['studentString']);
    $comments= htmlentities(add_slashes(trim($REQUEST_DATA['comments'])));

    if($studentString==''){
       echo 'Required Parameters Missing';
       die;
    }

    $studentInfoArray=explode('!~!~!',$studentString);
    $studentInfoCount=count($studentInfoArray);
    if($studentInfoCount==0){
       echo NO_DATA_FOUND;
       die;
    }

    $deleteString='';
    $insertString='';
    $dated=date('Y-m-d h:i:s');

    $insertDetailString='';
    $netCharges=0;
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        for($i=0;$i<$studentInfoCount;$i++){
            $studentTempArray=explode('_',$studentInfoArray[$i]);
            // $studentId, classId, isVariable, feeId, concessionAmount;  
            $studentId=trim($studentTempArray[0]);
            $classId=trim($studentTempArray[1]);
            $isVariable=trim($studentTempArray[2]);
            $feeId=trim($studentTempArray[3]);
            $charges=trim($studentTempArray[4]);
            
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
              $netCharges +=  $charges;   
            }
            
            if($charges>0) {
              if($insertDetailString!="") {
                $insertDetailString .= ",";  
              }
	          $insertDetailString .= "($studentId,$classId,$feeId,$isVariable,$charges)";            
            }
	} //end ot for loop   
         
        //building delete part
        if($insertDetailString=='') {
           $condition =" feeClassId = $classId AND studentId = $studentId";
           $ret=$studentAdhocConcessionManager->deleteAdhocConcessionDetail($condition);
           if($ret===false){
             echo FAILURE;
             die;
           }  
		 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
  
           $ret=$studentAdhocConcessionManager->deleteAdhocConcessionMaster($condition);
           if($ret===false){
             echo FAILURE;
             die;
           }  
		 $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
        }
        else {  
            $adhocId='';
            $condition =" AND acm.feeClassId = $classId AND acm.studentId = $studentId "; 
            $ret=$studentAdhocConcessionManager->getStudentAdhocConcession($condition);
            if(is_array($ret) && count($ret)>0 ) { 
              $adhocId=$ret[0]['adhocId']; 
            } 
            
            //$table,$date,$studentId,$classId,$userId,$comment,$amount
            if($adhocId=='') {
              $table = 'INSERT';  
              $condition='';
            }
            else {
              $table = 'UPDATE';
              $condition =" WHERE feeClassId = $classId AND studentId = $studentId";    
            }
            
            $ret=$studentAdhocConcessionManager->insertAdhocConcessionMaster($table,$dated,$studentId,$classId,$userId,$comments,$netCharges,$condition);   
            if($ret===false) {
              echo FAILURE;
              die; 
            }
             $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
            $condition =" feeClassId = $classId AND studentId = $studentId";
            $ret=$studentAdhocConcessionManager->deleteAdhocConcessionDetail($condition);
            if($ret===false){
              echo FAILURE;
              die;
            } 
             $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
            $ret=$studentAdhocConcessionManager->insertAdhocConcessionDetail($insertDetailString);   
            if($ret===false) {
               echo FAILURE;
               die; 
            } $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
         }
         
         //commit transaction
         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	  ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = "Following Student Adhoc Concession has been added: ";
		$auditTrialDescription .= $studentId;
		$type = STUDENT_ADHOC_CONCESSION_ADDED; 
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
