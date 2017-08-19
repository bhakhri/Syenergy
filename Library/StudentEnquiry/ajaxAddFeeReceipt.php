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
   define('MODULE','StudentFee');
   define('ACCESS','view');
   UtilityManager::ifNotLoggedIn();
   UtilityManager::headerNoCache();
    
   $errorMessage ='';
   $studentEnquiryManager = StudentEnquiryManager::getInstance();
   
   global $sessionHandler;      

   $userId     = $sessionHandler->getSessionVariable('UserId');
                                                                      $degreeId   = add_slashes(trim($REQUEST_DATA['degreeId']));
   $studentId  = add_slashes(trim($REQUEST_DATA['studentId']));
   $cashFee    = add_slashes(trim($REQUEST_DATA['cashFee']));
   $feeDD      = add_slashes(trim($REQUEST_DATA['feeDD']));
   $feeDDNo    = add_slashes(trim($REQUEST_DATA['feeDDNo']));
   $feeDDDate  = trim($REQUEST_DATA['feeDDDate']);
   $bankName   = add_slashes(trim($REQUEST_DATA['bankName']));  
   $remarks    = add_slashes(trim($REQUEST_DATA['remarks']));   
   
   
   if($degreeId=='') {
     echo "Select Degree";
     die;
   }
   
   if($studentId=='') {
     echo "Please correct competition exam roll no. / application form no.";  
     die;
   }
   
   if($cashFee=='') {
     $cashFee =0;
   }

   if($feeDD=='') {
     $feeDD =0;
   }
   
   $totalAmountPaid =  $cashFee + $feeDD;
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) { 

         $receiptNoArr = $studentEnquiryManager->getReceiptNo(); 
         if($receiptNoArr[0]['receiptNo']){
           $receiptNo = $receiptNoArr[0]['receiptNo']+1;
         }
         else{
           $receiptNo = FEE_RECEIPT_START;
         }
         $currentDate = date("Y-m-d");    
           
         $filter  = "studentId='$studentId',
                     classId='$degreeId', 
                     receiptNo='$receiptNo',
                     receiptDate='$currentDate',
                     cashAmount='$cashFee',
                     ddAmount='$feeDD',
                     totalAmountPaid='$totalAmountPaid',
                     ddNo='$feeDDNo',
                     ddDate='$feeDDDate',
                     ddBankName='$bankName',
                     userId ='$userId',
                     remarks = '$remarks' ";
          $returnStatus = $studentEnquiryManager->insertFeeDetailsInTransaction($filter);
          if($returnStatus===false) {
             echo FAILURE;
             die; 
          }
          
          $table  = "`student_enquiry`";
          $filter = "candidateStatus=2";
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
   
//$History: ajaxAddFeeReceipt.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation & condition updated
//

?>

