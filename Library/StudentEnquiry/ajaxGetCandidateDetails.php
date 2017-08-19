<?php
//-------------------------------------------------------
// This file is used to get values of selected candidate
//
//
// Author : Vimal Sharma
// Created on : (15.04.2009 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");  
    
    global $results;       
    $examRollNo1  = add_slashes(trim($REQUEST_DATA['examRollNo1']));
    $examRollNo2  = add_slashes(trim($REQUEST_DATA['examRollNo2']));  
    
    $condition = '';
    $condition1 .= " AND af.cancelStatus='N' ";
    if($examRollNo1!='') {
      $condition .= " WHERE (se.compExamRollNo = '".$examRollNo1."')  ";
    }
    
    if($examRollNo2!='') {
       if($condition=='') {
         $condition .= " WHERE (se.applicationNo = '".$examRollNo2."')"; 
       }  
       else  {
         $condition .= " AND (se.applicationNo = '".$examRollNo2."')";
       }
    }
    
    $condition .= " GROUP BY se.studentId "; 
    
    $foundArray = StudentEnquiryManager::getInstance()->getStudentEnquiryFeeData($condition1, $condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        
       $foundArray[0]['studentName'] = strtoupper(trim($foundArray[0]['firstName']).' '.trim($foundArray[0]['lastName']));
       if($foundArray[0]['compExamBy']!='') {
          $val = $foundArray[0]['compExamBy']; 
          $foundArray[0]['compExamBy'] = $results[$val];
       }
       if($foundArray[0]['compExamBy']=='') {
         $foundArray[0]['compExamBy'] = NOT_APPLICABLE_STRING;
       }       
       if($foundArray[0]['compExamRollNo']=='') {
         $foundArray[0]['compExamRollNo'] = NOT_APPLICABLE_STRING;
       }       
       if($foundArray[0]['compExamRank']=='') {
         $foundArray[0]['compExamRank'] = NOT_APPLICABLE_STRING;
       }       
       
       $contact1='';
       $contact2=''; 
       if($foundArray[0]['studentPhone'] != NOT_APPLICABLE_STRING) {
          $contact1 = $foundArray[0]['studentPhone'];      
       }
        
       if($foundArray[0]['studentMobileNo'] != NOT_APPLICABLE_STRING) {
          $contact2 = $foundArray[0]['studentMobileNo'];      
       }
       
         if(trim($contact1)==trim($contact2)) {
          $contact = $contact1;   
       }
       else {
          $contact = $contact1;   
          if($contact!='') { 
            if($contact2!='') { 
              $contact .=", ".$contact2;
            }
            else {
              $contact .=$contact2;  
            }
          }
          else {
              $contact =$contact2;  
          }
       }
       
       if(trim($contact)==null || trim($contact)=='') {
         $foundArray[0]['contact'] = NOT_APPLICABLE_STRING;  
       }
       
       if($foundArray[0]['studentEmail']  == '') {
          $foundArray[0]['studentEmail'] = NOT_APPLICABLE_STRING; 
       }
       $foundArray[0]['contactNo'] = $contact;
       
       if($foundArray[0]['enquiryDate']=='0000-00-00') {
          $foundArray[0]['enquiryDate'] = NOT_APPLICABLE_STRING;
       }
       else {
          $foundArray[0]['enquiryDate'] = UtilityManager::formatDate($foundArray[0]['enquiryDate']);
       }
       
       if($foundArray[0]['fatherName']=='' ) {
          $foundArray[0]['fatherName'] = NOT_APPLICABLE_STRING;
       }
       
       if($foundArray[0]['applicationNo']=='') {
          $foundArray[0]['applicationNo'] = NOT_APPLICABLE_STRING;
       }
       
       if($foundArray[0]['quotaId']=='') {
          $foundArray[0]['categoryId'] = NOT_APPLICABLE_STRING;
       }
       else {
          $field = " quotaName, quotaAbbr ";
          $table = " quota ";
          $cond  = " WHERE quotaId = '".$foundArray[0]['quotaId']."'";
          $foundArray1 = StudentManager::getInstance()->getSingleField($table, $field, $cond);
          if(is_array($foundArray1) && count($foundArray1)>0 ) { 
             $foundArray[0]['categoryId'] =strtoupper($foundArray1[0]['quotaName']); 
          }
          else {
            $foundArray[0]['categoryId'] = NOT_APPLICABLE_STRING;  
          }
       }
       
       echo json_encode($foundArray[0]);
    }
    else {
       echo 0;
    }
?>

<?php 
// $History: ajaxGetCandidateDetails.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated
//
//*****************  Version 3  *****************
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