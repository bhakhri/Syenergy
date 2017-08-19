<?php 
//  This File contains Student Internal Final Marks Foxpro Report
// Author :Parveen Sharma
// Created on : 16-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FinalMarksFoxproReport');
    define('ACCESS','view');  
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    UtilityManager::ifTeacherNotLoggedIn();
    
    require_once(MODEL_PATH."/StudentManager.inc.php");
    $studentManager1 = StudentManager::getInstance();

    require_once(MODEL_PATH."/Teacher/TeacherManager.inc.php");    
    require_once(MODEL_PATH."/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();


    // This function clearSpecialChars in subject code
    function clearSpecialChar($text) {
        if($text!="") {
          $text=strtolower($text);
          $code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
          $code_entities_replace = array('','','','','','','','','','','','','','','','','','','','','','','','','','');
          $text = str_replace($code_entities_match, $code_entities_replace, $text);
        }
        return $text;
    }
   
    
    $classId = $REQUEST_DATA['classId'];
    $reportHead  = "Class&nbsp;:&nbsp;".$REQUEST_DATA['className'];    
    
    //$condition = " AND c.classId = '$classId' ";
    //$recordArray = TeacherManager::getInstance()->getTeacherSubject($condition);
    
    //$recordArray = $studentManager->getTransferredSubjectList($classId);
    //$recordCount = count($recordArray);
    
    $tableName="subject a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b ";
    $fieldName="DISTINCT a.subjectId, a.subjectCode, a.subjectName, a.hasAttendance, a.hasMarks";
    $whereCondition="WHERE a.subjectId = b.subjectId AND b.classId IN ($classId) AND b.conductingAuthority IN (1,3) ";
    $recordArray = $studentManager1->getSingleField($tableName, $fieldName, $whereCondition);
    $recordCount = count($recordArray);

    $subjectId = '';    
    
     
    for ($k=0;$k<$recordCount;$k++) {
       if($subjectId == '')  {
         $subjectId = $recordArray[$k]['subjectId']; 
       }
       else {
         $subjectId .= ','.$recordArray[$k]['subjectId']; 
       }
       if(strlen($recordArray[$k]['subjectCode']) > 8 ) {
         $recordArray[$k]['subjectCode'] = clearSpecialChar(substr(trim($recordArray[$k]['subjectCode']),0,6).trim($k+1));  
       }
       //$recordArray[$k]['subjectCode'] = str_replace('-','',$recordArray[$k]['subjectCode']); 
       //$recordArray[$k]['subjectCode'] = str_replace(' ','',$recordArray[$k]['subjectCode']); 
       //$recordArray[$k]['subjectCode'] = str_replace('.','',$recordArray[$k]['subjectCode']); 
       if($recordArray[$k]['subjectCode']=='') {
         $recordArray[$k]['subjectCode'] = clearSpecialChar($recordArray[$k]['subjectName']);
         $recordArray[$k]['subjectCode'] = clearSpecialChar(substr(trim($recordArray[$k]['subjectCode']),0,6).trim($k+1));
         //$recordArray[$k]['subjectCode'] = str_replace('-','',$recordArray[$k]['subjectCode']); 
         //$recordArray[$k]['subjectCode'] = str_replace(' ','',$recordArray[$k]['subjectCode']); 
         //$recordArray[$k]['subjectCode'] = str_replace('.','',$recordArray[$k]['subjectCode']);  
       }
    }
    
    $filter ='';
    if($recordCount > 0 ) {
         $fieldValue='';
         for ($k=0;$k<$recordCount;$k++) {
               $filter .= ', (SELECT 
                                    SUM(IFNULL(ttm1.marksScored,0))+SUM(IFNULL(tgm.graceMarks,0)) AS marksScored 
                              FROM  
                                    subject sub,  '.TOTAL_TRANSFERRED_MARKS_TABLE.' ttm1 LEFT JOIN '.TEST_GRACE_MARKS_TABLE.' tgm ON 
                                    ttm1.studentId = tgm.studentId AND ttm1.subjectId = tgm.subjectId AND 
                                    ttm1.classId = tgm.classId  AND tgm.subjectId="'.$recordArray[$k]['subjectId'].'" 
                              WHERE 
                                    ttm1.subjectId=sub.subjectId AND
                                    ttm1.classId = ttm.classId AND ttm1.studentId = ttm.studentId AND 
                                    ttm1.subjectId="'.$recordArray[$k]['subjectId'].'" AND 
                                    ttm1.conductingAuthority IN(1,3) ) AS  sub'.$recordArray[$k]['subjectId'];   
         }                                                                                                                     
         $condition =" AND ttm.conductingAuthority IN (1,3) AND ttm.classId = '$classId' AND ttm.subjectId IN ($subjectId) ";
         $externalMarksArray = $studentManager->getStudentExternalMarks($filter,$condition);
         if($externalMarksArray === false) {
           echo INCORRECT_FORMAT;
           die;
         }
    }
    
    $externalRecordCount = count($externalMarksArray);                                

    // Generate Final Marks List    
    if($externalRecordCount > 0 ) {
      for($i=0;$i<$externalRecordCount;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
	    $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$externalMarksArray[$i]);
      }
    }
    
	$reportManager->setReportWidth(600);
	$reportManager->setReportHeading('Student Final Marks Report');
    $reportManager->setReportInformation($reportHead);         
	 
	$reportTableHead				        =	array();
	$reportTableHead['srNo']		        =	array('#','width="3%"', "align='center' ");
	$reportTableHead['universityRollNo']	=	array('URoll No.','width="10%" align="left" ','align="left"');
	$reportTableHead['studentName']		    =	array('Name','width=10% align="left"','align="left"');
	$reportTableHead['fatherName']	        =	array("Father's Name",'width="10%" align="left"', 'align="left"');
	for($k=0;$k<$recordCount;$k++) {   
       $subjectCode = "sub".$recordArray[$k]['subjectId']; 
       $reportTableHead[$subjectCode]      =  array($subjectCode,'width="5%" align="right"', 'align="right"');
    }  

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
 
// $History: teacherFinalMarksPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/05/10    Time: 6:03p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//query format updated (student_group checks added)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/29/09   Time: 1:15p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//use getTransferredSubjectList() function of student reports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:35p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added multiple table defines.
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/16/09    Time: 2:54p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//inital checkin
//

?>