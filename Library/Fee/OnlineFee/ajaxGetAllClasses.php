<?php
//Online Fee Payment Details
//
// Author :Harpreet
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);  
    }
    else{
      UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();
	
	  require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
   
	 require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
	
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
    $onlineManager = OnlineFeeManager::getInstance();  
   
    $studentId = $sessionHandler->getSessionVariable('StudentId');
	$classId = $sessionHandler->getSessionVariable('ClassId');
	 
    $StudentNameFee = $sessionHandler->getSessionVariable('StudentName');
    $RollNoFee = $sessionHandler->getSessionVariable('RollNo');
    $FatherNameFee = $sessionHandler->getSessionVariable('FatherName');
    $ClassNameFee = $sessionHandler->getSessionVariable('ClassName');
	
	
    if($studentId=='') {
      $studentId=0;  
    }
	$results = $onlineManager->getStudentClassesData($studentId,$classId);
	$returnValues = '<table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="border">';
    if(isset($results) && is_array($results)) {
      $count = count($results);
      for($i=0;$i<$count;$i++) {
         $feeClassId = $results[$i]['classId'];    
         $prevAcademicAmount =0;
		 $prevHostelAmount =0;
		 $prevTransportAmount=0;
         // Fetch Academic Total Amount  
         
           $feeResultMessage = getGenerateFee($feeClassId,$studentId);
           if($feeResultMessage!=SUCCESS) {
             echo $feeResultMessage;
             die;  
           } 
		
         	$academicHeadFeeArray = $onlineManager->getTotalAcademicHeadFee($studentId,$feeClassId);			
			$academicConcessionArray = $onlineManager->getTotalAcademicConcessionFee($studentId,$feeClassId);			
			$academicLedgerArray = $onlineManager->getTotalAcademicLedgerFee($studentId,$feeClassId); 
			
			    $prevAcademicAmount = $academicHeadFeeArray[0]['academicFees'] - $academicHeadFeeArray[0]['concession'];
			
				$prevAcademicAmount = $prevAcademicAmount + $academicLedgerArray[0]['acdDebit'];
					
				$prevAcademicAmount = $prevAcademicAmount - $academicLedgerArray[0]['acdCredit'];
					
				$prevAcademicAmount = $prevAcademicAmount + $academicLedgerArray[0]['fineDebit'];
					
				$prevAcademicAmount = $prevAcademicAmount - $academicLedgerArray[0]['fineCredit'];
			
		  //Fetch total hostel fee	
			 $hostelHeadFeeArray = $onlineManager->getTotalHostelHeadFee($studentId,$feeClassId);				
			$hostelLedgerArray = $onlineManager->getTotalHostelLedgerFee($studentId,$feeClassId);
			
			$prevHostelAmount = $hostelHeadFeeArray[0]['hostelFees'];
				$prevHostelAmount = $prevHostelAmount + $hostelHeadFeeArray[0]['hostelSecurity'];
				$prevHostelAmount = $prevHostelAmount + $hostelLedgerArray[0]['hostDebit'];
				$prevHostelAmount = $prevHostelAmount - $hostelLedgerArray[0]['hostCredit'];
				$prevHostelAmount = $prevHostelAmount + $hostelLedgerArray[0]['finehostDebit'];
				$prevHostelAmount = $prevHostelAmount - $hostelLedgerArray[0]['finehostCredit'];
		   //Fetch total Transport fee	
		    $transportHeadFeeArray = $onlineManager->getTotalTransportHeadFee($studentId,$feeClassId);				
			$transportLedgerArray = $onlineManager->getTotalTransportLedgerFee($studentId,$feeClassId);
		
			  $prevTransportAmount = $transportHeadFeeArray[0]['transportFees'];
		
				$prevTransportAmount = $prevTransportAmount +  $transportLedgerArray[0]['transDebit'];
				$prevTransportAmount = $prevTransportAmount - $transportLedgerArray[0]['transCredit'];
				$prevTransportAmount = $prevTransportAmount +  $transportLedgerArray[0]['finetransDebit'];
				$prevTransportAmount = $prevTransportAmount - $transportLedgerArray[0]['finetransCredit'];
       		 
		 				
			 if($prevAcademicAmount==''){
			 	$prevAcademicAmount=0;				
			 }
			 if($prevHostelAmount==''){
			 	$prevHostelAmount=0;				
			 }
			 if($prevTransportAmount==''){
			 	$prevTransportAmount=0;				
			 }			
			
             $academicBalance=0;
		     $hostelBalance=0;
		     $transportBalance=0;
		     
		   		    
		     $prevAcademicPaid = 0;  
		     $prevHostelPaid = 0; 
		     $prevTransportPaid = 0;
	         
			
             // Calculation Total Amount
	         $feeAcdSearch='0';
			 // Calculation Paid AMOUNT 
			  $prevSemAcademicPaidArray = $onlineManager->getTotalAcademicPaidAmount($studentId,$feeClassId);
			  $prevAcademicPaid = $prevSemAcademicPaidArray[0]['paidAcademicAmount'];
			   
			  $prevSemHostelPaidArray = $onlineManager->getTotalHostelPaidAmount($studentId,$feeClassId);
			  $prevHostelPaid = $prevSemHostelPaidArray[0]['paidHostelAmount'];
			   
			  $prevSemTransportPaidArray = $onlineManager->getTotalTransportPaidAmount($studentId,$feeClassId);
			  $prevTransportPaid = $prevSemTransportPaidArray[0]['paidTransportAmount'];	
			   
			  $prevSemAllPaidArray = $onlineManager->getTotalAllPaidAmount($studentId,$feeClassId);
			  $prevAllPaid = $prevSemTransportPaidArray[0]['paidAmount'];	
			  
			    if($prevAllPaid =='' || $prevAllPaid =='0') {
			  	 $academicBalance = $prevAcademicAmount - $prevAcademicPaid;
				 $hostelBalance = $prevHostelAmount - $prevHostelPaid;
				 $transportBalance = $prevTransportAmount - $prevTransportPaid;					 			
			  } 
              else {
                  if($ttAcademicApply=='1'){
				    $academicBalance = $prevAcademicAmount - $prevAllPaid;
				  } 
                  if($ttHostelApply=='1'){
                    $hostelBalance = $prevHostelAmount - $academicBalance; 
                  }
                  if($ttTransportApply=='1'){
                    $transportBalance = $prevTransportAmount - $hostelBalance; 
                  }
			   } 
                 
			 
                 if($academicBalance !='0' || $hostelBalance !='0' || $transportBalance !='0'){
                 	if( $isPreviousPaid =='0'){              
                    $prevAllClassAmount = $academicBalance + $hostelBalance + $transportBalance;                                                                 
                     $isPreviousPaid = 1;   
						$isPrevPrint =$feeClassId +1;                     
                   }  
				 }
           
			   	if($isPrevPrint==$feeClassId){
			   	  $academicBalance = $prevAllClassAmount + $academicBalance; 
				}	
				
			 if($transportBalance >0 || $academicBalance >0 || $hostelBalance >0 ){ 
	              $returnValues .= '<tr class="rowheading" >
	              						<td class="padding_top" align="center" colspan="2">';  
	                $returnValues .= '<b>'.$results[$i]['className'].'</b>';
	              $returnValues .= '</td></tr>';  
				  
	              $returnValues .= '<tr>';
				  if($academicBalance >0){
	            	  $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;font-size:13px;">
	                                  <nobr><b>&bull;&nbsp;</b> 
	                                  <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',1); return false;">Academic Fee</a>
	                                  </nobr><br></td>'; 
				  }else{
				  		 $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;color:grey;">
	                                  <nobr><b>&bull;&nbsp;</b> 
	                                  Academic Fee
	                                  </nobr><br></td>';
				  }
				   if($hostelBalance >0){
	             $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;font-size:13px;"> 
	                                <nobr><b>&bull;&nbsp;</b>
	                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',3); return false;">
                                    Hostel Fee</a>
	                              	</nobr><br>
	                              </td>';
				  }else{
				  		 $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;color:grey;">
	                                  <nobr><b>&bull;&nbsp;</b> 
	                                  Hostel Fee
	                                  </nobr><br></td>';
				  }
	              	 $returnValues .= '</tr>'; 
				     $returnValues .= '<tr>';  
				 if($transportBalance >0){                  
	             		$returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;font-size:13px;"> 
	                                <nobr><b>&bull;&nbsp;</b>
	                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',2); return false;">
                                    Transport Fee</a>
	                                </nobr><br>
	                              </td>';
				 }else{
				  		 $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;color:grey;">
	                                  <nobr><b>&bull;&nbsp;</b> 
	                                  Transport Fee
	                                  </nobr><br></td>';
				  }
				 if($transportBalance >0 && $academicBalance >0 || $hostelBalance >0 && $academicBalance >0 ){ 
	             		$returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;font-size:13px;"> 
	                                <nobr><b>&bull;&nbsp;</b>
	                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',4); return false;">All Fee</a>
	                             	 </nobr><br>
	                              </td>';
	              }  else{
				  		 $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;color:grey;">
	                                  <nobr><b>&bull;&nbsp;</b> 
	                                  All Fee
	                                  </nobr><br></td>';
				  }                  
	              $returnValues .= '</tr>';
	              $returnValues .= '<tr class="padding_top"><td style="height:14px;"></td></tr>';
	            
	           }
        } 
      } 
        $returnValues .= '</table>';
		
		echo $returnValues;

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
                  
					 break;
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
