<?php
//-------------------------------------------------------
// Purpose: To store the records of fee status in array from the database 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    //require_once(MODEL_PATH . "/StudentManager.inc.php");
    //$studentManager = StudentManager::getInstance();
    
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance();
    
    require_once(MODEL_PATH . "/FeeReportManager.inc.php");
    $feeReportManager = FeeReportManager::getInstance();
    

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

	/// Search filter /////  
	function parseName($value){
		$name=explode(' ',$value);
	    $genName="";
		$len= count($name);
		if($len > 0){
		  for($i=0;$i<$len;$i++){
            if(trim($name[$i])!=""){
               if($genName!=""){
				  $genName =$genName ." ".$name[$i];
				}
				else{
                  $genName =$name[$i];
				} 
			 }
		   }
        }
	    if($genName!=""){
           $genName=" OR CONCAT(TRIM(stu.firstName),' ',TRIM(stu.lastName)) LIKE '".$genName."%'";
	    }  
   	    return $genName;
	}

	/// Search filter ///// 
	// Degree
    $feeClassIdOld = "";
    $feeClassIdNew = "";
    if(UtilityManager::notEmpty($REQUEST_DATA['degree'])){
      //$feeClassIdOld = " AND stu.classId = ".$REQUEST_DATA['degree'];         
      $feeClassIdNew = " AND fr.feeClassId = '".add_slashes($REQUEST_DATA['degree'])."'";         
    }
	
	// Batch
	if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
	   $filter .= ' AND (cls.batchId = '.add_slashes($REQUEST_DATA['batch']).')';         
	}

	// Study Period
	if(UtilityManager::notEmpty($REQUEST_DATA['studyperiod'])) {
	   $filter .= ' AND (cls.studyPeriodId = '.add_slashes($REQUEST_DATA['studyperiod']).')';         
	}

	// Student Name
	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
		$studentName = $REQUEST_DATA['studentName'];
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $filter .= " AND (
						  TRIM(stu.firstName) LIKE '".add_slashes(trim($studentName))."%' 
						  OR 
						  TRIM(stu.lastName) LIKE '".add_slashes(trim($studentName))."%'
						  $parsedName
					 )";
	  // $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	// Roll No
	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
	}

	// fee cycle
	if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
	   $filter .= ' AND (fr.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';         
	}

	// from Date
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	// to date
	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}

	// instrument status
	if(UtilityManager::notEmpty($REQUEST_DATA['paymentStatus'])) {
	   $filter .= ' AND (instrumentStatus ='.add_slashes($REQUEST_DATA['paymentStatus']).')';         
	}

	// receipt status
	if(UtilityManager::notEmpty($REQUEST_DATA['receiptStatus'])) {
	   $filter .= ' AND (receiptStatus ='.add_slashes($REQUEST_DATA['receiptStatus']).')';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['receiptNo'])) {
       $filter .= ' AND (receiptNo LIKE "%'.add_slashes($REQUEST_DATA['receiptNo']).'%")';         
    }
                          
                        
	//$filter .= ' AND (paymentInstrument !=1)';   
	//$totalArray = $studentManager->getFeesHistoryListNew($filter);
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    
    if($sortField=='undefined') {
      $sortField='receiptDate';  
    }
    
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';  
    }
    
    $sortField1 = $sortField;
    if($sortField=='receiptDate') {
      $sortField1 = 'fr.receiptDate';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(fr.receiptNo)+0,fr.receiptNo';  
    }
    $orderBy = "$sortField1 $sortOrderBy";  
    
    
    // Findout Student Fee Receipt (New Format)
    //$filter .= " AND fr.studentId = 1 ";
    $condition = $feeClassIdNew." AND fr.isNew = 1 AND fr.receiptStatus NOT IN (4) ".$filter;
    
    $totalArray = $feeReportManager->getStudentFeeReceipt($condition);
    $studentRecordArray = $feeReportManager->getStudentFeeReceipt($condition,$orderBy,$limit);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) { 
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
        $conessionFormatId  = $studentRecordArray[0]['isConessionFormat']; 
        
        $chkInstallment = $studentRecordArray[$i]['installmentCount'];
        
        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);   
        $studentRecordArray[$i]['installmentCount'] = "Installment-".$studentRecordArray[$i]['installmentCount'];
        
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
        $feeCycleId = $studentRecordArray[$i]['feeCycleId'];
        $feeClassId = $studentRecordArray[$i]['feeClassId'];
        $studentId = $studentRecordArray[$i]['studentId']; 
        $quotaId = $studentRecordArray[$i]['quotaId']; 
        $isLeet = $studentRecordArray[$i]['isLeet']; 
        $feeType = $studentRecordArray[$i]['feeType']; 
          
        $tIsLeet=2; 
        if($isLeet==1) {
          $tIsLeet=1;  
        } 
        
        $discountedFeePayable = 0;
        $totalAmountPaid =0;
        $previousDues =0;
        
        getFindTotalFee($feeClassId,$quotaId,$tIsLeet,$feeReceiptId,$studentId,$conessionFormatId,$chkInstallment,$feeType);
        
        if($studentRecordArray[$i]['instrumentStatus']=='3') {
          $studentRecordArray[$i]['instrumentStatus'] = NOT_APPLICABLE_STRING;  
        }
        $retStatus = '<input type="hidden" readonly="readonly" id=feeRecId'.$feeReceiptId.' name="feeRecId" value='.$feeReceiptId.'>  
                     <select class="selectfield" style="width:90px" size="1" name="rStatus" id="rStatus'.$feeReceiptId.'" >
                     <option value="">Select</option>'.$htmlManager->getFeeReceiptStatus($studentRecordArray[$i]['receiptStatus'],'4')
                    .'</select>';

        $instStatus = '<select class="selectfield" style="width:90px" size="1" name="iStatus" id="iStatus'.$feeReceiptId.'" >
                     <option value="">Select</option>'.$htmlManager->getFeeReceiptPaymentStatus($studentRecordArray[$i]['instrumentStatus'],'4')
                    .'</select>';
       
        $deleteAction = '<a name="Delete" onclick="deleteReceipt('.$feeReceiptId.');return false;" title="Delete" ><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" title="Delete"></a>';
        
        if($studentRecordArray[$i]['receiptStatus']==3){ 
           $retStatus = "Cancel";  
           $instStatus = NOT_APPLICABLE_STRING;
           $previousDues = NOT_APPLICABLE_STRING;
           $discountedFeePayable = NOT_APPLICABLE_STRING;
           $totalAmountPaid  = NOT_APPLICABLE_STRING;
        } 
        
      
        $valueArray = array_merge(array('deleteAction'=> $deleteAction,
                                        'retStatus' =>  $retStatus,
                                        'instStatus' => $instStatus, 
                                        'discountedFeePayable' => $discountedFeePayable,
                                        'amountPaid' => $totalAmountPaid, 
                                        'previousDues' => $previousDues, 
                                        'srNos' => ($records+$i+1) ),
                                        $studentRecordArray[$i]);
        
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';      
    
    
function getFindTotalFee($feeClassId,$quotaId,$tIsLeet,$feeReceiptId,$studentId,$conessionFormatId,$chkInstallment,$feeTypeId) {
    
    
   
        global $collectStudentFeeManager;
        global $feeReportManager;   
        
        global $discountedFeePayable;
        global $totalAmountPaid;
        global $previousDues;
        
        $discountedFeePayable =0;
        $totalAmountPaid =0;
        $previousDues =0;
        
        $adhocConcession=0; 
        if($conessionFormatId==4) {
          $adhocCondition = " feeClassId = '$feeClassId' AND studentId = '$studentId' "; 
          $adhocConcessionArray = $collectStudentFeeManager->getCheckStudentConcession($adhocCondition); 
          if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0 ) {
            $adhocConcession = 1;
          }          
        }
        
        if($feeTypeId==4 || $feeTypeId==1) {                    // Set Academic Fee   
            $feeId = "-1";
            $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
            $foundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon);
            for($i=0; $i<count($foundArray); $i++) {
              $feeId .=",".$foundArray[$i]['feeId'];  
            }        
            
            $havingConditon = " COUNT(fhv.feeHeadId) >= 2"; 
            $isLeetCheck = "1,2,3";
            $foundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon,'',0,$isLeetCheck); 
            for($i=0; $i<count($foundArray); $i++) {
               $tFeeHeadId = $foundArray[$i]['feeHeadId']; 
               if($quotaId!='') {
                  $feeHeadCondition = " AND fhv.quotaId = $quotaId AND fhv.feeHeadId = $tFeeHeadId";  
                  $quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                  if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                    $feeId .=",".$quotaFoundArray[0]['feeId'];  
                  }
                  else {
                    $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                    $quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                    if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                      $feeId .=",".$quotaFoundArray[0]['feeId'];  
                    }
                    else {
                       $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                       $quotaFoundArray = $collectStudentFeeManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                       if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                         $feeId .=",".$quotaFoundArray[0]['feeId'];  
                       }
                    }
                  }
               }
               else {
                 $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                 $quotaFoundArray = $collectStudentFeeManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                 if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                   $feeId .=",".$quotaFoundArray[0]['feeId'];  
                 } 
               }
            }        
            if($feeId=='') {
              $feeId = "-1"; 
            }
            
            $foundArray = $collectStudentFeeManager->getStudentFeeHeadDetail($feeClassId,$feeId,$studentId);
            $feeHeadIds = "-1";
            for($i=0;$i<count($foundArray);$i++) {
              $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
            }
            
            // Student Concession Findout is Leet & Non Leet Base 
            $concessionArray = $collectStudentFeeManager->getStudentConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$condition='');
            $concessionFeeHeadIds = "-1"; 
            for($i=0;$i<count($concessionArray);$i++) {
              $concessionFeeHeadIds .= ",".$concessionArray[$i]['feeHeadId'];   
            }
            $concessionCondition = " AND fcv.feeHeadId NOT IN ($concessionFeeHeadIds)";
            $concessionFinalArray = $collectStudentFeeManager->getStudentFinalConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$concessionCondition);
            
            for($i=0; $i<count($foundArray); $i++) {
               $feeHeadDetailFind=1;
               $foundArray[$i]['concession']=0;
               $feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
               $totalFees +=$foundArray[$i]['feeHeadAmt'];
               $salFeeHeadId =  $foundArray[$i]['feeHeadId'];
               
            
               $concession =0;
               // Categories wise Concession 
               if($conessionFormatId==4) {
                  for($jj=0;$jj<count($adhocConcessionArray);$jj++) {
                     if($adhocConcessionArray[$jj]['feeHeadId']==$salFeeHeadId) {  
                       $concession = $adhocConcessionArray[$jj]['concessionAmount']; 
                     }
                  }
               }
               else {
                       $maxConcession = 0;
                       $minConcession = 0;
                       $reducingConcession = 0;
                       $chk=0;               
                       for($jj=0;$jj<count($concessionFinalArray);$jj++) {
                          if($concessionFinalArray[$jj]['feeHeadId']==$salFeeHeadId) {
                              if($concessionFinalArray[$jj]['concessionType']=='2') {
                                $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                                if($chk==1) {
                                  $reducingConcession = doubleval($reducingConcession) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                                }
                              }
                              if($concessionFinalArray[$jj]['concessionType']=='1') {
                                $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - (doubleval($foundArray[$i]['feeHeadAmt']) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                                if($chk==1) {
                                  $reducingConcession = doubleval($reducingConcession) - (doubleval($reducingConcession) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                                }
                              }
                              if($chk==0) {
                                 $maxConcession = $concessionAmt;
                                 $minConcession = $concessionAmt; 
                                 $reducingConcession = $concessionAmt;
                              }
                              if($concessionAmt < $maxConcession) {
                                $maxConcession = $concessionAmt;  
                              }
                              if($concessionAmt > $minConcession) {
                                $minConcession = $concessionAmt;  
                              }
                              $chk=1; 
                          }
                       }
                       if($conessionFormatId==1) {
                         $concession = $maxConcession; 
                       }
                       if($conessionFormatId==2) {
                         $concession = $minConcession; 
                       }
                       if($conessionFormatId==3) {
                         $concession = $reducingConcession; 
                       }
               }
               if($concession==0 || $concession=='') {
                 $conn = 0;    
               }
               else {
                 if($conessionFormatId==4) { 
                   $conn = doubleval($concession);  
                 }
                 else {
                   $conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession); 
                 }
               }
               $foundArray[$i]['concession'] = $conn;
               $totalConcession += doubleval($foundArray[$i]['concession']);  
               
               $feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
               $feeAmtPaidTotal +=$feesAmt;
               
               if($conn=='' || $conn=='0') {
                  $conn=NOT_APPLICABLE_STRING;  
               }
               $foundArray[$i]['concession'] =$conn;
               
               $discountedFeePayable += (doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($conn));
           }
       }
     
        
       
       if($feeTypeId == '4' || $feeTypeId == '2') {  
          $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND fsf.facilityType = 1";    
          $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
          if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
             $studentFeesArray[0]['transportFacility']=1;
             $trCharges = $facilityArrayCheck[0]['charges'];
             $trConcession = $facilityArrayCheck[0]['concession'];
           
             $discountedFeePayable += (doubleval($trCharges)-doubleval($trConcession)); 
          }
       }
         
       if($feeTypeId == '4' || $feeTypeId == '3') {      
          $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND fsf.facilityType = 2";                    
          $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
          if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
             $hrCharges = $facilityArrayCheck[0]['charges'];
             $hrConcession = $facilityArrayCheck[0]['concession'];
             
             $discountedFeePayable += (doubleval($hrCharges)-doubleval($hrConcession)); 
          }
       }
       
       $conditionTotalDue = " f.studentId = $studentId AND f.feeClassId = $feeClassId AND f.receiptStatus NOT IN (3,4) 
                              AND f.installmentCount < $chkInstallment ";  
       $foundArray = $feeReportManager->getPreviousFeePaymentDetail($conditionTotalDue);    
       $pervAmt=0;
       for($j=0;$j<count($foundArray);$j++) {
           $pervAmt +=  $foundArray[$j]['prevDuesPaid'];
           if($feeTypeId==1) {
             $discountedFeePayable += $foundArray[$j]['prevFeeFine'];
             $pervAmt += $foundArray[$j]['prevFeePaid'];
           }
           else if($feeTypeId==2) {
             $discountedFeePayable += $foundArray[$j]['prevTransportFine'];                
             $pervAmt += $foundArray[$j]['prevTransportPaid'];
           }
           else if($feeTypeId==3) {
             $discountedFeePayable += $foundArray[$j]['prevHostelFine'];                
             $pervAmt += $foundArray[$j]['prevHostelPaid'];  
           }
           else {
             $discountedFeePayable += ($foundArray[$j]['prevFeeFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevHostelFine']);  
             $pervAmt += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid']);
           }
       }   
       
       $totalAmountPaid=0;
       $condition = "f.studentId = $studentId AND f.feeClassId = $feeClassId AND f.feeReceiptId = $feeReceiptId AND
                     f.receiptStatus NOT IN (3,4)";  
       $foundArray = $feeReportManager->getPreviousFeePaymentDetail($condition);    
       for($j=0;$j<count($foundArray);$j++) {
         $totalAmountPaid += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid']+$foundArray[$j]['prevDuesPaid']);
         $discountedFeePayable += ($foundArray[$j]['prevHostelFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevFeeFine']); 
       }
       
       // Findout 
       $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$feeClassId' ";  
       $prevClassFeeArray = $collectStudentFeeManager->getPendingDuesList($prevCondition);  
       for($i=0; $i<count($prevClassFeeArray); $i++) {
         $discountedFeePayable +=  $prevClassFeeArray[$i]['dues'];
       }
       
       $previousDues = $discountedFeePayable - ($pervAmt+$totalAmountPaid);  
       $discountedFeePayable = $discountedFeePayable - $pervAmt; 
}    
?>