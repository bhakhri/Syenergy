<?php 
//  This File contains Student External Marks Reports Print
//
// Author : Aditi Miglani
// Created on : 09 Aug 2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseEvaluationReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/ClassWiseEvaluationReportManager.inc.php");
$classWiseEvaluationReportManager = ClassWiseEvaluationReportManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');       
    $reportManager = ReportManager::getInstance();

    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $classId= $REQUEST_DATA['classId'];
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }

    
    
   
    // Findout Time Table Name
    $timeNameArray = $classWiseEvaluationReportManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;  
    }
   
    // Findout Class Name
    if($classId != '') {   
      $classNameArray = $classWiseEvaluationReportManager->getSingleField('class', 'className', "WHERE classId  = $classId");
      $className = $classNameArray[0]['className'];
    }
    else {
      $className = NOT_APPLICABLE_STRING;      
    }
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $search =$className."<br>Time Table:&nbsp;".$timeTableName."<br>As On $formattedDate ";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';

        
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, universityRollNo)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, studentName)';
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
    
    
    //$condition = " AND stc.classId = '$classId' AND sub.hasMarks = 1 AND sub.hasAttendance =1 ";
    //$orderSubject = " sub.subjectTypeId, sub.subjectCode, sub.subjectId";
    //$recordArray = $classWiseEvaluationReportManager->classwiseSubjects($condition,$orderSubject);
    //$recordCount = count($recordArray);
    
    // Fetch Subject Type Wise List
    $filter= "  DISTINCT c.classId,su.subjectTypeId,st.subjectTypeName AS subjectTypeName, COUNT(DISTINCT st.subjectTypeName) AS cnt, COUNT(DISTINCT su.subjectName) AS cnt1 ";
    $groupBy = "GROUP BY c.classId, su.subjectTypeId ";
    $orderSubjectBy = " classId, subjectTypeId";
    $cond  = " AND su.hasMarks = 1 AND su.hasAttendance =1 AND tt.timeTableLabelId = $timeTableLabelId AND c.classId = $classId";
    $recordArray1 =  $classWiseEvaluationReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
    $recordCount1 = count($recordArray1);

    $tableData = '';
    $tableHead ='';
    $tableHead = "<table width='100%' border='1' cellspacing='1px' cellpadding='0px' class='reportTableBorder'> 
                    <tr>
                      <td width='2%'  ".$reportManager->getReportDataStyle()." ><b>#</b></td>
                      <td width='5%'  ".$reportManager->getReportDataStyle()."  align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'  ".$reportManager->getReportDataStyle()."  align='left'><strong>Roll No.</strong></td>
                      <td width='10%' ".$reportManager->getReportDataStyle()."  align='left'><strong>Student Name</strong></td>";
    
    if($recordCount1 >0) {
       $tableHead = "<table width='100%' border='1' cellspacing='1px' cellpadding='0px' class='reportTableBorder'> 
                    <tr>
                      <td width='2%'  rowspan=2 ".$reportManager->getReportDataStyle()." ><b>#</b></td>
                      <td width='5%'  rowspan=2 ".$reportManager->getReportDataStyle()."  align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='5%'  rowspan=2 ".$reportManager->getReportDataStyle()."  align='left'><strong>Roll No.</strong></td>
                      <td width='10%' rowspan=2 ".$reportManager->getReportDataStyle()."  align='left'><strong>Student Name</strong></td>";
        for($i=0; $i<$recordCount1; $i++) {    
          if($recordArray1[$i]['cnt1']>=2) {
            $colspanval = " colspan=".$recordArray1[$i]['cnt1'];  
          }
          else {
            $colspanval = "";     
          }
          $tableHead .= "<td width='5%' ".$colspanval." ".$reportManager->getReportDataStyle()."  align='center'><strong><nobr>".$recordArray1[$i]['subjectTypeName']."</nobr></strong></td>";  
        } 
        $tableHead .= "<td width='5%' rowspan='2' ".$reportManager->getReportDataStyle()."  align='right'>
                         <strong>Total</strong></td>";
    }
    $tableHead .= "</tr>";
                  
    
    $cnt = 0;
    $colSpanCount = 4;     
    // Fetch Subject Name
    $filter1 = "";
    $filter= " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = " classId, subjectTypeId, subjectCode";
    
    
    $recordArray =  $classWiseEvaluationReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy,  $orderSubjectBy );   
    $recordCount = count($recordArray); 
    if($recordCount > 0 ) {
        $tableHead .= "<tr>";
          for($i=0; $i<$recordCount; $i++) {  
              $subjectId = $recordArray[$i]['subjectId']; 
              $tableHead .= "<td width='5%' ".$reportManager->getReportDataStyle()."  align='right'><strong><nobr>".strip_slashes($recordArray[$i]['subjectCode'])."</nobr></strong></td>";
              $colSpanCount = $colSpanCount + 1;
           }
        $tableHead .= "</tr>";   
        
        $condition = " AND sg.classId = '$classId' ";
        $studentArray2 = $classWiseEvaluationReportManager->getStudentExternalMarks($condition,$orderBy);  
        $cnt = count($studentArray2);       
        
        $condition = " AND ttm.classId = '$classId' ";      
        $findMarksArray = $classWiseEvaluationReportManager->getStudentTotalExternalMarks($condition);  
        $findMarksTotal = count($findMarksArray);       
    }
    
    $tableData .= $tableHead;
    
    if($cnt > 0) {
      $scount=0;
      for($s=0; $s<$cnt; $s++) {      
           $studentId  = $studentArray2[$s]['studentId'];
           if($scount==25) {
             $tableData .= "</table>";  
             reportGenerate($tableData,$search); 
             $tableData ='';
             $tableData .= $tableHead;   
             $scount=0;
           }           
           $tableData .= "<tr>
                             <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".($records+$s+1)."</td>  
                             <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$s]['universityRollNo'])."</td>
                             <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$s]['rollNo'])."</td>    
                             <td valign='top' ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($studentArray2[$s]['studentName'])."</td>";    
                             
              $find=0;               
             // Fetch Subject Information     
             for($k=0;$k<$findMarksTotal; $k++) {
                if($findMarksArray[$k]['studentId']==$studentId) {
                  $find=1;
                  break; 
                }  
             }
             if($find==1) {
                 $total=0; 
                 for($i=0; $i<$recordCount; $i++) {
                    $subjectId = $recordArray[$i]['subjectId'];
                    $tstudentId = $findMarksArray[$k]['studentId'];  
                    $tsubjectId = $findMarksArray[$k]['subjectId'];
                    if($tstudentId==$studentId) {  
                       if($subjectId==$tsubjectId) {
                         $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($findMarksArray[$k]['marksScored'])."</td>";    
                         $total=$total+$findMarksArray[$k]['marksScored']; 
                         $k++;   
                       }
                       else {
                         $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";    
                       }
                    }
                    else {
                      $tableData .= "<td valign='top' class='padding_top' align='right'>".NOT_APPLICABLE_STRING."</td>";    
                    }
                 }
                 if($find==1) {
                    $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='right'>".$total."</td>";      
                 }
                 else {
                    $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='right'>".NOT_APPLICABLE_STRING."</td>";        
                 }
             }
             else {
               for($i=0; $i<$recordCount; $i++) {  
                  $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='right'>".NOT_APPLICABLE_STRING."</td>";      
               }  
               if($recordCount>0) {
                 $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()." align='right'>".NOT_APPLICABLE_STRING."</td>";      
               }
             }
          $scount = $scount +1;
          $tableData .= "</tr>";
       }
       
       if($scount!=0) {
         $tableData .= "</table>";  
         reportGenerate($tableData,$search);  
       }
       die;
   }
   else {
     $tableData .= "<tr><td colspan=$colSpanCount><center>No Data Found</center></td></tr>";  
   }
   $tableData .= "</table>";
   
   reportGenerate($tableData,$search);  
   die;

  // Report generate
  function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(780);
        $reportManager->setReportHeading('Student External Marks Report');
        $reportManager->setReportInformation("$heading");     
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
    }
?>

