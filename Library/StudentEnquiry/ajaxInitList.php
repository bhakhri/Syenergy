<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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

    

    $conditions = " a.instituteId = $instituteId AND a.sessionId = $sessionId ";
    
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

		$genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".$genName."%'";
	}  
  
	return $genName;
	}

	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
	/* START: search filter */
	foreach($REQUEST_DATA as $key => $values) {
		$$key = $values;
	}

	$conditionsArray = array();
	
	//Student Name
	$studentName = $REQUEST_DATA['studentNameSearch'];
	if (!empty($studentName)) {
		//$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
	}
    
    $fatherName=add_slashes(trim($REQUEST_DATA['fatherNameSearch']));
    if($fatherName!=''){
       $conditionsArray[] =" a.fatherName LIKE '".$fatherName."%' "; 
    }
   
    //degree
    $degree = $REQUEST_DATA['degreeId'];
    if (!empty($degree)) {
        $conditionsArray[] = " ( c.classId IN ($degree)) ";
    }
     
	//city
	$citys = $REQUEST_DATA['cityId'];
	if (!empty($citys)) {
		$conditionsArray[] = " ( corrCityId IN ($citys)) ";
	}

	//states
	$states = $REQUEST_DATA['stateId'];
	if (!empty($states)) {
		$conditionsArray[] = " ( corrStateId IN ($states) ) ";
		$qryString .= "&stateId=$states";
	}

	//country
	$cnts = $REQUEST_DATA['countyId'];
	if (!empty($cnts)) {
		$conditionsArray[] = " ( corrCountryId IN ($cnts) ) ";
		$qryString .= "&countryId=$cnts";
	}
	
	
	if(count($conditionsArray) > 0) {
      $cond1 = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    if($REQUEST_DATA['counselorId']!='') {
      if($REQUEST_DATA['counselorId']!='all') {  
        $conditions .=' AND a.addedByUserId='.$REQUEST_DATA['counselorId']; //11 for counselor
      }
    }  
    
    if($REQUEST_DATA['candidateStatusId']!='') {
      $conditions .=' AND a.candidateStatus='.$REQUEST_DATA['candidateStatusId']; //candidate Status
    }  
    
    $conditions .=$cond1;
     
     
/*  if($sessionHandler->getSessionVariable('RoleId')!=1){
      $conditions .=' AND u.userId='.$sessionHandler->getSessionVariable('UserId');
    }
    else{
       $conditions .=' AND r.roleType IN (1,11) '; //11 for counselor
       if($REQUEST_DATA['counselorId']!=''){
         $conditions .=' AND a.addedByUserId='.$REQUEST_DATA['counselorId']; //11 for counselor
       }
    }
*/
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    if($sortField == 'contact') {
	    $orderBy=" CONCAT(IFNULL(studentPhone,''),' ',IFNULL(studentMobileNo,'')) $sortOrderBy"; 
    }
    else if($sortField =='compExamRank') {
        $orderBy=" CAST(compExamRank AS UNSIGNED) $sortOrderBy"; 
    }
    else if($sortField =='compExamRollNo') {
        $orderBy=" CAST(compExamRollNo AS UNSIGNED) $sortOrderBy"; 
    }
    else  {
        $orderBy=" $sortField $sortOrderBy"; 
    }
    
    
	/* END: search filter */


	$totalArray = $studentManager->getTotalStudent($conditions);
    $studentRecordArray = $studentManager->getStudentList($conditions,$limit,$orderBy);
    
    $cnt = count($studentRecordArray);
    
    global $results;
    
    for($i=0;$i<$cnt;$i++) {
  	   $showlink = "<a href='#' onClick='editWindow(".$studentRecordArray[$i]['studentId'].")' alt='Edit' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>&nbsp;&nbsp;<a href='#' onClick='deleteStudentEnquiry(".$studentRecordArray[$i]['studentId'].")' alt='Delete' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' /></a>&nbsp;&nbsp;<a href='#' onClick='printStudentEnquiry(".$studentRecordArray[$i]['studentId'].")' title='Print'><img src='".IMG_HTTP_PATH."/print1.gif' border='0' /></a>";
       $contact=NOT_APPLICABLE_STRING;
       
       if($studentRecordArray[$i]['className1']==null) { 
          $studentRecordArray[$i]['className1']= NOT_APPLICABLE_STRING;     
       } 
       
       if($studentRecordArray[$i]['counselingDate_start']==null) {
         $studentRecordArray[$i]['counselingDate_start']= NOT_APPLICABLE_STRING;    
       }
       else if($studentRecordArray[$i]['counselingDate_start']=='0000-00-00') {
         $studentRecordArray[$i]['counselingDate_start']= NOT_APPLICABLE_STRING;    
       }
       else {
         $studentRecordArray[$i]['counselingDate_start']=UtilityManager::formatDate($studentRecordArray[$i]['counselingDate_start'])."<br>to<br>".UtilityManager::formatDate($studentRecordArray[$i]['counselingDate_end']);      
       }
       
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
       
       if($studentRecordArray[$i]['studentEmail']  != NOT_APPLICABLE_STRING) {
          if($contact != NOT_APPLICABLE_STRING) {    
             $contact .= "<br>".$studentRecordArray[$i]['studentEmail']; 
          }  
          else {
             $contact = $studentRecordArray[$i]['studentEmail']; 
          }
       }

       $studentRecordArray[$i]['contactNo'] = $contact;
       
       
       if($studentRecordArray[$i]['enquiryDate']=='0000-00-00') {
            $studentRecordArray[$i]['enquiryDate'] = NOT_APPLICABLE_STRING;
       }
       else {
            $studentRecordArray[$i]['enquiryDate'] = UtilityManager::formatDate($studentRecordArray[$i]['enquiryDate']);
       }
       
       if($studentRecordArray[$i]['compExamRollNo']=='') {
         $studentRecordArray[$i]['compExamRollNo']= NOT_APPLICABLE_STRING;    
       }
       $valueArray = array_merge(array('act' =>  $showlink, 'srNo' => ($records+$i+1), 'contact' => $contact  ),$studentRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 13  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/13/10    Time: 4:36p
//Updated in $/LeapCC/Library/StudentEnquiry
//query and validation format updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 3/24/10    Time: 3:53p
//Updated in $/LeapCC/Library/StudentEnquiry
//condition format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Library/StudentEnquiry
//query & condition format updated
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