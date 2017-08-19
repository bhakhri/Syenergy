<?php 
//  This File calls addFunction used in adding FeeHead Records
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','GenerateStudentFees');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);           
UtilityManager::headerNoCache(); 


global $sessionHandler;
$queryDescription =''; 
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$userId = $sessionHandler->getSessionVariable('UserId');
    $errorMessage ='';

	
	if(!isset($REQUEST_DATA['degreeId']) || trim($REQUEST_DATA['degreeId']) == '') {
		$errorMessage .= SELECT_DEGREE."\n";
	}
	if($errorMessage == '' && (!isset($REQUEST_DATA['branchId']) || trim($REQUEST_DATA['branchId']) == '')) {
		$errorMessage .= SELECT_BRANCH."\n";
	}
	if($errorMessage == '' && (!isset($REQUEST_DATA['batchId']) || trim($REQUEST_DATA['batchId']) == '')) {
		$errorMessage .= SELECT_BATCH."\n";
	}
	if(!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '') {
		$errorMessage .= SELECT_CLASS."\n";
	}
	if(!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
		$errorMessage .= SELECT_FEE_CYCLE."\n";
	}
	
	$feeClassId  = trim($REQUEST_DATA['classId']); 
	$feeCycleId  = trim($REQUEST_DATA['feeCycleId']);
	
	if(trim($errorMessage) == ''){
		require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
		$generateFeeManager = GenerateFeeManager::getInstance(); 
		// to fetch Current class of student
		$classArray = $generateFeeManager->getClass($feeClassId);
		if(count($classArray) == 0){
			echo "Class Not Found";
			die;
		}
		$classes = '';
		foreach($classArray as $key=> $value){
			if($classes == ''){
				$classes = $value['classId'];
			}
			else{
				$classes .= ",".$value['classId'];
			}
		} 
		//$currentClass = $classArray[0]['currentClassId'];
		$feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
		// check to see if student has generated fee receipt
		$dataArray = $generateFeeManager->checkForReceiptGenerated($feeClassId,$classes,$feeCycleId);
		if($dataArray[0]['cnt'] > 0){
			$errorMessage .= "Some students have Generated Fee.\nStudents Fee Can't be Deleted.";
		}
		
		$dataArray = $generateFeeManager->checkForPaidFee($feeClassId,$classes,$feeCycleId);
		if($dataArray[0]['cnt'] > 0){
			$errorMessage .= "Some students have Paid Fees.\nStudents Fee Can't be Deleted.";
		}
	}
			 
	
	if(trim($errorMessage) == ''){
		if(SystemDatabaseManager::getInstance()->startTransaction()){
			
			$deleteStatus = $generateFeeManager->deleteClassFee($feeClassId,$classes,$feeCycleId);
			if($deleteStatus == FALSE){
				echo FALIURE;
				die;
			}
			
		
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DELETE;
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
		echo $errorMessage;
	}
	
	
 
?>
