<?php
//-------------------------------------------------------
// Purpose: To generate topic taught list for subject centric Print
//
// Author :Parveen Sharma
// Created on : 02-06-2009
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    define('MODULE','COMMON');
    define('ACCESS','view');
   UtilityManager::ifNotLoggedIn(true);

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeManager = EmployeeReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    global $sessionHandler;   

    function parseOutput($data){
        return ((trim($data)!="" ? $data : NOT_APPLICABLE_STRING) );  
    }   
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';

    if($sortField == 'undefined') {
       $sortOrderBy = 'ASC';
       $sortField = 'fromDate';  
    }
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";  
    
    $conditions = "";

    $timeTableLabelId   = add_slashes($REQUEST_DATA['labelId']);    
    $employeeId   = add_slashes($REQUEST_DATA['employeeId']);
    $classId      = add_slashes($REQUEST_DATA['classId']);
    $subjectId    = add_slashes($REQUEST_DATA['subject']);
    $groupId      = add_slashes($REQUEST_DATA['group']);
    $subjectTopic = add_slashes($REQUEST_DATA['subjectTopic']);
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    
    if($classId=='') {
      $classId =0;   
    }
    
    if($subjectId=='') {
      $subjectId =0;   
    }
    
    if($groupId=='') {
      $groupId =0;   
    }
    
    if($subjectTopic=='') {
      $subjectTopic =0;   
    }
    
    // Findout Employee Name
   if($employeeId != '') {
     $employeeArray = $studentReportsManager->getSingleField(" employee emp LEFT JOIN department d ON emp.departmentId = d.departmentId ", 
                                                           " emp.employeeName, emp.employeeCode, 
                                                             IFNULL(d.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName ", 
                                                           " WHERE emp.employeeId = $employeeId");
                                                               
     $employeeName = $employeeArray[0]['employeeName'];
     $employeeCode = $employeeArray[0]['employeeCode'];
     $departmentName = $employeeArray[0]['departmentName'];
   }
    
   
    // Findout Class Name
   if($classId != '') {   
     $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
     $className = $classNameArray[0]['className'];
     $className2 = str_replace("-",' ',$className);
   }

    // Findout Subject
    if($subjectId!='') {
        $subCode = 'All';
        if ($subjectId != 'all') {
            $subCodeArray = $studentReportsManager->getSingleField(" `subject` sub, subject_type st ", 
                                                                   " sub.subjectName, sub.subjectCode, st.subjectTypeName ", 
                                                                   " WHERE st.subjectTypeId = sub.subjectTypeId AND subjectId = $subjectId");
            $subType = $subCodeArray[0]['subjectTypeName'];
            $subCode = $subCodeArray[0]['subjectCode'];
            $subName = $subCodeArray[0]['subjectName'];
        }
    }

    $conditions .= " AND cls.classId = ".$classId." AND (st.subjectTopicId IN (".$subjectTopic.")) ";  
        
    if(trim(strtolower($groupId))!='all') {
      $conditions .= " AND gr.groupId = $groupId";
    }
     
    $conditions .= " AND st.subjectId = $subjectId  AND tt.employeeId = ".$employeeId;  
    
    $startDate = $REQUEST_DATA['startDate'];
    $endDate = $REQUEST_DATA['endDate'];
    
    if($startDate!='' && $endDate =='') {
       $conditions .= " AND fromDate >='$startDate' ";
       $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
    }
        

    if($startDate=='' && $endDate!='') {
       $conditions .= " AND toDate <='$endDate'";
       $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    
    }
    
    if($startDate!='' && $endDate!=''){
       $conditions .= " AND ((fromDate BETWEEN '$startDate' AND '$endDate') OR (toDate BETWEEN '$startDate' AND '$endDate'))";
        
       $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
       $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    
    }
    
    $conditions .= " AND sub.hasAttendance = 1 ";     
    
    $employeeRecordArray = $employeeManager->getTeacherSubjectTopic($conditions, $orderBy, '');
    $cnt = count($employeeRecordArray);
    
    $tt=0;
    for($i=0;$i<$cnt;$i++) {
        $tt=1;  
        $employeeRecordArray[$i]['fromDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['fromDate']);    
        $employeeRecordArray[$i]['toDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['toDate']); 
        $employeeRecordArray[$i]['date'] = $employeeRecordArray[$i]['fromDate']." to ".$employeeRecordArray[$i]['toDate'];
        $employeeRecordArray[$i]['remarks'] =  "";
        $employeeRecordArray[$i]['sign'] =  "";
        $employeeRecordArray[$i]['hodSign'] =  "";
        $employeeRecordArray[$i]['srNo'] =  ($i+1) ;
        
        if($employeeRecordArray[$i]['topic'] != '') {
          $topic1 = $employeeRecordArray[$i]['topic'];  
          $employeeRecordArray[$i]['topic'] = NOT_APPLICABLE_STRING.$topic1; 
        }
        $valueArray[] = array_merge($employeeRecordArray[$i]);  
        
        if(($i+1)%10==0) {
          generateReport($valueArray);
          echo "<br class='page'>";
          $tt=0;
          $valueArray = array();
        }
    }
    
    //if(($i-1)%10!=0 || $i==1) {
    if($tt==1 || $cnt==0) {
      generateReport($valueArray);
    }
    
        
    function generateReport($employeeRecordArray) {
       
       global $reportManager;      
       global $employeeName;
       global $employeeCode;
       global $departmentName;
       global $subType;
       global $subCode;
       global $subName;
       global $className2;
       global $startDate1;
       global $endDate1;
       
/* 
       echo "<pre>"; 
       echo "check: ".$startDate1. "  --  ".$endDate1;
       print_r($REQUEST_DATA);
       die;
*/
?>
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Teacher Topic Taught Report</th></tr>
            <tr>
            <th colspan="3" >
               <table border='0' cellpadding="2px" cellspacing='0px' width="85%" class=""  align="center">
                  <tr>
                     <td align="center" colspan="6" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>>
                        <b><?php echo "(".$subType.")"; ?></b>
                     </td>
                  </tr>
                  <tr>
                     <td align="left" width="10%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Faculty Name&nbsp;</b></td>
                     <td align="left" width="2%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;" align="left" width="38%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($employeeName."&nbsp;&nbsp;(".$employeeCode.")"); ?></td>
                     <td align="left" width="10%" style="padding-left:15px" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Department&nbsp;</b></td>
                     <td align="left" width="2%"  valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"align="left" width="38%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($departmentName); ?></td>
                  </tr>
                   <tr>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Class Name&nbsp;</b></td>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"colspan="4"  align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($className2); ?></td>
                  </tr>
                  <tr>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Subject Name&nbsp;</b></td>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($subName); ?></td>
                     <td align="left" valign='top' style="padding-left:15px" nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Subject Code&nbsp;</b></td>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($subCode); ?></td>
                  </tr>
                  <tr>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Date From&nbsp;</b></td>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($startDate1); ?></td>
                     <td align="left" valign='top' style="padding-left:15px" nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Date To&nbsp;</b></td>
                     <td align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>:&nbsp;</b></td>
                     <td style="border-bottom: 1px solid #000; color: #000;"align="left" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><?php echo parseOutput($endDate1); ?></td>
                  </tr>
               </table>
            </th>
            </tr>
            </table> <br>
<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
<tr>
    <td valign="top" width="2%" align="left" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;#</b>
    <td valign="top" align="center" width="15%" <?php echo $reportManager->getReportDataStyle()?>><b>Date</b></td>
    <td valign="top" align="left" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Group</b></td>
    <td valign="top" align="center" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Period No.</b></td>
    <td valign="top" align="left" width="32%" <?php echo $reportManager->getReportDataStyle()?> nowrap>
        <b>Topics Covered<br><b><font color=red size=1>Note: '---' Means Multiple Topics</font></b></b>
    </td>
    <td valign="top" align="left" width="15%" <?php echo $reportManager->getReportDataStyle()?>><b>Remarks</b></td>
    <td valign="top" align="center" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Faculty<br>Sign.</b></td>
    <td valign="top" align="center" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>HOD/<br>Principal Sign.</b></td>
</tr>
<?php
    $recordCount = count($employeeRecordArray);
    $j=0;
    $k=0;
    if($recordCount >0 && is_array($employeeRecordArray) ) { 
        for($i=0; $i<$recordCount; $i++ ) {
            echo '<tr>
                <td valign="top"'.$reportManager->getReportDataStyle().' align="left">'.parseOutput($employeeRecordArray[$i]['srNo']).'</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="center">'.parseOutput($employeeRecordArray[$i]['date']).'</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.parseOutput($employeeRecordArray[$i]['groupName']).'</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="center">'.parseOutput($employeeRecordArray[$i]['periodNumber']).'</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.parseOutput($employeeRecordArray[$i]['topic']).'</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="left">'.$employeeRecordArray[$i]['remarks'].'&nbsp;</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$employeeRecordArray[$i]['sign'].'&nbsp;</td>
                <td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$employeeRecordArray[$i]['hodSign'].'&nbsp;</td>
                </tr>';
        }
    }
    else {
        echo '<tr><td colspan="10" align="center" '.$reportManager->getReportDataStyle().'>No record found</td></tr>';
    }
?> 
</table>

<table border='0' cellpadding="2px" cellspacing='0px' width="90%" class=""  align="center">
      <tr>
         <td align="left" colspan="2" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>><b>Note&nbsp;:</b>
           &nbsp;&nbsp;Comment in the remarks column where delivery plan is delayed and requires corrective action.  
         </td>
      </tr>
      <tr height="15px">
      </tr>
      <tr>
         <td align="left" width="10%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>>
         <b>Authorized Signatory</b><br><br><br>
         <hr><br>
         <hr>
         </td>
         <td align="right" width="60%" valign='top' nowrap <?php echo $reportManager->getReportDataStyle(); ?>>
         <b>Principal- At discretion</b>&nbsp;&nbsp;
         </td>
      </tr>
</table><br><br>    
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            
  <?php
     }
  ?>
    
    
<?php
// $History: teacherTopicCoveredPrint.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/11/10    Time: 4:45p
//Updated in $/LeapCC/Templates/EmployeeReports
//timetable label check added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/24/09   Time: 5:03p
//Updated in $/LeapCC/Templates/EmployeeReports
//parseOutput function added 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/24/09   Time: 3:44p
//Updated in $/LeapCC/Templates/EmployeeReports
//alignment & format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Templates/EmployeeReports
//date format & alignement updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/22/09   Time: 5:16p
//Updated in $/LeapCC/Templates/EmployeeReports
//format & validation format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/22/09   Time: 5:06p
//Updated in $/LeapCC/Templates/EmployeeReports
//code updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/03/09   Time: 3:57p
//Updated in $/LeapCC/Templates/EmployeeReports
//paging format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/09   Time: 3:22p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//

?>
