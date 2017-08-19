<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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

    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $classId  = $REQUEST_DATA['classId'];
    $subjectId   = $REQUEST_DATA['subjectId'];
    $groupId   = $REQUEST_DATA['groupId'];
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
      
     // to limit records per page    
     $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
     $records    = ($page-1)* RECORDS_PER_PAGE;
     $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
      
      
     // Fetch Subject Information
     $fieldName = "subjectId, subjectName, subjectCode, hasAttendance, hasMarks, '0' AS testCount, '0' AS totalCount";
     $condition = "WHERE subjectId IN ($subjectId) AND hasAttendance = 1 AND hasMarks = 1";
     $orderBySubject = " ORDER BY subjectTypeId, subjectCode ";
    // $subjectArray = $studentReportManager->getSingleField("`subject`",$fieldName,$condition.$orderBySubject);
     $subjectArray = $studentReportManager->getAlternateSubjects($subjectId,$classId);  
    
	 
     $subjectCount = count($subjectArray);  
     
     
     // Fetch Student List 
     $studentCondition = " AND c.classId = $classId AND tt.subjectId IN ($subjectId) AND tt.timeTableLabelId='".$timeTableLabelId."'";  
     if($groupId!='all') {
        $studentCondition .= " AND tt.groupId = $groupId ";  
     }

     $totalStudentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
     $totalStudent = count($totalStudentArray);
     $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy,$limit);  
     
     
     
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
    
 
    // Show Headings 
    if(count($studentTestArray)==0 || count($studentArray)==0) {
      $rowspan = "";    
    }
    else {    
      $rowspan = "rowspan='4'";      
    }
    $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                   <tr class='rowheading'>
                     <td width='2%'  class='searchhead_text' $rowspan align='left'><b><nobr>#</nobr></b></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Roll No.</nobr></strong></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Univ. Roll No.</nobr></strong></td>
                     <td width='10%' class='searchhead_text' $rowspan align='left'><strong><nobr>Student Name</nobr></strong></td>"; 
       
      
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
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>".$subjectCode."</nobr></strong></td>"; 
         }
         else {
            if($totalCount<=1) { 
              $colspan="";    
            }
            else {
              $colspan="colspan=$totalCount";    
            }
            $tableHead .= "<td width='5%' class='searchhead_text' $colspan align='center'><strong><nobr>".$subjectCode."</nobr></strong></td>";   
         } 
     }
     $tableHead .= "</tr>";
     
     if(count($studentTestArray)==0) {   
       $totalStudent=0;
       $tableHead .= "<tr><td colspan='20'><center>No Record Found</center></td></tr></table>";
       echo $tableHead.'!~~!'.$totalStudent;  
       die;  
     }
     
     if(count($studentArray)==0) {   
       $totalStudent=0;
       $tableHead .= "<tr><td colspan='20'><center>No Record Found</center></td></tr></table>";
       echo $tableHead.'!~~!'.$totalStudent;  
       die;  
     }
     
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr class='rowheading'>";  
         for($i=0;$i<count($subjectArray);$i++) {
            $subjectId = $subjectArray[$i]['subjectId'];
            $find=0;
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
                  $tableHead .= "<td width='5%' class='searchhead_text' $colspan align='center'><strong><nobr>".$testTypeName."</nobr></strong></td>";   
                  $find=1;
                } 
                if($find==1 && $testTypeArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>aaa".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
             }
         }     
         $tableHead .= "</tr>";
     }
     
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr class='rowheading'>";  
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
                    $tableHead .= "<td width='5%' class='searchhead_text' $colspan align='center'><strong><nobr>".$testName."</nobr></strong></td>";   
                  }
                  else {
                    $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>".$testName."</nobr></strong></td>";     
                  }
                  $find=1;
                } 
                if($find==1 && $testDetailArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>bbb".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
             }
         }     
         $tableHead .= "</tr>";
     }
     
     
     $finalSubject = array();
     
     $colspan=0;
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr class='rowheading'>";  
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
                        $groupType="ccc".NOT_APPLICABLE_STRING;
                     }
                     $finalSubject[$jj]['subjectId']=$subjectId; 
                     $finalSubject[$jj]['groupType']=$groupArr[$k];
                     $finalSubject[$jj]['testName'] =$testDetailArray[$j]['testName'];
                     $finalSubject[$jj]['testTypeName']=$testDetailArray[$j]['testTypeName'];   
                     $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>".$groupType."</nobr></strong></td>";   
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
                $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong><nobr>ddd".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
                $jj++;   
                $colspan++;
             }
         }     
         $tableHead .= "</tr>";
     }       
     $tableData = $tableHead;
     
                 
   // echo "<pre>";
   // print_r($studentTestArray);
   // die; 
     
    $pp=-1;
    // Show Student Test Type Details
    for($i=0; $i< count($studentArray); $i++) {
       $studentId = $studentArray[$i]['studentId']; 
       
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr class='$bg'>
                       <td valign='top' class='padding_top' align='left'>".($records+$i+1)."</td>  
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['universityRollNo']."</td>
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>"; 
       
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
           $tableData .= "<td valign='top' class='padding_top' align='center' >fff".NOT_APPLICABLE_STRING."</td>"; 
         }
       }
       else {
         $cnt2=0; 
         for($ii=0; $ii<count($finalSubject); $ii++) {
            $fSubjectId =$finalSubject[$ii]['subjectId'];
            $groupTypeId = $finalSubject[$ii]['groupType'];
            $testName = $finalSubject[$ii]['testName'];
            $find=0;
            $result="ggg".NOT_APPLICABLE_STRING;
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
               $tableData .= "<td valign='top' class='padding_top' align='center' >".$result."</td>";   
            }
            else {
               $tableData .= "<td valign='top' class='padding_top' align='center' >".NOT_APPLICABLE_STRING."</td>";   
            }
         }
       }
       $tableData .= "</tr>";   
       $pp=$pp+1;
    }

    if(count($studentTestArray)==0) {
       $tableData .= "</table>";      
       echo $tableData.'!~~!'.$totalStudent;
       die;
    }
    else if(count($studentArray)==0) {
       echo "<center>No Record Found</center>".'!~~!'.$totalStudent;
       die;
    }
    else {
       if($pp!=-1) {
         $tableData .= "</table>";      
         echo $tableData.'!~~!'.$totalStudent;
         die;
       }     
    }
    die;
    
// $History: studentInitTestWiseMarksReport.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/08/10    Time: 11:21a
//Updated in $/LeapCC/Library/StudentReports
//pagination added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/05/10    Time: 1:14p
//Updated in $/LeapCC/Library/StudentReports
//query format updated (optional subject code udpated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/02/10    Time: 5:33p
//Updated in $/LeapCC/Library/StudentReports
//table format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/10    Time: 3:25p
//Updated in $/LeapCC/Library/StudentReports
//record limit increase
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:34p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//

?>
