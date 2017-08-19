<?php 
// This File calls addFunction used in Fee Paymnt Report
//author: harpreet
// Created on : 2-Feb-2013
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeDetailHistoryReport');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);           
    UtilityManager::headerNoCache(); 
   
  
	 require_once(MODEL_PATH . "/Fee/StudentGenerateFeeManager.inc.php");
    $studentGenerateFeeManager = StudentGenerateFeeManager::getInstance();
     
    
    // Total Fee Classes
    
     $studentFeeClassesArray = $studentGenerateFeeManager->showAllFeeClasses($condition);
  	$classId='';
   for($xx=0;$xx<count($studentFeeClassesArray);$xx++){
	   	if($classId!=''){
	   		$classId .=",";
	   	}
	 $classId .=$studentFeeClassesArray[$xx]['feeClassId'];
		
   }  
  	
    $studentFeeDetailArray = $studentGenerateFeeManager->getStudentAllFeeCount($feeType,$classId,$condition);


if(SystemDatabaseManager::getInstance()->startTransaction()) {  	     		
		
	for($y=0;$y<=count($studentFeeDetailArray);$y++) {
        $ret = explode(',',$studentFeeDetailArray[$y]);
        $ttStudentId = $ret[0];  // Fetch StudentId 
        $ttClassId   = $ret[1];  // Fetch Class Id
        $ttPeriodValue  = $ret[2];            
       
		   if($ttStudentId==''){
		   	continue;
			
		   }
		   if($ttClassId==''){
		   	continue;		
		   }
		    if($ttPeriodValue==''){
		   	continue;		
		   }
		
			$academicHeadFeeDetailsArray = $studentGenerateFeeManager->getAcademicHeadFeeDetails($ttStudentId,$ttClassId,$condition);
			
			$academicHeadFeeArray = $studentGenerateFeeManager->getTotalAcademicHeadFee($ttStudentId,$ttClassId,$condition);
			
			$academicConcessionArray = $studentGenerateFeeManager->getTotalAcademicConcessionFee($ttStudentId,$ttClassId,$condition);
			
			$academicLedgerArray = $studentGenerateFeeManager->getTotalAcademicLedgerFee($ttStudentId,$ttClassId,$condition);
			
        $valueAcademicArray = '';
		$feeHeadDetails='';

		  for($i=0;$i<count($academicHeadFeeDetailsArray);$i++) {
		  		if($feeHeadDetails!=''){
		  		   $feeHeadDetails .="~~";
		  		}
          	$feeHeadDetails .= $academicHeadFeeDetailsArray[$i]['feeHeadId'].";".$academicHeadFeeDetailsArray[$i]['feeHeadName'].";".$academicHeadFeeDetailsArray[$i]['amount'];
			
	        }
			  for($i=0;$i<count($academicHeadFeeArray);$i++) {
          		$valueAcademicArray[] = $academicHeadFeeArray[$i]['classId'];//1
				$valueAcademicArray[] = $academicHeadFeeArray[$i]['quotaId'];//2
				$valueAcademicArray[] = $academicHeadFeeArray[$i]['academicFees'];//3
	        }
	        for($i=0;$i<count($academicConcessionArray);$i++) {
	        		         	
				$valueAcademicArray[] = $academicConcessionArray[$i]['concession'];//4
	        }
			for($i=0;$i<count($academicLedgerArray);$i++) {
	         	$valueAcademicArray[] = $academicLedgerArray[$i]['acdDebit'];//5
				$valueAcademicArray[] = $academicLedgerArray[$i]['acdCredit'];//6
				$valueAcademicArray[] = $academicLedgerArray[$i]['fineDebit'];//7
				$valueAcademicArray[] = $academicLedgerArray[$i]['fineCredit'];//8
	        }
			
		//Academic Fee Data End
		
		//Hostel Fee data Start	
        $valueHostelArray = '';
        
			 $hostelHeadFeeArray = $studentGenerateFeeManager->getTotalHostelHeadFee($ttStudentId,$ttClassId,$condition);			
			
			$hostelLedgerArray = $studentGenerateFeeManager->getTotalHostelLedgerFee($ttStudentId,$ttClassId,$condition);
			
			  for($i=0;$i<count($hostelHeadFeeArray);$i++) {
          		$valueHostelArray[] = $hostelHeadFeeArray[$i]['studentId'];//1
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['classId'];//2
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelFees'];//3
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelSecurity'];//4
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelRoomId'];//5
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelId'];//6
	        }
	        
			for($i=0;$i<count($hostelLedgerArray);$i++) {
	         	$valueHostelArray[] = $hostelLedgerArray[$i]['hostDebit'];//7
				$valueHostelArray[] = $hostelLedgerArray[$i]['hostCredit'];//8
				$valueHostelArray[] = $hostelLedgerArray[$i]['finehostDebit'];//9
				$valueHostelArray[] = $hostelLedgerArray[$i]['finehostCredit'];//10
	        }
		
      //Hostel Fee Data End
      
      
      //Transport Fee Data Start
        $valueTransportArray = '';
		
         $transportHeadFeeArray = $studentGenerateFeeManager->getTotalTransportHeadFee($ttStudentId,$ttClassId,$condition);			
			
			$transportLedgerArray = $studentGenerateFeeManager->getTotalTransportLedgerFee($ttStudentId,$ttClassId,$condition);
		 
			  for($i=0;$i<count($transportHeadFeeArray);$i++) {
          		$valueTransportArray[] = $transportHeadFeeArray[$i]['studentId'];//1
				$valueTransportArray[] = $transportHeadFeeArray[$i]['classId'];//2
				$valueTransportArray[] = $transportHeadFeeArray[$i]['transportFees'];//3
				$valueTransportArray[] = $transportHeadFeeArray[$i]['busRouteStopMappingId'];//4
				$valueTransportArray[] = $transportHeadFeeArray[$i]['busRouteId'];//5
				$valueTransportArray[] = $transportHeadFeeArray[$i]['busStopId'];//6
				$valueTransportArray[] = $transportHeadFeeArray[$i]['busStopCityId'];//7
				
	        }
	        
			for($i=0;$i<count($transportLedgerArray);$i++) {
	         	$valueTransportArray[] = $transportLedgerArray[$i]['transDebit'];//8
				$valueTransportArray[] = $transportLedgerArray[$i]['transCredit'];//9
				$valueTransportArray[] = $transportLedgerArray[$i]['finetransDebit'];//10
				$valueTransportArray[] = $transportLedgerArray[$i]['finetransCredit'];//11
	        }
			
	     //Transport Fee Data End
 		$strQuery ="periodValue ='$ttPeriodValue',
					   feeHeadDetails ='$feeHeadDetails',
					   academicFee ='$valueAcademicArray[2]',
					    concession ='$valueAcademicArray[3]',
					   ledgerAcademicDebit ='$valueAcademicArray[4]',
					   ledgerAcademicCredit ='$valueAcademicArray[5]',
						 academicFineDebit ='$valueAcademicArray[6]',
					   academicFineCredit ='$valueAcademicArray[7]',
					   hostelFee ='$valueHostelArray[3]',
					   hostelSecurity ='$valueHostelArray[4]',
					   hostelRoomId ='$valueHostelArray[5]',
					   hostelId ='$valueHostelArray[6]',
					   ledgerHostelCredit ='$valueHostelArray[8]',
					   ledgerHostelDebit ='$valueHostelArray[7]', 
					   hostelFineCredit ='$valueHostelArray[10]',
					   hostelFineDebit ='$valueHostelArray[9]',			
					   transportFee ='$valueTransportArray[3]',
					   busRouteId ='$valueTransportArray[5]',  
					   busStopId ='$valueTransportArray[6]', 
					   busStopCityId ='$valueTransportArray[7]',
					   busRouteStopMappingId ='$valueTransportArray[4]',
					   ledgerTransportCredit ='$valueTransportArray[9]',
					  ledgerTransportDebit ='$valueTransportArray[8]',
					  transportFineCredit ='$valueTransportArray[11]',
					  transportFineDebit ='$valueTransportArray[10]'";		
	     //check for generate student fee table 
	     $checkGenerateStudent = $studentGenerateFeeManager->getGenerateStudentFeeValue($ttStudentId,$ttClassId);
		 
		 if(count($checkGenerateStudent) >0){		
								 	
			 $updateGenerateStudent = $studentGenerateFeeManager->updateGenerateStudentFeeValue($ttStudentId,$ttClassId,$strQuery);	
			 if($updateGenerateStudent===false){		  		
				echo FAILURE;
		  	}
					
		 }else{
		 	$strQuery .=",studentId ='$ttStudentId',
					   classId ='$ttClassId'";
			$insertGenerateStudent = $studentGenerateFeeManager->insertGenerateStudentFeeValue($strQuery);	
			 if($insertGenerateStudent===false){		  		
				echo FAILURE;
		  	}
		 }
		} 
		  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		  	echo SUCCESS;
			
		  }
			
		}																								
																																			


?>
