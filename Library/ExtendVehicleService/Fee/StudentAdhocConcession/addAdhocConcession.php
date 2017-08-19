<?php
//-------------------------------------------------------
// THIS FILE IS USED TO DO STUDENT CONCESSION ENTRIES
// Author : Nishu Bindal
// Created on : (9.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','StudentAdhocConcessionNew');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/Fee/StudentAdhocConcessionManager.inc.php");
$studentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();


	global $sessionHandler;
	$queryDescription ='';  
        $error='';
	$userId = $sessionHandler->getSessionVariable('UserId');        
	
	$comments = htmlentities(add_slashes(trim($REQUEST_DATA['comments'])));
	if($comments == ''){
		$error = "Reason is Required Field.\n";
	}
	$studentId = trim($REQUEST_DATA['studentId']);
	if($studentId == ''){
		$error ="Required Parameter is missing.\n";
	}
	$classId = trim($REQUEST_DATA['classId']);
	$currentClassId = trim($REQUEST_DATA['currentClassId']);
	if($classId == '' && $currentClassId == ''){
		$error ="Required Parameter is missing.\n";
	}
	$discount = trim($REQUEST_DATA['concession']);
	$sessionId = $sessionHandler->getSessionVariable('SessionId');
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	/* check if student has paid fees
	$dataArray = $studentAdhocConcessionManager->checkIfAlreadyPaid($studentId,$classId,$currentClassId);
	if($dataArray[0]['cnt'] > 0){
		$error .="Student Has Already Paid Fee, Concession Can't be given/edited."; 
	}
*/
	if($error == ''){
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$condition =" WHERE feeClassId = $classId AND studentId = $studentId";  
			$ret = $studentAdhocConcessionManager->deleteAdhocConcessionMaster($condition);
			if($ret === false){
				echo FAILURE;
				die;
			} 
			$materResponse = $studentAdhocConcessionManager->insertAdhocConcessionMaster($studentId,$classId,$userId,$comments,$discount);
			if($materResponse === false){
				echo FAILURE;
				die;
			}
			
			// to check record exists in Fee Receipt Master if exists then update concession
			$feeReceiptDataArr = $studentAdhocConcessionManager->updateFeeReceiptMaster($studentId,$classId,$userId,$discount,$currentClassId);
			if($materResponse === false){
				echo FAILURE;
				die;
			}
		//Check for Generate Fee Student Table		start		
		 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){	
			  $checkGenerateStudent = $studentAdhocConcessionManager->getGenerateStudentFeeValue($studentId,$classId);
 					$strQuery ="concession ='$discount'";	
			 if(count($checkGenerateStudent) >0){									
								
				 $updateGenerateStudent = $studentAdhocConcessionManager->updateGenerateStudentFeeValue($studentId,$classId,$strQuery);	
				 if($updateGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			
			 }else{
				 	$strQuery .=",studentId ='$studentId',
								classId ='$classId'";
				$insertGenerateStudent = $studentAdhocConcessionManager->insertGenerateStudentFeeValue($strQuery);	
				 if($insertGenerateStudent===false){		  		
					echo FAILURE;
			  	}
			 }
		 }
		 //Check for Generate Fee Student Table		END
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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
	}
	else{
		echo $error;
		die;
	}
?>
