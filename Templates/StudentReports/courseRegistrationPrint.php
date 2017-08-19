<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    set_time_limit(0);
    //ini_set('MEMORY_LIMIT','100M');
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
   
    require_once(BL_PATH . '/ReportManager.inc.php');       
    $reportManager = ReportManager::getInstance();

 
    $studentManager = StudentManager::getInstance();
     
    $classId = trim($REQUEST_DATA['classId']);
    $termClassId = trim($REQUEST_DATA['termClassId']);
    $termClassId1 = trim($REQUEST_DATA['termClassId1']);  
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $incAll = trim($REQUEST_DATA['incAll']); 
       
    if($classId=='') {
      $classId=0;  
    }
    
    if($termClassId=='') {
      $termClassId=0;  
    }
    
    if($incAll=='') {
      $incAll=0;  
    }
    
    $termClassIdArr=explode(',',$termClassId);  
    
    if($subjectId=='') {
      $subjectId=-1;  
    }
    
    $blankSymbol='X';
    
 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
    
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    else if ($sortField == 'cgpa') {
        $sortField1 = 'IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED))';
    }
    else if ($sortField == 'cgpa1') {
        $sortField1 = 'IF(registrationDate="0000-00-00 00:00:00",-s.studentId, registrationDate), CAST(cgpa AS UNSIGNED)';
    }
    else if ($sortField == 'date1') {
        $sortField1 = 'IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED)), registrationDate ';
    }
    else if ($sortField == 'cgpa2') {
        $sortField1 = 'confirmId, IF(registrationDate="0000-00-00 00:00:00",-s.studentId, registrationDate), CAST(cgpa AS UNSIGNED)';
    }
    else if ($sortField == 'date2') {
        $sortField1 = 'confirmId, IF(IFNULL(cgpa,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",-s.studentId, CAST(cgpa AS UNSIGNED)), registrationDate ';
    }
    else if ($sortField == 'confirmId') {
        $sortField1 = 'confirmId';
    }
    else {
       $sortField == 'universityRollNo';
       $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    $orderBy = " $sortField1 $sortOrderBy"; 
    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
    
    $search='';
    // Findout Class Name
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);
    
    // Findout Class Name
    //$classNameArray = $studentManager->getSingleField('class c, study_period sp', 'periodValue', "WHERE c.studyPeriodId=sp.studyPeriodId AND classId  = $termClassId");
    //$periodValue = $classNameArray[0]['periodValue'];
    //$periodValue2 = "Term-".UtilityManager::romanNumerals($periodValue);
    
    $search='Class:&nbsp;'.$className2."<br>As On $formattedDate";  
    
    $totalPages =0; 
    $subjType[0]='Career';
    $subjType[1]='Elective';
    $orderBySubject="sub.subjectId, sub.subjectCode ASC";
    $filter="DISTINCT sub.subjectCode, sub.subjectName, sub.subjectId, d.classId";
    
    $chkCount=0;      
    for($i=0;$i<count($termClassIdArr);$i++) {   
        $termClassId = $termClassIdArr[$i];
        
        $orderBySubject = "d.classId, sub.subjectId, sub.subjectCode ASC";   
        $condition = " AND m.currentClassId = $classId AND d.classId=$termClassId AND d.subjectType='".$subjType[0]."'";
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $recordArray1[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject,$filter);
        $recordCount1[$i] = count($recordArray1[$i]);
        
        
        
        $condition = " AND m.currentClassId = $classId AND d.classId=$termClassId AND d.subjectType='".$subjType[1]."'";
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId) ";  
        }
        $recordArray2[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject,$filter);
        $recordCount2[$i] = count($recordArray2[$i]); 
         
        $cnt[$i]=$recordCount1[$i]+$recordCount2[$i];
        if($recordCount1[$i]==0) {
          //$cnt[$i]++;
        }
        if($recordCount2[$i]==0) {
          //$cnt[$i]++;
        }
        
        $chkCount=$chkCount+$cnt[$i];
        
        $orderBySubject = "m.studentId, sub.subjectId, sub.subjectCode ASC";    
        $condition = " AND m.currentClassId=$classId AND d.subjectType='".$subjType[0]."' AND d.classId=$termClassId"; 
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $registrationArray1[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject);   
    
        $condition = " AND m.currentClassId=$classId AND d.subjectType='".$subjType[1]."' AND d.classId=$termClassId"; 
        if($subjectId!=-1) {
          $condition .= " AND d.subjectId IN ($subjectId)";  
        }
        $registrationArray2[$i] = $studentManager->getStudentRegistration($condition,$orderBySubject);   
    }
    
    // Fetch Student List 
    $studentCondition = " AND c.classId = $classId ";  
    if($incAll==0) {
      $studentCondition .= " AND m.studentId = s.studentId";    
    }
    
    
    $conditionClassId = '';   
    if($subjectId!=-1) {
       $conditionClassId  = " AND d.subjectId IN ($subjectId) ";  
       $sCondition = " AND d.subjectId IN ($subjectId)"; 
    } 
    
    $ssCondition = $studentCondition.$sCondition;
    
    $ttStudentId =0;
    
    $orderStudentBy =  $orderBy;
    
    $studentArray4 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
    
    for($i=0;$i<count($studentArray4);$i++) {
      $ttStudentId .=",".$studentArray4[$i]['studentId'];  
    }
    
    $totalStudent  = count($studentArray4);
    $studentArray2 = array_merge($studentArray4);
    
    $conditionClassId='';
    if($subjectId!=-1 && $incAll==1) {
       //$conditionClassId  = " AND d.subjectId IN ($subjectId) ";  
       $sCondition = " AND s.studentId NOT IN (".$ttStudentId.")";  
    
       $ssCondition = $studentCondition.$sCondition; 
    
       $orderStudentBy =  $orderBy;
       $studentArray5 =  $studentManager->getRegistrationStudentList($ssCondition,$orderStudentBy,'',$conditionClassId);  
       
       $totalStudent  = count($studentArray4) + count($studentArray5);
       
    }

   if(count($studentArray4)>0) {
     $studentArray2 = array_merge($studentArray4);
   }    
   else if(count($studentArray5)>0) {
     $studentArray2 = array_merge($studentArray5);
   } 
   
   if(count($studentArray4)>0 && count($studentArray5)>0) {
     $studentArray2 = array_merge($studentArray4,$studentArray5);
   }  
   
    
    $tableHead = "<table border='1' cellpadding='0' cellspacing='0' width='100%' class='reportTableBorder'  align='center'>
                    <tr >
                      <td width='2%'    style='padding-left:2px' ".$reportManager->getReportDataStyle()." ><b>#</b></td>
                      <td width='5%'    style='padding-left:2px' ".$reportManager->getReportDataStyle()." align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'   style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'><strong>Roll No.</strong></td>
                      <td width='10%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'><strong>Student Name</strong></td>
                      <td width='10%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='center'><strong>Reg. Date</strong></td>
                      <td width='10%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='center'><strong>Confirm</strong></td>
                      <td width='10%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()." align='left'><strong>Major Concentration</strong></td>
                      <td width='4%'   style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'><strong>CGPA</strong></td>
                      <td width='4%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  align='left'><strong>Career</strong></td> 
                      <td width='4%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  align='left'><strong>Elective</strong></td> 
                    </tr>";
    
    
    if($chkCount==0) {
       $totalPages =0; 
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $colSpanCount=8;  
       $tableHead .= "<tr><td ".$reportManager->getReportDataStyle()." colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       reportGenerate($tableHead,$search);  
       die;
    }  
    
    
    
    
    if($chkCount >0) {
       $tableHead = "<table border='1' cellpadding='0' cellspacing='0' width='100%' class='reportTableBorder'  align='center'>
                    <tr >
                      <td width='2%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' ><b>#</b></td>
                      <td width='5%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'   style='padding-left:2px'  ".$reportManager->getReportDataStyle()." rowspan='3' align='left'><strong>Roll No.</strong></td>
                      <td width='10%'  style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' align='left'><strong>Student Name</strong></td>
                      <td width='10%'  style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' align='center'><strong>Reg. Date</strong></td>
                      <td width='10%'  style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' align='center'><strong>Confirm</strong></td>
                      <td width='8%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  rowspan='3' align='left'><strong>Major Concentration</strong></td>
                      <td width='4%'   style='padding-right:2px' ".$reportManager->getReportDataStyle()." rowspan='3' align='right'><strong>CGPA</strong></td>";
           for($i=0;$i<count($termClassIdArr);$i++) {
              $colspan='';
              if($cnt[$i]>=2) {
                $colspan="colspan=".$cnt[$i];  
              } 
              $colspan1[$i]='';
              $colspan2[$i]='';
              if($recordCount1[$i]>1) {
                $colspan1[$i]="colspan=$recordCount1[$i]"; 
              }
              if($recordCount2[$i]>1) {
                $colspan2[$i]="colspan=$recordCount2[$i]"; 
              }                   
              $termClassName = "Term-".UtilityManager::romanNumerals($i+4); 
              if(($recordCount1[$i]+ $recordCount2[$i])>0 ) {
                 $tableHead .= "<td width='4%'   style='padding-left:2px' ".$reportManager->getReportDataStyle()."  align='center' $colspan ><strong>$termClassName</strong></td>";
              }
           }
           $tableHead .= "</tr>";
           
           
           $tableData .= "<tr >";
           for($i=0;$i<count($termClassIdArr);$i++) {  
              if($recordCount1[$i]!=0) {
                 $tableHead .= "<td width='4%' ".$reportManager->getReportDataStyle()." $colspan1[$i] align='center'><strong>Career</strong></td>";
              }
              if($recordCount2[$i]!=0) {
                 $tableHead .= "<td width='4%' ".$reportManager->getReportDataStyle()." $colspan2[$i] align='center'><strong>Elective</strong></td>";
              }
           }
           $tableHead .= "</tr>";
          
           $chkStudent=0;
           $tableHead .= "<tr >";
           for($i=0;$i<count($termClassIdArr);$i++) {   
              
              if($recordCount1[$i]!=0) {      
                $tableHead .= courseList($recordCount1[$i],$recordArray1[$i]);        // Carrer Course
              }
              
              if($recordCount2[$i]!=0) {   
                $tableHead .= courseList($recordCount2[$i],$recordArray2[$i]);        // Elective Course
              }
              
              if(($recordCount1[$i]!=0 || $recordCount2[$i]!=0) && $temp==0) {
                $chkStudent=1; 
              }
           }
           $tableHead .= "</tr>"; 
    }
    
       
    
    if(count($studentArray2)==0) {
       $totalPages =0; 
       $colSpanCount=100;  
       $tableHead .= "<tr><td ".$reportManager->getReportDataStyle()." colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       reportGenerate($tableHead,$search);  
       die;
    }
    
    if($chkStudent==0) {
       $totalPages =0;   
       $colSpanCount=100;  
       $tableHead .= "<tr><td ".$reportManager->getReportDataStyle()." colspan='$colSpanCount' align='center'>No Data Found</td></tr></table>"; 
       reportGenerate($tableHead,$search);       
       die;
    }
    
    
    $totalPages = ceil($totalStudent/30);    
    $tableData ='';
    if($totalPages>0) {
      $pageCounter=1;
    }
     
    $cj=0; 
    for($i=0;$i<count($studentArray2);$i++) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
       $studentId = $studentArray2[$i]['studentId'];
       $cCurrentClassId = $studentArray2[$i]['classId'];
       
       $cgpa = $studentArray2[$i]['cgpa'];
       if($cgpa!=NOT_APPLICABLE_STRING) {
         $cgpa = number_format($studentArray2[$i]['cgpa'],2,'.','');         
       }
       
       if($tableData=='') {
         $tableData .=$tableHead;  
       }
       
       $confirmId = $studentArray2[$i]['confirmId'];
       if($studentArray2[$i]['registrationDate']=='0000-00-00 00:00:00') {
          $regDate = NOT_APPLICABLE_STRING; 
       }
       else {
          $regDate = date('d-M-Y h:i:s A', strtotime($studentArray2[$i]['registrationDate'])); 
       }
       
       $tableData .= "<tr>
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".($records+$i+1)."</td>  
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$i]['universityRollNo'])."</td>
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$i]['rollNo'])."</td>    
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$i]['studentName'])."</td>
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='center'>".strip_slashes($regDate)."</td>
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='center'>".strip_slashes($confirmId)."</td>
                         <td valign='top'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$i]['majorConcentration'])."</td>
                         <td valign='top'  style='padding-right:2px'  ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($cgpa)."</td>"; 
      
      
      $registrationId=-1;
      for($cj=0;$cj<count($termClassIdArr);$cj++) { 
           // Student Findout
           $findStudentId = "-1";
           
           if(count($registrationArray1[$cj])>0) {
             for($j=0; $j < count($registrationArray1[$cj]); $j++) { 
               if($registrationArray1[$cj][$j]['studentId']==$studentId && $registrationArray1[$cj][$j]['classId']==$termClassIdArr[$cj]) {  
                 $findStudentId = $j; 
                 $registrationId  = $registrationArray1[$cj][$j]['registrationId'];
                 break;
               }
             }
           }
           
           
           $cnt=count($recordArray1[$cj]);
           
           if($cnt==0) {
              //$tableData .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
              $tableData .= studentSubjectList($cnt,$recordArray1[$cj],$registrationArray1[$cj],$studentId,$findStudentId,$subjType[0],$termClassIdArr[$cj]);
           }
           
            // Student Findout
           $findStudentId = "-1";
           if(count($registrationArray2[$cj])>0) {
             for($j=0; $j < count($registrationArray2[$cj]); $j++) { 
               if($registrationArray2[$cj][$j]['studentId']==$studentId && $registrationArray2[$cj][$j]['classId']==$termClassIdArr[$cj]) {  
                 $findStudentId = $j; 
                 $registrationId  = $registrationArray2[$cj][$j]['registrationId'];  
                 break;
               }
             }
           }
           
           
           $cnt=count($recordArray2[$cj]);
           if($cnt==0) {
             //$tableData .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>".checkBlank($cnt)."</td>"; 
           }
           else {
               $tableData .= studentSubjectList($cnt,$recordArray2[$cj],$registrationArray2[$cj],$studentId,$findStudentId,$subjType[1],$termClassIdArr[$cj]);
           }
       }
       
       $tableData .= "<tr>";                  
       
       if(($i+1)%30==0) {
         $tableData .= "</table>"; 
         reportGenerate($tableData,$search);
         $tableData='';  
       }
    }

    if($tableData!='') {
      $tableData .= "</table>";
      reportGenerate($tableData,$search);
    }
    die;   
    
    
    function studentSubjectList($cnt,$subjectArray,$registrationArray,$studentId,$findId,$subjectType,$classId) {

        global $reportManager;
        global $blankSymbol;
        global $subjType;
        
        $result='';
        
        if($findId==-1) {
           for($j=0;$j<$cnt;$j++) {
             $result .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>0</td>";  
           }
        }
        else {
           $tFindId=$findId;
           for($j=0; $j<$cnt; $j++) {
              $subjectId = $subjectArray[$j]['subjectId'];
              $tStudentId = $registrationArray[$tFindId]['studentId'];
              $tSubjectType = $registrationArray[$tFindId]['subjectType'];
              $tSubjectId = $registrationArray[$tFindId]['subjectId'];
              $tClassIds = $registrationArray[$tFindId]['classId']; 
              if($tStudentId==$studentId && $tSubjectType==$subjectType && $tSubjectId==$subjectId && $tClassIds==$classId) {
                 $result .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>".$registrationArray[$tFindId]['credits']."</td>";  
                 $tFindId++;
              }
              else {
                 $result .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'>0</td>";    
              }
           }
        }
        return $result;
    }
    
    
    function checkBlank($num) {
        
        global $reportManager;
        global $blankSymbol;
        
        $result1='';
        if($num==0) {  
          $result1 = $blankSymbol;  
        }
        else {
          $result1 = 0;   
        }
        return $result1;
    }
    
    
    function courseList($cnt,$recordArray) {
        
        global $reportManager;
        global $blankSymbol;
        $result1='';
        if($cnt==0) {
          $result1 .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr>".$blankSymbol."</nobr></strong></td>";    
        }
        else {
            for($j=0; $j<$cnt; $j++) {
               $show=strip_slashes($recordArray[$j]['subjectName']);      
               $result1 .= "<td width='4%' ".$reportManager->getReportDataStyle()." align='center'><strong><nobr><span title='".$show."'>".strip_slashes($recordArray[$j]['subjectCode'])."</span></nobr></strong></td>";  
            }
        }
        return $result1;
    }
  
    
    // Report generate
    function reportGenerate($value,$heading) {
        
        global $reportManager;
        global $pageCounter;
        global $totalStudent;
        global $totalPages;
        
                          
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(780);
        $reportManager->setReportHeading('Courses Registration Report');
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
            <table border='0' cellspacing='0' width="100%" class="reportTableBorder"  align="center">
                <tr>
                    <td valign="top">
                        <?php echo $value; ?>        
                    </td>
                </tr> 
            </table>       
            <br>
            <?php
              if($totalPages!=0 ) {
            ?>
                <table border='0' cellspacing='0' cellpadding='0' width="100%">
                    <tr>
                        <td valign='' align="left"  <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                        <td valign='' align="right" <?php echo $reportManager->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                    </tr>
                </table>
                <br class='page'>
                <?php
                 $pageCounter++;
              }
              else {
            ?>
            <table border='0' cellspacing='0' cellpadding='0' width="100%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <?php
              }
            ?>
            <br class='page'> 
        </div>
<?php        
    }
?>
    
?>