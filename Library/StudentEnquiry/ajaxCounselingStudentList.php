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
    define('MODULE','AddStudentEnquiry');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    $studentManager = StudentEnquiryManager::getInstance();
    
    global $sessionHandler;   
    $instituteId = $sessionHandler->getSessionVariable('InstituteId'); 
    $sessionId = $sessionHandler->getSessionVariable('SessionId'); 
    
    $startingRecord = trim($REQUEST_DATA['startingRecord']);
    $totalRecords   = trim($REQUEST_DATA['totalRecords']);
    $entranceExam   = trim($REQUEST_DATA['entranceExam']);
    $entranceRollNo = trim($REQUEST_DATA['entranceRollNo']); 
    $rankFrom       = trim($REQUEST_DATA['rankFrom']);
    $rankTo         = trim($REQUEST_DATA['rankTo']);
    $startDate      = trim($REQUEST_DATA['startDate']);
    $endDate        = trim($REQUEST_DATA['endDate']);
    $studentId      = trim($REQUEST_DATA['chb']);   


    if($studentId!=='') {
      $studentId = 0;  
    }
    
    if($startingRecord=='') {
      $startingRecord = 1;  
    }
    
    if($totalRecords=='') {
      $totalRecords = 100;  
    }
    
    
    
    
    $conditions = " WHERE instituteId = $instituteId AND sessionId = $sessionId AND candidateStatus IN (1,3)";   
        
    if($rankFrom!='' && $rankTo != '') {    
      $conditions .= " AND CAST(compExamRank AS UNSIGNED) BETWEEN  CAST('$rankFrom' AS UNSIGNED) AND CAST('$rankTo' AS UNSIGNED) ";
    }
    
    if($entranceExam!='') {
       if($entranceExam!='all') { 
          if($conditions=='') { 
            $conditions .= " AND compExamBy = $entranceExam ";
          }
          else {
            $conditions .= " AND compExamBy = $entranceExam ";           
          }
       }
    }
    
    
    if($entranceRollNo!='') {
       if($conditions=='') { 
         $conditions .= " AND compExamRollNo = '$entranceRollNo' ";
       }
       else {
         $conditions .= " AND compExamRollNo = '$entranceRollNo' ";           
       }
    }
       
    
             
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* $totalRecords;
    $limit      = ' LIMIT '.$records.','.$totalRecords;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    
    if($sortField =='studentName') {
        $orderBy=" ORDER BY  CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) $sortOrderBy"; 
    }
    else if($sortField =='contact') {
	    $orderBy=" ORDER BY  CONCAT(IFNULL(studentPhone,''),' ',IFNULL(studentMobileNo,'')) $sortOrderBy"; 
    }
    else if($sortField =='compExamRank') {
        $orderBy=" ORDER BY CAST(compExamRank AS UNSIGNED) $sortOrderBy"; 
    }
    else if($sortField =='compExamRollNo') {
        $orderBy=" ORDER BY  CAST(compExamRollNo AS UNSIGNED) $sortOrderBy"; 
    }
    else  {
        $orderBy=" ORDER BY $sortField $sortOrderBy"; 
    }
	/* END: search filter */
    

	$studentRecordArray = $studentManager->getStudentEnquiryData($conditions,$orderBy,$limit);
    $cnt = count($studentRecordArray);
    
    global $results;
    global $enquiryStatusArr;
    
    for($i=0;$i<$cnt;$i++) {
       $studentId =  trim($studentRecordArray[$i]['studentId']);
  	  
       $contact=NOT_APPLICABLE_STRING;
       
       $firstName = trim($studentRecordArray[$i]['firstName']);
       $lastName  = trim($studentRecordArray[$i]['lastName']);
       
       $studentRecordArray[$i]['studentName'] = $firstName.' '.$lastName;      
       
       if($studentRecordArray[$i]['corrCityId'] == '') {
          $studentRecordArray[$i]['corrCityId'] =NOT_APPLICABLE_STRING;      
       }
       
       $contact1='';
       $contact2=''; 
       if($studentRecordArray[$i]['studentPhone'] != NOT_APPLICABLE_STRING) {
          $contact1 = $studentRecordArray[$i]['studentPhone'];      
       }
        
       if($studentRecordArray[$i]['studentMobileNo'] != NOT_APPLICABLE_STRING) {
          $contact2 = $studentRecordArray[$i]['studentMobileNo'];      
       }
       
       if($studentRecordArray[$i]['compExamBy']!=NOT_APPLICABLE_STRING) {
          $val = $studentRecordArray[$i]['compExamBy']; 
          $studentRecordArray[$i]['compExamBy'] = $results[$val];
          if($studentRecordArray[$i]['compExamBy']=='') {
            $studentRecordArray[$i]['compExamBy'] = NOT_APPLICABLE_STRING;
          }
       }
       
       if($studentRecordArray[$i]['candidateStatus']!='') {
          $val = $studentRecordArray[$i]['candidateStatus']; 
          $studentRecordArray[$i]['candidateStatus'] = $enquiryStatusArr[$val];
          if($studentRecordArray[$i]['candidateStatus']=='') {
            $studentRecordArray[$i]['candidateStatus'] = NOT_APPLICABLE_STRING;
          } 
           
       }
       
       
       if(trim($studentRecordArray[$i]['studentEmail']) =='' || trim($studentRecordArray[$i]['studentEmail']) == null) {
          $studentRecordArray[$i]['studentEmail'] = NOT_APPLICABLE_STRING; 
       }
       
       
       if(trim($studentRecordArray[$i]['compExamRollNo']) =='' || trim($studentRecordArray[$i]['compExamRollNo']) == null) {
          $studentRecordArray[$i]['compExamRollNo'] = NOT_APPLICABLE_STRING; 
       }
      
       if(trim($studentRecordArray[$i]['compExamRank']) =='' || trim($studentRecordArray[$i]['compExamRank']) == null) {
          $studentRecordArray[$i]['compExamRank'] = NOT_APPLICABLE_STRING; 
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
         $contact = NOT_APPLICABLE_STRING;  
       }
       
      
       
       
       $checkall  = '<input type="checkbox" checked=checked name="chb[]"  value="'.$studentId.'">';
       
       $valueArray = array_merge(array('checkAll' =>  $checkall, 
                                       'srNo' => ($records+$i+1),
                                       'contact' => $contact),
                                       $studentRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxCounselingStudentList.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/14/10    Time: 1:41p
//Updated in $/LeapCC/Library/StudentEnquiry
//condition format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/14/10    Time: 11:25a
//Updated in $/LeapCC/Library/StudentEnquiry
//query format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Library/StudentEnquiry
//query & condition format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/23/10    Time: 5:47p
//Created in $/LeapCC/Library/StudentEnquiry
//initial checkin
//
//*****************  Version 9  *****************
//User: Parveen      Date: 3/05/10    Time: 1:08p
//Updated in $/LeapCC/Library/StudentEnquiry
//comp. exam roll no. validation check added
//
//*****************  Version 8  *****************
//User: Parveen      Date: 3/03/10    Time: 5:44p
//Updated in $/LeapCC/Library/StudentEnquiry
//format & validation udpated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/20/10    Time: 12:43p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated 
//
//*****************  Version 6  *****************
//User: Administrator Date: 1/06/09    Time: 17:18
//Updated in $/LeapCC/Library/StudentEnquiry
//Updated student enquiry module
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/30/09    Time: 6:26p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation checks & spelling correct 
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 17:57
//Updated in $/LeapCC/Library/StudentEnquiry
//Corrected bugs
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 2:49p
//Updated in $/LeapCC/Library/StudentEnquiry
//enquiryDate added
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 16:57
//Updated in $/LeapCC/Library/StudentEnquiry
//Added role permissions
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Library/StudentEnquiry
//Created "Student Enquiry" module
?>