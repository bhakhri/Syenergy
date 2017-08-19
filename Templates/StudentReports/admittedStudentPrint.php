<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php                
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MessagesList');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager  = StudentReportsManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $classId = $REQUEST_DATA['classId'];
    $quotaId = $REQUEST_DATA['quotaId'];
    
    // Findout Class Name
    $classNameArray = $studentReportsManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    //$className2 = str_replace("-",' ',$className);
    
    if($classId=='') {
      $classId=0;  
    }
    
    $condition = " AND s.classId IN ($classId)";
    if($quotaId!='') {
      $condition .= " AND s.quotaId IN ($quotaId)";  
    }
   
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else 
    if ($sortField == 'fatherName') {
        $sortField1 = 'IF(IFNULL(fatherName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, fatherName)';
    }
    else
    if ($sortField == 'dateOfBirth') {
        $sortField1 = 'IF(IFNULL(dateOfBirth,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, dateOfBirth)';
    }
    else
    if ($sortField == 'compExamRollNo') {
        $sortField1 = 'IF(IFNULL(compExamRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRollNo AS UNSIGNED) )';
    }
    else
    if ($sortField == 'compExamRank') {
        $sortField1 = 'IF(IFNULL(compExamRank,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRank AS UNSIGNED))';
    }
    else
    if ($sortField == 'studentGender') {
        $sortField1 = 'IF(IFNULL(studentGender,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, studentGender)';
    }
    else
    if ($sortField == 'quotaName') {
        $sortField1 = 'IF(IFNULL(quotaName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, quotaName)';
    }
    else
    if ($sortField == 'managementCategory') {
        $sortField1 = 'IF(IFNULL(managementCategory,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, managementCategory)';
    }
    else {
        $sortField = 'studentName';
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';   
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    
     
    // Student Academic Details
    $recordArray = $studentReportsManager->getAdmittedStudentList($condition,$orderBy);
    $cnt = count($recordArray);
    
    $studentIds ="0";
    for($i=0;$i<count($recordArray);$i++) {
      $studentIds .=",".$recordArray[$i]['studentId']; 
    }
    
    $condition1 =" AND studentId IN ($studentIds) ";
    $academicArray = $studentReportsManager->getStudentAcademicList($condition1);
    $academicCount = count($academicArray);

    for($i=0;$i<$cnt;$i++) {
       $studentId = $recordArray[$i]['studentId'];        
       
       if($recordArray[$i]['dateOfBirth']!=NOT_APPLICABLE_STRING) {
          $recordArray[$i]['dateOfBirth'] = UtilityManager::formatDate($recordArray[$i]['dateOfBirth']);
       }
       
       if($recordArray[$i]['compExamRollNo']=='') {
          $recordArray[$i]['compExamRollNo'] = NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['compExamRank']=='') {
          $recordArray[$i]['compExamRank'] = NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['permAddress']=='') {
          $recordArray[$i]['permAddress'] = NOT_APPLICABLE_STRING;
       }
       
       $contact1='';
       $contact2=''; 
       if($recordArray[$i]['studentPhone'] != NOT_APPLICABLE_STRING) {
          $contact1 = $recordArray[$i]['studentPhone'];      
       }
        
       if($recordArray[$i]['studentMobileNo'] != NOT_APPLICABLE_STRING) {
          $contact2 = $recordArray[$i]['studentMobileNo'];      
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
       
       $recordArray[$i]['contactNo'] = $contact;
       
       $find=0;       
       $acd1= NOT_APPLICABLE_STRING; 
       $acd2= NOT_APPLICABLE_STRING;
       for($k=0; $k<$academicCount; $k++) {
          $aStudentId = $academicArray[$k]['studentId'];
          $per = $academicArray[$k]['previousPercentage'];  
          $examClass = $academicArray[$k]['previousClassId'];
          $per = $academicArray[$j]['previousPercentage'];    
          
          if($aStudentId == $studentId) {
            $find=1;
            if($examClass==1) { 
              $acd1 = $per;
            } 
            else
            if($examClass==2) { 
              $acd2 = $per;
            } 
            break;
          }  
          else if($find==1) {
            break;  
          }
       }
       $valueArray[] = array_merge(array('action1'=>$action1,'srNo' => ($records+$i+1), 'acd1' => $acd1,'acd2' => $acd1),$recordArray[$i]);
    }
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Admitted Student Report');
    $reportManager->setReportInformation("<b>Class:</b> $className<br>AS On ".$formattedDate);
    $reportTableHead                            =    array();
    
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']            =    array('#','width="3%" align=left', "align='left' ");
    $reportTableHead['studentName']     =    array('Name','width="10%" align="left"', 'align="left"');
    $reportTableHead['fatherName']      =    array("Father's Name",'width="10%" align="left"', 'align="left"');
    $reportTableHead['dateOfBirth']     =    array('DOB','width="8%" align="center"', 'align="center"');
    $reportTableHead['compExamRank']    =    array('CET/AIEEE<br>Rank','width="8%" align="left"', 'align="left"');
    $reportTableHead['compExamRollNo']  =    array('CET/AIEEE<br>Roll No.','width="8%" align="left"', 'align="left"');
    $reportTableHead['acd1']            =    array('10th<br><span style="font-size:9px">(%age)</span>','width="6%" align="right"', 'align="right"');
    $reportTableHead['acd2']            =    array('10+2<br><span style="font-size:9px">(%age)</span>','width="6%" align="right"', 'align="right"');
    $reportTableHead['studentGender']   =    array('Gender','width="8%" align="center"', 'align="center"');
    $reportTableHead['quotaName1']       =    array('Category','width="8%" align="left"', 'align="left"');
    $reportTableHead['managementCategory1']  =    array('Mgmt. Quota<br><span style="font-size:9px">(this will be entered at admission time)</span>','width="8%" align="left"', 'align="left"');
    $reportTableHead['permAddress']     =    array('Perm. Address','width="20%" align="left"', 'align="left"');
    $reportTableHead['contactNo']       =    array('Contact Nos.','width="12%" align="left"', 'align="left"');

    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>
