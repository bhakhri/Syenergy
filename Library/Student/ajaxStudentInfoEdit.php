<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInfoDetail');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==4){
 UtilityManager::ifStudentNotLoggedIn(true);
}
else{
 UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
        
        require_once(MODEL_PATH . "/StudentManager.inc.php");
        $studentManager = StudentManager::getInstance();
  
        global $sessionHandler;
        $studentId=$sessionHandler->getSessionVariable('StudentId');

        
      /* START: managing user details of student */
		$regularAilment	= $REQUEST_DATA['medicalAttention'];
		$familyAilment	= $REQUEST_DATA['familyAilment'];
		//$regularAilmentNo	= $REQUEST_DATA['regularAilmentNo'];
		$cnt = count($familyAilment);
		if($cnt > 0 AND is_array($familyAilment)) { 
		 $familyAilmentList = implode(",",$familyAilment);
		}
		if($regularAilment == 1) {
			//$regularAilment = $REQUEST_DATA['regularAilmentYes'];
			$natureAilment = add_slashes($REQUEST_DATA['natureAilment']);
			$familyAilment = $familyAilmentList;
			$otherAilment = add_slashes($REQUEST_DATA['otherAilment']);
		}
		else {
			$natureAilment = '';
			$familyAilment = '';
			$otherAilment = '';
		}

        //****************************************************************************************************************    
        //***********************************************STRAT TRANSCATION************************************************
        //****************************************************************************************************************
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
		   $getStudentAilment = $studentManager->getStudentAilment($studentId);
		   if($getStudentAilment[0]['totalRecords'] == 0) {
		     $returnStatus = $studentManager->insertStudentAilment($studentId,$regularAilment,$natureAilment,$familyAilment,$otherAilment);	
		   }
		   else {
		     $returnStatus = $studentManager->updateStudentAilment($regularAilment,$natureAilment,$familyAilment,$otherAilment,$studentId);
		   }
           if($returnStatus === false) {
		     echo FAILURE;
             die;
		   }
           
           $returnStatus = $studentManager->updateStudentInfo($studentId);
           if($returnStatus === false) {
             echo FAILURE;
             die;
           }
           
           /******************Update student payment details***********/
           $ret=$studentManager->deleteStudentPaymentDetails($studentId);
           if($ret==false){
               die(FAILURE);
           }
           //now make insert
           $serverDate=strtotime(date('Y-m-d'));
           
           if($REQUEST_DATA['ddNo']!=''){
            $ddNoArray=$REQUEST_DATA['ddNo'];
            $ddAmtArray=$REQUEST_DATA['ddAmt'];
            $ddBankArray=$REQUEST_DATA['ddBank'];
            $ddDateArray=$REQUEST_DATA['fromDate'];
            if((count($ddNoArray)!=count($ddAmtArray)) and (count($ddAmtArray) and count($ddBankArray)) and (count($ddBankArray)!=count($ddDateArray))){
               die('Discrepency in payment details data');
            }
            $paymentCnt=count($ddNoArray);
            $paymentInsertString='';
             for($x=0;$x<$paymentCnt;$x++){
                $ddNo=trim($ddNoArray[$x]);
                if($ddNo==''){
                    die(ENTER_DD_NO);
                }
                $ddDate=trim($ddDateArray[$x]);
                if($ddDate==''){
                    die(ENTER_DD_DATE);
                }
                if(strtotime($ddDate)>$serverDate){
                    die(DD_DATE_RESTRICTION);
                }
                $ddAmt=trim($ddAmtArray[$x]);
                if($ddAmt==''){
                    die(ENTER_DD_AMT);
                }
                if(!is_numeric($ddAmt)){
                    die(ENTER_DECIMAL_VALUE);
                }
                $ddBank=trim($ddBankArray[$x]);
                if($ddBank==''){
                    die(ENTER_DD_BANK_NAME);
                }
                if($paymentInsertString!=''){
                    $paymentInsertString .=',';
                }
                $paymentInsertString .= "( $studentId,'".add_slashes($ddNo)."','".add_slashes($ddDate)."','".$ddAmt."','".$ddBank."' ) ";
             }
             if($paymentInsertString!=''){
                $ret=$studentManager->insertStudentPaymentDetails($paymentInsertString);
                if($ret==false){
                    die(FAILURE);
                }                 
             }
           }
           
           /******************Update student payment details***********/
           
           
           //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             $errorMessage = SUCCESS;
           }
           else {
             $errorMessage =  FAILURE;
           }   
        }
        
echo  $errorMessage;
die;       