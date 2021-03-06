<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

set_time_limit(0); 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once($FE . "/Library/HtmlFunctions.inc.php");      
require_once(BL_PATH . '/ReportManager.inc.php');                      
define('MODULE','DisplayTimeTableReport');  
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();


    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $timeArr = explode('~',$timeTableLabelId);
     
    $timeTableType = 1;   
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    else {
      $timeTableLabelId=$timeArr[0]; 
      $timeTableType = $timeArr[3];    
    }
    
    
    
// Query Format
    
// classwise
$filter= "DISTINCT  cl.classId, SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className  ";
$condition = ' AND tt.timeTableLabelId='.$timeTableLabelId;
$orderBy = ' ORDER BY className';
$classArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
$classList = UtilityManager::makeCSList($classArray, 'classId');

// Subjectwise
$filter= "DISTINCT  sub.subjectId, sub.subjectCode AS subjectName ";
$condition = ' AND tt.timeTableLabelId='.$timeTableLabelId;
$orderBy = ' ORDER BY sub.subjectCode';
$subjectArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
$subjectList = UtilityManager::makeCSList($subjectArray, 'subjectId');


// Employeewise
$filter= "DISTINCT  emp.employeeId, emp.employeeName ";
$condition = ' AND tt.timeTableLabelId='.$timeTableLabelId;  
$orderBy = ' ORDER BY  emp.employeeName';
$employeeArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
$employeeList = UtilityManager::makeCSList($employeeArray, 'employeeId');


// Roomwise
$filter= "DISTINCT  r.roomId, r.roomName ";
$condition = ' AND tt.timeTableLabelId='.$timeTableLabelId;  
$orderBy = ' ORDER BY  r.roomName';
$roomArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
$roomList = UtilityManager::makeCSList($roomArray, 'roomId');



// Groupwise
$filter= "DISTINCT  gr.groupId, gr.groupShort ";
$condition = ' AND tt.timeTableLabelId='.$timeTableLabelId;  
$orderBy = ' ORDER BY  gr.groupShort';
$groupArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
$groupList = UtilityManager::makeCSList($groupArray, 'groupId');



$htmlFunctionsManager = HtmlFunctions::getInstance();    

$reportManager = ReportManager::getInstance();

//echo "<pre>";
//print_r($REQUEST_DATA);
//die;

    $conditions = '';
    $str = '';
    
    if($timeTableLabelId!='') {
       $conditions .=" AND tt.timeTableLabelId IN (".$timeTableLabelId.")";   
    }
    
    
    global $daysArr;
    $valueDays = "";
    $valueNoDays = "";  

    $countArr='';
     if($REQUEST_DATA['reportResult']=='weekWise') {
        $countArr = 1; 
        foreach($daysArr as $key=>$value) {   
          if($valueDays=="") {
             $valueDays = $value;
             $valueNoDays = $key;
          }
          else {
             $valueDays .= ", ".$value;
             $valueNoDays .=",".$key;
          }
        }
        if($REQUEST_DATA['dayWeeks']!='') {  
          $str = ' AND tt.daysOfWeek IN ('.$REQUEST_DATA['dayWeeks'].')';
        }
        else{
           $str = ' AND tt.daysOfWeek IN ('.$valueNoDays.')';
        }
    }
    else {
       if($REQUEST_DATA['dayWeeks']!='') {
         $conditions .=" AND tt.daysOfWeek IN  (".$REQUEST_DATA['dayWeeks'].")";   
       }
       else {
           foreach($daysArr as $key=>$value) {   
              if($valueDays=="") {
                 $valueDays = $key;
              }
              else {
                 $valueDays .= ",".$key;
              }
           } 
           $conditions .=" AND tt.daysOfWeek IN  (".$valueDays.")";     
       }
    }  
    
     if($REQUEST_DATA['reportResult']=='className') {
        if($REQUEST_DATA['classId']!='') {
           $countArr = explode(",",$REQUEST_DATA['classId']);   
        }
        else {
          $countArr = explode(",",$classList); 
        }
        $str = ' AND cl.classId = ';
    }
    else {
        if($REQUEST_DATA['classId']!='') {
          $conditions .=" AND cl.classId IN (".$REQUEST_DATA['classId'].")";   
        }
        else {
          $conditions .=" AND cl.classId IN (".$classList.")"; 
        }
    }
    
   
    
    if($REQUEST_DATA['reportResult']=='employeeName') {
        if($REQUEST_DATA['employeeId']!='') {
          $countArr = explode(",",$REQUEST_DATA['employeeId']);
        }
        else {
          $countArr = explode(",",$employeeList); 
        }
        $str = ' AND emp.employeeId = ';
    }
    else {
        if($REQUEST_DATA['employeeId']!='') {
           $conditions .=" AND emp.employeeId IN (".$REQUEST_DATA['employeeId'].")";   
        }
          else {
           $conditions .=" AND emp.employeeId IN (".$employeeList.")";   
          }
    }
    
    if($REQUEST_DATA['reportResult']=='roomName') {
        if($REQUEST_DATA['roomId']!='') {
          $countArr = explode(",",$REQUEST_DATA['roomId']); 
        }
        else {
          $countArr = explode(",",$roomList); 
        }
        $str = ' AND r.roomId = ';
    }
    else {
        if($REQUEST_DATA['roomId']!='') {
           $conditions .=" AND r.roomId IN (".$REQUEST_DATA['roomId'].")";   
        }
        else {
           $conditions .=" AND r.roomId IN (".$roomList.")";   
        }
    }
    
    if($REQUEST_DATA['reportResult']=='subjectName') {
        if($REQUEST_DATA['subjectId']!='') {
          $countArr = explode(",",$REQUEST_DATA['subjectId']); 
        }
        else {
          $countArr = explode(",",$subjectList); 
        }
        $str = ' AND sub.subjectId = ';
    }
    else {
        if($REQUEST_DATA['subjectId']!='') {
           $conditions .=" AND sub.subjectId IN (".$REQUEST_DATA['subjectId'].")";   
        } 
        else {
           $conditions .=" AND sub.subjectId IN (".$subjectList.")";   
        }
    }
    
   if($REQUEST_DATA['reportResult']=='groupShort') {
        //$countArr = explode(",",$REQUEST_DATA['sectionId']); 
        //$str = ' AND sc.sectionId = ';
        $countArr = 1; 
        if($REQUEST_DATA['groupId']!='') {
           $str = ' AND gr.groupId IN ('.$REQUEST_DATA['groupId'].')';
        }
        else {
           $str = ' AND gr.groupId IN ('.$groupList.')'; 
        }
    }
    else {
        if($REQUEST_DATA['groupId']!='') {
           $conditions .=" AND gr.groupId IN (".$REQUEST_DATA['groupId'].")";   
        }
        else {
           $conditions .=" AND gr.groupId IN (".$groupList.")";   
        }
    } 
    
    // Report generate
    function reportGenerate($value) {
        
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportHeading("Multi Utility Time Table Report");
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportInformation(" As On $formattedDate ");        
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
        </div>
<?php        
    }

    // tdTextBreak
    function tdBreak($arrList) {
       $j=count($arrList); 
       $arrText = '';
       $brChk='';
       $i=-1;
       while($j > 0)  {
         $arrText .= $brChk.substr($arrList,($i+1),150);
         $j-=150;  
         $i+=150;
         $brChk ='<br>';
       }
       if($i<count($arrList))
         $arrText .= $brChk.substr($arrList,($i+1),150);
       return $arrText;
    }

  // Search By 
    function seachBy($teacherRecordArray,$listShow,$valueDays) {
           if($listShow!='weekWise') {
               $arr = UtilityManager::makeCSList($teacherRecordArray, $listShow);   
               $arrList = explode(",",$arr); 
               $arrayUnique = array_unique($arrList);                                  
               $arrList1 = implode(", ",$arrayUnique);
           }
           else {
             $arrList1 = $valueDays; 
           }
           
           $result = '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
               <tr>
                   <td width="4%"  class="contenttab_internal_rows" valign="bottom" style="text-align:left"><nobr><b>Search By&nbsp;:&nbsp;</b></nobr></td>
                   <td width="96%" class="contenttab_internal_rows" valign="bottom" style="text-align:left"><nobr>'.tdBreak($arrList1).'</nobr></td>
               </tr>
           </table>';
           return $result;           
    }
    
    
    $condDate="";
    if($timeTableType==1) {
      if($REQUEST_DATA['reportView']=='1') { 
        $orderBy = ' ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber';            
      }
      else 
      if($REQUEST_DATA['reportView']=='2') { 
        $orderBy = ' ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek';       
      }
    }
    else if($timeTableType==2) {
      $fromDate =  $REQUEST_DATA['fromDate'];
      $toDate =  $REQUEST_DATA['toDate'];
      $orderBy = ' ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber';            
      $condDate = " AND (tt.fromDate BETWEEN  '$fromDate' AND '$toDate') ";
    }
    
    
     // Fetch Period Arrays
    $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');    
    
    
    $chkTimeTable  = '';
    $findTimeTable='';
    for($s=0;$s<count($countArr);$s++) {
        if($REQUEST_DATA['reportResult']=='groupShort') {      
           $cond = $conditions.' '.$str;  
        }
        else if($REQUEST_DATA['reportResult']=='weekWise') {      
           $cond = $conditions.' '.$str;   
        }
        else  {      
           $cond = $conditions.' '.$str .'"'.$countArr[$s].'"';  
        }
        for($ps=0; $ps < count($periodSlotArr); $ps++) {
            $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
            $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
            $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
            $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);
       
             // Period Slot Id check added
            $cond1 = $cond." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." AND ttl.timeTableType = ".$timeTableType;   
            if($timeTableType==2) {  
              $cond1 .= $condDate;
            }
         
             
            if($timeTableType==2) { 
               $fieldName = " DISTINCT fromDate";
               $orderFrom = " ORDER BY fromDate";
               $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
            }
         
            $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);
            $recordCount = count($teacherRecordArray);
               
            if($recordCount >0 && is_array($teacherRecordArray)) {     
               $chkTimeTable = "1";   
               $findTimeTable .= seachBy($teacherRecordArray, $REQUEST_DATA['reportResult'],$valueDays); 
               if($timeTableType==1) { 
                   if($REQUEST_DATA['reportView']=='1') {
                       $findTimeTable .=  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                       echo reportGenerate($findTimeTable);
                       echo "<br class='brpage'><br>"; 
                   }
                   else 
                   if($REQUEST_DATA['reportView']=='2') {
                      $findTimeTable .=  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                      echo reportGenerate($findTimeTable);
                      echo "<br class='brpage'><br>"; 
                   }
               }
               else 
               if($timeTableType==2) { 
                  $findTimeTable .=  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                  echo reportGenerate($findTimeTable);
                  echo "<br class='brpage'><br>"; 
               }
               $findTimeTable = '';
           }
      }
   }     
   
   if($chkTimeTable=='') {
     //$findTimeTable =  "<div align='center'>No record found</div>";
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Multi Utility Time Table Report');
        //$reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
   ?>
      <table border="0" cellspacing="0" cellpadding="0" width="<?php echo $reportManager->reportWidth?>">
            <tr>
                <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
                <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
                <td align="right" colspan="1" width="25%" class="">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo $formattedDate ;?></td>
                        </tr>
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="10" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="10" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="10" <?php echo $reportManager->getFooterStyle();?>>No record found</th></tr>
        </table>  
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table>         
<?php    
   }
   //echo $findTimeTable;
?>
<?php
//$History: detailsTimeTableReportPrint.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/20/10    Time: 5:03p
//Updated in $/LeapCC/Templates/TimeTable
//showTimeTable function (daily & weekly base format udpated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/20/10    Time: 11:24a
//Updated in $/LeapCC/Templates/TimeTable
//format updated 
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/19/10    Time: 4:47p
//Updated in $/LeapCC/Templates/TimeTable
//reportType base code updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 3/29/10    Time: 6:06p
//Updated in $/LeapCC/Templates/TimeTable
//code updated
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/04/10    Time: 3:26p
//Updated in $/LeapCC/Templates/TimeTable
//fixed issue: 0002664
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/19/10    Time: 10:16a
//Updated in $/LeapCC/Templates/TimeTable
//set_time_limit added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/21/09   Time: 11:39a
//Updated in $/LeapCC/Templates/TimeTable
//format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/14/09   Time: 5:06p
//Updated in $/LeapCC/Templates/TimeTable
//adjustment base time table format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/25/09    Time: 10:52a
//Updated in $/LeapCC/Templates/TimeTable
//formatting & alignment updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/24/09    Time: 11:40a
//Updated in $/LeapCC/Templates/TimeTable
//function format udpated (no record found)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/23/09    Time: 1:39p
//Updated in $/LeapCC/Templates/TimeTable
//conditions format updated  (timetable labelId check)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/19/09    Time: 4:10p
//Created in $/LeapCC/Templates/TimeTable
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/18/09    Time: 5:35p
//Updated in $/LeapCC/Templates/TimeTable
//classwise time table show
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/10/09    Time: 1:24p
//Updated in $/LeapCC/Templates/TimeTable
//order by condition added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/04/09    Time: 11:37a
//Updated in $/LeapCC/Templates/TimeTable
//Order by Clause Update (LENGTH(p.periodNumber)+0,p.periodNumber) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/16/09    Time: 10:29a
//Updated in $/LeapCC/Templates/TimeTable
//show time table function update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/09    Time: 3:53p
//Created in $/LeapCC/Templates/TimeTable
//file added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/13/09    Time: 4:40p
//Updated in $/Leap/Source/Templates/ScTimeTable
//report view code update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/09    Time: 4:35p
//Updated in $/Leap/Source/Templates/ScTimeTable
//function base time table update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/08/09    Time: 6:47p
//Created in $/Leap/Source/Templates/ScTimeTable
//file added

?>