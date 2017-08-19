<?php 
//This file is used as printing version for payment receipt.
//
// Author :Rajeev Aggarwal
// Created on : 29-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance();
    
    //require_once(TEMPLATES_PATH . "/CollectFees/paymentReceiptTemplate.php");  
    require_once(TEMPLATES_PATH . "/CollectFees/collectPaymentReceiptTemplate.php");  
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    require_once(MODEL_PATH . "/FeeReportManager.inc.php");
    $feeReportManager = FeeReportManager::getInstance();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance(); 
    
	require_once(BL_PATH . '/NumToWord.class.php');
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    global $sessionHandler;        
    global $receiptArr;
    global $receiptPaymentArr; 
    
    
    $captionFine=$sessionHandler->getSessionVariable('LABEL_FINE');
    $captionHostel=$sessionHandler->getSessionVariable('LABEL_HOSTEL');
    $captionTransport=$sessionHandler->getSessionVariable('LABEL_TRANSPORT'); 

    // Fee Type Details
    //$feeTypeArr = array("4"=>"All","1"=>"Only Academic","2"=>"Only Transport","3"=>"Only Hostel");    
    
    // Receipt Payment Arr Details
    //$receiptPaymentArr = array("1"=>"With Clerk","2"=>"With Bank", "3"=>"Closed","4"=>"Bounced");
    
    $receiptNo = add_slashes(trim($REQUEST_DATA['id']));
    $receiptId = add_slashes(trim($REQUEST_DATA['receiptId'])); 
    
    $inclHeadDetail = add_slashes(trim($REQUEST_DATA['feeHeadChk']));
    $inclPaymentDetail = add_slashes(trim($REQUEST_DATA['paymentChk']));
    
    if($inclHeadDetail=='') {
      $inclHeadDetail=0;  
    }
    
    if($inclPaymentDetail=='') {
      $inclPaymentDetail=0;  
    }
    
    if($receiptNo=='') {
      $receiptNo=0;  
    }
    
    
    // Fetch Student Receipt Detail
    if($receiptNo!='') {
      $condition = " AND f.receiptNo = '$receiptNo' AND f.receiptStatus NOT IN (3,4)  ";
      $condition1 = " receiptNo = '$receiptNo'";  
    }
    else if($receiptId!='') {
       $inclHeadDetail=1;  
       $inclPaymentDetail=1;  
       $condition = " AND f.feeReceiptId = '$receiptId' AND f.receiptStatus NOT IN (3,4)  ";   
       
       $condition1 = " feeReceiptId = '$receiptId'";  
    }
    $studentFeesArray = $collectStudentFeeManager->getStudentReceiptPrintDetail($condition);  
    
    
    $printRemarkArray = $studentManager->getSingleField("fee_receipt", "IFNULL(printRemarks,'') AS printRemarks ", "WHERE ".$condition1 );
    $printRemarks = $printRemarkArray[0]['printRemarks'];
    $condition1='';
    
    
    $netPaidAmount='';
    if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) { 
        
            $receiptData = $paymentReceiptPrint;
            
            $conessionFormatId  = $studentFeesArray[0]['isConessionFormat'];  
            $studentId  = $studentFeesArray[0]['studentId'];
            $feeCycleId = $studentFeesArray[0]['feeCycleId'];
            $feeClassId = $studentFeesArray[0]['feeClassId'];
            $quotaId    = $studentFeesArray[0]['quotaId'];
            $isLeet     = $studentFeesArray[0]['isLeet'];
            $feeReceiptId = $studentFeesArray[0]['feeReceiptId']; 
            $studentInstallmentCount = $studentFeesArray[0]['installmentCount'];    
            $feeType   = $studentFeesArray[0]['feeType'];
            $feeTypeId = $feeType; 
            
            $chkInstallment = $studentInstallmentCount;
            
            $tIsLeet=2; 
            if($isLeet==1) {
              $tIsLeet=1;  
            }
            
            // Check Adhoc Concession 
            $adhocConcession=0; 
            if($conessionFormatId==4) {
               $adhocConcession=1;  
               $adhocCondition = " feeClassId = '$feeClassId' AND studentId = '$studentId' "; 
               $adhocConcessionArray = $collectStudentFeeManager->getCheckStudentConcession($adhocCondition); 
               if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0 ) {
                  $adhocConcession = 1; 
               }  
            }
            
            
        //***********************************************Display Institue Detail (START) ************************************************  
            $insFileName = $studentFeesArray[0]['instituteLogo'];
            $insLogo='';  
            
            $icardLogo = IMG_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO');    
            if(file_exists($icardLogo)) {
                $icardLogo = IMG_HTTP_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO')."?zz=".rand(0,1000);    
                $insLogo = "<img  src=\"".$icardLogo." \"height=\"40px\" \"width=\"85px\" valign=\"middle\" >";
            }
            else if(file_exists(IMG_PATH.'/Institutes/'.$insFileName)=="") {
               $insFileName = IMG_HTTP_PATH."/Institutes/".$insFileName."?ii=".rand(0,1000);
               $insLogo = "<img src=\"".$insFileName."\" height=\"50px\" width=\"50px\">";                                        
            }
             
            $receiptData = str_replace("<InstituteLogo>",$insLogo,$receiptData);
            $receiptData = str_replace("<InstituteName>",$studentFeesArray[0]['instituteName'],$receiptData); 
            
            $insAddress = $studentFeesArray[0]['insAddress'];
            //$insAddress .= "<br>".$studentFeesArray[0]['insCityName'];
            //$insAddress .= " ".$studentFeesArray[0]['insStateName']." ".$studentFeesArray[0]['insCountryName'];
            //$insAddress .= " ".$studentFeesArray[0]['insPinCode'];
            $insAddress .= "<br>".$studentFeesArray[0]['insContactNo']; ;
            
            $receiptData = str_replace("<InstituteAddress>",$insAddress,$receiptData); 
        //***********************************************Display Institue Detail (END) ************************************************  
        
        
        
        
        //***********************************************Display Student Personal & Receipt Detail (START) *****************************
            $receiptData = str_replace("<By1>",$studentFeesArray[0]['by1'],$receiptData); 
            $receiptData = str_replace("<StudentName>",$studentFeesArray[0]['studentName'],$receiptData); 
            $receiptData = str_replace("<By2>",$studentFeesArray[0]['by2'],$receiptData); 
            $receiptData = str_replace("<FatherName>",$studentFeesArray[0]['fatherName'],$receiptData); 
           
            $receiptDate = UtilityManager::formatDate($studentFeesArray[0]['receiptDate']);
            $receiptData = str_replace("<ReceiptNo>",$studentFeesArray[0]['receiptNo'],$receiptData); 
            $receiptData = str_replace("<ReceiptDate>",$receiptDate,$receiptData);
            $receiptData = str_replace("<Semester>",$studentFeesArray[0]['semester'],$receiptData);
            $receiptData = str_replace("<Branch>",$studentFeesArray[0]['branchName'],$receiptData);
            $receiptData = str_replace("<RollNo>",$studentFeesArray[0]['rollNo'],$receiptData);
            $receiptData = str_replace("<RegNo>",$studentFeesArray[0]['regNo'],$receiptData);
            
            if($inclHeadDetail==1) {
              $following = "towards the following:"; 
              $receiptData = str_replace("<Following>",$following,$receiptData);
            }
            
            $amtPaid = $studentFeesArray[0]['feePaid']+$studentFeesArray[0]['transportPaid']+$studentFeesArray[0]['hostelPaid']+$studentFeesArray[0]['duesPaid'];
            
            $receiptData = str_replace("<Amount>",$amtPaid,$receiptData);
            $num = new NumberToWord($amtPaid);
            $num1 = trim(ucwords(strtolower($num->word)));
            if($num1!='') {
              $num1 .=" Only";  
            }
            $receiptData = str_replace("<WordsAmount>",trim($num1),$receiptData);
       //***********************************************Display Student Personal & Receipt Detail (END) *****************************
       
       
         
       //***********************************************Fee Head Details (START) **********************************     
            $netPaidAmount=0;
            
             
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
            // Quota wise Validation end
            
           //================================FEE HEAD DETAILS (Start)=======================================   
            $feeHeads=""; 
            $feeHeads = "<tr>
                            <td class='dataFont' align='left'  width='5%'><b>#</b></td>
                            <td class='dataFont' align='left'  width='55%'><b>Particulars</b></td>
                            <td class='dataFont' align='right' width='18%' nowrap><b>Amount<br>(Due)</b></td> 
                            <td class='dataFont' align='right' width='18%' nowrap><b>Concession</b></td> 
                            <td class='dataFont' align='right' width='25%' nowrap><b>Amount<br>(Paid)</b></td> 
                         </tr>"; 
            
            $rSrNo=1;             
            $discountedFeePayable = 0;       
            
            // ======== Prev Dues START ===========       
            $srNo=$rSrNo;       
            $prevArray = array();         
            $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$feeClassId' ";  
            $prevClassFeeArray = $collectStudentFeeManager->getPendingDuesList($prevCondition);  
            for($i=0; $i<count($prevClassFeeArray); $i++) {
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
                  $showTDuesAmtPaid += doubleval($duesAmt) - doubleval($paidAmt);
                  
                  $headName='Dues-'.$prevClassFeeArray[$i]['periodName'];
                  
                  $concession="";
                  $feeHeads .= printFeeHeadValues($srNo,$headName,$duesAmt,NOT_APPLICABLE_STRING,$paidAmt);
                  $rSrNo = $rSrNo +1;  
            }
            // ======== Prev Dues END ===========       
            
                 
            if($feeType==4 || $feeType==1) {                    // Set Academic Fee
                    $fineHeadCollection=NOT_APPLICABLE_STRING; 

                    $foundArray = $collectStudentFeeManager->getStudentFeeHeadDetail($feeClassId,$feeId,$studentId);
                    $feeHeadIds = "-1";
                    for($i=0;$i<count($foundArray);$i++) {
                      $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
                    }
                    
                    $applCondition = " AND fhc.feeHeadId IN ($feeHeadIds) AND fhc.feeReceiptId = $feeReceiptId"; 
                    $applFinalArray = $collectStudentFeeManager->getStudentFeeHeadCollection($feeClassId,$studentId,$applCondition);
                    
                    
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
                       
                       $feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
                       $feeAmtPaidTotal +=$feesAmt;
                       
                       if($conn=='' || $conn=='0') {
                          $conn=NOT_APPLICABLE_STRING;  
                       }
                        
                       $headCollectionAmount=NOT_APPLICABLE_STRING; 
                       for($jj=0;$jj<count($applFinalArray);$jj++) {  
                          if($applFinalArray[$jj]['feeHeadId']==$salFeeHeadId) { 
                             $headCollectionAmount=doubleval($applFinalArray[$jj]['feeHeadAmt']); 
                             break;  
                          } 
                       } 
                       
                       $discountedFeePayable += (doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($conn));
                       
                       $srNo=$rSrNo; 
                       $headName = ucwords($foundArray[$i]['headName']);
                       $feeHeads .= printFeeHeadValues($srNo,$headName,$foundArray[$i]['feeHeadAmt'],$conn,$headCollectionAmount);
                       $rSrNo = $rSrNo +1;
                    }
                    
                    $applFineCondition = " AND fhc.feeHeadType = 2 AND fhc.feeReceiptId = $feeReceiptId"; 
                    $applFineArray = $collectStudentFeeManager->getStudentFeeHeadCollection($feeClassId,$studentId,$applFineCondition);
                    $fineHeadCollection = $applFineArray[0]['feeHeadAmt'];
                    if($fineHeadCollection=='') {
                      $fineHeadCollection = NOT_APPLICABLE_STRING;  
                    }
                    $headName = $captionFine; 
                    $fine =  number_format($studentFeesArray[0]['fine'],0);
                    if($fine!=0 || $fineHeadCollection!=NOT_APPLICABLE_STRING) {  
                      $srNo=$rSrNo;               
                      //$discountedFeePayable += doubleval($fine);  
                      $feeHeads .= printFeeHeadValues($srNo,$headName,$fine,NOT_APPLICABLE_STRING,$fineHeadCollection);
                      $rSrNo = $rSrNo +1;
                    }
            }                
            //================================FEE HEAD DETAILS (End)=======================================
         
            
             if($feeTypeId == '4' || $feeTypeId == '2') {  
                 $condition  = " fsf.studentId = $studentId AND fsf.classId = '$feeClassId' AND fsf.facilityType = 1";    
                 $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
                 
                 if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                     $studentFeesArray[0]['transportFacility']=1;
                     $transportFacility = 1; 
                     $trCharges = $facilityArrayCheck[0]['charges'];
                     $trConcession = $facilityArrayCheck[0]['concession'];
                     
                     $discountedFeePayable += (doubleval($trCharges)-doubleval($trConcession)); 
                       
                     $headName = "Transport Charges"; 
                     $transportDues =  number_format($trCharges,0);
                     $conn =  number_format($trConcession,0);  
                     $applTransport =  number_format($studentFeesArray[0]['applTransport'],0);  
                     if($transportDues!=0 || $conn!=0 || $applTransport!=0) {    
                       $srNo=$rSrNo;
                       $feeHeads .= printFeeHeadValues($srNo,$headName,$transportDues,$conn,$applTransport);
                       $rSrNo = $rSrNo +1; 
                     }
                    
                     //$headName = "Transport Fine"; 
                     $headName=$captionTransport;
                     $transportDues =  number_format($studentFeesArray[0]['transportFine'],0);
                     $conn = NOT_APPLICABLE_STRING;  
                     $applTransport =  number_format($studentFeesArray[0]['applTransportFine'],0);  
                     if($transportDues!=0 || $conn!=NOT_APPLICABLE_STRING || $applTransport!=0) {
                       $srNo=$rSrNo;
                       $feeHeads .= printFeeHeadValues($srNo,$headName,$transportDues,$conn,$applTransport); 
                       $rSrNo = $rSrNo +1; 
                     }
                     
                     $condition  = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";    
                     $previousTransportArray = $collectStudentFeeManager->getPreviousTransportPaymentDetail($condition); 
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
                 $facilityArrayCheck = $collectStudentFeeManager->getFacility($condition);   
                 if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
                     $hostelFacility = 1;
                     $studentFeesArray[0]['hostelFacility']=1;
                     $hrCharges = $facilityArrayCheck[0]['charges'];
                     $hrConcession = $facilityArrayCheck[0]['concession'];
                     
                     $discountedFeePayable += (doubleval($hrCharges)-doubleval($hrConcession)); 
                     
                     $headName = "Hostel Charges"; 
                     $hostelDues =  number_format($hrCharges,0);
                     $conn =  number_format($hrConcession,0);  
                     $applHostel =  number_format($studentFeesArray[0]['applHostel'],0);  
                     if($hostelDues!=0 || $conn!=0 || $applHostel!=0) {
                       $srNo = $rSrNo;   
                       $feeHeads .= printFeeHeadValues($srNo,$headName,$hostelDues,$conn,$applHostel);
                       $rSrNo = $rSrNo +1;  
                     }
                    
                     //$headName = "Hostel Fine"; 
                     $headName=$captionHeadHostel;
                     $hostelDues =  number_format($studentFeesArray[0]['hostelFine'],0);
                     $conn = NOT_APPLICABLE_STRING;  
                     $applHostel =  number_format($studentFeesArray[0]['applHostelFine'],0);  
                     if($hostelDues!=0 || $conn!=NOT_APPLICABLE_STRING || $applHostel!=0) {       
                       $srNo = $rSrNo;      
                       $feeHeads .= printFeeHeadValues($srNo,$headName,$hostelDues,$conn,$applHostel);
                       $rSrNo = $rSrNo +1;                     
                     }
                     
                     $condition = " f.studentId = $studentId AND f.receiptStatus NOT IN (3,4) AND f.feeClassId = '$feeClassId' ";   
                     $previousHostelArray = $collectStudentFeeManager->getPreviousHostelPaymentDetail($condition); 
                     if(is_array($previousHostelArray) && count($previousHostelArray)>0 ) { 
                        $prevHostelCharges=$hrCharges-$hrConcession;
                        $prevHostelFine=$previousHostelArray[0]['hostelPrevFine'];
                        $prevHostelPaid=$previousHostelArray[0]['hostelPrevPaid'];
                        $prevHostelDues=($prevHostelCharges+$prevHostelFine)-$prevHostelPaid;
                     }   
                 }
             }
            
            if($feeHeads=='') {
                $feeHeads="<tr><td colspan='4' class='dataFont' align='center'><b>No Data Found</b></td></tr>"; 
            }                                 
            
            if($inclHeadDetail==1) {   
              $receiptData = str_replace("<FeeHeadsDetail>",$feeHeads,$receiptData);      
            }
            
       //***********************************************Fee Head Details (END) ***********************************    
     
     
      
      //***********************************************Total Due Amount (START) **********************************
           /*
           $feeHeadArray = $feeReportManager->getStudentFeeHeadDetail($studentId,$feeClassId,$quotaId,$isLeet,$feeReceiptId,$feeId);
           if(is_array($feeHeadArray) && count($feeHeadArray)>0) {  
              $discountedFeePayable += $feeHeadArray[0]['feeHeadAmt'] - $feeHeadArray[0]['concession'];
           }    
           $discountedFeePayable += ($studentRecordArray[$i]['hostelDues'] - $studentRecordArray[$i]['hostelConcession']);
           $discountedFeePayable += ($studentRecordArray[$i]['transportDues'] - $studentRecordArray[$i]['transportConcession']);  
           */
           
           $conditionTotalDue = " f.studentId = $studentId AND f.feeClassId = $feeClassId AND f.receiptStatus NOT IN (3,4) AND
                                  f.installmentCount < $chkInstallment ";  
           $foundArray = $collectStudentFeeManager->getPreviousFeePaymentDetail($conditionTotalDue);    
           $pervAmt=0;
           $prevFineAmt=0;
           for($j=0;$j<count($foundArray);$j++) {
               $pervAmt += $foundArray[$j]['prevDuesPaid']; 
               if($feeType==1) {
                 $pervAmt += $foundArray[$j]['prevFeePaid'];
               }
               else if($feeType==2) {
                 $pervAmt += $foundArray[$j]['prevTransportPaid'];  
               }
               else if($feeType==3) {
                 $pervAmt += $foundArray[$j]['prevHostelPaid'];  
               }
               else {
                 $pervAmt += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid']);
               }
               
               if($feeType==1) {
                 $prevFineAmt += $foundArray[$j]['prevFeeFine'];
               }
               else if($feeType==2) {
                 $prevFineAmt += $foundArray[$j]['prevTransportFine'];  
               }
               else if($feeType==3) {
                 $prevFineAmt += $foundArray[$j]['prevHostelFine'];  
               }
               else {
                 $prevFineAmt += ($foundArray[$j]['prevFeeFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevHostelFine']);
               }
           }   
           $discountedFeePayable -= $pervAmt; 
           
           
           $totalAmountPaid=0;
           $condition = " f.studentId = $studentId AND f.feeClassId = $feeClassId AND f.feeReceiptId = $feeReceiptId AND
                         f.receiptStatus NOT IN (3,4)";  
           $foundArray = $collectStudentFeeManager->getPreviousFeePaymentDetail($condition);    
           for($j=0;$j<count($foundArray);$j++) {
             $totalAmountPaid += ($foundArray[$j]['prevHostelPaid']+$foundArray[$j]['prevTransportPaid']+$foundArray[$j]['prevFeePaid'])+$foundArray[$j]['prevDuesPaid'];
             $discountedFeePayable += ($foundArray[$j]['prevHostelFine']+$foundArray[$j]['prevTransportFine']+$foundArray[$j]['prevFeeFine']); 
           }
           
           
           if($discountedFeePayable > 0 && $chkInstallment != 1) {
              $previousDues = $discountedFeePayable - $totalAmountPaid;
           }
           else {
              $previousDues = $discountedFeePayable;    
           }
           
           $receiptData= str_replace("<NetPaidAmount>",number_format($netPaidAmount,2,'.',''),$receiptData); 
           
           $netPaidAmount += $prevFineAmt;   
           if($prevFineAmt!='') {
               $prevFineShow="<tr>
                                <td class='dataFont'>&nbsp;</td>
                                <td class='dataFont' align='left'><b>Prev. Fine Amt.</b></td>
                                <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                                <td class='dataFont' align='right' >".number_format($prevFineAmt,2,'.','')."</td>
                               </tr>";
           }
           $receiptData= str_replace("<PrevFineAmount>",$prevFineShow,$receiptData);   
           
           $receiptData= str_replace("<Installment>",$studentInstallmentCount,$receiptData); 
       //***********************************************Total Due Amount (END) **********************************
     

     
       //***********************************************Installment and Previous Payment Detail (START) *****************
            // Fetch Student Fee Class Installment 
            $prevDetail =''; 
            $headName='<nobr>Curr. Paid Amount</nobr>';
            $prevDetail .= printPrevDetail($headName,$amtPaid);
            
            if($studentInstallmentCount!=1) {
              $headName='<nobr>Prev. Paid Amount</nobr>';
              $prevDetail .= printPrevDetail($headName,$pervAmt);
            }
            
            $headName='';
            
            if(($netPaidAmount-($amtPaid+$pervAmt)) > 0 ) {
               $headName="Pending Dues ";
               $paidAmountCalculation = $netPaidAmount-($amtPaid+$pervAmt);
            }
            else if(($netPaidAmount-($amtPaid+$pervAmt)) < 0 ) {
               $headName="Advance Payment";  
               $paidAmountCalculation = ABS($netPaidAmount-($amtPaid+$pervAmt));
            }
          
            if($headName!='') {
              $prevDetail .= printPrevDetail($headName,$paidAmountCalculation);
            }
            
            $receiptData= str_replace("<PrevPaymentDetail>",$prevDetail,$receiptData);      
       //***********************************************Installment and Previous Payment Detail  (END) *********************
      
      
     
      
      //***********************************************Cash / Cheque / Draft Payment Detail (START) *****************
           if($inclPaymentDetail==1) { 
                $showReceiptPaymentDetail = $showPaymentModeDetail1;     
                
                $cash = $studentFeesArray[0]['cashAmount'];
                if($cash=='') {                                    
                  $cash = NOT_APPLICABLE_STRING;   
                }
                $showReceiptPaymentDetail = str_replace("<CashAmount>",$cash,$showReceiptPaymentDetail);   
                $instrumentDetail = "";
                $condition = " fpd.feeReceiptId = '".$studentFeesArray[0]['feeReceiptId']."'";
                $studentFeesDetailArray = $collectStudentFeeManager->getStudentPaymentDetail($condition);  
                if($studentFeesDetailArray[0]['paymentInstrument']!='4'){
					 $condition = "AND fpd.feeReceiptId = '".$studentFeesArray[0]['feeReceiptId']."'";
				// echo $studentFeesDetailArray;die;
					$studentFeesDetailArray = $collectStudentFeeManager->getStudentPaymentPrintDetail($condition);  
					$recordCount = count($studentFeesDetailArray); 
					for($i=0; $i<$recordCount; $i++ ) {
						$instrumentDate=UtilityManager::formatDate($studentFeesDetailArray[$i]['instrumentDate']);
						$instrumentDetail .= "<tr class='dataFont'>
                                           <td>".$modeArr[$studentFeesDetailArray[$i]['paymentInstrument']]."</td>
                                           <td align='left'>".$studentFeesDetailArray[$i]['instrumentNo']."</td>
                                           <td align='right'>".number_format($studentFeesDetailArray[$i]['instrumentAmount'],2,'.','')."</td>
                                           <td align='left'>".$studentFeesDetailArray[$i]['bankAbbr']."</td>
                                           <td align='center'>".$instrumentDate."</td>
                                         </tr>";
					}
				}
				else{
					$recordCount = count($studentFeesDetailArray); 
					for($i=0; $i<$recordCount; $i++ ) {
						$instrumentDate=UtilityManager::formatDate($studentFeesDetailArray[$i]['instrumentDate']);
						$instrumentDetail .= "<tr class='dataFont'>
                                           <td>".$modeArr[$studentFeesDetailArray[$i]['paymentInstrument']]."</td>
                                           <td align='left'>".$studentFeesDetailArray[$i]['instrumentNo']."</td>
                                           <td align='right'>".number_format($studentFeesDetailArray[$i]['instrumentAmount'],2,'.','')."</td>
                                           <td align='left'>".onlineTransaction_bank."</td>
                                           <td align='center'>".$instrumentDate."</td>
                                         </tr>";
					}
				
				}
				
                // var_dump($studentFeesDetailArray);
                if($instrumentDetail != "") {
                   $receiptPaymentDetail = $paymentModeDetail1;   
                   $receiptPaymentDetail = str_replace("<InstrumentDetail>",$instrumentDetail,$receiptPaymentDetail); 
                   $showReceiptPaymentDetail = str_replace("<PaymentModeDetail>",$receiptPaymentDetail,$showReceiptPaymentDetail);  
                }
                
                $receiptData= str_replace("<ShowPaymentDetail>",$showReceiptPaymentDetail,$receiptData);  
                
                if(trim($printRemarks)!='') {
                   $printMsg = "<tr><td class='dataFont' colspan='2'><br>".trim($printRemarks)."</td></tr>";    
                   $receiptData= str_replace("<PrintRemarks>",$printMsg,$receiptData);   
                }
            }
        //***********************************************Cash / Cheque / Draft Payment Detail (END) *********************

        echo $receiptData;
    }
    else {
       $heading = "Receipt No.:&nbsp;$receiptNo";  
       $value="<div class='dataFont' align='center'><b>Receipt No. doesn't exist</b></div>";
       reportGenerate($value,$heading); 
    }

die;
    
    function printPrevDetail($headName,$amt) {
        
        global $paddingLeft;
        
        $paidAmt = number_format($amt,2,'.','');
        $result = "<tr>
                     <td class='dataFont'>&nbsp;</td>
                     <td class='dataFont'><b>$headName</b></td>
                     <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                     <td class='dataFont' align='right' >$paidAmt</td>
                   </tr>"; 
                  
        return $result;          
    }
      
    function printFeeHeadValues($srNo,$headName,$dues,$conn,$appl) {
       
       global $netPaidAmount; 
       
       $dues = str_replace(',','',$dues);
       $conn = str_replace(',','',$conn);
       $appl = str_replace(',','',$appl);
       
       $result = "<tr class='dataFont'>
                     <td>".$srNo."</td>
                     <td align='left'>$headName</td>
                     <td align='right'>$dues</td>
                     <td align='right'>$conn</td>
                     <td align='right'>$appl</td>
                  </tr>"; 

              
       if($conn==NOT_APPLICABLE_STRING || $conn=='') {
         $conn = 0;
       }
       
       if($dues==NOT_APPLICABLE_STRING || $dues=='') {
         $dues = 0;
       }                

       $netPaidAmount += ($dues - $conn);
       
       return $result;                                     
    }  
  
    function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('RECEIPT FOR FEE & CHARGES');
        $reportManager->setReportInformation($heading);      
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
            <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
            </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
            <?php echo $value; ?>        
            </td>
            </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'>
        </div>
<?php        
   }
?>

