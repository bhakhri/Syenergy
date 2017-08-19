<?php 
//This file is used as Excel Version for  marks report (Backup).
//
// Author :Ipta Thakur
// Created on : (24.01.2012 )
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>
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

	     
    

      if($labelId=='') {
      $labelId='0';  
    }
    if($classId=='') {
      $classId='0';  
    }
    if($subjectId=='') {
      $subjectId='0';  
    }
	
        $subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId in ($subjectId)");
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
        else if($record['marksScoredStatus']=='0') {
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
            $studentArray[$i][$subjectIdNew]['E'] = $dataArray[$studentId][$subjectIdNew]['E'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['E'];
            $studentArray[$i][$subjectIdNew]['T'] = $dataArray[$studentId][$subjectIdNew]['T'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['T'];
            $studentArray[$i][$subjectIdNew]['G'] = $dataArray[$studentId][$subjectIdNew]['G'] == '' ? 'I' : $dataArray[$studentId][$subjectIdNew]['G'];
            $studentArray[$i]['totalMarks'] = $studentFinalMarksArray[$studentId];
		}
		$i++;
	}




	foreach($maxMarksArray as $subjectId => $marksTypeArray) {
		foreach($marksTypeArray as $examType => $maxMarksVal) {
			$maxMarksArray[$subjectId][$examType] = "$maxMarksVal";
		}
	}
    
    
    
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
    
	$csvData="#, RollNo.,Name";
    for($i=0;$i < count($subjectArray); $i++) {

    
       $subjectId = $subjectArray[$i]['subjectId'];
       $subjectCode = $subjectArray[$i]['subjectCode'];
	   $csvData .= ",".$subjectCode;
	   for($j=0;$j<$colCounter-1;$j++) {
         $csvData .=",";  
       }
    } 
  	$csvData .=",,";
    for($i=0;$i < count($subjectArray); $i++) {
       
            $csvData .="  ,InternalMarks"; 
            $csvData .="  ,ExternalMarks"; 
            $csvData .="  ,TotalMarks"; 
            $csvData .="  ,Grade"; 
          
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
          
                  $mm = $dataArray[$studentId][$subjectId]['IR']; 
                  if($mm=='') {
                    $mm='0';  
                  }
                  if($emptyList=='on') {
                    $mm='';  
                  }
               	  $csvData .=",".$mm; 
                
                
                $ext='';
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
                
         
                    $mm = $dataArray[$studentId][$subjectId]['G'];
                    if($mm=='') {
                      $mm='I';  
                    }
                    if($emptyList=='on') {
                      $mm='';  
                    }
               	  $csvData .=",".$mm; 
               
            }
            
	        $csvData .="\n"; 
        }
        
    UtilityManager::makeCSV($csvData,'StudentAwardList.csv');
    
    die();
  

?>
