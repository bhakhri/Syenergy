<?php 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
       
    define('MODULE','StudentGazetteReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
                                                                  
    
    require_once(MODEL_PATH . "/StudentCGPARepotManager.inc.php");
    $studentCGPARepotManager = StudentCGPARepotManager::getInstance();

    
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
  
    if($gradeIAllow=='') {
       $gradeIAllow = '1';
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
   
   
   
  // $subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId IN ($subjectId) ORDER BY subjectCode");
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
    
    $condition=" AND c.studyPeriodId = '".$studyPeriodId."'";
    $studentCGPARecordArray = $studentReportsManager->getStudentClasswiseCGPA($condition);     
    
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
        $tableData ="<tr>
                        <td valign='top'".$reportManager->getReportDataStyle()." width='15%' align='left'>$srNo<br>$rollNo<br>$studentName</td>";
                        
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
                    $ssResult = "$subjectCode<br>$credits<br>$grade<br>$gradePoint" ;
                  }
                  else {
                    $ssResult = "";  
                  }
                  
                  if($gradeIAllow=='0') {
                     if($grade=='' || $grade=='I') {
                       $ssResult = "$subjectCode<br>".$sessionHandler->getSessionVariable('GRADE_I_FORMAT_ABBR');
                       $resultHoldStatus='1';
                     }
                  }
                  
                  $subjectCntCheck++;
                  $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='left'>$ssResult</td>";
              }
              else {
                $ssResult='';  
              }
        }
        if($subjectCntCheck != count($subjectArray)) {
           for($j=$subjectCntCheck;$j<count($subjectArray);$j++) {
             $tableData .="<td valign='top'".$reportManager->getReportDataStyle()." width='$tableWidth%' align='left'></td>";  
           }
        }
        
        if($resultHoldStatus!='') {
           
           $gradeFormatName = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_NAME');
           $gradeFormatAbbr = $sessionHandler->getSessionVariable('GRADE_I_FORMAT_ABBR');
           $ssResult = $gradeFormatName." (".$gradeFormatAbbr.")";
           
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssResult</td>";
           
           $ssPrevious = $ssResult;  
           
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssPrevious</td>";
           
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssResult</td>";
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssResult</td>";
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssResult</td>";
           $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()." width='$tableWidth%' align='center'>$ssResult</td>";
        }
        else {
           for($xx=0;$xx<count($studentCGPARecordArray);$xx++) {
               $ttStudentId = $studentCGPARecordArray[$xx]['studentId'];  
               if($ttStudentId==$studentId) {
                 $currentCredit =  number_format($studentCGPARecordArray[$xx]['currentCredit'],2);
                 $currentGradePoint =   number_format($studentCGPARecordArray[$xx]['currentGradePoint'],2);   
                 $currentCreditEarned =   number_format($studentCGPARecordArray[$xx]['currentCreditEarned'],2);   
                  
                 $lessCredit =   number_format($studentCGPARecordArray[$xx]['lessCredit'],2);     
                 $lessGradePoint =   number_format($studentCGPARecordArray[$xx]['lessGradePoint'],2);   
                 $lessCreditEarned  =   number_format($studentCGPARecordArray[$xx]['lessCreditEarned'],2);   
                  
                 $previousCredit =   number_format($studentCGPARecordArray[$xx]['previousCredit'],2);   
                 $previousGradePoint =   number_format($studentCGPARecordArray[$xx]['previousGradePoint'],2);   
                 $previousCreditEarned  =   number_format($studentCGPARecordArray[$xx]['previousCreditEarned'],2);   
                  
                 $netCredit =  number_format($studentCGPARecordArray[$xx]['netCredit'],2); 
                 $netGradePoint =  number_format($studentCGPARecordArray[$xx]['netGradePoint'],2);
                 $netCreditEarned  =  number_format($studentCGPARecordArray[$xx]['netCreditEarned'],2); 
                  
                 $sgpa = UtilityManager::decimalRoundUp($studentCGPARecordArray[$xx]['gpa']);  
                 $cgpa = UtilityManager::decimalRoundUp($studentCGPARecordArray[$xx]['cgpa']);    
                 break;
               }
            } 
            $ssResult ="<b><br>$currentCredit<br>$currentCreditEarned<br>$currentGradePoint</b>";
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='center'>$ssResult</td>";
            
            $ssResult ="<b><br>$previousCredit<br>$previousCreditEarned<br>$previousGradePoint";
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='center'>$ssResult</td>";
           
            $ssResult ="<b><br>$lessCredit<br>&nbsp;<br>$lessGradePoint</b>";    
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='center'>$ssResult</td>";
             
            $ssResult ="<b><br>$netCredit<br>$netCreditEarned<br>$netGradePoint</b>";
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='center'>$ssResult</td>"; 
            
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='left'><b><br>$sgpa</b></td>"; 
            $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='left'><b><br>$cgpa</b></td>"; 
        }
        $tableData .="<td valign='top' ".$reportManager->getReportDataStyle()."  width='$tableWidth%' align='left'>&nbsp;</td>"; 
        $tableData .="</tr>";
        $foundArray[$i]=$tableData;
    }
    
    $find='0';
    
    $pageCounter = '1';
    $totalPages = '1';
    $pageRecordLimit ='8';
    
    $cntPage = count($foundArray)/$pageRecordLimit;
   
    if(intval($cntPage)==$cntPage) {
      $totalPages = intval($cntPage);  
    }
    else {
       $totalPages = intval($cntPage)+1;
    }
    
    if($totalPages=='') {
      $totalPages='1';  
    }
    
    if($totalPages=='0') {
      $totalPages='1';  
    }
    
    
    for($i=0;$i<count($foundArray);$i++) {
       $srNo=($i+1); 
       
       if($find=='0') { 
         $tableData = tableHeader($subjectArray,$rollId);  
       }
       $tableData .= $foundArray[$i];
       
       $find++;    
       if($find==$pageRecordLimit) {
          $tableData .= "</table>";  
         
          reportGenerate($tableData,$heading,$univeristyName);    
          $find='0';
          $tableData = '';
       }
    }
    
    if($tableData != '') {
      $tableData .= "</table>"; 
      reportGenerate($tableData,$heading,$universityName);  
    }
	
    if($gradeSetId=='') {
      $gradeSetId='0';  
    }
    
    $gradeListArray = $studentReportsManager->getGradeList($gradeSetId); 
    $gradeList = "<table border='1' cellspacing='2px' cellpadding='2px' width='10%' align='left'>
                     <tr>
                        <td valign='' align='left' nowrap ".$reportManager->getFooterStyle().">Grade</td>
                        <td valign='' align='left' nowrap ".$reportManager->getFooterStyle().">Qualitative Meaning</td>
                        <td valign='' align='left' nowrap ".$reportManager->getFooterStyle().">Grade Points</td>
                    </tr>";
    for($i=0;$i<count($gradeListArray);$i++) {
      $gradeLabel = $gradeListArray[$i]['gradeLabel'];
      $gardeMeaining = $gradeListArray[$i]['gradeStatus'];
      $gradePoints = $gradeListArray[$i]['gradePoints'];  
      $gradeList .= "<tr>
                        <td valign='' align='center' nowrap ".$reportManager->getReportDataStyle().">$gradeLabel</td>
                        <td valign='' align='left' nowrap ".$reportManager->getReportDataStyle().">$gardeMeaining</td>
                        <td valign='' align='center' nowrap ".$reportManager->getReportDataStyle().">$gradePoints</td>
                    </tr>";
    }
    $gradeList .= "</table>";
    reportGenerate($gradeList,$heading,$universityName,1);  
    
die;

 // Report generate
    function reportGenerate($value,$heading,$univName,$isFooter='0') {
        
        
        global $pageCounter;
        global $totalPages;
        
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('MAIN RESULT GAZETTE/SHEET');
        $reportManager->setReportInformation("$heading");     
        
        
        $ttFooter = "<table border='0' cellspacing='2px' cellpadding='2px' width='100%' align='left'>
                     <tr>
                        <td valign='' align='left' ".$reportManager->getFooterStyle().">
                         Signature
                        </td>
                        <td valign='' align='left' ".$reportManager->getFooterStyle().">
                         _______________
                        </td>
                    </tr>
                    <tr>    
                        <td valign='' align='left' ".$reportManager->getFooterStyle().">
                         Name 
                        </td>
                        <td valign='' align='left' ".$reportManager->getFooterStyle().">
                         _______________
                        </td>
                    </tr>
                    <tr>    
                        <td valign='' align='left' ".$reportManager->getFooterStyle().">
                         Designation
                        </td>
                        <td valign='' align='left' ".$reportManager->getFooterStyle()." >
                         _______________
                        </td>
                    </tr>
                   </table> ";
       ?>     
        
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>>
                <?php echo $univName; ?><br>
                <?php echo $reportManager->getInstituteName(); ?>
            </th>
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
                <?php echo $value; ?>        
            </td>
            </tr> 
            </table> 
            <br>
            <?php
            if($isFooter=='0') {
            ?>
                <table border='0' cellspacing='0' cellpadding='0' width="95%" align="center">
                <?php
                    for($i=1;$i<=4;$i++) {
                      echo "<td ".$reportManager->getFooterStyle().">$ttFooter</td>";  
                    }
                ?>
                </table>
                <?php
            }
            ?>
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="95%" align="center">
                <tr>
                    <td valign='' align="left"  <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                    <td valign='' align="right" <?php echo $reportManager->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
        $pageCounter++;
    }

    function tableHeader($subjectArray,$rollId) {
        
        global $tableWidth;
        
        $reportManager = ReportManager::getInstance();     
        
        $result = "<table border='1' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'> 
                    <tr>
                       <td valign='top'  width='15%' ".$reportManager->getReportDataStyle()." ><b>SR.NO.<br>$rollId<br>NAME</b></td>";        
        for($i=0;$i<count($subjectArray);$i++) {
           $id = ($i+1);  
           $result .= " <td width='$tableWidth%' valign='top' ".$reportManager->getReportDataStyle()." ><b>CS-$id<br>CRD-$id<br>GD-$id<br>PNT-$id</b></td>";   
        }                                       
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b>CURRENT<br>CRD-RG<br>CRD-ER<br>GPNTS</b></td>";          $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b>PREVIOUS<br>CRD-RG<br>CRD-ER<br>GPNTS</b></td>";
        
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b>LESS<br>CRD-RG<br><br>GPNTS</b></td>";    
           
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b>NET<br>CRD-RG<br>CRD-ER<br>GPNTS</b></td>";   
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b><br>SGPA</b></td>";   
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b><br>CGPA</b></td>";
        $result .= " <td valign='top' width='$tableWidth%' ".$reportManager->getReportDataStyle()." ><b>GRADE CARD NO./<BR><BR>REMARKS</b></td>";
        $result .= "</tr>";
       
        return $result; 
    }


    
