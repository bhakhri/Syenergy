<?php
//-------------------------------------------------------
// Purpose: To store the records of fees receipt
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','CollectFees');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentMiscChargesManager.inc.php"); 
$studentMiscChargesManager = StudentMiscChargesManager::getInstance();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
$collectStudentFeeManager = CollectStudentFeeManager::getInstance();  

                      
    global $sessionHandler;
    global $receiptArr;
    global $receiptPaymentArr;
    $queryDescription =''; 

        $studentMisc = $REQUEST_DATA['studentMisc'];
        $studentMiscApplAmt = $REQUEST_DATA['studentMiscApplAmt'];
        $miscAmount=0;      
        $miscApplAmount=0;
        for($i=0;$i<count($studentMisc);$i++) {
          if($studentMisc[$i]!='') {
            $miscAmount += $studentMisc[$i];   
          }  
          if($studentMiscApplAmt[$i]!='') {
            $miscApplAmount += $studentMiscApplAmt[$i];   
          }  
        }
        if($miscAmount=='') {
          $miscAmount=0;   
        }      
        if($miscApplAmount=='') {
          $miscApplAmount=0;   
        }      
      
     
   
    $sessionHandler->setSessionVariable('IdToFindFeeRollNo','');
    $sessionHandler->setSessionVariable('IdToFindFeeClassId',''); 
    
    $userId = $sessionHandler->getSessionVariable('UserId'); 
     
    $hostelDues            =  trim($REQUEST_DATA['hostelDue']);
    $hostelConcession     =  trim($REQUEST_DATA['hostelConcession']);
    $hostelFine           =  trim($REQUEST_DATA['hostelFine']);
    $transportDues         =  trim($REQUEST_DATA['transportDue']);
    $transportConcession  =  trim($REQUEST_DATA['transportConcession']);
    $transportFine        =  trim($REQUEST_DATA['transportFine']);
    $studentFine          =  trim($REQUEST_DATA['studentFine']);  
    $isConessionFormat    =  trim($REQUEST_DATA['isConessionFormatId']);  
    $duesAmtPaid          = trim($REQUEST_DATA['duesAmtPaid']); 
    $receiptDate    =  $REQUEST_DATA['receiptDate'];
    $receiptNumber  =  $REQUEST_DATA['receiptNumber'];
    $feeType        =  $REQUEST_DATA['feeType'];
    $feeCycle       =  $REQUEST_DATA['feeCycle'];
    $feeClassId     =  $REQUEST_DATA['feeClassId'];
    $studentId      =  $REQUEST_DATA['studentId'];
    $classId        =  $REQUEST_DATA['studentClass'];
    $printRemarks   =  add_slashes(trim($REQUEST_DATA['printRemarks']));
    $generalRemarks =  add_slashes(trim($REQUEST_DATA['generalRemarks']));
    $receivedFrom   =  add_slashes(trim($REQUEST_DATA['receivedFrom']));
    $installmentCount = $REQUEST_DATA['installmentCount'];
    $favouringBankBranchId = $REQUEST_DATA['favouringBank'];
    $cashAmount =   trim($REQUEST_DATA['cashAmount']);  
       
    $duesCharges = $REQUEST_DATA['duesCharges'];  
    $duesClass = $REQUEST_DATA['duesClass'];
    $feeHeadIds = $REQUEST_DATA['feeHeadIds'];
    $applAmt = $REQUEST_DATA['applAmt'];
    $studentFineApplAmt =  trim($REQUEST_DATA['studentFineApplAmt']);
    
    $applTransport =   trim($REQUEST_DATA['applAmtTransportPaid']);
    $applTransportFine=   trim($REQUEST_DATA['applAmtTransportFine']);      
    
    $applHostel=   trim($REQUEST_DATA['applAmtHostelPaid']);
    $applHostelFine =   trim($REQUEST_DATA['applAmtHostelFine']);

    $feeAmtPaid =0;
    $transportPaid =0;
    $hostelPaid =0;
    
    if($feeType==1 || $feeType==4) {  
      $feeAmtPaid  =  trim($REQUEST_DATA['feeAmtPaid']);   
    }
    if($feeType==2 || $feeType==4) {  
      $transportPaid  =  trim($REQUEST_DATA['transportAmtPaid']);   
    }
    if($feeType==3|| $feeType==4) {  
      $hostelPaid =  trim($REQUEST_DATA['hostelAmtPaid']);   
    }
    
    //$feeAmtPaid += $miscAmount;

    if($hostelDues=='') {
      $hostelDues = 0; 
    }
    if($hostelFine=='') {
      $hostelFine = 0;
    }
    if($hostelConcession=='') {
      $hostelConcession = 0;
    }
    if($hostelPaid=='') {
      $hostelPaid = 0;
    }
    
    if($transportDues=='') {
      $transportDues = 0;
    }
    if($transportFine=='') {
      $transportFine = 0;
    }
    if($transportConcession=='') {
      $transportConcession = 0;
    }
    if($transportPaid=='') {
      $transportPaid = 0;
    }

    if($feeAmtPaid =='') {
      $feeAmtPaid = 0;
    } 
    
    if($duesAmtPaid =='') {
      $duesAmtPaid = 0;
    } 
    
    if($applTransport=='') {
      $applTransport=  0;
    }
    if($applTransportFine=='') {
      $applTransportFine =  0;
    }
    
    if($applHostel=='') {
      $applHostel =  0;
    }
    
    if($applHostelFine=='') {
      $applHostelFine= 0;
    }
    
    
    $netTotalAmtPaid=0; 
    if($feeType==1 || $feeType==4) {
      $netTotalAmtPaid = $feeAmtPaid;    
    }
    
    if($feeType==2 || $feeType==4) {
      $netTotalAmtPaid += $transportPaid;    
    }
    
    if($feeType==3 || $feeType==4) {
      $netTotalAmtPaid += $hostelPaid;  
    }
    $netTotalAmtPaid += $duesAmtPaid;  
    
    //Cheque/Draft Payment Detail
    $paymentTypeId   =  $REQUEST_DATA['paymentTypeId'];
    $instId          =  $REQUEST_DATA['instId'];
    $amtId           =  $REQUEST_DATA['amtId'];
    $issuingBankId   =  $REQUEST_DATA['issuingBankId'];
    
    $checkPaidAmount  = $cashAmount;
    if($checkPaidAmount=='') {
      $checkPaidAmount=0;
    }
    for($i=0;$i<count($paymentTypeId);$i++) {
      $chequeDate[$i]  =  $REQUEST_DATA['leaveDate'.($i+1)];
      $checkPaidAmount  += $amtId[$i];
    }
    
    
    if(trim($checkPaidAmount)!=trim($netTotalAmtPaid)) {
       echo PAYMENT_DETAIL;
       die;     
    }
    
   
     
    // Fee Head Wise Collection Check   ============ START ==================
        
        $headTotal=0;
        for($i=0;$i<count($applAmt);$i++) {
          $headTotal += $applAmt[$i];
        }
        $headTotal += $miscApplAmount; 
        
        if($studentFineApplAmt!='') {
          $headTotal += $studentFineApplAmt;
        }
        
        if($headTotal>$feeAmtPaid) {
           echo APPL_HEAD_PAYMENT_DETAIL;
           die;  
        }
        
        if($feeAmtPaid > $headTotal) {
          $advanceFeeHead = $feeAmtPaid - $headTotal;
        }
        
        
        if(($applTransportFine+$applTransport)>$transportPaid) {
           echo APPL_TRANS_HEAD_PAYMENT_DETAIL;    
           die;  
        }
        
        if(($applHostelFine+$applHostel)>$hostelPaid) {
            echo APPL_HOSTEL_HEAD_PAYMENT_DETAIL;   
            die;  
        }
        
    // Fee Head Wise Collection Check   ============ END ==================    
    
    
   
    // fee Head Wise Collection Details   
    // feeHeadCollectionArr          "1"=>"Fee Head","2"=>"Fine", "3"=>"Advance"
    // fee receipt status            "1"=>"Open","2"=>"Closed", "3"=>"Cancel","4"=>"Delete"; 
    // Fee receipt Payment status    "1"=>"With Clerk","2"=>"With Bank", "3"=>"Closed","4"=>"Bounced";
    $condition = " f.receiptNo = '$receiptNumber'  AND f.receiptStatus NOT IN (4)   ";
    $foundArray = $collectStudentFeeManager->getReceiptNo($condition);   
    if($foundArray[0]['cnt'] > 0) {
      echo RECEIPT_ALREADY_EXIST; 
      die;  
   }


    // Findout Student Name
    $tableName='student';
    $fieldName="rollNo, CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName";
    $cond = "WHERE studentId  = '".$studentId."'"; 
    $studentNameArray = $studentManager->getSingleField($tableName, $fieldName, $cond);
    $studentName = $studentNameArray[0]['studentName'];
    $rollNo = $studentNameArray[0]['rollNo'];
   
    // Findout Fee Class Name
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = '$feeClassId'");
    $className = $classNameArray[0]['className'];
   
   
   
     $currentDate = date('Y-m-d'); 
    //****************************************************************************************************************    
    //***********************************************STRAT TRANSCATION************************************************
    //****************************************************************************************************************
     if(SystemDatabaseManager::getInstance()->startTransaction()) {
         
          $studentMisc = $REQUEST_DATA['studentMisc'];
          $studentMiscApplAmt = $REQUEST_DATA['studentMiscApplAmt'];
          $studentMiscFeeHead = $REQUEST_DATA['miscFeeHeadIds'];
           
          if(count($studentMisc) >0) {
              $dated=date('Y-m-d h:i:s');
              for($i=0;$i<count($studentMiscFeeHead);$i++) {
                  $miscArray = explode('~',$studentMiscFeeHead[$i]);
                  $miscFeeHeadId =  $miscArray[0];
                  $miscCharges =  trim($studentMisc[$i]);
                  if($miscCharges!='') {
                     $miscCondition = " classId = '$feeClassId' AND studentId = '$studentId' AND feeHeadId = '$miscFeeHeadId' ";  
                     $returnStatus=$collectStudentFeeManager->getMiscFeeHead($miscCondition);  
                     if(is_array($returnStatus) && count($returnStatus)>0 ) {   
                        $ret=$studentMiscChargesManager->deleteStudentMiscCharges($miscCondition);    
                        if($ret===false){
                          echo FAILURE;
                          die;
                        }
                     }
                     $insertString = "($miscFeeHeadId,$feeClassId,$studentId,$miscCharges,$userId,'".$dated."' )"; 
                     $ret=$studentMiscChargesManager->insertStudentMiscCharges($insertString);
                     if($ret===false){
                       echo FAILURE;
                       die;
                     }
                  }
              }
          }
          
    
          $str = "('$studentId', '$classId', '$feeType', '$feeCycle', '$feeClassId', '$receiptNumber', '$receiptDate','1', '$printRemarks', 
                   '$generalRemarks', '$paidAmount', '$cashAmount', '$studentFine', '$userId','$installmentCount','$favouringBankBranchId',
                   '$feeAmtPaid','$hostelDues', '$hostelFine', '$hostelConcession', '$hostelPaid', 
                   '$transportDues', '$transportFine', '$transportConcession', '$transportPaid', '$duesAmtPaid',
                   '$applHostel', '$applHostelFine', '$applTransport', '$applTransportFine','$isConessionFormat','1' )";
          $returnStatus = $collectStudentFeeManager->addFeeReceiptCollection($str);
          if($returnStatus === false) {
            echo FAILURE; 
            die;
          }
          $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
          
          $receiptId = $sessionHandler->getSessionVariable('IdToFeeReceipt'); 
          
          $str ='';
          for($i=0;$i<count($paymentTypeId);$i++) {   
             if($str!='') {
               $str .=",";  
             } 
             $str .= "('$receiptId','$studentId', '$classId', '$feeCycle', '$feeClassId', '".$paymentTypeId[$i]."',
                      '".$instId[$i]."', '".$amtId[$i]."', '".$issuingBankId[$i]."', '".$chequeDate[$i]."','2')";
          }
          if($str!='') {
             $returnStatus = $collectStudentFeeManager->addFeePaymentDetailCollection($str);
             if($returnStatus === false) {
               echo FAILURE; 
               die;
             }
             $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
          }
          
          // Fee Headwise Collection Add
          $str ='';
          for($i=0;$i<count($feeHeadIds);$i++) {   
             $amt =  $applAmt[$i];
             if($amt!='') {
               if($str!='') {
                 $str .=",";  
               } 
               $str .= "('$currentDate','$receiptId','$studentId', '$classId', '$feeCycle', '$feeClassId', '".$feeHeadIds[$i]."','1','$amt')";
             }
          }
          

          // Misc Fee Headwise Collection Added
          $studentMisc = $REQUEST_DATA['studentMisc'];
          $studentMiscApplAmt = $REQUEST_DATA['studentMiscApplAmt'];
          $miscAmount=0;      
          $miscApplAmount=0;
          for($i=0;$i<count($studentMisc);$i++) {
            $amt ='';  
            if($studentMiscApplAmt[$i]!='') {
              $miscArray = explode('~',$studentMiscFeeHead[$i]);
              $miscFeeHeadId =  $miscArray[0];  
              $amt =  $studentMiscApplAmt[$i];
            }
            if($amt!='') {
               if($str!='') {
                 $str .=",";  
               } 
               $str .= "('$currentDate','$receiptId','$studentId', '$classId', '$feeCycle', '$feeClassId', '".$miscFeeHeadId."','1','$amt')";
            }
          }          
          
        
          // Fee Headwise Collection Fine
          $amt = trim($REQUEST_DATA['studentFineApplAmt']);
          if($amt!='') {
             if($str!='') {
               $str .=",";  
             } 
             $str .= "('$currentDate','$receiptId','$studentId', '$classId', '$feeCycle', '$feeClassId', NULL, '2','$amt')";
          }
          
          
          // Fee Headwise Collection Advance
          if($advanceFeeHead!=0) {
             if($str!='') {
               $str .=",";  
             } 
             $str .= "('$currentDate','$receiptId','$studentId', '$classId', '$feeCycle', '$feeClassId', NULL, '3','$advanceFeeHead')";
          }
          
          
          if($str!='') {
             $returnStatus = $collectStudentFeeManager->addFeeHeadCollection($str);
             if($returnStatus === false) {
                echo FAILURE; 
                die;
             }
             $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
          }
          
          // Fee Dues Collection Add
          $str ='';
          for($i=0;$i<count($duesClass);$i++) {   
             $amt =  $duesCharges[$i];
             $classId = $duesClass[$i];
             if($amt!='') {
               if($str!='') {
                 $str .=",";  
               } 
               $str .= "('$classId','$studentId','$amt','$receiptId')";
             }
          }
          
          if($str!='') {
             $returnStatus = $collectStudentFeeManager->addFeeDuesCollection($str);
             if($returnStatus === false) {
                echo FAILURE; 
                die;
             }
             $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
          }
          
	      //*****************************COMMIT TRANSACTION************************* 
          if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		    ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$auditTrialDescription = "Fees has been collected from :$studentName($rollNo) ,";
            $auditTrialDescription.=$className;
			$type = FEES_IS_COLLECTED; //Fee is collected
            $queryDescription='';
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
             echo SUCCESS;  
          }
          else {
             echo FAILURE;  
          }    	
     }
?>
