<?php
   ini_set('MEMORY_LIMIT','200M');
   set_time_limit(0);
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','COMMON');
   define('ACCESS','view');
   define('MANAGEMENT_ACCESS',1);
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
                                                                  
    
    require_once(MODEL_PATH . "/StudentCGPARepotManager.inc.php");
    $studentCGPARepotManager = StudentCGPARepotManager::getInstance();
    
    
    $finalGraphArray = array(); 
    
    $labelId = trim($REQUEST_DATA['timeTable']);
    $classId = trim($REQUEST_DATA['degree']);
    $subjectId = trim($REQUEST_DATA['subjectId']);
    
    $internal = trim($REQUEST_DATA['internal']); 
    $attendance = trim($REQUEST_DATA['attendance']); 
    $external = trim($REQUEST_DATA['external']); 
    $total = trim($REQUEST_DATA['total']); 
    $isCGPA = trim($REQUEST_DATA['isCGPA']); 
    $isSGPA = trim($REQUEST_DATA['isSGPA']);          
    $gradeList = trim($REQUEST_DATA['gradeList']); 

    $class1 =  $reportManager->getReportHeadingStyle();  
    $class2 =  $reportManager->getReportDataStyle();    
    
    if($labelId=='') {
      $labelId='0';  
    }
    if($classId=='') {
      $classId='0';  
    }
    if($subjectId=='') {
      $subjectId='0';  
    }
    
    $condition = " WHERE sc.classId = '$classId' ";
    $resourceRecordArrayGPA = $studentCGPARepotManager->getStudentCGPASGPA($condition);
    $gpaArray = array();
    $cgpaArray = array();
    for($i=0;$i<count($resourceRecordArrayGPA);$i++) {
       $ggStudentId = $resourceRecordArrayGPA[$i]['studentId'];    
     
       $gpa = UtilityManager::decimalRoundUp($resourceRecordArrayGPA[$i]['gpa']);
       $gpaArray[$ggStudentId]=$gpa;
     
       $cgpa = UtilityManager::decimalRoundUp($resourceRecordArrayGPA[$i]['cgpa']);
       $cgpaArray[$ggStudentId]=$cgpa;
    }

   // $subjectArray = $studentReportsManager->getSingleField("subject", "DISTINCT subjectId, subjectCode, subjectName", " where subjectId IN ($subjectId)");
     $subjectArray = $studentReportsManager->getAlternateSubject($subjectId,$classId);
    $subjectCodeArray = array();
    foreach($subjectArray as $record) {
      $subjectCodeArray[$record['subjectId']] = $record['subjectCode'];
    }
    
    
    $gradeGraphArray = array(); 
    
    $graphCondition = "";
    if($subjectId!='0') {
      $graphCondition = " AND a.subjectId IN ('$subjectId') ";  
    } 
    $returnGraphArray = $studentReportsManager->getStudentGraphGradingNew($classId, $graphCondition); 
    
    for($j=0;$j<count($returnGraphArray);$j++) {
      $gradeGraphArray[$j]['gradeSetId']= $returnGraphArray[$j]['gradeSetId']; 
      $gradeGraphArray[$j]['gradeId']= $returnGraphArray[$j]['gradeId'];  
      $gradeGraphArray[$j]['gradeLabel'] = $returnGraphArray[$j]['gradeLabel'];  
      $gradeGraphArray[$j]['gradePoints'] = $returnGraphArray[$j]['gradePoints'];  
    }
    $gradeGraphArray[$j]['gradeSetId']= ""; 
    $gradeGraphArray[$j]['gradeId']='';  
    $gradeGraphArray[$j]['gradeLabel'] = "I";  
    $gradeGraphArray[$j]['gradePoints'] = "0"; 
    
    for($i=0;$i<count($subjectArray);$i++) {
       $ggSubjectId = $subjectArray[$i]['subjectId'];
       $ggSubjectCode = $subjectArray[$i]['subjectCode'];
       $ggSubjectName = $subjectArray[$i]['subjectName'];
       for($j=0;$j<count($returnGraphArray);$j++) {
          $ggGradeLabel = $returnGraphArray[$j]['gradeLabel'];
          
          $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectId']= $ggSubjectId; 
          $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectCode']= $ggSubjectCode; 
          $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectName']= $ggSubjectName;
          $finalGraphArray[$ggSubjectId][$ggGradeLabel]['gradeLabel'] = $ggGradeLabel;
          $finalGraphArray[$ggSubjectId][$ggGradeLabel]['studentCount'] = 0;
       }
       $ggGradeLabel = 'I';     
       $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectId']= $ggSubjectId; 
       $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectCode']= $ggSubjectCode; 
       $finalGraphArray[$ggSubjectId][$ggGradeLabel]['subjectName']= $ggSubjectName;
       $finalGraphArray[$ggSubjectId][$ggGradeLabel]['gradeLabel'] = $ggGradeLabel;
       $finalGraphArray[$ggSubjectId][$ggGradeLabel]['studentCount'] = 0;
    }
    
    
    $allDetailsArray = array();
    $sortBy = '';

    $sorting = $REQUEST_DATA['sorting'];
    if ($sorting == 'cRollNo') {
        $sortBy = ' length(rollNo)+0,rollNo ';
    }
    elseif ($sorting == 'uRollNo') {
        $sortBy = ' length(universityRollNo)+0,universityRollNo ';
    }
    elseif ($sorting == 'name') {
        $sortBy = ' studentName ';
    }
    $sortBy .= $REQUEST_DATA['ordering'];

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    $studentArray2 = $studentReportsManager->countClassStudents($classId);
    $totalStudents = $studentArray2[0]['cnt'];

    $studentArray = $studentReportsManager->getClassStudents($classId, $sortBy, '');
    $studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
    $studentNameList = UtilityManager::makeCSList($studentArray, 'studentName');
    if (empty($studentIdList)) {
        $studentIdList = 0;
    }
    if (empty($studentNameList)) {
        $studentNameList = 0;
    }
    
    
    
    

    //$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", "where subjectId in ($subjectId) order by subjectCode");

       $maxMarksArray = array();
        $gradeLabelNotExist = 'I';

        $dataArray = array();
        $studentStatusArray = array();
        $subMaxMarksArray = $studentReportsManager->getInternalMaxMarks($classId, $subjectId);
        foreach($subMaxMarksArray as $record) {
            $maxMarksArray[$record['subjectId']]['I'] = round($record['maxMarks'],1);
         }

      
        $internalArray = $studentReportsManager->getInternalMarks($studentIdList, $classId, $subjectId);
        foreach($internalArray as $record) {
            $studentId = $record['studentId'];
            $subjectIdNew = $record['subjectId'];
            $gradeSetId = $record['gradeSetId'];
            
            
            if($record['marksScoredStatus']=='Marks') {
              $dataArray[$studentId][$subjectIdNew]['I'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['P'] = $record['gradePoints'];
              $dataArray[$studentId][$subjectIdNew]['C'] = $record['credits'];
              $dataArray[$studentId][$subjectIdNew]['IR'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel'];  
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = $record['failGrade'];  
            }
            else if($record['marksScoredStatus']=='D') {
              $dataArray[$studentId][$subjectIdNew]['I'] = '0';
              $dataArray[$studentId][$subjectIdNew]['P'] = $record['gradePoints'];
              $dataArray[$studentId][$subjectIdNew]['C'] = $record['credits'];
              $dataArray[$studentId][$subjectIdNew]['IR'] = $record['marksScoredStatus'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;    
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            else if($record['marksScoredStatus']=='0') {
              $dataArray[$studentId][$subjectIdNew]['I'] = '0';
              $dataArray[$studentId][$subjectIdNew]['P'] = $record['gradePoints'];
              $dataArray[$studentId][$subjectIdNew]['C'] = $record['credits'];
              $dataArray[$studentId][$subjectIdNew]['IR'] = $record['marksScoredStatus'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;    
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            else {
              $dataArray[$studentId][$subjectIdNew]['I'] = '0';
              $dataArray[$studentId][$subjectIdNew]['IR'] = 'A';
              $dataArray[$studentId][$subjectIdNew]['P'] = $record['gradePoints'];
              $dataArray[$studentId][$subjectIdNew]['C'] = $record['credits'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;   
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            if($maxMarksArray[$subjectId]['I'] > 0) {
               $percent = 0; 
               if($record['marksScoredStatus']=='Marks') {
                 $percent = (($dataArray[$studentId][$subjectIdNew]['I'] * 100) / $maxMarksArray[$subjectIdNew]['I']);
               }
               if($percent < 40) {
                 $studentStatusArray[$studentId][$subjectIdNew] = 'RTI ' . $subjectCodeArray[$subjectIdNew];
               }
            }
        }
        
       
        /*
            $internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
            foreach($internalArray as $record) {
                $dataArray[$record['studentId']][$record['subjectId']]['A'] = '<u>'.$record['lectureAttended'] .'</u><br>' . $record['lectureDelivered'];
            }
        */
        
        
        if ($attendance == 'on') {
            $internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
            foreach($internalArray as $record) {
                $dataArray[$record['studentId']][$record['subjectId']]['A'] = '<u>'.$record['lectureAttended'] .'</u><br>' . $record['lectureDelivered'];
            }
        }
        
        $subMaxMarksArray = $studentReportsManager->getExternalMaxMarks($classId, $subjectId);
        foreach($subMaxMarksArray as $record) {
            $maxMarksArray[$record['subjectId']]['E'] = round($record['maxMarks'],1);
        }
            
        $externalArray = $studentReportsManager->getExternalMarks($studentIdList, $classId, $subjectId); 
        foreach($externalArray as $record) {
            $studentId = $record['studentId'];
            $subjectIdNew = $record['subjectId'];
            //$dataArray[$studentId][$subjectIdNew]['E'] = $record['marksScored'] == '' ? 0 : round($record['marksScored'],1);
            if($record['marksScoredStatus']=='Marks') {
              $dataArray[$studentId][$subjectIdNew]['E'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['ER'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel'];
            }
            else if($record['marksScoredStatus']=='D') {
              $dataArray[$studentId][$subjectIdNew]['E'] = '0';
              $dataArray[$studentId][$subjectIdNew]['ER'] = $record['marksScoredStatus'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist; 
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            else if($record['marksScoredStatus']==0) {
              $dataArray[$studentId][$subjectIdNew]['E'] = '0';
              $dataArray[$studentId][$subjectIdNew]['ER'] = $record['marksScoredStatus'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist; 
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            else {
              $dataArray[$studentId][$subjectIdNew]['E'] = '0';
              $dataArray[$studentId][$subjectIdNew]['ER'] = 'A';
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist; 
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = '';  
            }
            
            if($maxMarksArray[$subjectId]['E'] > 0) {
               $percent = 0; 
               if($record['marksScoredStatus']=='Marks') {
                 $percent = (($dataArray[$studentId][$subjectIdNew]['E'] * 100) / $maxMarksArray[$subjectIdNew]['E']);
               }
               if($percent < 40) {
                 $studentStatusArray[$studentId][$subjectIdNew] = 'RP ' . $subjectCodeArray[$subjectIdNew];
               }
            }
        }

     
        $subMaxMarksArray = $studentReportsManager->getTotalMaxMarks($classId, $subjectId);
        foreach($subMaxMarksArray as $record) {
            $maxMarksArray[$record['subjectId']]['T'] = round($record['maxMarks'],1);
        }
        $internalArray = $studentReportsManager->getTotalStudentMarks($studentIdList, $classId, $subjectId);
        foreach($internalArray as $record) {
            $studentId = $record['studentId'];
            $subjectIdNew = $record['subjectId'];
            $dataArray[$studentId][$subjectIdNew]['T'] = round($record['marksScored'],1);
                      $percent = (($dataArray[$studentId][$subjectIdNew]['T'] * 100) / $maxMarksArray[$subjectIdNew]['T']);
            if ($percent < 40) {
                $studentStatusArray[$studentId][$subjectIdNew] = 'RT '. $subjectCodeArray[$subjectIdNew];

            }
        }
       

        $finalMaxMarksArray = $studentReportsManager->getFinalMaxMarks($classId, $subjectId);


        $studentFinalMarksArray = array();
        $finalMarksScoredArray = $studentReportsManager->getFinalMarksScored($studentIdList, $classId, $subjectId);
        $i = 0;
        foreach($finalMarksScoredArray as $record) {
            $studentId = $record['studentId'];
            $subjectIdNew = $record['subjectId'];
            $studentFinalMarksArray[$studentId] = round($record['marksScored'],1);
            if (isset($studentStatusArray[$studentId])) {
                $status = implode('<br>',$studentStatusArray[$studentId]);
                $finalMarksScoredArray[$i]['marksScored'] = $status;//$studentStatusArray[$studentId][$subjectIdNew];
                $studentFinalMarksArray[$studentId] = $status;
            }
            $i++;
        }
 

    $mainStudentArray = array();
    $i = 0;
    foreach($studentArray as $record) {
        $studentId = $record['studentId'];
        foreach($subjectArray as $subjectRecord) {
            $subjectIdNew = $subjectRecord['subjectId'];
            $studentArray[$i][$subjectIdNew]['I'] = $dataArray[$studentId][$subjectIdNew]['I'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['I'];
            $studentArray[$i][$subjectIdNew]['A'] = $dataArray[$studentId][$subjectIdNew]['A']== '' ? '-' : $dataArray[$studentId][$subjectIdNew]['A'];
            $studentArray[$i][$subjectIdNew]['E'] = $dataArray[$studentId][$subjectIdNew]['E'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['E'];
            $studentArray[$i][$subjectIdNew]['T'] = $dataArray[$studentId][$subjectIdNew]['T'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['T'];
            $studentArray[$i][$subjectIdNew]['G'] = $dataArray[$studentId][$subjectIdNew]['G'] == '' ? 'I' : $dataArray[$studentId][$subjectIdNew]['G'];
            $studentArray[$i][$subjectIdNew]['failGrade'] = $dataArray[$studentId][$subjectIdNew]['failGrade'] == '' ? 'I' : $dataArray[$studentId][$subjectIdNew]['G'];
            $studentArray[$i][$subjectIdNew]['P'] = $dataArray[$studentId][$subjectIdNew]['P'] == '' ? '' : $dataArray[$studentId][$subjectIdNew]['P'];
            $studentArray[$i][$subjectIdNew]['C'] = $dataArray[$studentId][$subjectIdNew]['C'] == '' ? '' : $dataArray[$studentId][$subjectIdNew]['C'];
            $studentArray[$i]['totalMarks'] = $studentFinalMarksArray[$studentId];
        }
        $studentArray[$i]['gpa'] = $gpaArray[$studentId];   
        $studentArray[$i]['cgpa'] = $cgpaArray[$studentId];   
        $i++;
    }

    //$studentMarksArray = $studentReportsManager->getStudentMarks($studentIdList, $classId, $subjectId);
    foreach($maxMarksArray as $subjectId => $marksTypeArray) {
        foreach($marksTypeArray as $examType => $maxMarksVal) {
            $maxMarksArray[$subjectId][$examType] = "$maxMarksVal";
        }
    }
    
    $cntWidth = (count($subjectArray)+6);
    
    $tableWidth = intval(($cntWidth/85)*85);  
    
  
   $condition=" AND c.studyPeriodId = '".$studyPeriodId."'";
   $studentCGPARecordArray = $studentReportsManager->getStudentClasswiseCGPA($condition);
    
   
    
        $tableFooter="
                     <table border='0' cellpadding='1px' cellspacing='1px' width='95%' align='center'>
                        <tr>
               <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Name
                </td>
                    </tr>
                        <tr>
               <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Designation
                </td>
                    </tr>
                        <tr>
               <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportManager->tableHeadArray)." ".$class1.">
                     Signature
                </td>
                    </tr>
                    
                    </table>";
    
    $align='left';
   $tableExFooter="<table border='0' cellspacing='1px' cellpadding='1px' width='95%'  align='center'>
                     <tr >
 <td valign='' align='$align' width='25%' ".$class1.">
                           Name 
                    </td>     
<td valign='' align='$align'  width='25%'  ".$class1.">
Name
</td>    

                    </td>     
<td valign='' align='$align'  width='20%'  ".$class1.">
Name
</td>    

                    </td>     
<td valign='' align='$align'  width='30%' ".$class1.">
Name
</td>    
</tr>

  <tr>
 <td valign='' align='$align'  width='25%'  ".$class1.">
                           Designation
                    </td>     
<td valign='' align='$align'  width='20%'  ".$class1.">
  Designation
</td>    

                    </td>     
<td valign='' align='$align'  width='30%'  ".$class1.">
  Designation
</td>    

                    </td>     
<td valign='' align='$align'  width='25%'  ".$class1.">
  Designation
</td>    
</tr>

  <tr>
 <td valign='' align='$align'  width='25%'  ".$class1.">
                           Email Id And Phone
                    </td>     
<td valign='' align='$align'   width='20%'  ".$class1.">
Email Id And Phone
</td>    

                    </td>     
<td valign='' align='$align'  width='30%'  ".$class1.">
Email Id And Phone
</td>    

                    </td>     
<td valign='' align='$align' nowrap width='25%'  ".$class1.">
Email Id And Phone
</td>    
</tr>

    <tr>
               <td valign='' align='$align'  width='25%'  ".$class1.">
                     Signature
                </td>
                <td valign='' align='$align'  width='20%'  ".$class1.">
                     Signature
                </td>
                <td valign='' align='$align'  width='30%'  ".$class1.">
                     Signature
                </td>
                <td valign='' align='$align'  width='25%'  ".$class1.">
                     Signature
                </td>
                    </tr>

                  </table>";
    
    $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);

    $subCode = 'All';
    if ($subjectId != 'all') {
        $subCodeArray = $studentReportsManager->getSingleField('subject', 
        "subjectId, subjectName, subjectCode, CONCAT(subjectName, ' (',subjectCode,')') AS subjectNames", 
        "WHERE subjectId  IN (".$REQUEST_DATA['subjectId'].")");
        foreach($subCodeArray as $record) {
           if(CLIENT_INSTITUTES >=10) { 
               if($classId=='586') {
                 if($record['subjectId']==1540) {
                   $record['subjectCode'] = 'AML4209';
                 }   
                 else if($record['subjectId']==1543) {
                   $record['subjectCode'] = 'ECP1209';
                 }   
                 else if($record['subjectId']==1538) {
                   $record['subjectCode'] = 'ECL4209';
                 }   
               }   
           }   
           $subNameArray[] = $record['subjectName']." (".$record['subjectCode'].")";
           $subCodesArray[] = $record['subjectCode'];
        }
    }
    $subCode = implode(',', $subCodesArray);
    $subName = implode(',', $subNameArray);   
   
    $subName = str_replace(",",', ',$subName); 
   
    $heading = "$className2<br> Subject: $subName";
   
    
    $colCounter = 0;
    
        if($internal == 'on') {
          $colCounter++;
        }
        if($attendance == 'on') {
          $colCounter++;
        }
        if ($external == 'on') {
            $colCounter++;
        }
        if ($total == 'on') {
            $colCounter++;
        }
        if ($gradeList == 'on') {
            $colCounter++;
        }
        
         
                            //class='rowheading'
        $tableData ="<table border='1' cellpadding='0px' cellspacing='2px' width='800' class='reportTableBorder'  align='center'> 
                        <tr >
                            <td $class1 rowspan='2' align='left'>#</td>
                            <td $class1 rowspan='2' align='left'>Roll No.</td>
                            <td $class1 rowspan='2' align='left'>Name</td>";
        for($i=0;$i < count($subjectArray); $i++) {
           $subjectId = $subjectArray[$i]['subjectId'];
           $subjectCode = $subjectArray[$i]['subjectCode'];
           if(CLIENT_INSTITUTES >=10) { 
               if($classId=='586') {
                 if($subjectId==1540) {
                   $subjectCode = 'AML4209';
                 }   
                 else if($subjectId==1543) {
                   $subjectCode = 'ECP1209';
                 }   
                 else if($subjectId==1538) {
                   $subjectCode = 'ECL4209';
                 }   
               }   
           }  
           $tableData .="<td $class1 align='center' colspan='$colCounter'>$subjectCode</td>";          
        } 
        
         if ($isSGPA == 'on') {
          $tableData .="<td $class1 align='center' rowspan='2'>SGPA</td>"; 
        }
            
        if ($isCGPA == 'on') {
          $tableData .="<td $class1 align='center' rowspan='2'>CGPA</td>"; 
        }
        $tableData .="</tr> <tr>";   
        for($i=0;$i < count($subjectArray); $i++) {
            $ttSubjectIdNew = $subjectArray[$i]['subjectId'];  
            
            $ttMax1 = $maxMarksArray[$ttSubjectIdNew]['E'];
            $ttMax2 = $maxMarksArray[$ttSubjectIdNew]['I'];
            //$ttMax3 = $maxMarksArray[$ttSubjectIdNew]['A'];
            $ttSum = $ttMax1+$ttMax2;   
            if($ttMax1=='') {
              $ttMax1=NOT_APPLICABLE_STRING;  
            }
            if($ttMax2=='') {
              $ttMax2=NOT_APPLICABLE_STRING;  
            }
            
            if($internal == 'on') {
             $tableData .="<td $class1 align='center'>Internal Marks<br>($ttMax2)</td>"; 
            }
            if($attendance == 'on') {
              $tableData .="<td $class1 align='center'>Attendance</td>";  
            }
            if ($external == 'on') {
              $tableData .="<td $class1 align='center'>External Marks<br>($ttMax1)</td>"; 
            }
            if ($total == 'on') {
              $tableData .="<td $class1 align='center'>Total Marks<br>($ttSum)</td>"; 
            }
            if ($gradeList == 'on') {
              $tableData .="<td $class1 align='center'>Grade</td>"; 
            }
        }
        $tableData .="</tr>";
        
        $tableHead = $tableData;
        
        $pageCounter = 0;    
        $totalPages  = 1; 
        
        $printRecord = trim($REQUEST_DATA['printRecord']);   
        if($printRecord=='') {
          $printRecord = 12;  
        }
        $recordsPerPage = $printRecord;
        
        $totalRecords = count($studentArray);
        $totalPages = floor($totalRecords / $recordsPerPage);
        $balanceRecords = round($totalRecords % $recordsPerPage);
        if($balanceRecords > 0) {
          $totalPages++;
        }
        $pageCounter = 0;
        $recordCounter = 0;
        
        $chkList='';
        for($i=0;$i < count($studentArray); $i++) {  
           // $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"'; 
            $bg='';
            $rollNo = $studentArray[$i]['rollNo'];
            $studentName=$studentArray[$i]['studentName'];
            $studentId = $studentArray[$i]['studentId']; 
            $srNo = ($records+$i+1);
            
      
            $chkList='';
            $tableData .="<tr $bg>
                            <td $class2 > $srNo </td>
                            <td $class2 > $rollNo</td>
                            <td $class2 > $studentName</td>";   
           
            for($j=0;$j < count($subjectArray); $j++) {
                
               $totalMks ='';    
               
               $subjectId = $subjectArray[$j]['subjectId'];   
                
               // Internal Total Marks
               $mm = $dataArray[$studentId][$subjectId]['IR']; 
                
               if($mm=='') {
                 if($internal == 'on') {      
                   $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                 }
               }
               else {
                 if($internal == 'on') {        
                   $tableData .="<td $class2 align='center'>$mm</td>";   
                 }
                 $totalMks = $dataArray[$studentId][$subjectId]['I'];
               }
               if($mm=='') {
                $mm='0';  
               }
              
              
                // Attendance 
               $mm = $dataArray[$studentId][$subjectId]['A'];
               if($mm=='') {
                  if($attendance == 'on') {         
                    $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                  }
               }
               else {
                  if($attendance == 'on') { 
                    $tableData .="<td $class2 align='center'>$mm</td>";  
                  }
               }
               
              
                // external Marks
                $ext='';
                $mm = $dataArray[$studentId][$subjectId]['ER'];
                if($mm=='A' || $mm=='D') {
                  $ext='D';  
                  $dataArray[$studentId][$subjectId]['G']=$gradeLabelNotExist; 
                }
                if($mm=='') {
                   if($external == 'on') {   
                     $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                   }
                }
                else {
                   if($external == 'on') {   
                     $tableData .="<td $class2 align='center'>$mm</td>";  
                   }
                   $totalMks = $totalMks + $dataArray[$studentId][$subjectId]['E'];
                }
                if($mm=='') {
                  $mm='0';  
                }  
                    
                // Total Marks and Grading     
                if($totalMks=='') {
                  if ($total == 'on') {   
                    $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                  }
                }
                else {
                   if ($total == 'on') {   
                     $tableData .="<td $class2 align='center'>$totalMks</td>";   
                   }
                }
                
                // Grading                 
                $mm = $dataArray[$studentId][$subjectId]['G'];
                if($mm=='') {
                  $mm='N/A';  
                }
                if($mm=='') {
                  if ($gradeList == 'on') {  
                    $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";      
                  }
                }
                else {
                  if ($gradeList == 'on') {  
                    $tableData .="<td $class2 align='center'>$mm</td>";   
                  }
                }
                if($mm!='N/A') {
                  $finalGraphArray[$subjectId][$mm]['studentCount']++; 
                }
            }
            
            if ($isSGPA == 'on') {   
               $sgpa = $gpaArray[$studentId];  
               $tableData .="<td $class2 align='center'>".$sgpa."</td>";   
            }
              
            if ($isCGPA == 'on') {   
              $cgpa = $cgpaArray[$studentId]; 
              $tableData .="<td $class2 align='center'>".$cgpa."</td>";   
            }
            $tableData .="</tr>";
            
            if(($i+1)%$recordsPerPage==0) {    
              $tableData .= "</table><br><br>";   
              echo reportGenerate($tableData,$heading,$tableFooter);
              $tableData = $tableHead;  
              $chkList='1';
            }
        }
        
        if($chkList == '') {
          $tableData .= "</table><br><br>";   
          echo reportGenerate($tableData,$heading,$tableFooter);    
        }
        
        
echo "<br class='page'>".getChartGenerate($finalGraphArray,$totalStudents,$subjectArray,$gradeGraphArray);
  

function getChartGenerate($charArray,$totalStudents,$subjectArray,$gradeGraphArray) {
    
     global $reportManager;
     
    
    
     $graphFooter='<tr>';
     $graphFooter1='<tr>';
     for($i=0;$i<count($subjectArray);$i++) {
        $subjectCode = $subjectArray[$i]['subjectCode'];  
        $subjectId = $subjectArray[$i]['subjectId'];  
        if($i==0) {
         $graphFooter .='<th rowspan="2"></th>';  
        }
        for($j=0; $j<count($gradeGraphArray);$j++) {
          $gradeLabel = $gradeGraphArray[$j]['gradeLabel'];  
          $graphFooter .='<th '.$reportManager->getReportDataStyle().' >'.$gradeLabel.'</th>';
        }
        $graphFooter1 .='<th '.$reportManager->getReportDataStyle().' colspan="'.count($gradeGraphArray).'">'.$subjectCode.'</th>';
     }
     $graphFooter .='</tr>';
     $graphFooter1 .='</tr>';
     
     $chartResult ='';
     $chartResult1 ='';
     $chartResult2 ='';
     
     $finalCnt='1';
     $cnt = '1';
     if($totalStudents > 10) {
       if(intval($totalStudents/10)== $totalStudents/10) {
          $cnt = intval($totalStudents/10,0);   
       }
       else {
          $cnt = intval($totalStudents/10,0)+1;     
       }
     }
     $finalCnt = $cnt;   
         
     $ttChartResult2 = "";      
     
     $maxStudent=0;
     $chartResult1 = "<tr>";
         for($i=0;$i<count($subjectArray);$i++) {  
           $subjectId = $subjectArray[$i]['subjectId'];
           for($gg=0; $gg<count($gradeGraphArray);$gg++) {
             $gradeLabel = $gradeGraphArray[$gg]['gradeLabel'];  
           
             $color = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
             $percentage = $charArray[$subjectId][$gradeLabel]['studentCount']; 
             $subjectName = $charArray[$subjectId][$gradeLabel]['subjectName'].'  ('. $charArray[$subjectId][$gradeLabel]['subjectCode'].') ';
             if($percentage=='0') {
               $percentage1 = '0';  
               $percentage  ='&nbsp;0&nbsp;';
             } 
             else {
                $msg = "alt='$gradeLabel ($subjectName) ($percentage)' title='$gradeLabel ($subjectName) ($percentage)'";
                $per = $percentage;
                  
                $height = 2.5*($finalCnt*10);
                $perpx = intval($per*2.5); 
                
                $style = "style='width:10px;height:$perpx;'";   
                  
                $fileName = HTTP_PATH."/Storage/Images/bar.gif?g=".rand(500,0);
                $img = "<img src='".$fileName."' width='20px' height='$perpx' $msg>";
                $percentage1 = "<div>$percentage</div><div>$img</div>"; 
                $percentage2 = "$percentage 
                                  <table border='0' $style  class='reportTableBorder' cellpadding='0' cellspacing='0' valign='bottom'>
                                    <tr>
                                       <td  align='center' valign='bottom' $style >&nbsp;</td>
                                    </tr>
                                 </table>";
             }    
             $setWidth=" width='".intval($cnt/90*100)."' ";
             
             if($percentage>$maxStudent) {
               $maxStudent = $percentage;  
             }
             
             $ttChartResult2 .= "<td  ".$reportManager->getReportDataStyle()." $setWidth valign='bottom' height='100%' align='center'>$percentage1</td>";
           }
         }
         
         
         
         $ttChartResult1 .= "<td  ".$reportManager->getReportDataStyle()." valign='bottom' height='100%' align='center'>  
                         <table border='0' cellpadding='0' cellspacing='0'  width='100%' class='reportTableBorder'>";    
         
         if($maxStudent > 10) {
           if(intval($maxStudent/10)== $maxStudent/10) {
              $maxcnt = intval($maxStudent/10,0);   
           }
           else {
              $maxcnt = intval($maxStudent/10,0)+1;     
           }
         }
         $ss = $maxcnt*10;
         for($kk=$ss;$kk>=0;$kk-=10) {
             $cc=$kk;
             if($kk==0) {
               $cc=1;  
             }
             $ttChartResult1 .="<tr>
                               <td valign='bottom' height='25px' align='center' ".$reportManager->getReportDataStyle()." width='5px' >".$cc."</td>";
             if($i==$ss) {                    
              $ttChartResult1 .= $chartResult1;
             }
             $ttChartResult1 .= "</tr>";
        } 
        $ttChartResult1 .= "</table></td>";  
         
         
        $chartResult1 = $ttChartResult1.$ttChartResult2;     
         
        $chartResult1 .= "</tr>";
     
     $cntColSpan = '';
     if(count($charArray)>0) {
       $cntColSpan = "colspan='".(count($charArray)+1)."'";
     }
     
     $chartResult  =  "<br>
                       <table border='0' cellpadding='0' cellspacing='0'  width='650px' class='reportTableBorder'  align='center'>
                        <tr>
                           <td ".$reportManager->getReportDataStyle()." colspan=2 align=center style='padding-left:90px'>
                              <b>Student Award List Graph</b>
                           </td>
                        </tr>
                        <tr>
                           <td width='2%'  ".$reportManager->getReportDataStyle()." valign='middle' align='left' style='padding-right:10px'>
                              <b>Student Count</b>
                           </td> 
                           <td width='98%'>
                            <table border='1' cellpadding='0' cellspacing='0'  width='100%' class='reportTableBorder'>
                               $chartResult1
                               $graphFooter
                               $graphFooter1
                            </table>
                          </tr>
                          <tr>
                           <td ".$reportManager->getReportDataStyle()." colspan=2 align=center style='padding-left:90px'>
                              <b>Grade</b>
                           </td>
                        </tr>
                         </table>";
           
    return $chartResult;   
}            
   
    // Report generate
    function reportGenerate($value,$heading,$footer='') {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Award List');
        $reportManager->setReportInformation("$heading");  
        global $pageCounter;    
        global $totalPages;
        global $tableExFooter;
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
            <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
            </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
            <?php 
                $pageCounter++; 
                echo $value; 
                if($pageCounter==$totalPages){ 
                  echo "<br><br>";
                  echo $tableExFooter; 
                }
                else {
                  echo $footer; 
                }
            ?>
            </td>
            </tr> 
            </table>       
            <br><br>
            <table border='0' cellspacing='0' cellpadding='0' width="600px" align="right">
                <tr>
                    <td valign='' align="right" <?php echo $reportManager->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
    }
?> 


 
