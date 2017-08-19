<?php 
//This file is used as printing version for gazetter report.
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
?>
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

	require_once(BL_PATH . '/ReportFooterManager.inc.php');
	$reportFooterManager = ReportFooterManager::getInstance();

    require_once(MODEL_PATH . "/StudentCGPARepotManager.inc.php");
    $studentCGPARepotManager = StudentCGPARepotManager::getInstance();

    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
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

    $gradeChartArray= array();
      
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
    
    
    $reportTableWidth="600";

    /*
	if (empty($labelId) or empty($classId) or empty($subjectId)) {
		echo 'Required parameters missing1';
		die;
	}
    */
    /*
	if (empty($internal) and empty($attendance) and empty($external) and empty($total)) {
		echo 'Required parameters missing2';
		die;
	}
    */
    
   /*
       if($isSGPA=='on') {
           $condition = " c.classId = '$classId' ";
           $resourceRecordArrayGPA = $studentCGPARepotManager->getStudentClasswiseGPA($condition);
           $gpaArray = array();
           for($i=0;$i<count($resourceRecordArrayGPA);$i++) {
             $gpa = UtilityManager::decimalRoundUp($resourceRecordArrayGPA[$i]['gpa']);
             $ggStudentId = $resourceRecordArrayGPA[$i]['studentId'];    
             $gpaArray[$ggStudentId]=$gpa;
           }
        }    
        
        if($isCGPA=='on') {         
           $condition = " c.classId = '$classId' ";
           $resourceRecordArrayCGPA = $studentCGPARepotManager->getStudentClasswiseCGPA($condition);
           $cgpaArray = array();
           for($i=0;$i<count($resourceRecordArrayCGPA);$i++) {
             $cgpa = UtilityManager::decimalRoundUp($resourceRecordArrayCGPA[$i]['CGPA']);
             $ggStudentId = $resourceRecordArrayCGPA[$i]['studentId'];    
             $cgpaArray[$ggStudentId]=$cgpa;
           }
        }
   */   
   
   
    $tableName = "class c, degree d, branch b, study_period sp, university u";
    $fieldName = " d.degreeCode, b.branchName, sp.periodName, u.universityName, c.studyPeriodId ";
    $where = " WHERE c.degreeId = d.degreeId AND
              c.branchId = b.branchId AND
              c.studyPeriodId = sp.studyPeriodId AND
              u.universityId = c.universityId AND
              c.classId = '$classId' ";
    $searchArray = $studentReportsManager->getSingleField($tableName, $fieldName, $where);  
      
    
   
    
    
     
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
	if(empty($studentIdList)) {
	  $studentIdList = 0;
	}
    if(empty($studentNameList)) {
	  $studentNameList = 0;
	}
 
    $gradeLabelNotExist = 'I';    
	
    $subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectName, subjectCode", "where subjectId in ($subjectId) order by subjectCode");
    
    
    $graphCondition = "";
    if($subjectId!='0') {
      $graphCondition = " AND a.subjectId IN ('$subjectId') ";  
    } 
    $gradeGraphArray = $studentReportsManager->getStudentGraphGrading($classId, $graphCondition); 
    
    $finalGraphArray = array(); 
    for($i=0;$i<count($subjectArray);$i++) {
       $subjectId = $subjectArray[$i]['subjectId'];
       $subjectCode = $subjectArray[$i]['subjectCode'];
       $subjectName = $subjectArray[$i]['subjectName'];
       for($j=0;$j<count($gradeGraphArray);$j++) {
          $gradeLabel = $gradeGraphArray[$j]['gradeLabel'];
          $finalGraphArray[$subjectId][$gradeLabel]['subjectCode']= $subjectCode; 
          $finalGraphArray[$subjectId][$gradeLabel]['subjectName']= $subjectName;
          $finalGraphArray[$subjectId][$gradeLabel]['gradeLabel'] = $gradeLabel;
          $finalGraphArray[$subjectId][$gradeLabel]['studentCount'] = 0;
       }
    }
    
    
    
	$maxMarksArray = array();

	//$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId in ($subjectId)");
	$subjectCodeArray = array();
	foreach($subjectArray as $record) {
		$subjectCodeArray[$record['subjectId']] = $record['subjectCode'];
	}
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
			//$dataArray[$studentId][$subjectIdNew]['I'] = round($record['marksScored'],1);
            //$dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel'];
            if($record['marksScoredStatus']=='Marks') {
              $dataArray[$studentId][$subjectIdNew]['I'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['IR'] = round($record['marksScored'],1);
              $dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel'];
             /*
              if($record['marksScored']=='') {
                $dataArray[$studentId][$subjectIdNew]['IR']='A';
              }
              if($record['gradeLabel']=='') {
                $dataArray[$studentId][$subjectIdNew]['G'] = $gradeLabelNotExist;           
              }
              else {
                $dataArray[$studentId][$subjectIdNew]['G'] = $record['gradeLabel']; 
              } 
              */
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
            
			if ($maxMarksArray[$subjectId]['I'] > 0) {
				$percent = (($dataArray[$studentId][$subjectIdNew]['I'] * 100) / $maxMarksArray[$subjectIdNew]['I']);
				if ($percent < 40) {
					$studentStatusArray[$studentId][$subjectIdNew] = 'RTI ' . $subjectCodeArray[$subjectIdNew];
				}
			}
		}
	
	if ($attendance == 'on') {
		$internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
		foreach($internalArray as $record) {
			$dataArray[$record['studentId']][$record['subjectId']]['A'] = '<u>'.$record['lectureAttended'] .'</u><br>' . $record['lectureDelivered'];
		}
	}
	
    if ($external == 'on') {
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
               $dataArray[$studentId][$subjectIdNew]['G']=$gradeLabelNotExist;
           }
           else if($record['marksScoredStatus']=='0') {
              $dataArray[$studentId][$subjectIdNew]['E'] = '0';
              $dataArray[$studentId][$subjectIdNew]['ER'] = $record['marksScoredStatus'];
               $dataArray[$studentId][$subjectIdNew]['G']=$gradeLabelNotExist;
           }
           else {
              $dataArray[$studentId][$subjectIdNew]['E'] = '0';
              $dataArray[$studentId][$subjectIdNew]['ER'] = 'A';
              $dataArray[$studentId][$subjectIdNew]['G']=$gradeLabelNotExist;
           }
            
			if ($maxMarksArray[$subjectIdNew]['I'] > 0) {
				$percent = (($dataArray[$studentId][$subjectIdNew]['E'] * 100) / $maxMarksArray[$subjectIdNew]['E']);
				if ($percent < 40) {
					$studentStatusArray[$studentId][$subjectIdNew] = 'RP '. $subjectCodeArray[$subjectIdNew];
				}
			}
		}
	}
    
	if ($total == 'on') {
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
	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';
	if ($subjectId != 'all') {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 
        "subjectName, subjectCode, CONCAT(subjectName, ' (',subjectCode,')') AS subjectNames", 
        "WHERE subjectId  IN (".$REQUEST_DATA['subjectId'].")");
		foreach($subCodeArray as $record) {
		   $subNameArray[] = $record['subjectNames'];
           $subCodesArray[] = $record['subjectCode'];
		}
	}
	
    $subCode = implode(',', $subCodesArray);
    $subName = implode(',', $subNameArray);   
    
        $tableFooter="
                     <table border='0' cellspacing='0' cellpadding='0' width='".$reportTableWidth."'>
                        <tr>
               <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Name
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Name
                </td>
                    </tr>
                        <tr>
               <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Designation
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Designation
                </td>
                    </tr>
                        <tr>
               <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='left' colspan=".count($reportFooterManager->tableHeadArray)." ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                    </tr>
                    
                    </table>";
    
    $align='left';
   $tableExFooter="<table border='0' cellspacing='5px' cellpadding='5px'  width='".$reportTableWidth."'>
                     <tr >
 <td valign='' align='$align' width='25%' ".$reportFooterManager->getFooterStyle().">
                           Name 
                    </td>     
<td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
Name
</td>    

                    </td>     
<td valign='' align='$align'  width='20%'  ".$reportFooterManager->getFooterStyle().">
Name
</td>    

                    </td>     
<td valign='' align='$align'  width='30%' ".$reportFooterManager->getFooterStyle().">
Name
</td>    
</tr>

  <tr>
 <td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
                           Designation
                    </td>     
<td valign='' align='$align'  width='20%'  ".$reportFooterManager->getFooterStyle().">
  Designation
</td>    

                    </td>     
<td valign='' align='$align'  width='30%'  ".$reportFooterManager->getFooterStyle().">
  Designation
</td>    

                    </td>     
<td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
  Designation
</td>    
</tr>

  <tr>
 <td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
                           Email Id And Phone
                    </td>     
<td valign='' align='$align'   width='20%'  ".$reportFooterManager->getFooterStyle().">
Email Id And Phone
</td>    

                    </td>     
<td valign='' align='$align'  width='30%'  ".$reportFooterManager->getFooterStyle().">
Email Id And Phone
</td>    

                    </td>     
<td valign='' align='$align' nowrap width='25%'  ".$reportFooterManager->getFooterStyle().">
Email Id And Phone
</td>    
</tr>

    <tr>
               <td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='$align'  width='20%'  ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='$align'  width='30%'  ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                <td valign='' align='$align'  width='25%'  ".$reportFooterManager->getFooterStyle().">
                     Signature
                </td>
                    </tr>

                  </table>";
    
      
    
      for($i=0;$i < count($studentArray); $i++) {  
            $rollNo = $studentArray[$i]['rollNo'];
            $studentName=$studentArray[$i]['studentName'];
            $studentId = $studentArray[$i]['studentId'];      
            $srNo = ($records+$i+1);
         
            $studyPeriodId = $searchArray[0]['studyPeriodId']; 
            
            // Findout GPA
            $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId = '".$studyPeriodId."'";
            $resourceRecordArray = $gradeCardRepotManager->getStudentClasswiseGPA($condition);
          
            $condition=" s.studentId = '".$studentId."' AND c.studyPeriodId <= '".$studyPeriodId."'";
            $resourceRecordArray1 = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
            $cgpa = UtilityManager::decimalRoundUp($resourceRecordArray1[0]['CGPA']);
           
            $gpa = UtilityManager::decimalRoundUp($resourceRecordArray[0]['gpa']);

            for($j=0;$j < count($subjectArray); $j++) {
                $subjectId = $subjectArray[$j]['subjectId'];   
                $mm = $dataArray[$studentId][$subjectId]['IR']; 
                if($mm=='') {
                  $mm='0';  
                }
                
            
                $mm = $dataArray[$studentId][$subjectId]['A'];
                if($mm=='') {
                  $mm='A';  
                }
                
                $ext='';
                
                $mm = $dataArray[$studentId][$subjectId]['ER'];
                if($mm=='') {
                  $mm='0';  
                }
                if($mm=='A' || $mm=='D') {
                  $ext='D';  
                  $dataArray[$studentId][$subjectId]['G']=$gradeLabelNotExist; 
                }
               
                
                $mm = $dataArray[$studentId][$subjectId]['T'];
                if($mm=='A' || $ext!='') {
                  $mm=($dataArray[$studentId][$subjectId]['I'] + 0);    
                }
                if($mm=='') {
                  $mm='0';  
                }
                
                
                $mm = $dataArray[$studentId][$subjectId]['G'];
                if($mm=='') {
                  $mm='I';  
                }
                $finalGraphArray[$subjectId][$mm]['studentCount']++;
            }
    }
    
    
    
	$reportFooterManager->setReportWidth($reportTableWidth);
	$reportFooterManager->setReportHeading('Award List');
	$reportFooterManager->setReportInformation("$className2<br> Subject: $subName");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']	=	array('#','width="2%" align="left" rowspan="2"', "align='left' ");
	//$reportTableHead['rollNo']	=	array('Roll No.<br>Name<br>Father\'s Name','width="10%" align="left" rowspan="2"', "align='left' ");
    $reportTableHead['rollNo']   =    array('Roll No.','width="15%" align="left" rowspan="2"', "align='left' ");
    $reportTableHead['studentName']   =    array('Name','width="15%" align="left" rowspan="2"', "align='left' ");
    
    $allDetailsArray = array('subjectArray'=>$subjectArray, 
                         'recordArray'=>$studentArray, 
                         'totalStudents'=>$totalStudents, 
                         'maxMarks'=>$maxMarksArray,
                         'finalMaxMarksArray' => $finalMaxMarksArray);

	$reportFooterManager->setRecordsPerPage(30);
	$reportFooterManager->setReportData($reportTableHead, $allDetailsArray,$tableFooter,'N',$tableExFooter);
	$reportFooterManager->showGazetteReport();	

    
    echo "<br class='page'>".getChartGenerate($finalGraphArray,$totalStudents,$subjectArray,$gradeGraphArray);
    
function getChartGenerate($charArray,$totalStudents,$subjectArray,$gradeGraphArray) {
    
     global $reportFooterManager;
    
     $graphFooter='<tr>';
     $graphFooter1='<tr>';
     for($i=0;$i<count($subjectArray);$i++) {
        $subjectCode = $subjectArray[$i]['subjectCode'];  
        $subjectId = $subjectArray[$i]['subjectId'];  
        for($j=0; $j<count($gradeGraphArray);$j++) {
          $gradeLabel = $gradeGraphArray[$j]['gradeLabel'];  
          if($i==0) {
            $graphFooter .='<th rowspan="2">&nbsp;</th>';
          }
          else {
            $graphFooter .='<th>'.$gradeLabel.'</th>';
          }
        }
        $graphFooter1 .='<th colspan="'.count($gradeGraphArray).'">'.$subjectCode.'</th>';
     }
     $graphFooter1='<tr>';
     $graphFooter1='</tr>';
    
     
    
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
         
     if($cnt != '') {
       $cntRow = "rowspan='".($cnt+1)."'";
     }  
    
     
     for($j=0;$j<count($charArray);$j++) {
        $color = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
        $percentage = $charArray[$j]['studentCount']; 
        
        $gradeLabel = $charArray[$j]['gradeLabel'];
        $subjectName = $charArray[$j]['subjectName'].'  ('. $charArray[$j]['subjectCode'].') ';
        if($percentage=='0') {
          $percentage1 = '';  
          $percentage  ='';
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
                          <table border='0' $style cellpadding='0' cellspacing='0' valign='bottom'>
                            <tr>
                               <td  align='center' valign='bottom' $style >&nbsp;</td>
                            </tr>
                         </table>";
        }    
        $setWidth=" width='".intval($cnt/90*100)."' ";
        $chartResult1 .= "<td ".$reportFooterManager->getReportDataStyle()." $cntRow  $setWidth valign='bottom' height='100%' align='center'>$percentage1</td>";
        $chartResult2 .= "<td ".$reportFooterManager->getReportDataStyle()." align='center'>".$charArray[$j]['gradeLabel']."</td>";
     }
     $cntColSpan = '';
     if(count($charArray)>0) {
       $cntColSpan = "colspan='".(count($charArray)+1)."'";
     }
     
     $chartResult  =  "
     <br>
     <table border='0' cellpadding='0' cellspacing='0'  width='650px' class='reportTableBorder'  align='center'>
     <tr>
     <td ".$reportFooterManager->getReportDataStyle()." colspan=2 align=center style='padding-left:90px'>
     <b>Student Award List Graph</b>
     </td>
     </tr>
     <td width='2%' ".$reportFooterManager->getReportDataStyle()." valign='middle' align='left' style='padding-right:10px'><b>Student Count</b></td> 
     <td width='98%'>
         <table border='1' cellpadding='0' cellspacing='0'  width='100%' class='reportTableBorder'>";
            $ss = $finalCnt*10;
            for($i=$ss;$i>=0;$i-=10) {
                 $cc=$i;
                 if($i==0) {
                   $cc=1;  
                 }
                 $chartResult .="<tr>
                                   <td valign='bottom' height='25px' align='center' ".$reportFooterManager->getReportDataStyle()." width='5px' >".$cc."</td>";
                 if($i==$ss) {                    
                  $chartResult .= $chartResult1;
                 }
                 $chartResult .= "</tr>";
            }
         
         
    
     $chartResult .= "<tr><td valign='bottom' ".$reportFooterManager->getReportDataStyle()."></td>".$chartResult2."</tr>
                      </table>
                      </td>
                      </tr>
                      <tr><td colspan='2' ".$reportFooterManager->getReportDataStyle()." align='center'><b>Grade<br>$subjectName</b></td></tr>
                     </table>";

    return $chartResult;   
}    
?>
