<?php
//-------------------------------------------------------
// Purpose: To store the records of student fees from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : 09-12-2008
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
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==3){
       UtilityManager::ifParentNotLoggedIn(true);  
       $studentId = $sessionHandler->getSessionVariable('StudentId'); 
    }
    else if($roleId==4){
       UtilityManager::ifStudentNotLoggedIn(true);  
       $studentId = $sessionHandler->getSessionVariable('StudentId'); 
    }
    else{
      UtilityManager::ifNotLoggedIn();
      $studentId = $REQUEST_DATA['studentId'];
    }
    UtilityManager::headerNoCache(); 

   
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
    $filter .= " AND fr.studentId = '$studentId' ";
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
        
        
        $instStatus =  $receiptPaymentArr[$studentRecordArray[$i]['instrumentStatus']];
        if($instStatus=='') {
          $instStatus = NOT_APPLICABLE_STRING;  
        }
        $retStatus = $receiptArr[$studentRecordArray[$i]['receiptStatus']];
        if($retStatus=='') {
           $retStatus = NOT_APPLICABLE_STRING;
        }
                    
        $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($studentRecordArray[$i]['feeReceiptId']).'">';
        
        if($studentRecordArray[$i]['receiptStatus']==3) {
            $printAction = NOT_APPLICABLE_STRING;
        }
        
        if($studentRecordArray[$i]['receiptStatus']==3){ 
           $retStatus = "Cancel";  
           $instStatus = NOT_APPLICABLE_STRING;
           $previousDues = NOT_APPLICABLE_STRING;
           $discountedFeePayable = NOT_APPLICABLE_STRING;
           $totalAmountPaid  = NOT_APPLICABLE_STRING;
        } 
        
        $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($studentRecordArray[$i]['feeReceiptId']).'">';
        
        
        $valueArray = array_merge(array('retStatus' =>  $retStatus,
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

die;    
function getFindTotalFee($feeClassId,$quotaId,$tIsLeet,$feeReceiptId,$studentId,$conessionFormatId,$chkInstallment,$feeTypeId) {
    
        global $collectStudentFeeManager;
        global $feeReportManager;   
        
        global $discountedFeePayable;
        global $totalAmountPaid;
        global $previousDues;
        
        $discountedFeePayable =0;
        $totalAmountPaid =0;
        $previousDues =0;
        
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
               
               if($concession==0 || $concession=='') {
                 $conn = 0;    
               }
               else {
                 $conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession);
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
         $totalAmountPaid += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid']);
         $discountedFeePayable += ($foundArray[$j]['prevHostelFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevFeeFine']); 
       }
       
       $previousDues = $discountedFeePayable - ($pervAmt+$totalAmountPaid);  
       $discountedFeePayable = $discountedFeePayable - $pervAmt; 
}     
?>    