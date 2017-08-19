<?php
ini_set('MEMORY_LIMIT','400M');
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
    
  
    //to parse csv values    
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

	$csvData='';
    $labelId = trim($REQUEST_DATA['timeTable']);
	$classId = trim($REQUEST_DATA['degree']);
	$subjectId = trim($REQUEST_DATA['subjectId']);

	$internal = $REQUEST_DATA['internal'];
	$attendance = $REQUEST_DATA['attendance'];
	$external = $REQUEST_DATA['external'];
	$total = $REQUEST_DATA['total'];
    $gradeList = $REQUEST_DATA['gradeList'];     
    $emptyList = $REQUEST_DATA['emptyList'];      
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
	
	if (empty($internal) and empty($attendance) and empty($external) and empty($total) and empty($isSGPA) and empty($isCGPA)) {
        echo 'Please select atleast 1 option for showing marks (Internal Marks, External Marks, Attendance, Total Marks, SGPA, CGPA, Grade) ';
        die;
    }

	//$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId in ($subjectId)");
	  $subjectArray = $studentReportsManager->getAlternateSubject($subjectId,$classId);
	$subjectCodeArray = array();
	foreach($subjectArray as $record) {
	  $subjectCodeArray[$record['subjectId']] = $record['subjectCode'];
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
	if (empty($studentIdList)) {
   	$studentIdList = 0;
	}
        if (empty($studentNameList)) {
   	$studentNameList = 0;
	}

	//$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", "where subjectId in ($subjectId) order by subjectCode");

	$maxMarksArray = array();
    $gradeLabelNotExist = 'I';

    if($emptyList!='on') {
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
            if($record['marksScoredStatus']=='Marks') {
              $dataArray[$studentId][$subjectIdNew]['I'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['IR'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel'];  
            }
            else if($record['marksScoredStatus']=='D') {
              $dataArray[$studentId][$subjectIdNew]['I'] = '0';
              $dataArray[$studentId][$subjectIdNew]['IR'] = $record['marksScoredStatus'];
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;    
            }
            else {
              $dataArray[$studentId][$subjectIdNew]['I'] = '0';
              $dataArray[$studentId][$subjectIdNew]['IR'] = 'A';
              $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;   
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
        
        
        
	   
		    $internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
		    foreach($internalArray as $record) {
			    $dataArray[$record['studentId']][$record['subjectId']]['A'] =$record['lectureAttended']."/".$record['lectureDelivered'];
		    }
	    
	    
        
		    $subMaxMarksArray = $studentReportsManager->getExternalMaxMarks($classId, $subjectId);
		    foreach($subMaxMarksArray as $record) {
			    $maxMarksArray[$record['subjectId']]['E'] = round($record['maxMarks'],1);
		    }
		    $internalArray = $studentReportsManager->getExternalMarks($studentIdList, $classId, $subjectId);
		    foreach($internalArray as $record) {
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
                }
                else if($record['marksScoredStatus']==0) {
                  $dataArray[$studentId][$subjectIdNew]['E'] = '0';
                  $dataArray[$studentId][$subjectIdNew]['ER'] = $record['marksScoredStatus'];
                  $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;
                }
                else {
                  $dataArray[$studentId][$subjectIdNew]['E'] = '0';
                  $dataArray[$studentId][$subjectIdNew]['ER'] = 'A';
                  $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;
                }
                
                if($maxMarksArray[$subjectId]['I'] > 0) {
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
    }

	$mainStudentArray = array();
    $i = 0;
    foreach($studentArray as $record) {
        $studentId = $record['studentId'];
        foreach($subjectArray as $subjectRecord) {
            $subjectIdNew = $subjectRecord['subjectId'];
            if ($internal != '') {
                $studentArray[$i][$subjectIdNew]['I'] = $dataArray[$studentId][$subjectIdNew]['I'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['I'];
                $studentArray[$i][$subjectIdNew]['IR'] = $dataArray[$studentId][$subjectIdNew]['IR'];
            }
            if ($attendance != '') {
                $studentArray[$i][$subjectIdNew]['A'] = $dataArray[$studentId][$subjectIdNew]['A']== '' ? NOT_APPLICABLE_STRING : $dataArray[$studentId][$subjectIdNew]['A'];
            }
            if ($external != '') {
                $studentArray[$i][$subjectIdNew]['E'] = $dataArray[$studentId][$subjectIdNew]['E'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['E'];
                $studentArray[$i][$subjectIdNew]['ER'] = $dataArray[$studentId][$subjectIdNew]['ER'];
            }
            if ($total != '') {
                $studentArray[$i][$subjectIdNew]['T'] = $dataArray[$studentId][$subjectIdNew]['T'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['T'];
            }   
            if($gradeList != '') {
              if($dataArray[$studentId][$subjectIdNew]['G'] != '' && ($dataArray[$studentId][$subjectIdNew]['ER']!='' || $dataArray[$studentId][$subjectIdNew]['ER']!='A' || $dataArray[$studentId][$subjectIdNew]['ER']!='D')) {
                $gradeChartArray[$dataArray[$studentId][$subjectIdNew]['G']]++;  
              }
              else {
                $gradeChartArray[$gradeLabelNotExist]++;   
              }
              $studentArray[$i][$subjectIdNew]['G'] = $dataArray[$studentId][$subjectIdNew]['G'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['G'];
            }   
            $studentArray[$i]['totalMarks'] = $studentFinalMarksArray[$studentId];
        }
        
        if ($isSGPA != '') {   
          $studentArray[$i]['gpa'] = $gpaArray[$studentId];   
        }
        if ($isCGPA != '') {   
          $studentArray[$i]['cgpa'] = $cgpaArray[$studentId];   
        }
        $i++;
    }



	//$studentMarksArray = $studentReportsManager->getStudentMarks($studentIdList, $classId, $subjectId);

	foreach($maxMarksArray as $subjectId => $marksTypeArray) {
		foreach($marksTypeArray as $examType => $maxMarksVal) {
			$maxMarksArray[$subjectId][$examType] = "$maxMarksVal";
		}
	}
    
    
    
    $colCounter = 0;
    if ($internal == 'on') {
        $colCounter++;
    }
    if ($attendance == 'on') {
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
    
               
	$csvData="#, RollNo.,Name";
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
	   $csvData .= ",".$subjectCode;
	   for($j=0;$j<$colCounter-1;$j++) {
         $csvData .=",";  
       }
    } 
    
    
    if($isSGPA == 'on') {
       $csvData .=",SGPA"; 
    } 
    if($isCGPA == 'on') {
      $csvData .=",CGPA"; 
    } 
	$csvData .="\n";
   

	$csvData .=",,";
    for($i=0;$i < count($subjectArray); $i++) {
       if ($internal == 'on') {
            $csvData .="  ,InternalMarks"; 
        }
        if ($attendance == 'on') {
            $csvData .="  ,Attendance"; 
        }
        if ($external == 'on') {
            $csvData .="  ,ExternalMarks"; 
        }
        if ($total == 'on') {
            $csvData .="  ,TotalMarks"; 

        }
        if ($gradeList == 'on') {
            $csvData .="  ,Grade"; 
        }  
    }
	$csvData .="\n";
        
        for($i=0;$i < count($studentArray); $i++) {  
            $rollNo = $studentArray[$i]['rollNo'];
            $studentId = $studentArray[$i]['studentId'];
            $studentName=$studentArray[$i]['studentName'];
            $srNo = ($records+$i+1);
	        $csvData .=parseCSVComments($srNo).",".parseCSVComments($rollNo).",".parseCSVComments($studentName);   
            
            for($j=0;$j < count($subjectArray); $j++) {
               $subjectId = $subjectArray[$j]['subjectId'];   
                if ($internal == 'on') {
                  $mm = $dataArray[$studentId][$subjectId]['IR']; 
                  if($mm=='') {
                    $mm='0';  
                  }
                  if($emptyList=='on') {
                    $mm='';  
                  }
               	  $csvData .=",".$mm; 
                }
                if ($attendance == 'on') {
                    $mm = $dataArray[$studentId][$subjectId]['A'];
                    if($mm=='') {
                      $mm='A';  
                    }
                    if($emptyList=='on') {
                      $mm='';  
                    }
               	  $csvData .=",".$mm; 
                }
                $ext='';
                if ($external == 'on') {
                    $mm = $dataArray[$studentId][$subjectId]['ER'];
                    if($mm=='') {
                      $mm=0;  
                    }
                    if($mm=='A' || $mm=='D') {
                      $ext='D';  
                    }
                    if($emptyList=='on') {
                      $mm='';  
                    }
               	  $csvData .=",".$mm; 
                }
                if ($total == 'on') {
                    $mm = $dataArray[$studentId][$subjectId]['T'];
                    if($mm=='A' || $ext!='') {
                      $mm=($dataArray[$studentId][$subjectId]['I'] + 0);    
                    }
                    if($mm=='') {
                      $mm='0';  
                    }
                    if($emptyList=='on') {
                      $mm='';  
                    }
               	  $csvData .=",".$mm; 
                }
                if ($gradeList == 'on') {
                    $mm = $dataArray[$studentId][$subjectId]['G'];
                    if($mm=='') {
                      $mm='N/A';  
                    }
                    if($emptyList=='on') {
                      $mm='';  
                    }
               	  $csvData .=",".$mm; 
                }  
            }
            if ($isSGPA == 'on') {
                $mm = $gpaArray[$studentId];
                if($mm=='') {
                  $mm=NOT_APPLICABLE_STRING;  
                }
                if($emptyList=='on') {
                  $mm='';  
                }
                $csvData .=",$mm"; 
            }  
            if ($isCGPA == 'on') {
                $mm = $cgpaArray[$studentId];
                if($mm=='') {
                  $mm=NOT_APPLICABLE_STRING;  
                }
                if($emptyList=='on') {
                  $mm='';  
                }
                $csvData .=",$mm"; 
            }  
	        $csvData .="\n"; 
        }
        
    UtilityManager::makeCSV($csvData,'StudentAwardList.csv');
    
    die();
  

?>
