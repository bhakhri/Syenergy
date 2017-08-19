<?php
// This file generate a list Student Test Wise Marks Report Print
//
// Author :Parveen Sharma
// Created on : 05-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

    require_once(BL_PATH . '/ReportManager.inc.php');       
    $reportManager = ReportManager::getInstance();

    
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
    $heading = "<B>Time Table:</b>&nbsp;$timeTableName<br>
                <B>Degree:</b>&nbsp;$className2<br>";
     if($subName!='') {           
       $heading .= "<B>Subject:</B>&nbsp;$subName&nbsp;<br>";
     }
     if($groupName!='') {           
       $heading .= "<B>Group:</B>&nbsp;$groupName<br>";
     }
     if($testTypeCategoryName!='') {
        $heading .= "<B>Test Type:</b>&nbsp;$testTypeCategoryName<br>";
     }
     $heading .= "As On $formattedDate ";
                 
     // Show Headings 
     if(count($studentTestArray)==0 || count($studentArray)==0) {
       $rowspan = "";    
     }
     else {    
       $rowspan = "rowspan='4'";      
     }
     $tableHead = "<table border='1' cellpadding='0' cellspacing='0' width='90%' class='reportTableBorder'  align='center'> 
                    <tr>
                        <td width='2%'  ".$reportManager->getReportDataStyle()."  $rowspan  align='left'><b><nobr>#</nobr></b></td>
                        <td width='5%'  ".$reportManager->getReportDataStyle()."  $rowspan  align='left'><strong><nobr>Roll No.</nobr></strong></td>
                        <td width='5%'  ".$reportManager->getReportDataStyle()."  $rowspan  align='left'><strong><nobr>Univ. Roll No.</nobr></strong></td>
                        <td width='10%' ".$reportManager->getReportDataStyle()."  $rowspan  align='left'><strong><nobr>Student Name</nobr></strong></td>"; 

  
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
            $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".$subjectCode."</nobr></strong></td>"; 
         }
         else {
            if($totalCount<=1) { 
              $colspan="";    
            }
            else {
              $colspan="colspan=$totalCount";    
            }
            $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." $colspan align='center'><strong><nobr>".$subjectCode."</nobr></strong></td>";   
         } 
     }
     $tableHead .= "</tr>";
     
     if(count($studentTestArray)==0) {   
       $tableHead .= "<tr><td colspan='20'><center>No Record Found</center></td></tr></table>";
       reportGenerate($tableHead,$heading);
       die;  
     }
     
     if(count($studentArray)==0) {   
       $tableHead .= "<tr><td colspan='20'><center>No Record Found</center></td></tr></table>";
       reportGenerate($tableHead,$heading);
       die;  
     }
     
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr >";  
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
                  $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." $colspan align='center'><strong><nobr>".$testTypeName."</nobr></strong></td>";   
                  $find=1;
                } 
                if($find==1 && $testTypeArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
             }
         }     
         $tableHead .= "</tr>";
     }
     
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr >";  
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
                    $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." $colspan align='center'><strong><nobr>".$testName."</nobr></strong></td>";   
                  }
                  else {
                    $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".$testName."</nobr></strong></td>";     
                  }
                  $find=1;
                } 
                if($find==1 && $testDetailArray[$j]['subjectId']!=$subjectId) {
                   break; 
                }
             }
             if($find==0) {
                $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
             }
         }     
         $tableHead .= "</tr>";
     }
     
     
     $finalSubject = array();
     
     $colspan=0;
     if(count($testTypeArray)>0) {
         $tableHead .= "<tr >";  
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
                     $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".$groupType."</nobr></strong></td>";   
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
                $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".NOT_APPLICABLE_STRING."</nobr></strong></td>"; 
                $jj++;   
                $colspan++;
             }
         }     
         $tableHead .= "</tr>";
     }       
     $tableData = $tableHead;
     
     
    $pp=-1;
    // Show Student Test Type Details
    for($i=0; $i< count($studentArray); $i++) {
       $studentId = $studentArray[$i]['studentId']; 
       
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr >
                       <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".($records+$i+1)."</td>  
                       <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".$studentArray[$i]['rollNo']."</td>
                       <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".$studentArray[$i]['universityRollNo']."</td>
                       <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".$studentArray[$i]['studentName']."</td>"; 
       
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
           $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='center' >".NOT_APPLICABLE_STRING."</td>"; 
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
               $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='center' >".$result."</td>";   
            }
            else {
               $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='center' >".NOT_APPLICABLE_STRING."</td>";   
            }
         }
       }
       $tableData .= "</tr>";   
       $pp=$pp+1;   
       if($pp==24) {
         $tableData .= "</table>";     
         reportGenerate($tableData,$heading);  
         $tableData = $tableHead;
         $pp=-1;  
       }   
    }
    
    $tableData .= "</table>";     
    reportGenerate($tableData,$heading);  
                     
    // Report generate
    function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Student Test Wise Marks Report');
        $reportManager->setReportInformation($heading);      
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
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
                        <?php echo $value; ?>        
                    </td>
                </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="100%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'> 
        </div>
<?php        
    }
?>

<?php
// $History: studentTestWiseMarksPrint.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/05/10    Time: 1:14p
//Updated in $/LeapCC/Templates/StudentReports
//query format updated (optional subject code udpated)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/02/10    Time: 5:45p
//Updated in $/LeapCC/Templates/StudentReports
//replace test category to test type (update)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/02/10    Time: 5:33p
//Updated in $/LeapCC/Templates/StudentReports
//table format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/10    Time: 4:33p
//Updated in $/LeapCC/Templates/StudentReports
//pageBreak update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:33p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
?>
