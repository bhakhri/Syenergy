<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Nishu Bindal
// Created on : (7.Feb.212 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");   
$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeadValuesNew');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
global $sessionHandler;
$queryDescription ='';

	$classId  =   $REQUEST_DATA['classId'];
	$editFeeHeadId  =   $REQUEST_DATA['editFeeHeadId']; 
	$feeHeadId  =  $REQUEST_DATA['feeHeadId']; 
	$quotaId  =  $REQUEST_DATA['quotaId']; 
	$isLeet  =  $REQUEST_DATA['isLeet']; 
	$idNos  =   $REQUEST_DATA['idNos']; 
	$totalAmount  =  $REQUEST_DATA['totalAmount']; 
	$batchId = $REQUEST_DATA['batchId'];
	$branchId = $REQUEST_DATA['branchId'];	
	$degreeId = $REQUEST_DATA['degreeId'];	
    $feeCycleId = $REQUEST_DATA['feeCycleId'];
	$sessionId = $sessionHandler->getSessionVariable('SessionId');
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	
    
    $errorMessage ='';

    if(trim($classId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
     if(trim($feeCycleId) == '') {  
      $errorMessage = "Select Fee Cycle";
    }
    
   
    $str = '';
   
    $msg='';
    $totalValues = count($REQUEST_DATA['quotaId']);  
    for($i = 0; $i < $totalValues; $i++) {  
        $quotaId = $REQUEST_DATA['quotaId'][$i];
        $feeHeadId = $REQUEST_DATA['feeHeadId'][$i];
        $isLeet = $REQUEST_DATA['isLeet'][$i];
        $idNo = $REQUEST_DATA['idNos'][$i];
        $c=0; 
        $c1=0;
        $idBoth ='';
        for($j=($i +1); $j<$totalValues; $j++) {   
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==0) {
             $c++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==1) {
             $c++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==2) {
             $c++;
             $idBoth=$REQUEST_DATA['idNos'][$j]; 
           }
           if($c==3) {
             break;  
           }
           
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])==$quotaId && ($REQUEST_DATA['isLeet'][$j]==0 || $REQUEST_DATA['isLeet'][$j]==1) ) {
             $c1++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])==$quotaId && $REQUEST_DATA['isLeet'][$j]==2) {
             $c1++;
             $idBoth=$REQUEST_DATA['idNos'][$j];
           }
           if($c1==2) {
             break;  
           }
        }
        if($c==3 || $c1==2) {
          if($msg!='') {
            $msg .="!~!";  
          }  
          $msg .= $idBoth;
        }
    }
    
    if($msg!='') {
      echo FEE_HEAD_VALUE_NOT_APPLICABLE_TO_BOTH.'!~!'.$msg;
      die;
    }
    
    
    /*
	$recordArray = $feeHeadValuesManager->checkIfFeePaidAlreadyGenerated($classId);
	if($recordArray[0]['cnt'] > 0){
	  $errorMessage = "Fee Head Values Can't be Edited.\nClass Fee Has been Already Generated.";
	}
    */
	
	if (trim($errorMessage) == '') {
		//$recordArray1 = $feeHeadValuesManager->checkInAdhocConcession($classId);
		//if($recordArray1[0]['cnt'] > 0){
	    //	$errorMessage = "Fee Head Values Can't be Edited.\n Adhoc Concession Has been Given.";
		//}
	}
	
	
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()){
          
               $str = '';
               $returnStatus = $feeHeadValuesManager->deleteFeeCylceHeadValue($classId);
               if($returnStatus === false) {
                  $errorMessage = FAILURE; 
                  die;
               } 
               
               $str='';
			    $feeHeadDetails='';
			
					 
			   $generateAmount=0;
               for($i = 0; $i < $totalValues; $i++) {
                  $quotaId = $REQUEST_DATA['quotaId'][$i];
                  $feeHeadId = $REQUEST_DATA['feeHeadId'][$i];
                  $isLeet = $REQUEST_DATA['isLeet'][$i];
                  $feeHeadAmount = $REQUEST_DATA['totalAmount'][$i];  
				  $generateAmount += $feeHeadAmount;
				
				 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){
				 	 
				    $checkfeeHeadName = $feeHeadValuesManager->getFeeHeadName($feeHeadId);
					
					$feeHeadName = $checkfeeHeadName[0]['headName'];
				   		if($feeHeadDetails!=''){
					  		   $feeHeadDetails .="~~";
					  		}
			          	$feeHeadDetails .= $feeHeadId.";".$feeHeadName.";".$feeHeadAmount;						
					
				 }  
                  if(strtolower($quotaId)=='all') {
                    $quotaId = 'null'; 
                  }
				  
				  
                  if($str!='') {
                    $str .=",";  
                  }
                  $str .= "('$classId','$feeHeadId','$quotaId','$isLeet','$feeHeadAmount','$sessionId','$instituteId', '$feeCycleId')";
               }
               
               if($totalValues!=0) {
               		
                  $returnStatus = $feeHeadValuesManager->addFeeCylceHeadValue($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
 	         //Check for Generate Fee Student Table		START		
				 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){
				 	$condition='';
				 $studentFeeTotalDetailArray = $feeHeadValuesManager->getStudentAllFeeCount($classId,$condition);					
					
					for($xx=0;$xx<=count($studentFeeTotalDetailArray);$xx++){
						
						  $ret = explode(',',$studentFeeTotalDetailArray[$xx]);
      					  $ttStudentId = $ret[0];  // Fetch StudentId 
       					  $ttClassId   = $ret[1];  // Fetch Class Id
       					  $ttPeriodValue  = $ret[2];            
				       		
							
							   $checkGenerateStudent = $feeHeadValuesManager->getGenerateStudentFeeValue($ttStudentId,$ttClassId);
 
									 if(count($checkGenerateStudent) >0){		
											
									$strQuery ="periodValue ='$ttPeriodValue',
			 	 								feeHeadDetails ='$feeHeadDetails',
			  									academicFee ='$generateAmount' ";					
					  				 	
										 $updateGenerateStudent = $feeHeadValuesManager->updateGenerateStudentFeeValue($ttStudentId,$ttClassId,$strQuery);	
										 if($updateGenerateStudent===false){		  		
											echo FAILURE;
									  	}
												
									 }else{
									 	$strQuery .=",studentId ='$ttStudentId',
										   			classId ='$ttClassId'";
								$insertGenerateStudent = $feeHeadValuesManager->insertGenerateStudentFeeValue($strQuery);	
								 if($insertGenerateStudent===false){		  		
									echo FAILURE;
							  	}
							 }
					} 	
				 }
					
			 //Check for Generate Fee Student Table		END					
               }

              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		                  $errorMessage = FEE_HEAD_VALUE_ADDED_SUCCESSFULLY;
                 if($totalValues==0) {
                   $errorMessage = FEE_HEAD_VALUE_DELETE_SUCCESSFULLY;  
                 }
                 else if($editFeeHeadId==1) { 
                   $errorMessage = FEE_HEAD_VALUE_UPDATED_SUCCESSFULLY;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
        }
        else {
		echo FAILURE;
		die;
	}
    }
 
    echo $errorMessage;
 ?>
