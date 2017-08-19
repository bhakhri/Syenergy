<?php
//It contains the store student fee admit details
//
// Author :Parveen Sharma
// Created on : 12-Jun-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
   define('MODULE','COMMON');
   define('ACCESS','view');
   UtilityManager::ifNotLoggedIn();
   UtilityManager::headerNoCache();
    
   $errorMessage ='';
   $studentEnquiryManager = StudentEnquiryManager::getInstance();
   
   global $sessionHandler;      

   $studentId = trim($REQUEST_DATA['studentId']);   
   $feeReceiptId = trim($REQUEST_DATA['feeReceiptId']);    
   $dateOfEntry  = date("Y-m-d");
   $refundMode   = trim($REQUEST_DATA['refundMode']);  
   $refundValue  = trim($REQUEST_DATA['refundValue']);  
   $refundAmount = trim($REQUEST_DATA['refundAmount']);  
   $remarks      = add_slashes(trim($REQUEST_DATA['remarks']));  
   $userId       = $sessionHandler->getSessionVariable('UserId');
   
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) { 

         // Save details in cancellation status
         $filter  = "feeReceiptId='$feeReceiptId',
                     dateOfEntry='$dateOfEntry',
                     refundMode='$refundMode',
                     refundValue='$refundValue',
                     refundAmount='$refundAmount',
                     remarks='$remarks',
                     userId ='$userId'";
          $returnStatus = $studentEnquiryManager->insertFeeCancellationDetailsInTransaction($filter);
          if($returnStatus===false) {
             echo FAILURE;
             die; 
          }
          
          // Fee Cancellation Status 
          $table  = "`adm_fee_receipt`";
          $filter = "cancelStatus='Y'";
          $condition = "WHERE feeReceiptId='$feeReceiptId'";                                  
          $returnStatus = $studentEnquiryManager->updateStudentStatus($table,$filter,$condition);
          if($returnStatus===false) {
             echo FAILURE;
             die; 
          }
          
          // Vacant Seat
          $table  = "`student_enquiry`";
          $filter = "candidateStatus=1";
          $condition = "WHERE studentId='$studentId'";                                  
          $returnStatus = $studentEnquiryManager->updateStudentStatus($table,$filter,$condition);
          if($returnStatus===false) {
             echo FAILURE;
             die; 
          }
          if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo SUCCESS;
          }
          else {
            echo FAILURE;
          }  
   }
   
//$History: ajaxCancelFeeReceipt.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Library/StudentEnquiry
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation & condition updated
//

?>

