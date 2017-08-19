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
	
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
    $onlineManager = OnlineFeeManager::getInstance();  
    
	require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    	
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();    
  
	
    $studentId = $sessionHandler->getSessionVariable('StudentId');
	$classId = $sessionHandler->getSessionVariable('ClassId');
	
	
	//Fine SetUp module linkingg...
    function dateDiff($start, $end) {
       $start_ts = strtotime($start);
       $end_ts = strtotime($end);
       
       $diff = $end_ts - $start_ts;
       return round($diff / 86400);
    }
    
    
    
    
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
    
	
    if($studentId=='') {
      $studentId=0;  
    }
	$studentClassArray = $onlineManager->getStudentAllFeeClasses($studentId,$classId);
	$tableFee='';
	
	$tableFee='<table width="100%" border="0" cellspacing="2px" cellpadding="2px" class="border">                   
                   <tr class="rowheading">
                      <td class="contenttab_internal_rows" align="left"><nobr><b>Fee Class</b></nobr></td>
                      <td class="contenttab_internal_rows"><center><nobr><b>Academic</b></nobr></center></td>  
                      <td class="contenttab_internal_rows"><center><nobr><b>Transport</b></nobr></center></td> 
                      <td class="contenttab_internal_rows"><center><nobr><b>Hostel</b></nobr></center></td>
                   </tr> ';  
                   
				
    $prevAcademicClassAmount=0; 
    $prevHostelClassAmount =0;
    $prevTransportClassAmount =0;

	$isOnlineFeeCheck='0';
    $isPreviousPaid = '0';	                 
    if(count($studentClassArray) >0 && is_array($studentClassArray)) {  	
      for($y=0;$y<count($studentClassArray);$y++) {
  	        $feeClassId = $studentClassArray[$y]['feeClassId'];       
            $ttFeeClassName  = $studentClassArray[$y]['cycleName']; 
            
            $ttAcademicApply  = $studentClassArray[$y]['academic'];
            $ttHostelApply  = $studentClassArray[$y]['hostel'];
            $ttTransportApply  = $studentClassArray[$y]['transport'];
	
             $academicBalance=0;
		     $hostelBalance=0;
		     $transportBalance=0;
		     
		     $prevAcademicAmount = 0;
		     $prevHostelAmount = 0;
		     $prevTransportAmount = 0;
			    
		     $prevAcademicPaid = 0;  
		     $prevHostelPaid = 0; 
		     $prevTransportPaid = 0;
	         
             // Calculation Total Amount
	         $feeAcdSearch='0';
              
             if($feeAcdSearch=='0') {
                 $feeResultMessage = getGenerateFee($feeClassId,$studentId);
                 if($feeResultMessage!=SUCCESS) {
                   echo $feeResultMessage;
                   die;  
                 }
             }          
					   
	         $prevSemFeeArray = $onlineManager->getPreviousTotalAmount($studentId,$feeClassId);			 
			 $prevAcademicAmount = $prevSemFeeArray[0]['acdemicFees'];
			 $prevHostelAmount = $prevSemFeeArray[0]['hostelFees'];
			 $prevTransportAmount = $prevSemFeeArray[0]['transportFees'];
			 
			 if($prevAcademicAmount==''){
			 	$prevAcademicAmount=0;				
			 }
			 if($prevHostelAmount==''){
			 	$prevHostelAmount=0;				
			 }
			 if($prevTransportAmount==''){
			 	$prevTransportAmount=0;				
			 }
			
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
			
			
	           if($academicBalance=='0' || $academicBalance < 0){
	        	 $acdemicFeeDetail='---';
			   }
               else {
	        	 $acdemicFeeDetail=' <nobr><input type="checkbox" id="chbCheckHostel'.$y.'" name="chbCheckFee[]" onClick="getPaybleFee();" 
                 value="'.$feeClassId.'~1~'.$academicBalance.'"><span id="spanfeeAcademic'.$y.'" >'.$academicBalance.'</span> </nobr>';
		      } 
			
			  if($transportBalance=='0' || $transportBalance < 0){
	        	$transportFeeDetail='---';
			  } 
              else {
	        	$transportFeeDetail='<nobr><input type="checkbox" id="chbCheckHostel'.$y.'" name="chbCheckFee[]" onClick="getPaybleFee();" 
                value="'.$feeClassId.'~2~'.$transportBalance.'"><span id="spanfeeTransport'.$y.'">'.$transportBalance.'</span></nobr>  ';
		      } 
			  if($hostelBalance=='0' || $hostelBalance < 0){
	        	$hostelFeeDetail='---';
			  } 
              else {
	            $hostelFeeDetail=' <nobr><input type="checkbox" id="chbCheckHostel'.$y.'" name="chbCheckFee[]" onClick="getPaybleFee();" 
                value="'.$feeClassId.'~3~'.$hostelBalance.'"><span id="spanfeeHostel'.$y.'" >'.$hostelBalance.'</span></nobr>  ';
		 	 } 	
		 	   
			 if($academicBalance >0 || $transportBalance > 0 || $hostelBalance > 0) {
                 $isOnlineFeeCheck='1';
                 $bg = $bg =='row0' ? 'row1' : 'row0';
                 $tableFee .= '<tr class="'.$bg.'">  
	                             <td class="padding_top" >
	                               <nobr> '.$ttFeeClassName.'</nobr>                    
	                             </td>
	                             <td class="padding_top" align="center">
	                             '.$acdemicFeeDetail.'                    
	                             </td>
	                              <td class="padding_top" align="center">
	                             '.$transportFeeDetail.'                    
	                             </td>
	                              <td class="padding_top" align="center">
	                             '.$hostelFeeDetail.'                    
	                             </td>
	                           </tr>';
			  }	
          	}
          }
          else {
             $tableFee .= "<tr class='row1'><td class='padding_top' colspan='4'><center>No Data Found</center></td></tr>";
          }
          
          if($isOnlineFeeCheck=='0') {
             $tableFee .= "<tr class='row1'><td class='padding_top' colspan='4'><center>No Data Found</center></td></tr>";  
          }
          $tableFee .= "</table> ";  
          echo trim($tableFee)."!!~~!!~~!!".$isOnlineFeeCheck;  
       
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
            $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$feeClassId);
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
                if($studentArr['isMigration'] == 1 && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    echo FEE_HEAD_NOT_DEFINE;
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
