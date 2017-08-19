<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
   
    function parseCSVComments($comments) {
      $comments = str_replace('"', '""', $comments);
      $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
        return '"'.$comments.'"'; 
      } 
      else {
        return chr(160).$comments;    
      }
    }

 
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
        $sortField1 = 'confirmId, IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED)), registrationDate ';
    }
    else if ($sortField == 'confirmId') {
        $sortField1 = 'confirmId';
    }
    else {
       $sortField == 'universityRollNo';
       $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    $orderBy = " $sortField1 $sortOrderBy"; 
    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
    
    $search='';
    // Findout Class Name
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);
    
    // Findout Class Name
    //$classNameArray = $studentManager->getSingleField('class c, study_period sp', 'periodValue', "WHERE c.studyPeriodId=sp.studyPeriodId AND classId  = $termClassId");
    //$periodValue = $classNameArray[0]['periodValue'];
    //$periodValue2 = "Term-".UtilityManager::romanNumerals($periodValue);
    
    //$search='Class:&nbsp;'.$className2."<br>Term:&nbsp;".$periodValue2."<br>As On $formattedDate";  
    $csvData  = "";
    $csvData .= "Class,".parseCSVComments($className2)."\nAs On,".parseCSVComments($formattedDate); 
    $csvData .= "\n";
    
    
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
        
        $orderBySubject = "m.studentId, sub.subjectId, sub.subjectCode ASC"; 
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
    
    $studentArray4 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
    
    for($i=0;$i<count($studentArray4);$i++) {
      $ttStudentId .=",".$studentArray4[$i]['studentId'];  
    }
    
    $totalStudent  = count($studentArray4);
    $studentArray2 = array_merge($studentArray4);
    
    $conditionClassId='';
    if($subjectId!=-1 && $incAll==1) {
       //$conditionClassId  = " AND d.subjectId IN ($subjectId) ";  
       $sCondition = " AND s.studentId NOT IN (".$ttStudentId.")";  
    
       $ssCondition = $studentCondition.$sCondition; 
    
       $orderStudentBy =  $orderBy;
       $studentArray5 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
       
       $totalStudent  = count($studentArray4) + count($studentArray5);
       
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
    
    $csvData1 = "Sr. No., Univ. Roll No., Roll No., Student Name, Reg. Date, Confirm, Major Concentration, CGPA, Career, Elective";
    if($chkCount==0) {
      $csvData .=$csvData1; 
      $csvData .= "\n,,, No Data Found";
      UtilityManager::makeCSV($csvData,'CourseRegistration.csv'); 
      die;
   }  
    
    
    
    
    if($chkCount >0) {
           $csvData .= "Sr. No., Univ. Roll No., Roll No., Student Name, Reg. Date, Confirm, Major Concentration, CGPA";
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
                 $csvData .=",".parseCSVComments(strip_slashes($termClassName));
                 for($ccol=0;$ccol<($cnt[$i])-1;$ccol++) {
                   $csvData .=",";  
                 }
              }
              
           }
           $csvData .= "\n";
           $csvData .= ",,,,,,,";
           for($i=0;$i<count($termClassIdArr);$i++) {  
              if($recordCount1[$i]!=0) {
                 $csvData .=",".parseCSVComments(strip_slashes('Career')); 
                 for($ccol=0;$ccol<($recordCount1[$i])-1;$ccol++) {
                   $csvData .=",";  
                 }
              }
              if($recordCount2[$i]!=0) {
                $csvData .=",".parseCSVComments(strip_slashes('Elective')); 
                for($ccol=0;$ccol<($recordCount2[$i])-1;$ccol++) {
                   $csvData .=",";  
                 }
              }
           }
           $csvData .= "\n";  
        
           $csvData .= ",,,,,,,";
           $chkStudent=0;
           for($i=0;$i<count($termClassIdArr);$i++) {   
              if($recordCount1[$i]!=0) {      
                $csvData .= courseList($recordCount1[$i],$recordArray1[$i]);        // Carrer Course
              }
              if($recordCount2[$i]!=0) {   
                $csvData .= courseList($recordCount2[$i],$recordArray2[$i]);        // Elective Course
              }
              if(($recordCount1[$i]!=0 || $recordCount2[$i]!=0) && $temp==0) {
                $chkStudent=1; 
              }
           }
           $csvData .= "\n";   
    }
    
       
    
    if(count($studentArray2)==0) {
      $csvData .= "\n,,,,, No Data Found";
      UtilityManager::makeCSV($csvData,'CourseRegistration.csv'); 
      die;
    }
    
    if($chkStudent==0) {
      $csvData .= "\n,,,,, No Data Found";
      UtilityManager::makeCSV($csvData,'CourseRegistration.csv'); 
      die;
    }
    
 
     
    $cj=0; 
    for($i=0;$i<count($studentArray2);$i++) {
       $studentId = $studentArray2[$i]['studentId'];
       $cCurrentClassId = $studentArray2[$i]['classId'];
       
       $cgpa = $studentArray2[$i]['cgpa'];
       if($cgpa!=NOT_APPLICABLE_STRING) {
         $cgpa = number_format($studentArray2[$i]['cgpa'],2,'.','');         
       }
       
       if($tableData=='') {
         $tableData .=$tableHead;  
       }
       
       $confirmId = $studentArray2[$i]['confirmId'];
       if($studentArray2[$i]['registrationDate']=='0000-00-00 00:00:00') {
         $regDate = NOT_APPLICABLE_STRING; 
       }
       else {
         $regDate = date('d-M-Y h:i:s A', strtotime($studentArray2[$i]['registrationDate'])); 
       }
       
       $csvData .=($i+1).",".parseCSVComments(strip_slashes($studentArray2[$i]['universityRollNo']));
       $csvData .=",".parseCSVComments(strip_slashes($studentArray2[$i]['rollNo']));
       $csvData .=",".parseCSVComments(strip_slashes($studentArray2[$i]['studentName']));
       $csvData .=",".parseCSVComments(strip_slashes($regDate));  
       $csvData .=",".parseCSVComments(strip_slashes($confirmId));  
       $csvData .=",".parseCSVComments(strip_slashes($studentArray2[$i]['majorConcentration']));
       $csvData .=",".parseCSVComments(strip_slashes($cgpa));
       
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
              //$tableData .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
              $csvData .= studentSubjectList($cnt,$recordArray1[$cj],$registrationArray1[$cj],$studentId,$findStudentId,$subjType[0],$termClassIdArr[$cj]);
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
             //$tableData .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
               $csvData .= studentSubjectList($cnt,$recordArray2[$cj],$registrationArray2[$cj],$studentId,$findStudentId,$subjType[1],$termClassIdArr[$cj]);
           }
        }
        $csvData .= "\n";                  
    }

    UtilityManager::makeCSV($csvData,'CourseRegistrationReport.csv');
    die;   
    
    
    function studentSubjectList($cnt,$subjectArray,$registrationArray,$studentId,$findId,$subjectType,$classId) {

        global $blankSymbol;
        global $subjType;
        
        $result='';
        if($findId==-1) {
           for($j=0;$j<$cnt;$j++) {
             $result .=",0";
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
                 $result .=",".$registrationArray[$tFindId]['credits']; 
                 $tFindId++;  
              }
              else {
                 $result .=",0";    
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
          $result1 .=",".parseCSVComments($blankSymbol);
        }
        else {
            for($j=0; $j<$cnt; $j++) {
               $show=strip_slashes($recordArray[$j]['subjectName']);      
               $result1 .=",".parseCSVComments(strip_slashes($recordArray[$j]['subjectCode']));  
            }
        }
        return $result1;
    }
  
    
?>