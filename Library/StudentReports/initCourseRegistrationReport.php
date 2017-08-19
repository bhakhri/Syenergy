<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    ini_set('memory_limit','20M');
    set_time_limit(0);
    //ini_set('MEMORY_LIMIT','100M');
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    $studentManager = StudentManager::getInstance();
     
    $classId = trim($REQUEST_DATA['classId']);
    $termClassId = trim($REQUEST_DATA['termClassId']);
    $termClassId1 = trim($REQUEST_DATA['termClassId1']);  
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $incAll = trim($REQUEST_DATA['incAll']); 
       
    if($classId=='') {
      $classId=0;  
    }
    
    if($termClassId=='') {
      $termClassId=0;  
    }
    
    if($incAll=='') {
      $incAll=0;  
    }
    
    $termClassIdArr=explode(',',$termClassId);  
    
    if($subjectId=='') {
      $subjectId=-1;  
    }
    
    $blankSymbol='X';
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
    
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    else if ($sortField == 'cgpa') {
        $sortField1 = 'IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED))';
    }
    else if ($sortField == 'cgpa1') {
        $sortField1 = 'IF(registrationDate="0000-00-00 00:00:00",-s.studentId, registrationDate), CAST(cgpa AS UNSIGNED)';
    }
    else if ($sortField == 'date1') {
        $sortField1 = 'IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED)), registrationDate ';
    }
    else if ($sortField == 'cgpa2') {
        $sortField1 = 'confirmId, IF(registrationDate="0000-00-00 00:00:00",-s.studentId, registrationDate), CAST(cgpa AS UNSIGNED)';
    }
    else if ($sortField == 'date2') {
        $sortField1 = 'confirmId, IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED)), registrationDate';
    }
    else if ($sortField == 'confirmId') {
        $sortField1 = 'confirmId';
    }
    else {
       $sortField == 'universityRollNo';
       $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    $orderBy = " $sortField1 $sortOrderBy"; 
    
    
    
    $subjType[0]='Career';
    $subjType[1]='Elective';
    $orderBySubject="sub.subjectId, sub.subjectCode ASC";
    $filter="DISTINCT sub.subjectCode, sub.subjectName, sub.subjectId, d.classId";
    
    $chkCount=0;      
    for($i=0;$i<count($termClassIdArr);$i++) {   
        $termClassId = $termClassIdArr[$i];
        
        $orderBySubject = "d.classId, sub.subjectId, sub.subjectCode ASC";     
        $condition = " AND m.currentClassId = $classId AND d.classId=$termClassId AND d.subjectType='".$subjType[0]."'";
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $recordArray1[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject,$filter);
        $recordCount1[$i] = count($recordArray1[$i]);
        
        $condition = " AND m.currentClassId = $classId AND d.classId=$termClassId AND d.subjectType='".$subjType[1]."'";
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId) ";  
        }
        $recordArray2[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject,$filter);
        $recordCount2[$i] = count($recordArray2[$i]); 
         
        $cnt[$i]=$recordCount1[$i]+$recordCount2[$i];
        if($recordCount1[$i]==0) {
          //$cnt[$i]++;
        }
        if($recordCount2[$i]==0) {
          //$cnt[$i]++;
        }
        
        $chkCount=$chkCount+$cnt[$i];
        
        $orderBySubject = "m.studentId,sub.subjectId, sub.subjectCode ASC"; 
        $condition = " AND m.currentClassId=$classId AND d.subjectType='".$subjType[0]."' AND d.classId=$termClassId"; 
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $registrationArray1[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject);   
    
        $condition = " AND m.currentClassId=$classId AND d.subjectType='".$subjType[1]."' AND d.classId=$termClassId"; 
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $registrationArray2[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject);   
    }
    
    // Fetch Student List 
    $studentCondition = " AND c.classId = $classId ";  
    if($incAll==0) {
      $studentCondition .= " AND m.studentId = s.studentId";    
    }
    
    
    $conditionClassId = '';   
    if($subjectId!=-1) {
       $conditionClassId  = " AND d.subjectId IN ($subjectId) ";  
       $sCondition = " AND d.subjectId IN ($subjectId)"; 
    } 
    
    $ssCondition = $studentCondition.$sCondition;
    
    $ttStudentId =0;
    
    $orderStudentBy =  $orderBy;
    $totalStudentArray4 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
    $totalStudent4 = count($totalStudentArray4);
    
    $studentArray4 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,$limit,$conditionClassId);  
    $totalStudent  = $totalStudent4;
    
    for($i=0;$i<count($studentArray4);$i++) {
      $ttStudentId .=",".$studentArray4[$i]['studentId'];  
    }
    
    $conditionClassId='';
    if($subjectId!=-1 && $incAll==1) {
       //$conditionClassId  = " AND d.subjectId IN ($subjectId) ";  
       $sCondition = " AND s.studentId NOT IN (".$ttStudentId.")";  
    
       $ssCondition = $studentCondition.$sCondition; 
    
       $orderStudentBy =  $orderBy;
       $totalStudentArray5 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
       $totalStudent5 = count($totalStudentArray5);
       $studentArray5 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,$limit,$conditionClassId);  
       
       $totalStudent  = $totalStudent4 + $totalStudent5;
      
    }
    
    
    if(count($studentArray4)>0) {
     $studentArray2 = array_merge($studentArray4);
   }    
   else if(count($studentArray5)>0) {
     $studentArray2 = array_merge($studentArray5);
   } 
   
   if(count($studentArray4)>0 && count($studentArray5)>0) {
     $studentArray2 = array_merge($studentArray4,$studentArray5);
   } 
    
    
    //$studentArray2 =  $studentManager->getRegistrationStudentList($studentCondition,$orderStudentBy,$limit,$conditionClassId);  
    
    
    $tableData = "<table width='100%' border='0' cellspacing='1px' cellpadding='0px' class=''> 
                    <tr class='rowheading'>
                      <td width='2%'    style='padding-left:2px' class='searchhead_text' ><b>#</b></td>
                      <td width='5%'    style='padding-left:2px' class='searchhead_text' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text' align='left'><strong>Student Name</strong></td> 
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text' align='center'><strong>Reg. Date</strong></td>
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text' align='center'><strong>Confirm</strong></td> 
                      <td width='10%'   style='padding-left:2px' class='searchhead_text' align='left'><strong>Major Concentration</strong></td>
                      <td width='4%'   style='padding-right:2px' class='searchhead_text' align='right'><strong>CGPA</strong></td>
                      <td width='4%'   style='padding-left:2px' class='searchhead_text'  align='left'><strong>Career</strong></td> 
                      <td width='4%'   style='padding-left:2px' class='searchhead_text'  align='left'><strong>Elective</strong></td> 
                    </tr>";
    
    
    if($chkCount==0) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $colSpanCount=8;  
       $tableData .= "<tr class='$bg'><td class='padding_top' colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       echo $tableData.'!~~!'."";     
       die;
    }  
    
    
    if($chkCount >0) {
       $tableData = "<table width='100%' border='0' cellspacing='1px' cellpadding='0px' class=''> 
                    <tr class='rowheading'>
                      <td width='2%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' ><b>#</b></td>
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='left'><strong>Roll No.</strong></td>
                      <td width='10%'  style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='left'><strong>Student Name</strong></td>
                      <td width='9%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='center'><strong>Reg. Date</strong></td>
                      <td width='9%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='center'><strong>Confirm</strong></td>
                      <td width='8%'   style='padding-left:2px'  class='searchhead_text'  rowspan='3' align='left'><strong>Major Concentration</strong></td>
                      <td width='4%'   style='padding-right:2px' class='searchhead_text'  rowspan='3' align='right'><strong>CGPA</strong></td>";
           for($i=0;$i<count($termClassIdArr);$i++) {
              $colspan='';
              if($cnt[$i]>=2) {
                $colspan="colspan=".$cnt[$i];  
              } 
              $colspan1[$i]='';
              $colspan2[$i]='';
              if($recordCount1[$i]>1) {
                $colspan1[$i]="colspan=$recordCount1[$i]"; 
              }
              if($recordCount2[$i]>1) {
                $colspan2[$i]="colspan=$recordCount2[$i]"; 
              }                   
              $termClassName = "Term-".UtilityManager::romanNumerals($i+4); 
              if(($recordCount1[$i]+ $recordCount2[$i])>0 ) {
                 $tableData .= "<td width='4%'   style='padding-left:2px' class='searchhead_text'  align='center' $colspan ><strong>$termClassName</strong></td>";
              }
           }
           $tableData .= "<td width='4%' class='searchhead_text' align='center' rowspan='3' ><strong>Action</strong></td></tr>";
           
           
           $tableData .= "<tr class='rowheading'>";
           for($i=0;$i<count($termClassIdArr);$i++) {  
              if($recordCount1[$i]!=0) {
                 $tableData .= "<td width='4%' class='searchhead_text' $colspan1[$i] align='center'><strong>Career</strong></td>";
              }
              if($recordCount2[$i]!=0) {
                 $tableData .= "<td width='4%' class='searchhead_text' $colspan2[$i] align='center'><strong>Elective</strong></td>";
              }
           }
           $tableData .= "</tr>";
          
           $chkStudent=0;
           $tableData .= "<tr class='rowheading'>";
           for($i=0;$i<count($termClassIdArr);$i++) {   
              
              if($recordCount1[$i]!=0) {      
                $tableData .= courseList($recordCount1[$i],$recordArray1[$i]);        // Carrer Course
              }
              
              if($recordCount2[$i]!=0) {   
                $tableData .= courseList($recordCount2[$i],$recordArray2[$i]);        // Elective Course
              }
              
              if(($recordCount1[$i]!=0 || $recordCount2[$i]!=0) && $temp==0) {
                $chkStudent=1; 
              }
           }
           $tableData .= "</tr>"; 
    }
    
       
    if(count($studentArray2)==0) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $colSpanCount=100;  
       $tableData .= "<tr class='$bg'><td class='padding_top' colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       echo $tableData.'!~~!'."";   
       die;
    }
    
    if($chkStudent==0) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $colSpanCount=100;  
       $tableData .= "<tr class='$bg'><td class='padding_top' colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       echo $tableData.'!~~!'."";   
       die;
    }
    
    
    
    $cj=0; 
    for($i=0;$i<count($studentArray2);$i++) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $studentId = $studentArray2[$i]['studentId'];
       $cCurrentClassId = $studentArray2[$i]['classId'];
       
       $cgpa = $studentArray2[$i]['cgpa'];
       if($cgpa!=NOT_APPLICABLE_STRING) {
         $cgpa = number_format($studentArray2[$i]['cgpa'],2,'.','');         
       }
       
       $confirmId = $studentArray2[$i]['confirmId'];
       if($studentArray2[$i]['registrationDate']=='0000-00-00 00:00:00') {
         $regDate = NOT_APPLICABLE_STRING; 
       }
       else {
         $regDate = date('d-M-Y h:i:s A', strtotime($studentArray2[$i]['registrationDate'])); 
       }
       
       $tableData .= "<tr class='$bg'>
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='left'>".($records+$i+1)."</td>  
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($studentArray2[$i]['universityRollNo'])."</td>
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($studentArray2[$i]['rollNo'])."</td>    
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($studentArray2[$i]['studentName'])."</td>
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='center'>".strip_slashes($regDate)."</td> 
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='center'>".strip_slashes($confirmId)."</td>   
                         <td valign='top'  style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($studentArray2[$i]['majorConcentration'])."</td>
                         <td valign='top'  style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($cgpa)."</td>"; 
      
      
      $registrationId=-1;
      for($cj=0;$cj<count($termClassIdArr);$cj++) { 
           // Student Findout
           $findStudentId = "-1";
           
           if(count($registrationArray1[$cj])>0) {
             for($j=0; $j < count($registrationArray1[$cj]); $j++) { 
               if($registrationArray1[$cj][$j]['studentId']==$studentId && $registrationArray1[$cj][$j]['classId']==$termClassIdArr[$cj]) {  
                 $findStudentId = $j; 
                 $registrationId  = $registrationArray1[$cj][$j]['registrationId'];
                 break;
               }
             }
           }
           
           
           $cnt=count($recordArray1[$cj]);
           
           if($cnt==0) {
              //$tableData .= "<td width='4%' class='padding_top' align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
              $tableData .= studentSubjectList($cnt,$recordArray1[$cj],$registrationArray1[$cj],$studentId,$findStudentId,$subjType[0],$termClassIdArr[$cj]);
           }
           
            // Student Findout
           $findStudentId = "-1";
           if(count($registrationArray2[$cj])>0) {
             for($j=0; $j < count($registrationArray2[$cj]); $j++) { 
               if($registrationArray2[$cj][$j]['studentId']==$studentId && $registrationArray2[$cj][$j]['classId']==$termClassIdArr[$cj]) {  
                 $findStudentId = $j; 
                 $registrationId  = $registrationArray2[$cj][$j]['registrationId'];  
                 break;
               }
             }
           }
           
           
           $cnt=count($recordArray2[$cj]);
           if($cnt==0) {
             //$tableData .= "<td width='4%' class='padding_top' align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
               $tableData .= studentSubjectList($cnt,$recordArray2[$cj],$registrationArray2[$cj],$studentId,$findStudentId,$subjType[1],$termClassIdArr[$cj]);
           }
       }
       
       if($registrationId==-1) {
         $showlink = "<div align='left' style='padding-left:10px'><a href='studentCourseRegistrationDetail.php?studentId=".$studentId."&currentClassId=".$cCurrentClassId."&page=".$page."&sortField=".$sortField."&sortOrderBy=".$sortOrderBy."&incAll=".$incAll."' alt='Edit' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>&nbsp;&nbsp;</div>";
       }
       else {
         $showlink = "<div align='left' style='padding-left:10px'><a href='studentCourseRegistrationDetail.php?studentId=".$studentId."&currentClassId=".$cCurrentClassId."&page=".$page."&sortField=".$sortField."&sortOrderBy=".$sortOrderBy."&incAll=".$incAll."' alt='Edit' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>&nbsp;&nbsp;
                    <a href='#' onClick='deleteStudent(".$registrationId.")' alt='Delete' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif'  border='0' /></a>&nbsp;&nbsp;
                    <a href='#' onClick='printStudentReport(".$registrationId.",".$cCurrentClassId.",".$studentId.")' alt='Print' title='Print'><img src='".IMG_HTTP_PATH."/print1.gif'  border='0' /></a>&nbsp;</div>";
       }
       
       $tableData .= "<td width='2%' class='padding_top' align='left'><nobr>".$showlink."</nobr></td>"; 
       $tableData .= "<tr>";                  
    }

    $tableData .= "</table>";
    echo $tableData.'!~~!'.$totalStudent;   
    die;   
    
    
    function studentSubjectList($cnt,$subjectArray,$registrationArray,$studentId,$findId,$subjectType,$classId) {

        global $blankSymbol;
        global $subjType;
        
        $result='';
        
        if($findId==-1) {
           for($j=0;$j<$cnt;$j++) {
             $result .= "<td width='4%' class='padding_top' align='center'>0</td>";  
           }
        }
        else {
           $tFindId=$findId;
           for($j=0; $j<$cnt; $j++) {
              $subjectId = $subjectArray[$j]['subjectId'];
              $tStudentId = $registrationArray[$tFindId]['studentId'];
              $tSubjectType = $registrationArray[$tFindId]['subjectType'];
              $tSubjectId = $registrationArray[$tFindId]['subjectId'];
              $tClassIds = $registrationArray[$tFindId]['classId']; 
              if($tStudentId==$studentId && $tSubjectType==$subjectType && $tSubjectId==$subjectId && $tClassIds==$classId) {
                 $result .= "<td width='4%' class='padding_top' align='center'>".$registrationArray[$tFindId]['credits']."</td>";  
                 $tFindId++;
              }
              else {
                 $result .= "<td width='4%' class='padding_top' align='center'>0</td>";    
              } 
           }
        }
        return $result;
    }
    
    
    function checkBlank($num) {
        
        global $blankSymbol;
        
        $result1='';
        if($num==0) {  
          $result1 = $blankSymbol;  
        }
        else {
          $result1 = 0;   
        }
        return $result1;
    }
    
    
    function courseList($cnt,$recordArray) {
        global $blankSymbol;
        $result1='';
        if($cnt==0) {
          $result1 .= "<td width='4%' class='searchhead_text' align='center'><strong><nobr>".$blankSymbol."</nobr></strong></td>";    
        }
        else {
            for($j=0; $j<$cnt; $j++) {
               $show=strip_slashes($recordArray[$j]['subjectName']);      
               $result1 .= "<td width='4%' class='searchhead_text' align='center'><strong><nobr><span title='".$show."'>".strip_slashes($recordArray[$j]['subjectCode'])."</span></nobr></strong></td>";  
            }
        }
        return $result1;
    }
    
?>