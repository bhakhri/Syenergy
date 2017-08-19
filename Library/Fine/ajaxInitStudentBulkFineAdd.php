<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Fine Category
// Author : Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FineManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance(); 

define('MODULE','COMMON');
define('ACCESS','add');

$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
  UtilityManager::ifNotLoggedIn(true);
}
else if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==5){
  UtilityManager::ifManagementNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);  
}
UtilityManager::headerNoCache();


$errorMessage ='';
    
    
    global $sessionHandler;     
     
    $studentIds      = trim($REQUEST_DATA['studentId']); 
    $amount         = trim($REQUEST_DATA['amount']); 
    $fineCategoryId = trim($REQUEST_DATA['fineCategoryId']); 
    $fineDate1      = trim($REQUEST_DATA['fineDate1']);
    $remarksTxt     = add_slashes(trim($REQUEST_DATA['remarksTxt'])); 
    $userId = $sessionHandler->getSessionVariable('UserId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
    
    $fineAlreadyStudent = '';
    
    if($studentIds=='' || $fineCategoryId=='' || $userId=='' || $instituteId=='') {
       echo FAILURE.'!~~!!~~!'.$fineAlreadyStudent;  
       die;
    }
    
    $studentIdArray = explode(',',$studentIds);
    $duplicateFineStudentId="";
    $isTransaction = '';

    if(SystemDatabaseManager::getInstance()->startTransaction()) {
       $c=count($studentIdArray);
       for($i=0;$i<count($studentIdArray);$i++) {   
            $studentIdArr = explode('~',$studentIdArray[$i]);
		    $studentId= $studentIdArr[0];
		    $classId = $studentIdArr[1]; 
            
			// Duplicate Fine
            $condition = ' AND fs.studentId="'.$studentId.'" AND fc.fineCategoryId="'.$fineCategoryId.'"  
                           AND fs.fineDate="'.$fineDate1.'"  AND fs.userId="'.$userId.'" AND fs.classId="'.$classId.'"';
            $foundArray = FineManager::getInstance()->getFineStudent($condition); 
			if(is_array($foundArray) && count($foundArray)>0 ) {
			   if($duplicateFineStudentId !='') {
				  $duplicateFineStudentId .= ",";   
			   }
			   $duplicateFineStudentId .= "'".$studentId."~".$classId."'"; 
			}
			else {	
              // Fetch InstituteId
              $foundArray = FineManager::getInstance()->checkStudentInstitute($classId); 
			  if(is_array($foundArray) && count($foundArray)>0 ) {
			    $instituteId = $foundArray[0]['instituteId']; 
  			  }

              // Save 
			  $isTransaction='1';
			  $filter = "INSERT INTO `fine_student` 
						 (`studentId`,`amount`,`fineCategoryId`,`fineDate`,`reason`,`paid`,`status`,`userId`,`instituteId`,`classId`) 
						 VALUES 
						 ('$studentId','$amount','$fineCategoryId','$fineDate1','$remarksTxt','0','1','$userId','$instituteId','$classId') "; 
			  $returnStatus = FineManager::getInstance()->addBulkFineStudent($filter,$value);
			  if($returnStatus === false) {
			     echo FAILURE.'!~~!'.$fineAlreadyStudent;  
			     die;
			  }
			}
	    }
		if($isTransaction=='1') {
          if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            $errorMessage =  SUCCESS;
          }
		  else {
            $errorMessage = FAILURE;
          }
		}
    }
    else {
       $errorMessage = FAILURE; 
    }
    
    $fineAlreadyStudent='';
    if($duplicateFineStudentId != '') {
		$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
		$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
		$orderBy=" ORDER BY $sortField $sortOrderBy"; 
		
		// fine already exist 
		$tableName = "student s, class c";
		$filter = " DISTINCT 
							CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName, className, 
							IF(IFNULL(rollNo,'')='','".NOT_APPLICABLE_STRING."',rollNo) AS rollNo, 
							IF(IFNULL(universityRollNo,'')='','".NOT_APPLICABLE_STRING."',universityRollNo) AS universityRollNo, 
							IF(IFNULL(studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',studentMobileNo) AS studentMobileNo,
							IF(IFNULL(studentEmail,'')='','".NOT_APPLICABLE_STRING."',studentEmail) AS studentEmail";
		$condition = "WHERE s.classId=c.classId AND CONCAT(s.studentId,'~',s.classId) IN ($duplicateFineStudentId)";    
		$foundArray = $studentManager->getSingleField($tableName,$filter,$condition.$orderBy);
		if(count($foundArray)>0) {
		   $fineAlreadyStudent ="<table width='100%' border='0' cellspacing='2' cellpadding='0'>
								 <tr class='rowheading'>
									<td width='2%'  valign='middle'  class='searchhead_text' align='left'><b>#</b></td>
									<td width='5%'  valign='middle'  class='searchhead_text' align='left'><strong>URoll No.</strong></td>
									<td width='5%'  valign='middle'  class='searchhead_text' align='left'><strong>Roll No.</strong></td>
									<td width='10%' valign='middle'  class='searchhead_text' align='left'><strong>Student Name</strong></td>
									<td width='15%' valign='middle'  class='searchhead_text' align='left'><strong>Class</strong></td>
									<td width='10%' valign='middle'  class='searchhead_text' align='left'><strong>Mobile No.</strong></td>
									<td width='10%' valign='middle'  class='searchhead_text' align='left'><strong>Email</strong></td>";
		   for($i=0;$i<count($foundArray);$i++) {
			  $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
			  $fineAlreadyStudent .= "<tr class='$bg'>
									   <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['universityRollNo']."</td>
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['rollNo']."</td>
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['studentName']."</td>
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['className']."</td>
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['studentMobileNo']."</td>
									   <td valign='top' class='padding_top' align='left'>".$foundArray[$i]['studentEmail']."</td>
									  </tr>";
		   }
		   $fineAlreadyStudent .='</table>';  
		}
	}
    
    echo  $errorMessage.'!~~!!~~!'.$fineAlreadyStudent;
    die;
?>
