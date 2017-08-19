<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
   
   
    UtilityManager::ifStudentNotLoggedIn(true);
   
    UtilityManager::headerNoCache();
	
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance(); 
    
    global $sessionHandler;    

    $lblName = "Paid:&nbsp;";
    
    $captionFine=$sessionHandler->getSessionVariable('LABEL_FINE');
    $captionHostel=$sessionHandler->getSessionVariable('LABEL_HOSTEL');
    $captionTransport=$sessionHandler->getSessionVariable('LABEL_TRANSPORT'); 
    
    
	
	/* START: function to fetch student details along with class */
    
    $sessionHandler->setSessionVariable('IdToFindFeeRollNo','');
    $sessionHandler->setSessionVariable('IdToFindFeeClassId',''); 
   
    // Concession Format Define
    // "Max Head" => 1,  "Min Head" => 2, "Reducing Category" => 3, "Adhoc Concession" => 4
    $conessionFormatId =  $sessionHandler->getSessionVariable('CONCESSION_FORMAT'); 
    if($conessionFormatId=='') {
      $conessionFormatId=1;  
    }
    $conessionFormatId=3;  
    
    $condition='';
    $previousFees='0';
    $previousPayment='0';
    $totalFees='0';
    $studentFine='0';
    $totalConcession='0';
    $netAmount='0';
    $previousPaymentCurr='0';
    $previousFineCurr=0;
    $paidAmount='';
    $cashAmount='';
    $studentInstallmentCount =1;
    $previousFine =0;
      
    $favouringBankBranchId = "";
    
    $hostelFacility = 0;
    $transportFacility = 0;
    
    $prevTransportCharges=0;
    $prevTransportFine=0;
    $prevTransportPaid=0;
    $prevTransportDues=0; 
    
    $prevHostelCharges=0;
    $prevHostelFine=0;
    $prevHostelPaid=0;
    $prevHostelDues=0; 
    
    $receiptDate = $REQUEST_DATA['receiptDate'];  
    $receiptNumber = add_slashes(trim($REQUEST_DATA['receiptNumber']));  
    $feeTypeId = $REQUEST_DATA['feeTypeId'];  
    //$feeCycleId = $REQUEST_DATA['feeCycleId'];  
    $feeClassId = $REQUEST_DATA['feeClassId'];  
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));  
    $studentId = -1;
    
    $includePreviousDues = add_slashes(trim($REQUEST_DATA['includePreviousDues']));
    
    if($includePreviousDues=='') {
      $includePreviousDues=0;  
    }
    
    // Findout Student Details
    $condition = " AND (stu.rollNo='".$rollNo."' OR stu.regNo='".$rollNo."' OR stu.universityRollNo='".$rollNo."') ";
    $studentFeesArray = $collectStudentFeeManager->getStudentDetailClass($condition,$feeClassId);     
    if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
       if($studentFeesArray[0]['feeClassId']==-1) {
         echo COLLECT_FEE_CLASS; 
         die;
       }
    }  
    else {
       echo COLLECT_FEE_ID_NOT_EXIST; 
       die; 
    }
    
    $studentCurrentStatus = "(Active)"; 
    $isDelete = $studentFeesArray[0]['isDelete']; 
    if($isDelete=='0') {
      if($studentFeesArray[0]['studentStatus']==0) {
        $studentCurrentStatus = "(Inactive)";  
      }
    }
    else {
      $studentCurrentStatus = "(Deleted)";  
    }
    
    $studentId = $studentFeesArray[0]['studentId'];  
    $quotaId = $studentFeesArray[0]['quotaId'];  
    $isLeet = $studentFeesArray[0]['isLeet'];  
    
    $isMigration = $studentFeesArray[0]['isMigration'];  
    $migrationClassId = $studentFeesArray[0]['migrationClassId'];  
     
    $universityId = $studentFeesArray[0]['universityId'];
    $batchId = $studentFeesArray[0]['batchId'];
    $degreeId = $studentFeesArray[0]['degreeId'];
    $branchId = $studentFeesArray[0]['branchId'];
    $studyPeriodId = $studentFeesArray[0]['studyPeriodId'];
    $feeStudyPeriodId = $studentFeesArray[0]['feeStudyPeriodId'];
    
    $tIsLeet=2; 
    if($isLeet==1) {
      $tIsLeet=1;  
    }
    
    // Check Adhoc Concession 
    $adhocConcession=0; 
    $adhocCondition = " feeClassId = '$feeClassId' AND studentId = '$studentId' "; 
    $adhocConcessionArray = $collectStudentFeeManager->getCheckStudentConcession($adhocCondition); 
    if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0) {
      $adhocConcession = 1; 
      $conessionFormatId = 4;
    }  
    
    $ttPaidReceiptId = -1; 
    $applFinalArray = $collectStudentFeeManager->getStudentPaidReceipt($feeClassId,$studentId); 
    for($i=0;$i<count($applFinalArray);$i++) {
      $ttPaidReceiptId .=",". $applFinalArray[$i]['feeReceiptId'];
    }
    
    
    // ============ Fetch student Current Class Transport and Hostel Facility Check  (START) ==========================
        $studentFeesArray[0]['transportFacility'] =0; 
        $studentFeesArray[0]['hostelFacility'] = 0; 
        if($feeTypeId == '4' || $feeTypeId == '2') {     // Transport Check
             $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND IFNULL(fsf.facilityType,'') = 1 ";    
             $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
             if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                 $studentFeesArray[0]['transportFacility']=1;
                 $transportFacility = 1; 
                 $trCharges = $facilityArrayCheck[0]['charges'];
                 $trConcession = $facilityArrayCheck[0]['concession'];
                 
                 $prevApplTransport =0;
                 $condition  = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";    
                 $previousTransportArray = $collectStudentFeeManager->getPreviousTransportPaymentDetail($condition); 
                 if(is_array($previousTransportArray) && count($previousTransportArray)>0 ) { 
                    $prevTransportCharges=$trCharges-$trConcession;
                    $prevTransportFine=$previousTransportArray[0]['transportPrevFine'];
                    $prevTransportPaid=$previousTransportArray[0]['transportPrevPaid'];
                    $prevTransportDues=($prevTransportCharges+$prevTransportFine)-$prevTransportPaid; 
                    $prevApplTransport= $previousTransportArray[0]['transportApplPaid'];
                 }
             }
         }
     
         if($feeTypeId == '4' || $feeTypeId == '3') {    // Hostel Check  
             $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
             $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
             if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                 $hostelFacility = 1;
                 $studentFeesArray[0]['hostelFacility']=1;
                 $hrCharges = $facilityArrayCheck[0]['charges'];
                 $hrConcession = $facilityArrayCheck[0]['concession'];
                 
                 $prevApplHostel =0;
                 $condition = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";   
                 $previousHostelArray = $collectStudentFeeManager->getPreviousHostelPaymentDetail($condition); 
                 if(is_array($previousHostelArray) && count($previousHostelArray)>0 ) { 
                    $prevHostelCharges=$hrCharges-$hrConcession;
                    $prevHostelFine=$previousHostelArray[0]['hostelPrevFine'];
                    $prevHostelPaid=$previousHostelArray[0]['hostelPrevPaid'];
                    $prevHostelDues=($prevHostelCharges+$prevHostelFine)-$prevHostelPaid;
                    $prevApplHostel= $previousHostelArray[0]['hostelApplPaid'];   
                 }   
             }
         }
     // ============ Fetch student Current Class Transport and Hostel Facility Check  (END) ==========================    
     
  
      // ============= Fetch Student Fee Class Installment and payment Detail  (START) =======================    
         $condition = " f.studentId = '$studentId' AND f.feeClassId = '$feeClassId'";
         $cond = $condition." AND f.receiptStatus NOT IN (4) ";
         $foundArray = $collectStudentFeeManager->getCountInstallment($cond);   
         $studentInstallmentCount = $foundArray[0]['cnt']+1;
         
         $conditionPrev =  $condition." AND f.receiptStatus NOT IN (3,4) ";
         $foundArray = $collectStudentFeeManager->getPreviousFeePaymentDetail($conditionPrev);  
         if(is_array($foundArray) && count($foundArray)>0 ) {
            for($i=0; $i <count($foundArray); $i++) {
              $previousPaymentCurr += $foundArray[$i]['prevFeePaid'];  
              $previousFineCurr += $foundArray[$i]['prevFeeFine'];  
            }
         }
     // ============= Fetch Student Fee Class Installment and payment Detail  (END) =======================
     
     
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
             $condition='';
             $feeHeadDetailFind=0;                   
             $feeAmtPaidTotal=0;
             
             $showTFeeAmtPaid = 0;
             $showTTransportAmtPaid = 0;
             $showTHostelAmtPaid = 0;
             $showTDuesAmtPaid = 0;
                   
        
         // ======== Prev Dues START ===========
            $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$feeClassId' ";  
            $prevClassFeeArray = $collectStudentFeeManager->getPendingDuesList($prevCondition);  
            
            $rSrNo=1;
            $prevArray = array();
            for($i=0; $i<count($prevClassFeeArray); $i++) {
               if($prevClassFeeArray[$i]['dues'] != $prevClassFeeArray[$i]['paid']) {
                  $srNo=$rSrNo;  
                  $duesClassId = $prevClassFeeArray[$i]['classId'];
                  
                  $duesAmt = $prevClassFeeArray[$i]['dues'];
                  $paidAmt = $prevClassFeeArray[$i]['paid'];
                  
                  if($duesAmt=='') {
                    $duesAmt=0;  
                  }
                  if($paidAmt=='') {
                    $paidAmt=0;  
                  }
                  
                  $dif = doubleval($duesAmt) - doubleval($paidAmt);
                  
                  $showTDuesAmtPaid += doubleval($duesAmt) - doubleval($paidAmt);
                  
                  $headName='Dues-'.$prevClassFeeArray[$i]['periodName'];
                  
                  $feeHeadAmt ="<label id='HeadDuesAmt$duesClassId'>$duesAmt</label>
                                <input type='hidden' id='duesClass' name='duesClass[]' value='".$duesClassId."' maxlength=8 style='width:70px' class='inputbox2'>
                                <input type='hidden' id='duesCharges' name='duesCharges[]' value='".$dif."' maxlength=8 style='width:70px' class='inputbox2'>";
                                
                  $applFeeHead ="<label id='HeadDuesPaidAmt$duesClassId'>".$dif."</label>";                                
                  
                  $concession="";
                  feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applFeeHead);
                  $rSrNo = $rSrNo +1;  
               }
            }
         // ======== Prev Dues END ===========
     
         // var_dump($feeTypeId);die;
                   
         if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic
            // Quota wise Validation start
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

            //================================FEE HEAD DETAILS (Start)=======================================
            $foundArray = $collectStudentFeeManager->getStudentFeeHeadCollectionDetail($feeClassId,$feeId,$studentId);
            $feeHeadIds = "-1";
            for($i=0;$i<count($foundArray);$i++) {
               if($foundArray[$i]['feeHeadId']!='') {  
                 $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
               }
            }
            
            // Student Concession Findout is Leet & Non Leet Base 
            $concessionArray = $collectStudentFeeManager->getStudentConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$condition='');
            $concessionFeeHeadIds = "-1"; 
            for($i=0;$i<count($concessionArray);$i++) {
              $concessionFeeHeadIds .= ",".$concessionArray[$i]['feeHeadId'];   
            }
            
            $concessionCondition = " AND fcv.feeHeadId NOT IN ($concessionFeeHeadIds)";
            $concessionFinalArray = $collectStudentFeeManager->getStudentFinalConcession($feeClassId,$studentId,$feeHeadIds,$tIsLeet,$concessionCondition);
             
            $applCondition = " AND fhc.feeHeadId IN ($feeHeadIds) AND fhc.feeReceiptId IN ($ttPaidReceiptId) "; 
            $applFinalArray = $collectStudentFeeManager->getStudentFeeHeadCollection($feeClassId,$studentId,$applCondition); 
            
            $i=0;
            $concession=0;    
            for($i=0; $i<count($foundArray); $i++) {
               if($foundArray[$i]['feeId']=='') {
                 continue;  
               } 
               $feeHeadDetailFind=1;
               $foundArray[$i]['concession']=0;
               $feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
               $totalFees +=$foundArray[$i]['feeHeadAmt'];
               $salFeeHeadId =  $foundArray[$i]['feeHeadId'];
               
               $concession =0;     
               // Categories wise Concession 
               if($adhocConcession==1) {
                  for($jj=0;$jj<count($adhocConcessionArray);$jj++) {
                     if($adhocConcessionArray[$jj]['feeHeadId']==$salFeeHeadId) {  
                       $concession = $adhocConcessionArray[$jj]['concessionAmount']; 
                     }
                  }
               }
               else if($adhocConcession==0) {
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
                  if($adhocConcession==0) { 
                    $conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession);
                  }
                  else {
                    $conn = doubleval($concession);  
                  }
               }
               $foundArray[$i]['concession'] = $conn;
               
               $totalConcession += doubleval($foundArray[$i]['concession']);  
               
               $srNo = ($rSrNo)."<input type='hidden'  readonly='readonly'  id='feeId$feeId' name='feeId[]' value='".$feeId."'>";
                
               $feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
               $ids = "applAmt".$i;
               $feeHeadId = $foundArray[$i]['feeHeadId'];  
               
               // Applicable Amount Remaining Part Calculated 
               $ttApplTotal=$feesAmt;   
               $ttPrevPaid='';
               $dif=0;
               for($jj=0;$jj<count($applFinalArray);$jj++) {  
                  if($applFinalArray[$jj]['feeHeadId']==$salFeeHeadId) { 
                     $ttApplTotal= doubleval($ttApplTotal) - doubleval($applFinalArray[$jj]['feeHeadAmt']);
                     $dif = doubleval($feesAmt)-doubleval($ttApplTotal);
                     break;  
                  } 
               } 
               $ttPrevPaid = "<br><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$dif</span>";
               
               $applAmt="<input type='text' readonly='readonly'  name='applAmt[]'    id='applAmt$i'     value='$ttApplTotal'  maxlength='14' style='width:70px' class='inputbox2'>
                         <input type='hidden' readonly='readonly'  name='applAmtD[]'   id='applAmtD$i'    value='$ttApplTotal'  class='inputbox2' >
                         <input type='hidden' readonly='readonly'  name='feeHeadIds[]' id='feeHeadIds$i'  value='$feeHeadId'  class='inputbox2' >
                         <input type='hidden' readonly='readonly'  name='fIds[]' id='fIds$i'  value='$i'  class='inputbox2' >
                         $ttPrevPaid";
               
               $valueArray = array_merge(array('srNo' => $srNo,
                                               'applAmt' => $applAmt
                                              ), $foundArray[$i]);   
               
               $showTFeeAmtPaid += $ttApplTotal;
               
               $feeAmtPaidTotal += ($feesAmt-$dif);
               
               //if($ttApplTotal!='0' && $dif != $feesAmt) {   
                   if(trim($json_val)=='') {
                     $json_val = json_encode($valueArray);
                   }
                   else {
                     $json_val .= ','.json_encode($valueArray);           
                   }  
                   $rSrNo = $rSrNo +1;   
               //}
            }
            
            
            //================================FEE HEAD DETAILS (End)=======================================
         }
           
         
         if($transportFacility==1 && ($feeTypeId == '4' || $feeTypeId == '2')) {    // Only Transport
               $srNo=$rSrNo;
               $headName='Transport Charges';
               $feeHeadAmt="<input type='hidden'  readonly='readonly'  onblur='calculateConcession();'  name='transportDue' id='transportDue' maxlength=8 style='width:70px' class='inputbox2'>
                            <label id='lblTransportDue'>$trCharges</label>";
                            
               $concession="<input type='hidden'  readonly='readonly'  onblur='calculateConcession();'  name='transportConcession' id='transportConcession'  style='width:70px' maxlength=8 class='inputbox2'>
                            <label id='lblTransportConcession'>$trConcession</label>";
                           
               $trApplAmt = $trCharges - $trConcession;            
               
               // Applicable Amount Remaining Part Calculated 
               $ttApplTotal = doubleval($trApplAmt)-doubleval($prevApplTransport);
            
               $showTTransportAmtPaid = $ttApplTotal;
               
               $dif = doubleval($trApplAmt)-doubleval($ttApplTotal);
               
               $ttPrevPaid=''; 
               $ttPrevPaid = "<br><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$dif</span>";
               
               $applAmt="<input type='text' readonly='readonly' name='applAmtTransportPaid' id='applAmtTransportPaid' value='".$ttApplTotal."' maxlength='10' style='width:70px' class='inputbox2'>
                         <input type='hidden' readonly='readonly' name='txtApplAmtTransportPaid' id='txtApplAmtTransportPaid' value='".$ttApplTotal."' maxlength='10' style='width:70px' class='inputbox2'>$ttPrevPaid";
                                       
               //if($ttApplTotal!='0' && $dif != $trApplAmt) {
                 feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
                 $rSrNo = $rSrNo +1; 
              // }
               
              
         }
            
         if($hostelFacility==1 && ($feeTypeId == '4' || $feeTypeId == '3')) {   // Only Hostel
               $srNo=$rSrNo; 
               $headName='Hostel Charges';
               $feeHeadAmt="<input type='hidden'  readonly='readonly'  onblur='calculateConcession();' name='hostelDue' id='hostelDue' maxlength=8 style='width:70px' class='inputbox2'>
                            <label id='lblTransportConcession'>$hrCharges</label>";
                            
               $concession="<input type='hidden'  readonly='readonly'  onblur='calculateConcession();' name='hostelConcession' id='hostelConcession'  style='width:70px' maxlength=8 class='inputbox2'>
                           <label id='lblTransportConcession'>$hrConcession</label>";
                
              
               $hrApplAmt =  $hrCharges - $hrConcession;   
               
               // Applicable Amount Remaining Part Calculated
               $ttApplTotal = doubleval($hrApplAmt)-doubleval($prevApplHostel);
              
               $showTHostelAmtPaid = $ttApplTotal;
                
               $dif = doubleval($hrApplAmt)-doubleval($ttApplTotal);
               $ttPrevPaid='';
               $ttPrevPaid = "<br><span align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'>$lblName$dif</span>";
                
                                        
               $applAmt="<input type='text' readonly='readonly' name='applAmtHostelPaid' id='applAmtHostelPaid' value='".$ttApplTotal."' maxlength='10' style='width:70px' class='inputbox2'>
                 <input type='hidden'  readonly='readonly' name='txtApplAmtHostelPaid' id='txtApplAmtHostelPaid' value='".$ttApplTotal."' maxlength='10' style='width:70px' class='inputbox2'>$ttPrevPaid";
               //if($ttApplTotal!='0' && $dif != $trApplAmt) {
               feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
               $rSrNo = $rSrNo +1;
              // }
               
              
         }
         
        
           
         
         
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
     
      $netAmount = 0;
     // Check Net Payable Amount 
     if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic  
        //$totalFees = $totalFees + $previousFineCurr;
        $netAmount = (($totalFees-$totalConcession)+$previousFineCurr)-$previousPaymentCurr; 
     }
     
    
     
     $transportTotalFees=0;
     $hostelTotalFees=0;
     if($transportFacility==1 && ($feeTypeId == '4' || $feeTypeId == '2')) {    // Only Transport
        $prevTransportCharges =  $trApplAmt;
        $netAmount += ($trApplAmt+$prevTransportFine)-$prevTransportPaid;  
        $prevTransportDues = ($trApplAmt+$prevTransportFine)-$prevTransportPaid;  
        $transportTotalFees = ($trApplAmt+$prevTransportFine)-$prevTransportPaid;
     }
     
     if($hostelFacility==1 && ($feeTypeId == '4' || $feeTypeId == '3')) {    // Only Hostel      
        $prevHostelCharges =  $hrApplAmt;
        $netAmount += ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;  
        $prevHostelDues = ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;  
        $hostelTotalFees =  ($hrApplAmt+$prevHostelFine)-$prevHostelPaid;
     }
     
     $json_student =  json_encode($studentFeesArray[0]);     
     
     
     
     // echo $json_val; die;
     
     echo '{"previousFees":"'.$previousFees.'","previousPayment":"'.$previousPayment.'","totalFees":"'.$totalFees.'",
            "transportTotalFees":"'.$transportTotalFees.'", "hostelTotalFees":"'.$hostelTotalFees.'", 
            "studentInstallmentCount":"'.$studentInstallmentCount.'","studentFine":"'.$studentFine.'","hostelFacility":"'.$hostelFacility.'",
            "transportFacility":"'.$transportFacility.'","previousFine":"'.$previousFine.'",
            "prevTransportCharges":"'.$prevTransportCharges.'","prevTransportFine":"'.$prevTransportFine.'",
            "prevTransportPaid":"'.$prevTransportPaid.'","prevTransportDues":"'.$prevTransportDues.'",
            "prevHostelCharges":"'.$prevHostelCharges.'","prevHostelFine":"'.$prevHostelFine.'",
            "prevHostelPaid":"'.$prevHostelPaid.'","prevHostelDues":"'.$prevHostelDues.'",
            "totalConcession":"'.$totalConcession.'","netAmount":"'.$netAmount.'",
            "previousPaymentCurr":"'.$previousPaymentCurr.'","previousFineCurr":"'.$previousFineCurr.'",
            "feeHeadDetailFind":"'.$feeHeadDetailFind.'","favouringBankBranchId":"'.$favouringBankBranchId.'",
            "feeAmtPaidTotal":"'.$feeAmtPaidTotal.'","conessionFormatId":"'.$conessionFormatId.'", 
            "showTFeeAmtPaid":"'.$showTFeeAmtPaid.'","showTTransportAmtPaid":"'.$showTTransportAmtPaid.'", 
            "showTHostelAmtPaid":"'.$showTHostelAmtPaid.'","showTDuesAmtPaid":"'.$showTDuesAmtPaid.'",
            "studentCurrentStatus":"'.$studentCurrentStatus.'",
            "studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}'; 
die;  
     
  
  
function feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt='') {
    global $json_val; 
    
    if($applAmt=='') {
      $applAmt = NOT_APPLICABLE_STRING;  
    }
    $valueArray = array_merge(array('srNo' => $srNo,
                                    'headName' =>$headName,
                                    'feeHeadAmt' =>$feeHeadAmt,
                                    'concession' =>$concession,
                                    'applAmt' => trim($applAmt) ));   
    if(trim($json_val)=='') {
      $json_val = json_encode($valueArray);
    }
    else {
      $json_val .= ','.json_encode($valueArray);           
    }                                    
}      
  
?>
