<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFees');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CollectFeesManager.inc.php");   
    $collectFeesManager = CollectFeesManager::getInstance(); 
    
    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");   
    $feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
    
	global $sessionHandler;
	/* START: function to fetch student details along with class */
    
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
    
    $condition = " AND (stu.rollNo='".$rollNo."' OR stu.regNo='".$rollNo."' OR stu.universityRollNo='".$rollNo."') ";
    $studentFeesArray = $collectFeesManager->getStudentDetailClass($condition,$feeClassId);     
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
    
     //$hostelFacility = $studentFeesArray[0]['hostelFacility']; 
     //$transportFacility = $studentFeesArray[0]['transportFacility']; 
    
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
     
     
     
     // =============== Fetch Student Previous Classes Dues  (START) ==================
        if($includePreviousDues==1) {
            $condition  = " AND c.universityId = $universityId AND c.batchId = $batchId AND c.degreeId = $degreeId AND c.branchId= $branchId ";
            $condition .= " AND c.studyPeriodId < $feeStudyPeriodId ";
                 
            if($isMigration=='1') {
              $condition .= " AND c.classId >=$migrationClassId ";  
            }
            else if($isLeet=='1') {
              $condition .= " AND sp.periodValue >=3 "; 
            }
            
            
            //AND sg.studentId = $studentId AND sg.classId = c.classId AND fc.classId = c.classId ";
            $previousClassArray = $collectFeesManager->getStudentPreviousClassDetail($condition);
            if(is_array($previousClassArray) && count($previousClassArray)>0 ) { 
              for($i=0; $i<count($previousClassArray); $i++) { 
                  $prevFeeClassId = $previousClassArray[$i]['classId'];
                  
                  $condition = " f.studentId = '$studentId' AND f.feeClassId = '$prevFeeClassId' AND f.receiptStatus NOT IN (3,4) ";
                  $foundArray = $collectFeesManager->getPreviousFeePaymentDetail($condition);  
                  if(is_array($foundArray) && count($foundArray)>0 ) {
                    for($i=0; $i <count($foundArray); $i++) {
                      $previousPayment += $foundArray[$i]['prevFeePaid'];  
                      $previousFine += $foundArray[$i]['prevFeeFine'];  
                    }
                  }
                  
                  $trCharges=0;
                  $trConcession=0;
                  $condition  = " fsf.studentId = $studentId AND fsf.classId = '$prevFeeClassId' AND fsf.facilityType = 1";    
                  $facilityArrayCheck = $collectFeesManager->getFacility($condition);   
                  if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                     $trCharges = $facilityArrayCheck[0]['charges'];
                     $trConcession = $facilityArrayCheck[0]['concession'];
                  }
                  $condition  = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$prevFeeClassId' ";    
                  $previousTransportArray = $collectFeesManager->getPreviousTransportPaymentDetail($condition); 
                  if(is_array($previousTransportArray) && count($previousTransportArray)>0 ) { 
                     $prevTransportCharges= $trCharges-$trConcession;
                     $prevTransportFine= $previousTransportArray[0]['transportPrevFine'];
                     $prevTransportPaid= $previousTransportArray[0]['transportPrevPaid'];
                     $prevTransportDues +=($prevTransportCharges+$prevTransportFine)-$prevTransportPaid; 
                  }
                        
                  $hrCharges=0;
                  $hrConcession=0;
                  $condition  = " fsf.studentId = $studentId AND fsf.classId = '$prevFeeClassId' AND fsf.facilityType = 2";    
                  $facilityArrayCheck = $collectFeesManager->getFacility($condition);   
                  if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                     $hrCharges = $facilityArrayCheck[0]['charges'];
                     $hrConcession = $facilityArrayCheck[0]['concession'];
                  }      
                  $condition = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$prevFeeClassId' ";   
                  $previousHostelArray = $collectFeesManager->getPreviousHostelPaymentDetail($condition); 
                  if(is_array($previousHostelArray) && count($previousHostelArray)>0 ) { 
                     $prevHostelCharges= $hrCharges-$hrConcession;
                     $prevHostelFine= $previousHostelArray[0]['hostelPrevFine'];
                     $prevHostelPaid= $previousHostelArray[0]['hostelPrevPaid'];
                     $prevHostelDues += ($prevHostelCharges+$prevHostelFine)-$prevHostelPaid;
                  }
                  
              }  // END For Loop
            } // END If condition
         }  // END If condition   (includePreviousDues)
     // =============== Fetch Student Previous Classes Dues  (END) ==================
     
     
     
     // ============= Fetch Student Fee Class Installment and payment Detail  (START) =======================    
         $condition = " f.studentId = '$studentId' AND f.feeClassId = '$feeClassId'";
         $cond = $condition." AND f.receiptStatus NOT IN (4) ";
         $foundArray = $collectFeesManager->getCountInstallment($cond);   
         $studentInstallmentCount = $foundArray[0]['cnt']+1;
         
         $conditionPrev =  $condition." AND f.receiptStatus NOT IN (3,4) ";
         $foundArray = $collectFeesManager->getPreviousFeePaymentDetail($conditionPrev);  
         if(is_array($foundArray) && count($foundArray)>0 ) {
            for($i=0; $i <count($foundArray); $i++) {
              $previousPaymentCurr += $foundArray[$i]['prevFeePaid'];  
              $previousFineCurr += $foundArray[$i]['prevFeeFine'];  
            }
         }
     // ============= Fetch Student Fee Class Installment and payment Detail  (END) =======================
     
      $transportFacility=0;  
      $hostelFacility=0;
      $studentFeesArray[0]['transportFacility'] =0; 
      $studentFeesArray[0]['hostelFacility'] = 0; 
     
     // ============ Fetch student Current Class Transport and Hostel Facility Check  (START) ==========================
         if($feeTypeId == '4' || $feeTypeId == '2') {  
             $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND fsf.facilityType = 1";    
             $facilityArrayCheck = $collectFeesManager->getFacility($condition);   
             if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                 $studentFeesArray[0]['transportFacility']=1;
                 $transportFacility = 1; 
                 $trCharges = $facilityArrayCheck[0]['charges'];
                 $trConcession = $facilityArrayCheck[0]['concession'];
                 
                 $condition  = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";    
                 $previousTransportArray = $collectFeesManager->getPreviousTransportPaymentDetail($condition); 
                 if(is_array($previousTransportArray) && count($previousTransportArray)>0 ) { 
                    $prevTransportCharges=$trCharges-$trConcession;
                    $prevTransportFine=$previousTransportArray[0]['transportPrevFine'];
                    $prevTransportPaid=$previousTransportArray[0]['transportPrevPaid'];
                    $prevTransportDues=($prevTransportCharges+$prevTransportFine)-$prevTransportPaid; 
                 }
             }
         }
     
         if($feeTypeId == '4' || $feeTypeId == '3') {      
             $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND fsf.facilityType = 2";                    
             $facilityArrayCheck = $collectFeesManager->getFacility($condition);   
             if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                 $hostelFacility = 1;
                 $studentFeesArray[0]['hostelFacility']=1;
                 $hrCharges = $facilityArrayCheck[0]['charges'];
                 $hrConcession = $facilityArrayCheck[0]['concession'];
                 
                 $condition = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";   
                 $previousHostelArray = $collectFeesManager->getPreviousHostelPaymentDetail($condition); 
                 if(is_array($previousHostelArray) && count($previousHostelArray)>0 ) { 
                    $prevHostelCharges=$hrCharges-$hrConcession;
                    $prevHostelFine=$previousHostelArray[0]['hostelPrevFine'];
                    $prevHostelPaid=$previousHostelArray[0]['hostelPrevPaid'];
                    $prevHostelDues=($prevHostelCharges+$prevHostelFine)-$prevHostelPaid;
                 }   
             }
         }
     // ============ Fetch student Current Class Transport and Hostel Facility Check  (END) ==========================    
  
  
        
        
     $condition='';
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
         $feeHeadDetailFind=0;                   
         $feeAmtPaidTotal=0;
         $i=0;
         if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic
            // Quota wise Validation start
            $feeId = "-1";
            $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
            $foundArray = $collectFeesManager->getCountFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$havingConditon);
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
                  $quotaFoundArray = $collectFeesManager->getQuotaFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$feeHeadCondition);  
                  if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                    $feeId .=",".$quotaFoundArray[0]['feeId'];  
                  }
                  else {
                    $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                    $quotaFoundArray = $collectFeesManager->getQuotaFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$feeHeadCondition);  
                    if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                      $feeId .=",".$quotaFoundArray[0]['feeId'];  
                    }
                    else {
                       $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                       $quotaFoundArray = $collectFeesManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                       if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                         $feeId .=",".$quotaFoundArray[0]['feeId'];  
                       }
                    }
                  }
               }
               else {
                 $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                 $quotaFoundArray = $collectFeesManager->getQuotaFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$feeHeadCondition);  
                 if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                   $feeId .=",".$quotaFoundArray[0]['feeId'];  
                 } 
               }
            }        
            if($feeId=='') {
              $feeId = "-1"; 
            }
            // Quota wise Validation end
            
            
            $foundArray = $collectFeesManager->getStudentFeeHeadDetail($studentId,$feeClassId,$quotaId,$isLeet,'',$feeId);
            $i=0;
            for($i=0; $i<count($foundArray); $i++) {
               $feeHeadDetailFind=1;
               $feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
               $totalFees +=$foundArray[$i]['feeHeadAmt'];
               
               $concessionType  = $foundArray[$i]['concessionType'];
               $concessionValue = $foundArray[$i]['concessionValue']; 
               $discountValue = $foundArray[$i]['discountValue'];    
               
               if($concessionType==1) {
                 $ttAmt = $foundArray[$i]['feeHeadAmt'] * $concessionValue/100.0; 
                 $foundArray[$i]['concession'] = $ttAmt;
               }   
               else if($concessionType==2) {
                 $foundArray[$i]['concession'] = $concessionValue; 
               }
               $totalConcession +=$foundArray[$i]['concession'];  
               
               
               $srNo = ($records+$i+1)."<input type='hidden' id='feeId$feeId' name='feeId[]' value='".$feeId."'>";
              
               $feesAmt = $foundArray[$i]['feeHeadAmt'] - $foundArray[$i]['concession'];
               $ids = "applAmt".$i;
               $feeHeadId = $foundArray[$i]['feeHeadId'];
               $applAmt="<input type='text'   name='applAmt[]'    id='applAmt'.$i     value='$feesAmt'    maxlength='14' style='width:70px' class='inputbox2'>
                         <input type='hidden' name='applAmtD[]'   id='applAmtD'.$i    value='$feesAmt'    class='inputbox2' >
                         <input type='hidden' name='feeHeadIds[]' id='feeHeadIds'.$i  value='$feeHeadId'  class='inputbox2' >";
               $valueArray = array_merge(array('srNo' => $srNo,
                                               'applAmt' => $applAmt
                                              ), $foundArray[$i]);   
               
               $feeAmtPaidTotal +=$feesAmt;
               if(trim($json_val)=='') {
                  $json_val = json_encode($valueArray);
               }
               else {
                  $json_val .= ','.json_encode($valueArray);           
               }
            }
            $srNo=($i+1);  
            $headName='Fee Fine';
            //$feeHeadAmt="<input type='text' id='studentFine' name='studentFine' onChange='calculateConcession();' maxlength=8 style='width:70px' class='inputbox2'>";
            $feeHeadAmt  ="<input type='text' onchange='calculateConcession();' id='studentFine' name='studentFine' maxlength=8 style='width:70px' class='inputbox2'>";
            $applFeeHead ="<input type='text' name='studentFineApplAmt' id='studentFineApplAmt' style='width:70px' class='inputbox2'>";
                         
            $concession="";
            feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applFeeHead);
            $i=$i+1;
         }
           
         if($transportFacility==1 && ($feeTypeId == '4' || $feeTypeId == '2')) {    // Only Transport
               $srNo = ($i+1);
               $headName='Transport Charges';
               $feeHeadAmt="<input type='hidden' onblur='calculateConcession();'  name='transportDue' id='transportDue' maxlength=8 style='width:70px' class='inputbox2'>
                            <label id='lblTransportDue'>$trCharges</label>";
                            
               $concession="<input type='hidden' onblur='calculateConcession();'  name='transportConcession' id='transportConcession'  style='width:70px' maxlength=8 class='inputbox2'>
                            <label id='lblTransportConcession'>$trConcession</label>";
                           
               $trApplAmt =  $trCharges - $trConcession;            
               $applAmt="<input type='text'    name='applAmtTransportPaid'   id='applAmtTransportPaid' value='".$trApplAmt."' maxlength='8' style='width:70px' class='inputbox2'>";
               $i=$i+1;
               feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
               
               $srNo=($i+1);  
               $headName='Transport Fine';
               $feeHeadAmt="<input type='text' onblur='calculateConcession();' name='transportFine' id='transportFine'  maxlength=8 style='width:70px' class='inputbox2'>";
               $applAmt="<input type='text'    name='applAmtTransportFine'   id='applAmtTransportFine' maxlength='8' style='width:70px' class='inputbox2'>";
               $concession="";
               feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
         }
            
         if($hostelFacility==1 && ($feeTypeId == '4' || $feeTypeId == '3')) {   // Only Hostel
               $srNo = ($i+1);
               $headName='Hostel Charges';
               $feeHeadAmt="<input type='hidden' onblur='calculateConcession();' name='hostelDue' id='hostelDue' maxlength=8 style='width:70px' class='inputbox2'>
                            <label id='lblTransportConcession'>$hrCharges</label>";
                            
               $concession="<input type='hidden' onblur='calculateConcession();' name='hostelConcession' id='hostelConcession'  style='width:70px' maxlength=8 class='inputbox2'>
                           <label id='lblTransportConcession'>$hrConcession</label>";
                
               $hrApplAmt =  $hrCharges - $hrConcession;                            
               $applAmt="<input type='text'   name='applAmtHostelPaid' id='applAmtHostelPaid' value='".$hrApplAmt."' maxlength='8' style='width:70px' class='inputbox2'>";
               $i=$i+1;
             
               feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
               
               $srNo=($i+1);  
               $headName='Hostel Fine';
               $feeHeadAmt="<input type='text' onblur='calculateConcession();' name='hostelFine' id='hostelFine'  maxlength=8 style='width:70px' class='inputbox2'>";
               $applAmt="<input type='text'    name='applAmtHostelFine'   id='applAmtHostelFine' maxlength='8' style='width:70px' class='inputbox2'>";
               $concession="";
               feeTypeFormat($srNo,$headName,$feeHeadAmt,$concession,$applAmt);
               $i=$i+1; 
         }
     // =========== Fetch Student Fee Head Wise Detail (START) ==============================
     
     $netAmount = 0;
     // Check Net Payable Amount 
     if($feeTypeId == '4' || $feeTypeId == '1') {           // Only Academic  
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
            "feeAmtPaidTotal":"'.$feeAmtPaidTotal.'",
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