<?php
//--------------------------------------------------------
// This file generate a list Student Rank wise Report
// Author :Parveen Sharma
// Created on : 12-12-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRank');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
  
    
    $conditions = "";
   
    $timeTableLabelId = trim($REQUEST_DATA['timeTable']);
    $classResultId = trim($REQUEST_DATA['classResultId']);
    $classId = trim($REQUEST_DATA['classId']);
    $rankValue = trim($REQUEST_DATA['rankValue']);
    $rank = trim($REQUEST_DATA['rank']);
    $examId = trim($REQUEST_DATA['examId']); 
    
    
    // Search filter
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else 
    if ($sortField == 'regNo') {
        $sortField1 = 'IF(IFNULL(regNo,"")="" OR regNo = "'.NOT_APPLICABLE_STRING.'",s.studentId, regNo)';
    }
    else
    if ($sortField == 'className') {
       $sortField1 = "s.studentId $sortOrderBy, s.classId";   
    }
    else
    if ($sortField == 'compExamBy') {
        $sortField1 = 'IF(IFNULL(compExamBy,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, compExamBy)';
    }
    else
    if ($sortField == 'compExamRollNo') {
        $sortField1 = 'IF(IFNULL(compExamRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRollNo AS UNSIGNED) )';
    }
    else
    if ($sortField == 'compExamRank') {
        $sortField1 = 'IF(IFNULL(compExamRank,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, CAST(compExamRank AS UNSIGNED))';
    }
    else {
        $sortField = 'studentName';
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR studentName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';   
    }
    
    $orderBy = " ORDER BY $sortField1 $sortOrderBy ";  
    
    
    //paging
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
   
    if($classResultId != ''){
      $cntArr = explode(',',$classResultId);
    }
    
    if($classId == ''){
      $classId = 0;      
    }
    
    if($REQUEST_DATA['examId']!='all') {
      $conditions .=" AND compExamBy = ".$REQUEST_DATA['examId'];  
    }
    
    // Rank  1 = Above,  2 = below, 3 = Equal
    if($REQUEST_DATA['rank']==1) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) > ".$REQUEST_DATA['rankValue'];
    }
    else if($REQUEST_DATA['rank']==2) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) < ".$REQUEST_DATA['rankValue'];
    }
    else if($REQUEST_DATA['rank']==3) {
        $conditions.=" AND CAST(s.compExamRank AS UNSIGNED) = ".$REQUEST_DATA['rankValue'];
    }
    
    
    // Student Academic Details
    $condition1 =" AND sg.classId IN ($classId)";
    $academicArray = $studentManager->getStudentAcademic($condition1);
    $academicCount = count($academicArray);
  
    $conditions .=" AND s.classId IN ($classId)";
    $recordCount = $studentManager->getStudentRankWiseCount($conditions);  
    $totalRecords = $recordCount[0]['cnt'];
    
    
    $recordArray = $studentManager->getStudentRankWise($conditions, $orderBy, $limit);
    $cnt = count($recordArray);
    
    for($i=0;$i<$cnt;$i++) {   
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $studentId = $recordArray[$i]['studentId'];
       
       if($recordArray[$i]['compExamBy'] == '' || $recordArray[$i]['compExamBy'] == 'NULL' || $recordArray[$i]['compExamBy'] == 'NA') {
          $recordArray[$i]['compExamBy']=NOT_APPLICABLE_STRING; 
       }
       else {
          $recordArray[$i]['compExamBy']=$results[$recordArray[$i]['compExamBy']];
       }
       
       if($recordArray[$i]['compExamRollNo']=='') {
         $recordArray[$i]['compExamRollNo']=NOT_APPLICABLE_STRING;
       }
       
       if($recordArray[$i]['compExamRank']=='') {
         $recordArray[$i]['compExamRank']=NOT_APPLICABLE_STRING;
       }

       for($j=0; $j<count($cntArr); $j++) {
          $str = $cntArr[$j];  
          $str1 = "m".substr($cntArr[$j],1,strlen($str));
          $recordArray[$i][$str] = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;";
          $recordArray[$i][$str1] = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;";
       }

       $find=0;       
       for($k=0; $k<$academicCount; $k++) {
          $aStudentId = $academicArray[$k]['studentId'];
          $per = $academicArray[$k]['previousPercentage'];  
          $mks = number_format($academicArray[$k]['previousMarks'],0);
          $maxMarks = number_format($academicArray[$k]['previousMaxMarks'],0);
          $examClass = "e".$academicArray[$k]['previousClassId'];
          $examClassM = "m".$academicArray[$k]['previousClassId'];  
          $mksChk = "$mks/$maxMarks";
          if($mksChk=='') {
            $mksChk = NOT_APPLICABLE_STRING."&nbsp;&nbsp;&nbsp;"; 
          }
          if($aStudentId == $studentId) {
            $find=1;
            for($j=0; $j<count($cntArr); $j++) {
               $str = $cntArr[$j];  
               if($str==$examClass) { 
                 $recordArray[$i][$examClassM] = $mksChk;     
                 $recordArray[$i][$str] = $per;
                 break;
               }
            }
          }  
          else if($find==1) {
            break;  
          }
       }
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: initStudentRankWiseReport.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/08/10    Time: 2:42p
//Updated in $/LeapCC/Library/StudentReports
//time table label base report format updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/11/10    Time: 3:42p
//Updated in $/LeapCC/Library/StudentReports
//sorting order format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/24/09   Time: 2:49p
//Updated in $/LeapCC/Library/StudentReports
//sorting order updated
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/23/09   Time: 4:58p
//Updated in $/LeapCC/Library/StudentReports
//fixed bug no.0002099
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:15p
//Created in $/LeapCC/Library/StudentReports
//file added
//

?>
