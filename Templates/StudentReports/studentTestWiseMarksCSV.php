<?php 
// This file generate a list Student Test Wise Marks Report CSV    
//
// Author :Parveen Sharma
// Created on : 05-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
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
    $studentReportManager = StudentReportsManager::getInstance();

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
    
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $classId   = $REQUEST_DATA['classId'];
    $subjectId  = $REQUEST_DATA['subjectId'];
    $subjectId1 = $REQUEST_DATA['subjectId1'];
    $groupId    = $REQUEST_DATA['groupId'];
    $testTypeCategoryId   = $REQUEST_DATA['testTypeCategoryId'];   
   
    if($timeTableLabelId=='') {
       $timeTableLabelId = 0;  
    }
   
    if($classId=='') {
       $classId = 0;  
    }
    
    if($subjectId=='') {
       $subjectId = 0;  
    }
    
    if($subjectId1=='') {
       $subjectId1 = 0;  
    }
   
    
    if($groupId=='') {
       $groupId = 0;  
    }
    
    if($testTypeCategoryId=='') {
       $testTypeCategoryId = 0;  
    } 
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
     
    if ($sortField == 'studentName') {
      $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
      $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, rollNo)';
    } 
    else if ($sortField == 'universityRollNo') {
      $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, universityRollNo)';
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
     
      
    // Fetch Subject Information
    $fieldName = "subjectId, subjectName, subjectCode, hasAttendance, hasMarks, '0' AS testCount, '0' AS totalCount";
    $condition = "WHERE subjectId IN ($subjectId) AND hasAttendance = 1 AND hasMarks = 1";
    $orderBySubject = " ORDER BY subjectTypeId, subjectCode ";
    $subjectArray = $studentReportManager->getSingleField("`subject`",$fieldName,$condition.$orderBySubject);   
    $subjectCount = count($subjectArray);  
     
     
     // Fetch Student List 
     $studentCondition = " AND c.classId = $classId AND tt.subjectId IN ($subjectId) AND tt.timeTableLabelId='".$timeTableLabelId."'";  
     if($groupId!='all') {
        $studentCondition .= " AND tt.groupId = $groupId ";  
     }

     //$totalStudentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
     //$totalStudent = count($totalStudentArray);
     $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy,'');  
     
     
     
     //fetch distinct types of test taken on this subject
     $conditions = " AND a.classId = ".$classId." AND a.subjectId IN ($subjectId) ";
     if($groupId!='all') {
       //$conditions .= " AND a.groupId IN ($groupId) ";  
     }
     if($testTypeCategoryId!='all') {
        $conditions .= " AND a.testTypeCategoryId IN ($testTypeCategoryId) ";  
     }
     $testTypeArray = $studentReportManager->getSubjctWiseTestType($conditions);   
     
     
     //fetch distinct types of test taken on this subject    
     $conditions = " AND a.classId = ".$classId." AND a.subjectId IN ($subjectId)";
     if($groupId!='all') {
       //$conditions .= " AND a.groupId IN ($groupId) ";  
     }
     if($testTypeCategoryId!='all') {
        $conditions .= " AND a.testTypeCategoryId IN ($testTypeCategoryId) ";  
     }
     $testDetailArray = $studentReportManager->getTestWiseDetails($conditions);
     
     
     //fetch student testwise details
     $conditions = " AND tm.classId = ".$classId." AND tm.subjectId IN ($subjectId) ";
      if($groupId!='all') {
        //$conditions .= " AND tm.groupId IN ($groupId) ";  
     }
     if($testTypeCategoryId!='all') {
        $conditions .= " AND tm.testTypeCategoryId IN ($testTypeCategoryId) ";  
     }
     $conditions .= " AND tm.subjectId IN ($subjectId) ";
     $studentTestArray = $studentReportManager->getStudentTestDetails($conditions); 
   
   
// Search Format   

    // Findout Time Table Name
    $timeNameArray = $studentReportManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;  
    }

    // Findout Class Name
    $classNameArray = $studentReportManager->getSingleField('class', 'className AS className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);
    if($className2=='') {
      $className2 = NOT_APPLICABLE_STRING;  
    }

    $subName='';
    // Findout Subject
    if($subjectId1 != 'all') {
       $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode, subjectName', "WHERE subjectId IN ($subjectId)");
       $subName = $subCodeArray[0]['subjectName']."&nbsp;(".$subCodeArray[0]['subjectCode'].")";
       if($subName=='') {
         $subName = NOT_APPLICABLE_STRING;  
       }
    }
   
    
    $groupName='';
    // Findout Group
    if ($groupId != 'all') {
       $subCodeArray = $studentReportManager->getSingleField('`group`', 'groupName, groupShort', "WHERE groupId  = $groupId");
       $groupName = $subCodeArray[0]['groupName'];
       if($groupName=='') {
         $groupName = NOT_APPLICABLE_STRING;  
       }
    }
    
    $testTypeCategoryName='';
     // Findout Group
    if ($testTypeCategoryId != 'all') {
       $subCodeArray = $studentReportManager->getSingleField('`test_type_category`', 'testTypeName', "WHERE testTypeCategoryId IN ($testTypeCategoryId)");
       $testTypeCategoryName = $subCodeArray[0]['testTypeName'];
       if($testTypeCategoryName=='') {
         $testTypeCategoryName = NOT_APPLICABLE_STRING;  
       }
    }
  
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
    $heading = "Time Table,".parseCSVComments($timeTableName)."\n";
    $heading .= "Degree,".parseCSVComments($className2)."\n";
    if($subName!='') {
       $heading .= "Subject,".parseCSVComments($subName).",".chr(160); 
    }
    if($groupName!='') {
      $heading .= "Group,".parseCSVComments($groupName)."\n";
    }
    if($testTypeCategoryName!='') {
      $heading .= "Test Type,".parseCSVComments($testTypeCategoryName)."\n";
    }
    $heading .= "\nAs On,".parseCSVComments($formattedDate)."\n";
                 
    $csvData='';
    
    $csvData .= $heading;
    
    
    // Show Headings 
    $csvData .="Sr. No.,Roll No.,Univ. Roll No., Student Name"; 
    for($i=0;$i<count($subjectArray);$i++) {
         $subjectId = $subjectArray[$i]['subjectId'];
         $subjectCode = $subjectArray[$i]['subjectCode']; 
         
         $totalCount=0;
         $find=0;
         for($j=0;$j<count($testDetailArray);$j++) {
            if($testDetailArray[$j]['subjectId']==$subjectId) {
               $totalCount+=($testDetailArray[$j]['cnt1']); 
               $find=1;
            } 
            if($find==1 && $testDetailArray[$j]['subjectId']!=$subjectId) {
               break; 
            }
         }
         $subjectArray[$i]['totalCount']=$totalCount;
         
         if($totalCount==0) {
            $csvData .= ",".parseCSVComments($subjectCode);  
         }
         else {
            if($totalCount<=1) { 
              $colspan="";    
            }
            else {
              $colspan="colspan=$totalCount";    
            }
            $csvData .= ",".parseCSVComments($subjectCode);  
            if($totalCount>1) {
              for($col=0;$col<($totalCount)-1;$col++) {
                $csvData .= ",".chr(160);  
              }
            }
         } 
     }
     $csvData .= "\n"; 
     
     
     if(count($studentTestArray)==0) {   
       $csvData .= "\n,,,".parseCSVComments("No Record Found");
	   UtilityManager::makeCSV($csvData,'StudentTestWiseMarksReport.csv');
       die;  
     }
     
     if(count($studentArray)==0) {   
       $csvData .= "\n,,,".parseCSVComments("No Record Found");
	   UtilityManager::makeCSV($csvData,'StudentTestWiseMarksReport.csv');
       die;  
     }
     
     $csvData .= ",,,";
     if(count($testTypeArray)>0) {
         for($i=0;$i<count($subjectArray);$i++) {
            $subjectId = $subjectArray[$i]['subjectId'];
            $find=0;
            $totalCount=0;
            for($j=0;$j<count($testTypeArray);$j++) {
               $colspan='';
               $totalCount=0;
               $subjectIds = $testTypeArray[$j]['subjectId'];
               $testTypeName = $testTypeArray[$j]['testTypeName'];
               for($k=0;$k<count($testDetailArray);$k++) {
                  if($testDetailArray[$k]['subjectId']==$subjectIds && $testDetailArray[$k]['testTypeName']==$testTypeName) {
                     $totalCount +=$testDetailArray[$k]['cnt1'];  
                  } 
               }
               if($totalCount<=1) { 
                  $colspan="";    
               }
               else {
                 $colspan="colspan=$totalCount";    
               } 
               if($testTypeArray[$j]['subjectId']==$subjectId) {
                  $testTypeName = $testTypeArray[$j]['testTypeName'];
                  $csvData .= ",".parseCSVComments($testTypeName);    
                  if($totalCount>1) {
                    for($col=0;$col<($totalCount)-1;$col++) {
                      $csvData .= ",".chr(160);  
                    }
                  } 
                  $find=1;
                } 
                if($find==1 && $testTypeArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);   
             }
         }     
          $csvData .= "\n"; 
     }
     
     
     $csvData .= ",,,";   
     if(count($testTypeArray)>0) {
         for($i=0;$i<count($subjectArray);$i++) {
            $subjectId = $subjectArray[$i]['subjectId'];
            $find=0;
            $colspan='';
            $cnt=0;
            for($j=0;$j<count($testDetailArray);$j++) {
               if($testDetailArray[$j]['subjectId']==$subjectId) {
                  $testName = $testDetailArray[$j]['testName'];
                  $colspan = '';  
                  $cnt=0;
                  if($testDetailArray[$j]['cnt1']>=2) {
                    $cnt=$testDetailArray[$j]['cnt1'];  
                    $colspan = "colspan='".$cnt."'";
                    $csvData .= ",".parseCSVComments($testName);     
                    for($col=0;$col<($cnt)-1;$col++) {
                      $csvData .= ",".chr(160);  
                    }
                  }
                  else {
                    $csvData .= ",".parseCSVComments($testName);       
                  }
                  $find=1;
                } 
                if($find==1 && $testDetailArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);   
             }
         }     
         $csvData .= "\n";
     }
     
   

     
     $finalSubject = array();
     
     $csvData .= ",,,";      
     $colspan=0;
     if(count($testTypeArray)>0) {
         $jj=0;
         for($i=0;$i<count($subjectArray);$i++) {
            $subjectId = $subjectArray[$i]['subjectId'];
            $find=0;
            $colspan='';
            $finalSubject[$jj]['subjectId']=$subjectId;
            $finalSubject[$jj]['groupType']=0;  
            $finalSubject[$jj]['testName']='';  
            $finalSubject[$jj]['testTypeName']='';  
            for($j=0;$j<count($testDetailArray);$j++) {
               if($testDetailArray[$j]['subjectId']==$subjectId) {
                  $groupType = $testDetailArray[$j]['cnt'];
                  $groupArr = explode(',',$groupType);
                  $cnt = count($groupArr);
                  for($k=0;$k<count($groupArr);$k++) {
                     $groupType=$groupArr[$k];
                     if($groupType==1) {
                        $groupType='T';    
                     }
                     else if($groupType==2) {
                        $groupType='P';    
                     }
                     else if($groupType==3) {
                        $groupType='L';   
                     }
                     else if($groupType==4) {
                        $groupType='W';  
                     }
                     else if($groupType==5) {
                        $groupType='A'; 
                     } 
                     else {
                        $groupType=NOT_APPLICABLE_STRING;
                     }
                     $finalSubject[$jj]['subjectId']=$subjectId; 
                     $finalSubject[$jj]['groupType']=$groupArr[$k];
                     $finalSubject[$jj]['testName'] =$testDetailArray[$j]['testName'];
                     $finalSubject[$jj]['testTypeName']=$testDetailArray[$j]['testTypeName'];   
                     
                     $csvData .= ",".parseCSVComments($groupType);  
                     
                     $colspan++;
                     $jj++;   
                  }
                  $find=1;
                } 
                if($find==1 && $testDetailArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);  
                $jj++;   
                $colspan++;
             }
         }     
         $csvData .= "\n";
     }       
    
    //UtilityManager::makeCSV($csvData,'StudentTestWiseMarksReport.csv');
    //die;
     
     
    $pp=-1;
    // Show Student Test Type Details
    for($i=0; $i< count($studentArray); $i++) {
       $studentId = $studentArray[$i]['studentId']; 
       $csvData .= parseCSVComments($i+1).",".parseCSVComments($studentArray[$i]['rollNo']); 
       $csvData .= ",".parseCSVComments($studentArray[$i]['universityRollNo']).",".parseCSVComments($studentArray[$i]['studentName']); 
      
       $j=0;
       $s=-1;
       $cnt2=0; 
       for($j=0; $j<count($studentTestArray); $j++) { 
         $ttStudentId  = $studentTestArray[$j]['studentId'];                       
         if($ttStudentId == $studentId) {
            $s=$j;  
            break; 
         }
       }
       
       if($s==-1) {
         for($j=0; $j<count($finalSubject); $j++) {  
            $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);  
         }
       }
       else {
         $cnt2=0; 
         for($ii=0; $ii<count($finalSubject); $ii++) {
            $fSubjectId =$finalSubject[$ii]['subjectId'];
            $groupTypeId = $finalSubject[$ii]['groupType'];
            $testName = $finalSubject[$ii]['testName'];
            $find=0;
            $result=NOT_APPLICABLE_STRING;
            if($studentTestArray[$s]['studentId']==$studentId && $studentTestArray[$s]['subjectId']==$fSubjectId && $studentTestArray[$s]['groupTypeId']==$groupTypeId && $studentTestArray[$s]['testName']==$testName) {
               $marks = $studentTestArray[$s]['marks']; 
               $maxMarks = $studentTestArray[$s]['maxMarks2']; 
               if($marks=='A') {
                 $result = "A";  
               }
               else if($marks=='N/A') {
                 $result = "N/A";
               }
               else {
                  $result = $marks."/".$maxMarks;
               } 
               $find=1;
               $s++;
            }
            if($find==1) {
               $csvData .= ",".parseCSVComments($result); 
            }
            else {
               $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING); 
            }
         }
       }
       $csvData .= "\n";
    }
       
    UtilityManager::makeCSV($csvData,'StudentTestWiseMarksReport.csv');
    die;
?>
<?php
// $History: studentTestWiseMarksCSV.php $       
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/06/10    Time: 5:13p
//Updated in $/LeapCC/Templates/StudentReports
//option subject condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/05/10    Time: 1:14p
//Updated in $/LeapCC/Templates/StudentReports
//query format updated (optional subject code udpated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/02/10    Time: 5:45p
//Updated in $/LeapCC/Templates/StudentReports
//replace test category to test type (update)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/02/10    Time: 5:33p
//Updated in $/LeapCC/Templates/StudentReports
//table format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:33p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//

?>