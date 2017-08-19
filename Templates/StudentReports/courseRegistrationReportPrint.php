<?php 
//This file is used as printing version for Course Registration Report 
//
// Author : Parveen Sharma
// Created on : 20-10-2008
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
    UtilityManager::ifNotLoggedIn(true);
    
    require_once(MODEL_PATH.'/CommonQueryManager.inc.php');
    $commonQueryManager = CommonQueryManager::getInstance();    
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    global $sessionHandler;
      
   
    $registrationId = add_slashes(trim($REQUEST_DATA['rid'])); 
    $studentId = add_slashes(trim($REQUEST_DATA['sid']));
    $currentClassId = add_slashes(trim($REQUEST_DATA['cid']));    
    
    if($registrationId=='') {
      $registrationId=0;  
    }
    
    if($studentId=='') {
      $studentId=0;  
    }
    
    if($currentClassId=='') {
      $currentClassId=0;  
    }
    
    
    $rptHead ='Student Course Registration Report';
    if($registrationId==0 && $studentId==0 &&  $currentClassId==0) {
        
        $rptHead ='Student Course Registration Form'; 
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
        $search = '';
        $search1 = "<table border='0' cellpadding='0px' cellspacing='2px' width='22%' class=''  align='center'>
                      <tr>
                          <td valign='middle' width='7%' ".$reportManager->getReportDataStyle().">Registration Date</td>
                          <td valign='middle' width='13%' ".$reportManager->getReportDataStyle().">&nbsp;:&nbsp</td>
                      </tr>
                      <tr>
                          <td valign='middle' width='7%' ".$reportManager->getReportDataStyle().">Confirm</td>
                          <td valign='middle' width='13%' ".$reportManager->getReportDataStyle().">&nbsp;:&nbsp</td>
                      </tr>
                      <tr>
                          <td valign='middle'  ".$reportManager->getReportDataStyle().">Class</td>
                          <td valign='middle'  ".$reportManager->getReportDataStyle().">&nbsp;:&nbsp</td> 
                      </tr>
                      <tr>
                          <td valign='middle' align='center' colspan='2' ".$reportManager->getReportDataStyle().">As On $formattedDate</td>
                      </tr>
                   </table>";
        
        $tableHead = "<table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'> 
                         <tr>
                            <td width='28%' valign='middle'  ".$reportManager->getReportDataStyle()."><b>Student Name</b></td>
                            <td width='2%'  valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td width='20%' valign='middle'  ".$reportManager->getReportDataStyle()."  ></td>
                            <td width='15%' valign='middle'  ".$reportManager->getReportDataStyle()."><b>Roll No.</b></td>
                            <td width='2%'  valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td width='31%' valign='middle'  ".$reportManager->getReportDataStyle()."></td>
                         </tr>
                         <tr>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Univ. Roll No.</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."  ></td>
                         </tr>
                         <tr>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Cumulative Grade Point Average (CGPA)</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."  ></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Major Concentration</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."></td>
                         </tr>
                  </table><br>";
        for($i=0; $i<3;$i++) {
           $tableHead .= "<br>
                         <table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>
                             <tr>
                               <td valign='middle' colspan='3' ".$reportManager->getReportDataStyle()."><b>Term-".UtilityManager::romanNumerals($i+4)."<td>
                             </tr>
                             <tr>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseListBlank('Career')."</td>
                               <td valign='top' width='2%' ".$reportManager->getReportDataStyle().">&nbsp;</td>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseListBlank('Elective')."</td>
                             </tr>
                        </table>";  
        }
        $search = $search1.$search2.$search4;  
        reportGenerate($tableHead,$search); 
        die; 
    }
    
    
    
    $classNameArray = $studentManager->getSingleField('class', 'className AS className', "WHERE classId  = $currentClassId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);
    if($className2=='') {
      $className2 = NOT_APPLICABLE_STRING;  
    }
    
    
    $condition = " AND c.classId = ".$currentClassId;
    $foundArray = $commonQueryManager->getDegreeName($condition);
    $recordCount = 0; 
    $condition='';
    $condition = " AND c.branchId = '".$foundArray[0]['branchId']."' AND c.batchId = '".$foundArray[0]['batchId']."'";
    $degreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE'); 
    if($foundArray[0]['degreeCode']!=$degreeCode) {
      $recordCount = 0;
    }
    else {
      $foundArray = $commonQueryManager->getRegistrationDegreeList($condition);
      $condition = " AND m.studentId = $studentId AND m.currentClassId=$currentClassId "; 
      $recordArray = $studentManager->getStudentRegistration($condition);   
      $studentName = $recordArray[0]['studentName']; 
    }
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);      
    $search = '';
    $search1 .= "Class&nbsp;:&nbsp; $className2<br>";
    $search2 .= "";
    $search4 .= "As On $formattedDate ";
    
    
    if(count($recordArray)==0) {
      $search = $search1.$search2.$search4;    
      reportGenerate("<div ".$reportManager->getReportDataStyle()." align='center'><br>No Data Found</div>",$search);
      die;
    }
    
    $careerCount=0; 
    $electiveCount=0;
    
    if(count($recordArray)>0) {
      $cgpa = strip_slashes(number_format($recordArray[0]['cgpa'],2,'.',''));
      if($recordArray[0]['majorConcentration']=='') {
         $major = NOT_APPLICABLE_STRING; 
      }
      else { 
        $major = $recordArray[0]['majorConcentration'];
      }
      $rollNo = $recordArray[0]['rollNo']; 
      $univRollNo = $recordArray[0]['universityRollNo'];    
      $rollNo = $recordArray[0]['rollNo'];
      $confirmId = $recordArray[0]['confirmId'];   
      if($recordArray[0]['registrationDate']=='0000-00-00 00:00:00') {
         $regDate = NOT_APPLICABLE_STRING; 
      }
      else {
        $regDate = date('d-M-Y h:i:s A', strtotime($recordArray[0]['registrationDate'])); 
      }
       
      $search2 = "Registration Date&nbsp;:&nbsp;$regDate<br>
                  Confirm&nbsp;:&nbsp;$confirmId<br>";
      
      $tableHead = "<table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'> 
                         <tr>
                            <td width='28%' valign='middle'  ".$reportManager->getReportDataStyle()."><b>Student Name</b></td>
                            <td width='2%'  valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td width='20%' valign='middle'  ".$reportManager->getReportDataStyle()."  >$studentName</td>
                            <td width='15%' valign='middle'  ".$reportManager->getReportDataStyle()."><b>Roll No.</b></td>
                            <td width='2%'  valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td width='31%' valign='middle'  ".$reportManager->getReportDataStyle().">$rollNo</td>
                         </tr>
                         <tr>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Univ. Roll No.</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."  >$univRollNo</td>
                         </tr>
                         <tr>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Cumulative Grade Point Average (CGPA)</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."  >$cgpa</td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>Major Concentration</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle()."><b>&nbsp;:&nbsp;</b></td>
                            <td valign='middle'  ".$reportManager->getReportDataStyle().">$major</td>
                         </tr>
                  </table><br>";
                  
                
        for($i=0;$i<count($foundArray);$i++) {                                      
           $ttClassId = $foundArray[$i]['classId'];                                                        
           $tableData .= "<br>
                         <table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>
                             <tr>
                               <td valign='middle' colspan='3' ".$reportManager->getReportDataStyle()."><b>Term-".UtilityManager::romanNumerals($i+4)."<td>
                             </tr>
                             <tr>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseList('Career',$ttClassId,$recordArray)."</td>
                               <td valign='top' width='2%' ".$reportManager->getReportDataStyle().">&nbsp;</td>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseList('Elective',$ttClassId,$recordArray)."</td>
                             </tr>
                        </table>";   
           if(($i+1)%2==0) {
             $search = $search1.$search2.$search4;    
             reportGenerate($tableHead.$tableData,$search);  
             $tableData=''; 
           }            
        }
        $tableData .= "<table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>
                             <tr>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseTotal('Career',$careerCount)."</td>
                               <td valign='top' width='2%' ".$reportManager->getReportDataStyle().">&nbsp;</td>
                               <td valign='top' width='49%' ".$reportManager->getReportDataStyle().">".courseTotal('Elective',$electiveCount)."</td>
                             </tr>
                        </table>"; 
        $search = $search1.$search2.$search4;                  
        reportGenerate($tableHead.$tableData.$tableTotal,$search);
    }
  
    die; 
     
     

    function courseList($courseType,$studPeriod,$recordArray) {
       global $reportManager;  
       
       global $electiveCount;
       global $careerCount;
       
       $result=''; 
       if(count($recordArray)>0) {
           $result .= "<table border='1' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>  
                        <tr>
                           <td valign='middle' align='left'   style='padding-left:2px' rowspan='2' width='10%' ".$reportManager->getReportDataStyle()."><b>Sr. No.</b></td>  
                           <td valign='middle' align='center' colspan='2' width='76%' ".$reportManager->getReportDataStyle()."><b>$courseType Courses</b></td>  
                           <td valign='middle' align='right'  style='padding-left:2px' rowspan='2' width='14%' ".$reportManager->getReportDataStyle()."><b>Credits</b></td>
                         </tr>  
                         <tr>
                            <td valign='middle' align='left'  style='padding-left:2px' width='26%' ".$reportManager->getReportDataStyle()."><b>Course Code</b></td>
                            <td valign='middle' align='left'  style='padding-left:2px' width='50%' ".$reportManager->getReportDataStyle()."><b>Course Name</b></td>
                         </tr>";
           $find=0;
           $cnt=count($recordArray);
           $x=0;
           for($ii=0;$ii<$cnt;$ii++) {
              $subjectCode = $recordArray[$ii]['subjectCode'];
              $subjectName = $recordArray[$ii]['subjectName'];
              $credit = $recordArray[$ii]['credits']; 
              if($recordArray[$ii]['subjectType']==$courseType && $recordArray[$ii]['classId']==$studPeriod) {
                  $result .= "<tr>
                                 <td valign='middle' align='left'  style='padding-left:2px' width='10%' ".$reportManager->getReportDataStyle().">".($x+1)."</td>  
                                 <td valign='middle' align='left'  style='padding-left:2px' width='26%' ".$reportManager->getReportDataStyle().">".$subjectCode."</td>  
                                 <td valign='middle' align='left'  style='padding-left:2px' width='50%' ".$reportManager->getReportDataStyle().">".$subjectName."</td>
                                 <td valign='middle' align='right' style='padding-right:2px' width='14%' ".$reportManager->getReportDataStyle().">".$credit."</td>
                             </tr>";    
                  $find=1;   
                  if($courseType=='Career') {
                    $careerCount+=$credit;
                  }
                  else {
                    $electiveCount+=$credit;  
                  }
                  $x++;
              }
              else if($find==1 && $recordArray[$ii]['subjectType']!=$courseType && $recordArray[$ii]['classId']!=$studPeriod) {
                 break; 
              }
           }
           if($find==0) {
              $result .= "<tr>
                             <td valign='middle' align='center' height='35px' style='padding-left:2px' colspan='4' ".$reportManager->getReportDataStyle().">No Data Found</td>
                          </td>";
           }                                                 
           $result .="</table>";
       }
       return $result;
    } 
    
    
    function courseTotal($courseType,$tot) {
       global $reportManager;  
       
       $result .= "<table border='0' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>
                    <tr>
                     <td valign='middle' align='right'  style='padding-left:2px' width='10%' ".$reportManager->getReportDataStyle().">&nbsp;</td>  
                     <td valign='middle' align='right' colspan='2' style='padding-right:2px' ".$reportManager->getReportDataStyle().">
                        <b>$courseType Courses - Total Credits&nbsp;:&nbsp;</b>&nbsp;&nbsp;".$tot."</td>  
                 </tr></table>";    
       return $result;
    } 
    
     
    function courseListBlank($courseType) {
        global $reportManager;       
         
        $result .= "<table border='1' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'>  
                        <tr>
                           <td valign='middle' align='left'   style='padding-left:2px' rowspan='2' width='10%' ".$reportManager->getReportDataStyle()."><b>Sr. No.</b></td>  
                           <td valign='middle' align='center' colspan='2' width='76%' ".$reportManager->getReportDataStyle()."><b>$courseType Courses</b></td>  
                           <td valign='middle' align='right'  style='padding-left:2px' rowspan='2' width='14%' ".$reportManager->getReportDataStyle()."><b>Credits</b></td>
                         </tr>  
                         <tr>
                            <td valign='middle' align='left'  style='padding-left:2px' width='26%' ".$reportManager->getReportDataStyle()."><b>Course Code</b></td>
                            <td valign='middle' align='left'  style='padding-left:2px' width='50%' ".$reportManager->getReportDataStyle()."><b>Course Name</b></td>
                         </tr>";
        for($ccol=0;$ccol<10;$ccol++) {
           $result .= "<tr>
                          <td valign='middle' align='left'  style='padding-left:2px' width='10%' ".$reportManager->getReportDataStyle().">".($ccol+1)."</td>  
                          <td valign='middle' align='left'  style='padding-left:2px' width='26%' ".$reportManager->getReportDataStyle().">&nbsp;</td>  
                          <td valign='middle' align='left'  style='padding-left:2px' width='50%' ".$reportManager->getReportDataStyle().">&nbsp;</td>
                          <td valign='middle' align='right' style='padding-right:2px' width='14%' ".$reportManager->getReportDataStyle().">&nbsp;</td>
                       </tr>";  
        }                 
        $result .= "</table>";  
        return $result;                  
    }  
     
     
    // Report generate
    function reportGenerate($value,$heading) {
        global $reportManager;  
        
        global $rptHead;
        
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(780);
        $reportManager->setReportHeading($rptHead);
        $reportManager->setReportInformation("$heading");     
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="98%" align="center">
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
<?php   
// $History: courseRegistrationReportPrint.php $

?>