<?php 
//  This File calls addFunction used in adding FeeHead Records
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();


     if($feeClassId=='') {
       $feeClassId='0';
     }
     
     if($studentId=='') {
       $studentId='0';
     }
   
    $feeCycleCondition = " studentId = '$studentId' AND classId = '$feeClassId' ";
    $feeCycleArray = $generateFeeManager->checkStudentFeeGenerate($feeCycleCondition);
    if(count($feeCycleArray)> 0){
       echo "Fee already generated"; 
       die;
    }
    
    function getGenerateFee($feeClassId,$studentId) {
         
         global $generateFeeManager;
         global $commonQueryManager;
         global $feeHeadManager;
         global $sessionHandler;
         
         if($feeClassId=='') {
           $feeClassId='0';
         }
         
         if($studentId=='') {
           $studentId='0';
         }
         
         $userId = $sessionHandler->getSessionVariable('UserId');
         $errorMessage =''; 
         
         
         $feeCycleCondition = " classId = '$feeClassId' ";
         $feeCycleArray = $generateFeeManager->checkStudentFeeCycle($feeCycleCondition);
         if(count($feeCycleArray) > 0){
            $feeCycleId = $feeCycleArray[0]['feeCycleId'];
         }
    
	     // to fetch Current class of student
         $classArray = $generateFeeManager->getClass($feeClassId);
         if(count($classArray) == 0){
           echo "Class Not Found";
           die;
        }
        
        // Fetch the all Classes 
        $classes = '';
        foreach($classArray as $key => $value){
          if($classes == ''){
            $classes = $value['classId'];
          }
          else{
            $classes .= ",".$value['classId'];
          }
        } 
        $feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
	
    
        if(SystemDatabaseManager::getInstance()->startTransaction()){
			// to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
			$studentDataArray = $generateFeeManager->getStudentDetails($classes,$condition1,$condition2);
			if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
			   echo "Students Not Found";  
			   die;
			}

	   		$j=1;
	   		foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
                /* 
                $instituteBankAccountNo = $_SESSION['INSTITUTE_ACCOUNT_NO'];
                $instituteBankId = $_SESSION['INSTITUTE_BANK_NAME'];
                if($instituteBankId !=''){
                  $bankNameArray=$generateFeeManager->getInstituteBankName($instituteBankId);
                  $instituteBankName = $bankNameArray[0]['bankAbbr'];
                }
                */
	       		
                $concession = '';
	       		$hostelFees='';
	       		$transportFees='';
	       		$busRouteId   = '';
				$busStopId = '';
				$feeReceiptId = '';
				$totalAcademicFee =0;
				$hostelSecurity = 0;
			 // to get Student Concession       			
	       		$adhocCondition =" acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
				$adhocArray=$generateFeeManager->getStudentAdhocConcessionNew($adhocCondition);
				$concession = $adhocArray[0]['adhocAmount']; // concession Amount
				$isMigration ='-1';
				if($studentArr['isMigration'] == 1){
				  $isMigration = 3;
				}
				 // to get Student Fee Heads
				 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
				 if(count($foundArray) == 0){
				 	echo FEE_HEAD_NOT_DEFINE;
				 	die;
				 }
			     
                 $feeArray = array();
			     $applicableHeadId = array();
			     $index = array();
			    
				 // code to find only applicable Head Value 
				 foreach($foundArray as $key =>$subArray) {
				    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
	       				$flag1 = true; // used for filtering purpose
	       			}  
				    $flag= true;
				    foreach($foundArray as $key1 =>$subArray1){
				       	if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
				       		$flag = true;
				       		foreach($applicableHeadId as $key2 => $value){
				       			if($value == $subArray['feeHeadId']){
				       				$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       				$index[$key2] = $key;
				       				$flag= false;
				       			}	
				       		}
				       		if($flag){
				       			$applicableHeadId[] = $subArray['feeHeadId'];
				       			$index[] = $key;
				       		}
				       		break;
				       	}
				       	else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
				       		$flag = true;
				       		foreach($applicableHeadId as $key2 => $value){
				       			if($value == $subArray['feeHeadId']){
				       				$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       				$index[$key2] = $key;
				       				$flag= false;
				       			}	
				       		}
				       		if($flag){
				       			$applicableHeadId[] = $subArray['feeHeadId'];
				       			$index[] = $key;
				       		}
				       		break;
				       	}
				       	else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
				       		$flag = true;
				       		foreach($applicableHeadId as $key2 => $value){
				       			if($value == $subArray['feeHeadId']){
				       				$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       				$index[$key2] = $key;
				       				$flag= false;
				       			}	
				       		}
				       		if($flag){
				       			$applicableHeadId[] = $subArray['feeHeadId'];
				       			$index[] = $key;
				       		}
				       		break;
				       	}
				       	else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
				       		$applicableHeadId[] = $subArray['feeHeadId'];
				       		$index[] = $key;
				       		break;
				       	
				       	}
				    }      		
				  }
		  
                  $applicableHeadId = array_unique($applicableHeadId); 

                  // to put other heads 
                  foreach($foundArray as $key =>$subArray){
	                if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
		                $feeArray[$key] = $foundArray[$key];
	                }
                  }
                  // to insert aplicable head at there place
                  $index = array_unique($index);
                  foreach($index as $key =>$value){
                    $feeArray[$value] = $foundArray[$value];
                  }
                  // this is done to mantain the order of fee it stores the key
                  $indexArr = array();
                  foreach($feeArray as $key =>$value){
                    $indexArr[] = $key;
                  }
                  sort($indexArr); // to sort the index
		    
			   	$studentId = $studentArr['studentId'];
			    $feeReceiptId = '';
                $feeReceiptArray= $generateFeeManager->getFeeMasterId($studentId,$feeClassId);
                if(count($feeReceiptArray) > 0 ) {
                  $feeReceiptId = $feeReceiptArray[0]['feeReceiptId'];
                }
                $status = $generateFeeManager->insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession);
				if($status === FALSE){
					echo FALIURE;
					die;
				}
                if($feeReceiptId=='') {
				  $feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
                }
                
				$cnt = count($indexArr);
				$instrumentValues = '';
				for($i=0;$i<$cnt; $i++){
					//feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {     
					    if($instrumentValues != ''){
						    $instrumentValues .=", ";
					    }
					    $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
					    $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
					    $totalAcademicFee = " ".$totalAcademicFee;
                    }
				}
		
				$status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
				if($status1 === FALSE){
					echo FALIURE;
					die;
				}
				
				$j++;
       		}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS; 
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
	 
?>
