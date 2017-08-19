<?php 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentGazetteReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
                                                                  
    
    require_once(MODEL_PATH . "/StudentCGPARepotManager.inc.php");
    $studentCGPARepotManager = StudentCGPARepotManager::getInstance();

    $pageRecordLimit = '10';
    $rollId = "BUPIN";
    
    // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    
    $rollId = "BUPIN";
    
    $labelId = trim($REQUEST_DATA['timeTable']);
    $classId = trim($REQUEST_DATA['degree']);
    $subjectId = trim($REQUEST_DATA['subjectId']);

    $total = $REQUEST_DATA['total'];
    $isSGPA = $REQUEST_DATA['isSGPA'];     
    $isCGPA = $REQUEST_DATA['isCGPA'];     
    
    if($labelId=='') {
      $labelId='0';  
    }
    if($classId=='') {
      $classId='0';  
    }
    if($subjectId=='') {
      $subjectId='0';  
    }
    
    
    global $sessionHandler;
        
        
    $gradeIDeclareResult = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_ALLOW'); 
    if($gradeIDeclareResult=='') {
      $gradeIDeclareResult='0';  
    }
        
    $gradeIAllow = $sessionHandler->getSessionVariable('GRADE_I_DECLARE_RESULT');
    $gradeFAllow = $sessionHandler->getSessionVariable('GRADE_F_FORMAT_ALLOW'); 
    
    if($gradeIAllow=='') {
       $gradeIAllow = '1';
    }
    
    if($gradeFAllow=='') {
      $gradeFAllow = '1';
    }
    
    
    
    $tableName = "class c, degree d, branch b, study_period sp, university u";
    $fieldName = " d.degreeCode, b.branchName, sp.periodName, u.universityName, c.studyPeriodId ";
    $where = " WHERE c.degreeId = d.degreeId AND
              c.branchId = b.branchId AND
              c.studyPeriodId = sp.studyPeriodId AND
              u.universityId = c.universityId AND
              c.classId = '$classId' ";
    $searchArray = $studentReportsManager->getSingleField($tableName, $fieldName, $where);                 
    
    
    $tableName = "time_table_labels";
    $fieldName = " labelName    ";
    $where = " WHERE timeTableLabelId = '$labelId' ";
    $timeTableArray = $studentReportsManager->getSingleField($tableName, $fieldName, $where);   
   
    
   $condition = " c.classId = '$classId' ";
   $resourceRecordArrayGPA = $studentCGPARepotManager->getStudentClasswiseGPA($condition);
   $gpaArray = array();
   for($i=0;$i<count($resourceRecordArrayGPA);$i++) {
     $gpa = UtilityManager::decimalRoundUp($resourceRecordArrayGPA[$i]['gpa']);
     $ggStudentId = $resourceRecordArrayGPA[$i]['studentId'];    
     $gpaArray[$ggStudentId]=$gpa;
   }

   $condition = " c.classId = '$classId' ";
   $resourceRecordArrayCGPA = $studentCGPARepotManager->getStudentClasswiseCGPA($condition);
   $cgpaArray = array();
   for($i=0;$i<count($resourceRecordArrayCGPA);$i++) {
     $cgpa = UtilityManager::decimalRoundUp($resourceRecordArrayCGPA[$i]['CGPA']);
     $ggStudentId = $resourceRecordArrayCGPA[$i]['studentId'];    
     $cgpaArray[$ggStudentId]=$cgpa;
   }
    
    
  //$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId IN ($subjectId) ORDER BY subjectCode");
    //$subjectArray = $studentReportsManager->getStudentAllSubject($classId," AND b.subjectId IN ($subjectId)");
  $subjectArray = $studentReportsManager->getAlternateSubject($subjectId,$classId);
    $subjectCodeArray = array();
    foreach($subjectArray as $record) {
      $subjectCodeArray[$record['subjectId']] = $record['subjectCode'];
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

  
    $studentArray = $studentReportsManager->getClassStudents($classId, $sortBy);
    $studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
    $studentNameList = UtilityManager::makeCSList($studentArray, 'studentName');
    if(empty($studentIdList)) {
      $studentIdList = 0;
    }
    
    if(empty($studentNameList)) {
      $studentNameList = 0;
    }

    //$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", "where subjectId in ($subjectId) order by subjectCode");

    $maxMarksArray = array();
    $gradeLabelNotExist = 'I';

        $gradeSetId='0';
   
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
        
      /*  $internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
        foreach($internalArray as $record) {
            $dataArray[$record['studentId']][$record['subjectId']]['A'] = '<u>'.$record['lectureAttended'] .'</u><br>' . $record['lectureDelivered'];
        }   */
        
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
              $dataArray[$studentId][$subjectIdNew]['failGrade'] = $record['failGrade'];  
              $dataArray[$studentId][$subjectIdNew]['P'] = $record['gradePoints'];
              $dataArray[$studentId][$subjectIdNew]['C'] = $record['credits'];
             
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
            $studentArray[$i][$subjectIdNew]['P'] = $dataArray[$studentId][$subjectIdNew]['P'] == '' ? '' : $dataArray[$studentId][$subjectIdNew]['P'];
            $studentArray[$i][$subjectIdNew]['C'] = $dataArray[$studentId][$subjectIdNew]['C'] == '' ? '' : $dataArray[$studentId][$subjectIdNew]['C'];
            $studentArray[$i][$subjectIdNew]['failGrade'] = $dataArray[$studentId][$subjectIdNew]['failGrade'] == '' ? 'I' : $dataArray[$studentId][$subjectIdNew]['G'];               
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
    
    
    $univeristyName = $searchArray[0]['universityName'];
    $studyPeriodId = $searchArray[0]['studyPeriodId'];  
    $heading = $searchArray[0]['degreeCode'].' ('.$searchArray[0]['branchName'].') '.$searchArray[0]['periodName'];
    $heading .= " Examination: ".$timeTableArray[0]['labelName'];

    
    $cntWidth = (count($subjectArray)+6);
    
    $tableWidth = intval(($cntWidth/85)*85);  
    
    $tableData ='';     
    for($i=0;$i < count($studentArray); $i++) {  
        $srNo = ($i+1);
        $rollNo = $studentArray[$i]['rollNo'];
        $studentName=$studentArray[$i]['studentName'];
        $studentId = $studentArray[$i]['studentId']; 
        $rollNo = $studentArray[$i]['rollNo'];  
        $srNo = ($records+$i+1);
        
        $resultHoldStatus ='';
        
        $ccPoint = 0; 
        $ccCredit = 0; 
        $ccGradePoint =0;
        $repCredit=0;
        
        $tableData .= parseCSVComments($srNo."\012".$rollNo."\012".$studentName);
        
        $subjectCntCheck='0';
        for($j=0;$j < count($subjectArray); $j++) {
              $subjectId = $subjectArray[$j]['subjectId'];   
              $subjectCode = $subjectArray[$j]['subjectCode'];  
			  /*
			   $replaceSubjectsCode = '';
                              if($subjectCode =='CSL4209') {
                                $replaceSubjectsCode = 'AML4209'; 
                              }
                              else if($subjectCode =='CSL4205') {
                                $replaceSubjectsCode = 'ECL4209'; 
                              }
                              else if($subjectCode =='CSP1205') {
                                $replaceSubjectsCode = 'ECP1209'; 
                              }
                              if($rollNo == 'CUN110103010' || $rollNo == 'CUN110103017' || $rollNo == 'CUN110103019' || $rollNo == 'CUN110104016') {
                                 if(trim($replaceSubjectsCode)!='') {
                                   $subjectCode = $replaceSubjectsCode;  
                                 }   
			  
			  }
               
              */
              $notIncludeSubject='';
              $mm = $dataArray[$studentId][$subjectId]['IR']; 
              if(trim($mm)=='') {
                $mm='0';  
                $notIncludeSubject='1'; 
              }  
             /*
              $mm = $dataArray[$studentId][$subjectId]['A'];
              if($mm=='') {
                $mm='A';  
              }
              
              if($mm=='') {
                $mm='0';  
              }
              */
              if($mm=='A' || $mm=='D') {
                $ext='D';  
                //$dataArray[$studentId][$subjectId]['G']=$gradeLabelNotExist; 
              }
            
              $mm = $dataArray[$studentId][$subjectId]['E'];
              if($mm=='A' || $ext!='') {
                $mm=($dataArray[$studentId][$subjectId]['I'] + 0);    
              }
              if($mm=='') {
                $mm='0';  
              }
              
              if($notIncludeSubject=='1' && $ext=='') {
                 if($mm!='A' || $mm!='D') {
                   $notIncludeSubject='';  
                 }  
              }
              
              if($notIncludeSubject=='') { 
                 $grade = $dataArray[$studentId][$subjectId]['G'];
                 $point = $dataArray[$studentId][$subjectId]['P'];  
                 $credits = $dataArray[$studentId][$subjectId]['C']; 
                 $failGrade = $dataArray[$studentId][$subjectId]['failGrade']; 
                  
                  // Reapper check
                 if($grade=='I' || strtoupper($failGrade) == 'Y' ) {
                   $repCredit += $credits;   
                 }
                  
                 if($grade=='I' || strtoupper($failGrade) =='Y' ) {
                   //$point = '0';   
                 }
                 
                  if($gradeIDeclareResult=='0' && ($grade=='' || $grade=='I') ) {
                    }
                    else {
                      $gradePoint = $credits*$point; 
                      
                      $ccCredit += $credits;
                      $ccPoint += $point;
                      $ccGradePoint += $gradePoint;
                    }
                 
              
                 $gradePoint = $credits*$point; 
                 $ccCredit += $credits;
                 $ccPoint += $point;
                 $ccGradePoint += $gradePoint;
              
                 if($grade!='') {
                   $ssResult = parseCSVComments($subjectCode."\012".$credits."\012".$grade."\012".$gradePoint);
                 }
                 else {
                    $ssResult =  "";  
                 }
                 if($gradeIAllow=='0') {
                     if($grade=='' || $grade=='I') {
                       $ssResult = parseCSVComments($subjectCode."\012".$sessionHandler->getSessionVariable('GRADE_I_FORMAT_ABBR'));
                       $resultHoldStatus='1';
                     }
                  }
                  
                  $subjectCntCheck++;
                  $tableData .=",".parseCSVComments(str_replace('"','',$ssResult));
              }
              else {
                 $ssResult=''; 
              }
        }
        
        if($subjectCntCheck != count($subjectArray)) {
           for($j=$subjectCntCheck;$j<count($subjectArray);$j++) {
             $tableData .=","; 
           }
        }
        
        
        
        if($resultHoldStatus!='') {
           
           $gradeFormatName = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_NAME');
           $gradeFormatAbbr = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_ABBR');
           $ssResult = $gradeFormatName." (".$gradeFormatAbbr.")";
           
           $tableData .=",".parseCSVComments($ssResult);
           
           $ssPrevious = $ssResult;
           
           $tableData .=",".parseCSVComments($ssPrevious);     
           $tableData .=",".parseCSVComments($ssResult);     
           $tableData .=",".parseCSVComments($ssResult);     
           $tableData .=",".parseCSVComments($ssResult);     
           $tableData .=",".parseCSVComments($ssResult);     
        }
        else {
             // Findout GPA
            $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId = '".$studyPeriodId."'";
            $resourceRecordArray = $gradeCardRepotManager->getStudentClasswiseGPA($condition);
          
            $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId <= '".$studyPeriodId."'";
            $resourceRecordArray1 = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
            $cgpa = UtilityManager::decimalRoundUp($resourceRecordArray1[0]['CGPA']);
           
            $gpa = UtilityManager::decimalRoundUp($resourceRecordArray[0]['gpa']);
            //$ccCredit= $resourceRecordArray[0]['credits'];
            if($ccCredit=='') {
              $ccCredit='0';  
            }
            
            //Current
            $ccIt = $ccCredit-$repCredit;    
            $tableData .=",".parseCSVComments("\012".$ccCredit."\012".$ccIt."\012".$ccGradePoint);
           
           
            //Previous    
            $prevCredit='0';                               
            $prevGradePoint = 0;
            $condition=" AND a.studentId = '".$studentId."' AND c.studyPeriodId < '".$studyPeriodId."'"; 
            $resourceRecordArray1 = $studentReportsManager->getStudentPreviousGrade($condition);
            for($kk=0;$kk<count($resourceRecordArray1);$kk++) {
              if($resourceRecordArray1[$kk]['gradePoints']>0) { 
                $prevCredit += $resourceRecordArray1[$kk]['credits'];
                $prevGradePoint += ($resourceRecordArray1[$kk]['credits']*$resourceRecordArray1[$kk]['gradePoints']);
              }
            }
            
            
            $prevPoint = 0;
            $condition=" AND a.studentId = '".$studentId."' AND c.studyPeriodId < '".$studyPeriodId."' AND gr.failGrade <> 'Y' "; 
            $recordArray11 = $studentReportsManager->getStudentPreviousGrade($condition);
            for($kk=0;$kk<count($recordArray11);$kk++) {
              if($recordArray11[$kk]['gradePoints']>0) { 
                $prevPoint += $recordArray11[$kk]['credits'];
                
              }
            }
            $tableData .=",".parseCSVComments("\012".$prevCredit."\012".$prevPoint."\012".$prevGradePoint); 
            
            
            
           //Reappear      
           $lessFind='0';
           for($xx=0;$xx<count($subjectArray); $xx++) {    
              $ttSubjectId = $subjectArray[$xx]['subjectId'];  
              for($yy=0;$yy<count($prevSubjectArray); $yy++) {
                if($prevSubjectArray[$yy]['subjectId']==$ttSubjectId) {
                  $lessFind .=",".$ttSubjectId; 
                  break;
                }  
              }
           }
           
           $lessCredit='0';
           $lessCreditPoint='0';
           $condition=" AND a.studentId = '".$studentId."' AND c.studyPeriodId = '".$studyPeriodId."' AND a.subjectId IN ($lessFind) "; 
           $lessRecordArray = $studentReportsManager->getStudentPreviousGrade($condition);
           for($kk=0;$kk<count($lessRecordArray);$kk++) {
              $lessCredit += $lessRecordArray[$kk]['credits'];
              $lessCreditPoint += $lessRecordArray[$kk]['gradePoints'];
           } 
           $tableData .=",".parseCSVComments("\012".$lessCredit."\012"."\012".$lessCreditPoint);
            
            
            
            //Final
            $totCredit = ($ccCredit + $prevCredit)-$lessCredit;
            $totPoint = $ccIt + $prevPoint;
            $totGradePoint = ($ccGradePoint + $prevGradePoint)-$lessCreditPoint;
            
            $tableData .=",".parseCSVComments("\012".$totCredit."\012".$totPoint."\012".$totGradePoint); 
                          
            $mm = $gpaArray[$studentId];
            if($mm=='') {
              $mm=NOT_APPLICABLE_STRING;  
            }
            $tableData .=",".parseCSVComments($gpa);

            $mm = $cgpaArray[$studentId];
            if($mm=='') {
              $mm=NOT_APPLICABLE_STRING;  
            }
            $tableData .=",".parseCSVComments($cgpa);
        }
        $tableData .=","; 
        $tableData .="\n";
    }
    
    $find='0';
    
    $csvData = tableHeader($subjectArray,$rollId);
    $csvData .= $tableData;  
   
    if($gradeSetId=='') {
      $gradeSetId='0';  
    }
    
    $csvData .="\n\n";
    $gradeListArray = $studentReportsManager->getGradeList($gradeSetId); 
    $csvData .="Grade,Qualitative Meaning,Grade Points"; 
    $csvData .="\n";
    for($i=0;$i<count($gradeListArray);$i++) {
      $gradeLabel = $gradeListArray[$i]['gradeLabel'];
      $gardeMeaining = $gradeListArray[$i]['gradeStatus'];
      $gradePoints = $gradeListArray[$i]['gradePoints'];  
      $csvData .= parseCSVComments($gradeLabel).",".parseCSVComments($gardeMeaining).",".parseCSVComments($gradePoints); 
      $csvData .="\n";
    }
    
    UtilityManager::makeCSV($csvData,'GazetteReport.csv');   
    
die;

 
    function tableHeader($subjectArray,$rollId) {
        
        global $tableHead;

        $tableHead = parseCSVComments("SR.NO."."\012"."$rollId"."\012"."NAME");
        for($i=0;$i<count($subjectArray);$i++) {
           $id = ($i+1);  
           $tableHead .= ",".parseCSVComments("CS-$id"."\012"."CRD-$id"."\012"."GD-$id"."\012"."PNT-$id");   
        }                                       
        $tableHead .= ",".parseCSVComments("CURRENT"."\012"."CRD-RG"."\012"."CRD-ER"."\012"."GPNTS");
        $tableHead .= ",".parseCSVComments("PREVIOUS"."\012"."CRD-RG"."\012"."CRD-ER"."\012"."GPNTS");
        $tableHead .= ",".parseCSVComments("LESS"."\012"."CRD-RG"."\012"."\012"."GPNTS");
        $tableHead .= ",".parseCSVComments("NET"."\012"."CRD-RG"."\012"."CRD-ER"."\012"."GPNTS");
        $tableHead .= ",".parseCSVComments("SGPA");
        $tableHead .= ",".parseCSVComments("CGPA");
        $tableHead .= ",".parseCSVComments("GRADE CARD NO./"."\012"."REMARKS");
        $tableHead .= "\n";
       
        return $tableHead; 
    }


    
