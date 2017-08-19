<?php
//-------------------------------------------------------
// Purpose: To store the records of Fee in array from the database functionality
// Author : Nishu Bindal
// Created on : (05.Mar.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeLedger');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    $errorMessage = '';
    require_once(MODEL_PATH . "/Fee/FeeLedgerManager.inc.php");   
    $feeLedgerManager = FeeLedgerManager::getInstance(); 
   
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
   
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    

   
    global $sessionHandler;
   
   
   
    $regRollNo = add_slashes(trim($REQUEST_DATA['regRollNo']));
    $includeFine = add_slashes(trim($REQUEST_DATA['includeFine']));
    
    
    if($regRollNo == ''){
      echo "Please Enter Roll No/Reg No.";
      die;
    }
    
    if($includeFine=='') {
      $includeFine='0';  
    }
    
    
    function dateDiff($start, $end) {
       $start_ts = strtotime($start);
       $end_ts = strtotime($end);
       
       $diff = $end_ts - $start_ts;
       return round($diff / 86400);
    }
    
    
    // Roll No.
    $ccStudentArray = $feeLedgerManager->fetchStudentId($regRollNo);  
    if(count($ccStudentArray)>0) {
      $ttStudentId = $ccStudentArray[0]['studentId'];  
      $sAllClass = $ccStudentArray[0]['sAllClass'];  
      if($sAllClass!='') {
        $ccAllClassArray = explode('~',$sAllClass);
      }
    }
    if($studentId=='') {
      $studentId='0';  
    }
    
    for($gg=0;$gg<count($ccAllClassArray);$gg++) {
       if(trim($ccAllClassArray[$gg])!='') {
           
            $ttClassId = $ccAllClassArray[$gg]; 
            $feeResultMessage = getGenerateFee($ttClassId,$ttStudentId);
            if($feeResultMessage!=SUCCESS) {
               continue;
            } 
            
             if($includeFine=='1') { 
                $studentId =  $ttStudentId;
                $feeClassId = $ttClassId;
                 
                // Calculate Student Late Fee Fine  (START)
                for($y=1;$y<=3;$y++) {
                        $ttFeeFineType = $y;
                        $condition = " AND frd.classId = '$feeClassId' AND frd.feeType IN ('$ttFeeFineType','4') AND frd.studentId = '$studentId' ";
                        $prevFeeArray = $studentFeeManager->getCheckStudentFine($condition);
                        if(is_array($prevFeeArray) && count($prevFeeArray)==0) {  
                              // Ledger Check  
                              $ledgerCondition = "frd.classId = '$feeClassId' AND frd.studentId = '$studentId' AND frd.ledgerTypeId = '$ttFeeFineType' ";  
                              $ledgerSetUpArray = $studentFeeManager->getLedgerCheckFine($ledgerCondition);
                              if($ledgerSetUpArray[0]['isFine']!='2') {  
                                  $fineSetUpArray = $studentFeeManager->getFineSetUpDetails($feeClassId,$ttFeeFineType);
                                  if(is_array($fineSetUpArray) && count($fineSetUpArray)>0) {  
                                     $serverDate = date('Y-m-d'); 
                                     $dif=-1;
                                     $fineCharges='0';
                                     for($i=0;$i<count($fineSetUpArray);$i++) {
                                        if($fineSetUpArray[$i]['fromDate']<=$serverDate) {
                                          $dif = abs(dateDiff($fineSetUpArray[$i]['fromDate'],$serverDate));
                                          if($dif==0) {
                                            $dif=1;
                                          }
                                        }
                                        if($dif > 0) {
                                          if($fineSetUpArray[$i]['chargesFormat']=='1') { // daily
                                            $fineCharges = $fineSetUpArray[$i]['charges']*$dif;
                                          }
                                          else if($fineSetUpArray[$i]['chargesFormat']=='2') { // fixed
                                            $fineCharges = $fineSetUpArray[$i]['charges'];
                                          }
                                          break;
                                        } 
                                     }      
                                     
                                     if(doubleval($dif)!=-1) {
                                        // Academic Fee/  Transport / Hostel
                                        $isApplyFine='0';
                                        if($ttFeeFineType=='1') {
                                           $fineApplyArray = $studentFeeManager->applyFineAcd($studentId,$feeClassId);
                                           if($fineApplyArray===false){
                                             echo FAILURE;
                                             die;
                                           } 
                                           if(count($fineApplyArray)>0){
                                             $isApplyFine='1';       
                                           }  
                                        } 
                                        else if($ttFeeFineType=='2') {
                                           $fineApplyArray = $studentFeeManager->applyFineTransport($studentId,$feeClassId);
                                           if($fineApplyArray===false){
                                             echo FAILURE;
                                             die;
                                           }
                                           if(count($fineApplyArray)>0){
                                              $isApplyFine='1';       
                                           }       
                                        }
                                        else if($ttFeeFineType=='3') {
                                           $fineApplyArray = $studentFeeManager->applyFineHostel($studentId,$feeClassId);
                                           if($fineApplyArray===false){
                                             echo FAILURE;
                                             die;
                                           }
                                           if(count($fineApplyArray)>0){
                                              $isApplyFine='1';       
                                           }      
                                        }

                                        
                                            $returnArray = $studentFeeManager->deleteFineLedgerData($studentId,$feeClassId,$ttFeeFineType);
                                            if($returnArray===false){
                                              echo FAILURE;
                                              die;
                                            }
                                
                                        if($isApplyFine=='1') { 
                                            if(SystemDatabaseManager::getInstance()->startTransaction()){      
                                                $returnArray = $studentFeeManager->updateFineLedgerData($studentId,$feeClassId,$ttFeeFineType,$fineCharges,$feeCycleId);
                                                if($returnArray===false){
                                                  echo FAILURE;
                                                  die;
                                                } 
                                                if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
                                                   //echo FAILURE;   
                                                }
                                            }
                                        }
                                     }
                                   }
                              }
                        }
                }
                //End of fIne setup Module
             
              // Calculate Student Late Fee Fine  (END)
            }
            
           
       } 
    }
    
    
	if($errorMessage == ''){
		$cnt =1;
		$feeLedgerArray = $feeLedgerManager->getStudentLedgerNew($regRollNo);
		if(count($feeLedgerArray) == 0){
			echo "Invalid Reg/Roll No.";
			die;
		}
		$studentName = $feeLedgerArray[0]['studentName'];
		$fatherName = $feeLedgerArray[0]['fatherName'];
		$rollNo = $feeLedgerArray[0]['rollNo'];
		$studentId = $feeLedgerArray[0]['studentId'];
		$class = '';
		$classId = '';
		$balance = 0;
        
        
        
        $studentClassArray = $feeLedgerManager->fetchClases($regRollNo);   
		foreach($feeLedgerArray as $key =>$value){
			$particulars = '';
			$date = '';
			$hostelDate = '';
			$academicDate = '';
			$transportDate = '';
			$debit = '';
			$credit = '';
			
			if($value['feeReceiptId'] !=''){
				$debit = $value['debit'];
				$particulars ="<span style='float:left'>Academic Fee</span>";
                if($value['date']=='0000-00-00 00:00:00') {
                  $date = NOT_APPLICABLE_STRING;
                }
                else {
				  $date = date('d-m-Y',strtotime($value['date']));
                }
			}
			else if($value['feeLedgerDebitCreditId'] !=''){
				$debit = $value['debit'];
				$particulars = $value['comments'];
                if($value['date']=='0000-00-00 00:00:00') {
                  $date = NOT_APPLICABLE_STRING;
                }
                else {
				  $date = date('d-m-Y',strtotime($value['date']));
                }
			}
			else{
                if($value['date']=='0000-00-00 00:00:00') {
                  $date = NOT_APPLICABLE_STRING;
                }
                else {
				  $date = date('d-m-Y',strtotime($value['date']));
                }
				$particulars = "By Receipt No. ".$value['receiptNo'];
			}
			
			if($value['hostelFees'] > 0){
				if($value['hostelSecurity'] > 0){
					$debit .="<br/>".$value['hostelSecurity'];
					$particulars .="<br/><span style='float:left'>Hostel Security</span>";
				}
				$debit .="<br/>".$value['hostelFees'];
				$particulars .="<br/><span style='float:left'>Hostel Fee</span>";	
			}
			if($value['transportFees'] >0){
				$debit .="<br/>".$value['transportFees'];
				$particulars .="<br/><span style='float:left'>Transport Fee</span>";
			}
			if($value['concession'] >0){
				$debit .="<br/>-".$value['concession'];
				$particulars .="<br/><span style='float:left'>Concession</span>";
			}
			if($debit == ''){
				$debit = "0.00";
			}
			$credit = $value['credit'];
			$balance += ($value['debit'] + $value['hostelFees'] + $value['transportFees'] + $value['hostelSecurity']) - ($value['credit'] + $value['concession']);
			$balance = number_format($balance, 2, '.', '');
			if($credit == ''){
				$credit = '0.00';
			}
            
			feeTypeValueFormat($cnt,$value['className'],$debit,$credit,$balance,$particulars,$date,$value['cycleName'],$value['feeLedgerDebitCreditId']);
			$cnt++;
			$classArray[$value['feeClassId']] = $value['className']; // stroing name & id of class
			$feeCycleIdArr[$value['feeCycleId']] = $value['cycleName']; // to find fee cycle of fee class
		}
	}
	else{
		echo $errorMessage; 
        die;
	}
	
    // to find fee class array 
	array_unique($classArray);
	ksort($classArray);
	foreach($classArray as $key =>$value){
		$classId = $key;
		$class = $value;
	}
	
	// to find latest fee cycleId
	// to find fee class array 
	array_unique($feeCycleIdArr);
	ksort($feeCycleIdArr);
	foreach($feeCycleIdArr as $key =>$value){
		$feeCycleId = $key;
		$feeCycle = $value;
	}


	if($studentName == ''){
		$studentName = "---";
	}
	if($rollNo == ''){
		$rollNo = "---";
	}
	if($class == ''){
		$class = "---";
	}
	if($fatherName == ''){
		$fatherName = "---";
	}                       
    
    
    $feeClass_val = ''; 
    for($i=0;$i<count($studentClassArray);$i++) {
       if(trim($feeClass_val)=='') {
            $feeClass_val = json_encode($studentClassArray[$i]);
        }
        else {
            $feeClass_val .= ','.json_encode($studentClassArray[$i]);           
        }    
    }
    
    
	echo '{"feeInfo" :['.$feePaid_val.'],
           "feeClassInfo" :['.$feeClass_val.'], 
           "fatherName" :"'.$fatherName.'",
           "studentName" : "'.$studentName.'","rollNo" : "'.$rollNo.'",
           "className" : "'.$class.'",
	       "studentId" : "'.$studentId.'","classId" : "'.$classId.'",
           "feeCycleId" : "'.$feeCycleId.'","feeCycleName" : "'.$feeCycle.'"}'; 
die; 
  
	function feeTypeValueFormat($srNo,$className,$debit,$credit,$balance,$particulars,$date,$feeCycleName,$ledgerId='') {
		
        global $feePaid_val;
		
        if(strpos($particulars, "<br/>") == false){
		  $particulars = substr(chunk_split($particulars,45,"<br/>"),0,-1);
		}
        
        $actionString = NOT_APPLICABLE_STRING;
        if($ledgerId!='') {
          $actionString='&nbsp;<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" onClick="editWindow(\''.$ledgerId.'\');"/></a>&nbsp;
                         &nbsp;<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteLedger(\''.$ledgerId.'\');"/></a>&nbsp;';  
        }
        
		$valueArray = array_merge(array('srNo' => $srNo,
				                        'className' =>$className,
				                        'particulars'=>$particulars,
				                        'date'=>$date,
				                        'feeCycle'=>$feeCycleName,
				                        'debit' =>$debit,
				                        'credit' =>$credit,
				                        'balance' =>$balance,
                                        'action' => $actionString)); 

		if(trim($feePaid_val)=='') {
			$feePaid_val = json_encode($valueArray);
		}
		else {
			$feePaid_val .= ','.json_encode($valueArray);           
		}                                    
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
         
         $ttFeeClassId=  $feeClassId;
         
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
           return  "Class Not Found";
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
            
                // To Delete old fee heads
                $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$ttFeeClassId);
                if($oldFeeHeadDelete===false) {
                   echo FAILURE;
                   die;
                }
            
     // Fetch Migration Fee  Start
                $migrationArray = $generateFeeManager->getCheckStudentMigration($studentId);
                if(count($migrationArray) > 0 && is_array($migrationArray)) {
                  $ttIsMigrationId=$migrationArray[0]['migrationStudyPeriod'];
                }
                if($ttIsMigrationId=='') {
                  $ttIsMigrationId='0';  
                }
                
                $ttPeriodValue='-1';  
                if($ttIsMigrationId>0) {
                   $migrationPeriodArray = $generateFeeManager->getMigrationStudyPeriod($feeClassId);
                   $ttPeriodValue = $migrationPeriodArray[0]['periodValue'];
                   if($ttPeriodValue=='') {
                     $ttPeriodValue='-1';  
                   }
                }
                if($ttIsMigrationId==$ttPeriodValue) {
                  $ttIsMigrationId=1; 
                }
                else {
                  $ttIsMigrationId=0;   
                }
            // Migration Fee END  

            // to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
            $studentDataArray = $generateFeeManager->getStudentDetailsNew($classes,$condition1,$condition2);
            if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
               return "Students Not Found";  
            }

            $j=1;
            foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
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
                if($studentArr['isMigration'] == 1  && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    return FEE_HEAD_NOT_DEFINE;
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
                    return FALIURE;
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
                    return FALIURE;
                }
                $j++;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $msg = SUCCESS; 
            }
            else {
               $msg = FAILURE;
            }
        }
        else {
          $msg = FAILURE;
        }
        return $msg; 
    }
            
?>
