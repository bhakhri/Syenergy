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

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
                                                                  
    
    require_once(MODEL_PATH . "/StudentCGPARepotManager.inc.php");
    $studentCGPARepotManager = StudentCGPARepotManager::getInstance();
    
    $labelId = trim($REQUEST_DATA['timeTable']);
	$classId = trim($REQUEST_DATA['degree']);
	$subjectId = trim($REQUEST_DATA['subjectId']);

    if($labelId=='') {
      $labelId='0';  
    }
    if($classId=='') {
      $classId='0';  
    }
    if($subjectId=='') {
      $subjectId='0';  
    }
    
    
    $internal = "on";
    $external = "on";
    $total = "on";
    $gradeList = "on";
    
	
	//$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId IN ($subjectId)");
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

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$studentArray2 = $studentReportsManager->countClassStudents($classId);
	$totalStudents = $studentArray2[0]['cnt'];

	$studentArray = $studentReportsManager->getClassStudents($classId, $sortBy, $limit);
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
    
    
    
    $colCounter = 0;
    if ($internal == 'on') {
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
        
        
        $class1 = "class='searchhead_text'";       
        $class2 = "class='padding_top'";       
        
        $tableData ="<table width='100%' border='0' cellspacing='2' cellpadding='0'> 
                        <tr class='rowheading'>
                            <td $class1 rowspan='2' align='left'>#</td>
                            <td $class1 rowspan='2' align='left'>Roll No.</td>
                            <td $class1 rowspan='2' align='left'>Name</td>";
        for($i=0;$i < count($subjectArray); $i++) {
           $subjectId = $subjectArray[$i]['subjectId'];
           $subjectCode = $subjectArray[$i]['subjectCode'];
           $tableData .="<td $class1 align='center' colspan='$colCounter'>$subjectCode</td>";          
        } 
        $tableData .="</tr> <tr class='rowheading'>";   
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
            
            $tableData .="<td $class1 align='center'>Internal Marks<br>($ttMax2)</td>"; 
            $tableData .="<td $class1 align='center'>External Marks<br>($ttMax1)</td>"; 
            $tableData .="<td $class1 align='center'>Total Marks<br>($ttSum)</td>"; 
            $tableData .="<td $class1 align='center'>Grade</td>"; 
        }
        
        $tableData .="</tr>";
        
        for($i=0;$i < count($studentArray); $i++) {  
            $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"'; 
            $rollNo = $studentArray[$i]['rollNo'];
            $studentName=$studentArray[$i]['studentName'];
            $studentId = $studentArray[$i]['studentId']; 
            $srNo = ($records+$i+1);
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
                  $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                }
                else {
                  $tableData .="<td $class2 align='center'>$mm</td>";   
                  $totalMks = $dataArray[$studentId][$subjectId]['I'];
                }
                if($mm=='') {
                  $mm='0';  
                }
              
                // external Marks
                $ext='';
                $mm = $dataArray[$studentId][$subjectId]['ER'];
                if($mm=='A' || $mm=='D') {
                  $ext='D';  
                  $dataArray[$studentId][$subjectId]['G']=$gradeLabelNotExist; 
                }
                if($mm=='') {
                  $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                }
                else {
                  $tableData .="<td $class2 align='center'>$mm</td>";  
                  $totalMks = $totalMks + $dataArray[$studentId][$subjectId]['E'];
                }
                if($mm=='') {
                  $mm='0';  
                }  
                    
                // Total Marks and Grading     
                if($totalMks=='') {
                  $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";   
                  $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";     
                }
                else {
                  $tableData .="<td $class2 align='center'>$totalMks</td>";   
                  // Grading                 
                  $mm = $dataArray[$studentId][$subjectId]['G'];
                  if($mm=='') {
                    $mm='I';  
                  }
                  if($mm=='') {
                    $tableData .="<td $class2 align='center'>".NOT_APPLICABLE_STRING."</td>";      
                  }
                  else {
                    $tableData .="<td $class2 align='center'>$mm</td>";   
                  }
                }
            }
            $tableData .="</tr>";
        }
        
	$resultData = array('subjectArray'=>$subjectArray, 'recordArray'=>$studentArray, 
                        'totalStudents'=>$totalStudents, 'maxMarks'=>$maxMarksArray, 
                        'finalMaxMarksArray' => $finalMaxMarksArray,
                        'resultList'=>$tableData);
	echo json_encode($resultData);
?>
