<?php

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExternalMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentManager = StudentReportsManager::getInstance();

    $timeTableLabelId = $REQUEST_DATA['timeTable'];
    $classId= $REQUEST_DATA['classId'];
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }

    
    //paging
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';

        
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, universityRollNo)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
    
    
    //$condition = " AND stc.classId = '$classId' AND sub.hasMarks = 1 AND sub.hasAttendance =1 ";
    //$orderSubject = " sub.subjectTypeId, sub.subjectCode, sub.subjectId";
    //$recordArray = $studentManager->classwiseSubjects($condition,$orderSubject);
    //$recordCount = count($recordArray);
    
    // Fetch Subject Type Wise List
    $filter= " c.classId, su.subjectTypeId, st.subjectTypeName AS subjectTypeName, COUNT(DISTINCT st.subjectTypeName) AS cnt, COUNT(DISTINCT su.subjectName) AS cnt1 ";
    $groupBy = "GROUP BY c.classId, su.subjectTypeId ";
    $orderSubjectBy = " classId, subjectTypeId ";
    $cond  = " AND su.hasMarks = 1 AND su.hasAttendance =1 AND c.classId = $classId";
    $recordArray1 =  $studentManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
    $recordCount1 = count($recordArray1);
    

    $tableData = '';
    $tableData = "<table width='100%' border='0' cellspacing='1px' cellpadding='0px' class=''> 
                    <tr class='rowheading'>
                      <td width='2%'  class='searchhead_text'><b>#</b></td>
                      <td width='5%'  class='searchhead_text' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'  class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                      <td width='10%' class='searchhead_text' align='left'><strong>Student Name</strong></td>";
    
    if($recordCount1 >0) {
       $tableData = "<table width='100%' border='0' cellspacing='1px' cellpadding='0px' class=''> 
                    <tr class='rowheading'>
                      <td width='2%'  rowspan=2 class='searchhead_text'><b>#</b></td>
                      <td width='5%'  rowspan=2 class='searchhead_text' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'  rowspan=2 class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                      <td width='10%' rowspan=2 class='searchhead_text' align='left'><strong>Student Name</strong></td>";
        for($i=0; $i<$recordCount1; $i++) {    
          if($recordArray1[$i]['cnt1']>=2) {
            $colspanval = " colspan=".$recordArray1[$i]['cnt1'];  
          }
          else {
            $colspanval = "";     
          }
          $tableData .= "<td width='5%' ".$colspanval." class='searchhead_text' align='center'><strong><nobr>".$recordArray1[$i]['subjectTypeName']."</nobr></strong></td>";  
        } 
        $tableData .= "<td width='5%' rowspan='2' class='searchhead_text' align='right'>
                         <strong>Total</strong></td>";
    }
    $tableData .= "</tr>";
                  
    
    $cnt = 0;
    $colSpanCount = 4;     
    // Fetch Subject Name
    $filter1 = "";
    $filter= " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = " classId,  subjectTypeId, subjectCode, subjectId";
    $recordArray =  $studentManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy,  $orderSubjectBy );   
    $recordCount = count($recordArray); 
    if($recordCount > 0 ) {
        $tableData .= "<tr class='rowheading'>";
          for($i=0; $i<$recordCount; $i++) {  
              $subjectId = $recordArray[$i]['subjectId']; 
              $tableData .= "<td width='5%' class='searchhead_text' align='right'><strong><nobr>".strip_slashes($recordArray[$i]['subjectCode'])."</nobr></strong></td>";
              $colSpanCount = $colSpanCount + 1;
           }
        $tableData .= "</tr>";   
    
        $condition = " AND sg.classId = '$classId' ";
        $externalMarksTotal = $studentManager->getStudentExternalMarks($condition);       
        $totalStudent = count($externalMarksTotal);
        
        $studentArray2 = $studentManager->getStudentExternalMarks($condition,$orderBy,$limit);  
        $cnt = count($studentArray2);       
        
        $condition = " AND ttm.classId = '$classId' ";      
        $findMarksArray = $studentManager->getStudentTotalExternalMarks($condition);  
        $findMarksTotal = count($findMarksArray);       
    }
    
    if($cnt > 0) {
      for($s=0; $s<$cnt; $s++) {      
             $studentId  = $studentArray2[$s]['studentId'];
             $bg = $bg =='trow0' ? 'trow1' : 'trow0';      
             
             $tableData .= "<tr class='$bg'>
                             <td valign='top' class='padding_top' align='left'>".($records+$s+1)."</td>  
                             <td valign='top' class='padding_top' align='left'>".strip_slashes($studentArray2[$s]['universityRollNo'])."</td>
                             <td valign='top' class='padding_top' align='left'>".strip_slashes($studentArray2[$s]['rollNo'])."</td>    
                             <td valign='top' class='padding_top' align='left'>".strip_slashes($studentArray2[$s]['studentName'])."</td>";    
                             
              $find=0;               
             // Fetch Subject Information     
             for($k=0;$k<$findMarksTotal; $k++) {
                if($findMarksArray[$k]['studentId']==$studentId) {
                  $find=1;
                  break; 
                }  
             }
             
             if($find==1) {
                 $total=0; 
                 for($i=0; $i<$recordCount; $i++) {
                    $subjectId = $recordArray[$i]['subjectId'];
                    $tstudentId = $findMarksArray[$k]['studentId'];  
                    $tsubjectId = $findMarksArray[$k]['subjectId'];
                    if($tstudentId==$studentId) {  
                       if($subjectId==$tsubjectId) {
                         $tableData .= "<td valign='top' class='padding_top' align='right'>".strip_slashes($findMarksArray[$k]['marksScored'])."</td>";    
                         $total=$total+$findMarksArray[$k]['marksScored']; 
                         $k++;   
                       }
                       else {
                         $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";    
                       }
                    }
                    else {
                      $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";    
                    }
                 }
                 if($find==1) {
                    $tableData .= "<td valign='top' class='padding_top' align='right'>".$total."</td>";      
                 }
                 else {
                    $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";        
                 }
             }
             else {
               for($i=0; $i<$recordCount; $i++) {  
                  $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";      
               }  
               if($recordCount>0) {
                 $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";      
               }
             }
          $tableData .= "</tr>";
       }
   }
   else {
     $bg = $bg =='trow0' ? 'trow1' : 'trow0';        
     $tableData .= "<tr class='$bg'><td colspan=$colSpanCount><center>No Data Found</center></td></tr>";  
   }
   $tableData .= "</table>";
   
   echo $tableData.'!~~!'.$totalStudent;  
   
die;
       
?>