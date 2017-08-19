<?php
//----------------------------------------------------------------------------------
// THIS FILE IS USED TO check whether the cookie is set for time table alert
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

$widgetId=trim($REQUEST_DATA['id']);
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance(); 

$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];

function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
function widget($id){
    global $teacherManager,$activeTimeTableLabelId;
    $widget = '';
                if($id=='widget1'){//notice
                    $curDate=date('Y')."-".date('m')."-".date('d');
                    $filter=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)";
                    
                    $noticeRecordArray = $teacherManager->getNoticeList($filter,' LIMIT 0,7','visibleFromDate DESC, visibleMode DESC, noticeId DESC');
                    $widget .='<table width="100%"  style="height:242px" border="0" id="tNotice">
                    <tr>
                     <td colspan="2" align="left" style="padding-left:10px" valign="top">';
                     $recordCount = count($noticeRecordArray);
                     if($recordCount >0 && is_array($noticeRecordArray) ) { 
                      $widget .='<table width="100%"  border="0" cellpadding="0" cellspacing="0">';
                      for($i=0; $i<$recordCount; $i++ ) {
                          
                          if($noticeRecordArray[$i]['visibleMode']=='3') {  
                            $visibleImageName = IMG_HTTP_PATH."/urgent1.png";
                          }
                          else if($noticeRecordArray[$i]['visibleMode']=='2') {  
                            $visibleImageName = IMG_HTTP_PATH."/important1.png";  
                          }
                          else {
                            $visibleImageName = IMG_HTTP_PATH."/new.gif";  
                          }
                      $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
                      $pic=split('-',strip_slashes($noticeRecordArray[$i]['noticeAttachment'])); 
                      $title="From : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']))." To : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1); 
                      $widget .='<tr class="'.$bg.'">';
                      $widget .='<td valign="top" class="padding_top" align="left">
                          <a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].');return false;" title="'.$title.'" >
                           <ul class="myUl"><li class="contenttab_internal_rows1">';
                           if(isset($pic[1])) {     
                            $widget .= trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),35).'-'.$noticeRecordArray[$i]['abbr'];
                           }
                           else {
                            $widget .= trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),35).'-<i>'.$noticeRecordArray[$i]['abbr'].'</i>';
                           }
                      $widget .= '</li></ul>';
                      require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                      global $sessionHandler;
                      $tdays = $sessionHandler->getSessionVariable('FLASHING_NEW_ICON_NOTICES');
                      if($tdays=='') {
                       $tdays=0;  
                      } 
                      if(is_numeric($tdays) === true) {
                       $tdays = intval($tdays);  
                       if($tdays <= 0 ) {
                        $tdays = 0;   
                       }  
                      }
                      else {
                       $tdays = 0;  
                      } 
                      $dt  = $noticeRecordArray[$i]['visibleFromDate'];
                      $dtArr = explode('-',$dt);
                      $dtArr = explode('-',$dt);
                      $dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+$tdays), date($dtArr[0])));
                      $currentDate = date('Y-m-d');
                      if($currentDate <= $dt1 && $tdays!=0) {
                        $widget .= '&nbsp;<img src="'.$visibleImageName.'">';
                      } 
                      $widget .= '</a></td><td valign="top" align="right" height="3px">';
                      if(isset($pic[1])) {
                        $widget .='<img style="margin-bottom:-5px;" src="'.IMG_HTTP_PATH.'/download.gif" title="'.$pic[1].'" onclick=download("'.$attactment.'"); />'; 
                      }
                      $widget .='</td></tr>';
                     }
                     $widget .= '<tr><td colspan="2" class="contenttab_internal_rows1" align="right" style="padding-right:10px"><a href="listInstituteNotice.php"><u>Show all Notices</u></a></td></tr>';  
                     $widget .='</table>';
                   }
                 else {
                  $widget .='<tr><td colspan="2" class="contenttab_internal_rows1" align="center">There are no new Notices to show</td></tr>';
                 }
                 $widget .='</table>'; 
                }
                if($id=='widget2'){//events
                  $curDate=date('Y')."-".date('m')."-".date('d');
                  //$filter=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
                  $filter =" AND DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY) <=CURDATE() AND e.endDate>=CURDATE() ";
                  $eventRecordArray = $teacherManager->getEventList($filter,$limit,'e.startDate DESC');
                  $widget .='<table width="100%" style="height:200px"  border="0" id="tEvent">
                        <tr>
                        <td colspan="2" align="left" style="padding-left:10px" valign="top">';
                        $recordCount = count($eventRecordArray);
                        if($recordCount >0 && is_array($eventRecordArray) ) { 
                        $widget .='<table width="100%"  border="0" cellspacing="5">';
                        $widget .= "<ul class='myUL'>";
                        for($i=0; $i<$recordCount; $i++ ) {
                            $title="From : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate']))." To : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription'])),100,2);   
                            $widget .='<li class="contenttab_internal_rows1"><a href="" name="bubble" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].');return false;" title="'.$title.'" >'
                                       .trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['eventTitle'])),25)
                                       .'</a></li>';
                        }
                        $widget .= "</ul>";
                        $widget .= '<tr><td align="right" style="padding-right:10px" class="contenttab_internal_rows1"><a href="listInstituteEvent.php"><u>Show all Events</u></a></td></tr>';
                        $widget .='</table>';
                       }
                       else {
                        $widget .='<tr><td colspan="2" class="contenttab_internal_rows1" align="center" >There are no new Events to show</td></tr>';
                        }
                        $widget .='</table>';
                }
                if($id=='widget3'){//resource download count
                    $courseResourceRecordArray = $teacherManager->getResourceList(' ',' ',' downloadCount desc');    
                    $widget .='<table width="100%" height="100%" border="0" >
                              <tr>
                                <td height="236" valign="top" >
                                 <form name="searchForm" action="" method="post">
                                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tr>
                                  <td align="left" width="100%">
                                   <div id="results" style="width:100%"> 
                                   <table width="100%" border="0" cellspacing="1" cellpadding="0"  id="anyid">
                                    <tr class="contenttab_internal_rows1">
                                     <td width="3%" class="searchhead_text" bgcolor="#DCDCDC" align="left">&nbsp;&nbsp;<b>#</b></td>
                                     <td width="200" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Subject</b></td>
                                     <td width="1200" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Description</b></td>
                                     <td width="130" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Count</b></td>
                                    </tr>';
                                     $recordCount = count($courseResourceRecordArray);
                                     if($recordCount >0 && is_array($courseResourceRecordArray) ) { 
                                     for($i=0; $i<$recordCount; $i++ ) {
                                     if($i>=8){
                                       continue;
                                     } 
                                     $bg = $bg =='row0' ? 'row1' : 'row0';
                                     $recourceSubject=$courseResourceRecordArray[$i]['subject'];
                                     $recourceSubjectTemp=$recourceSubject;
                                     if(strlen($recourceSubject)>10){
                                      $recourceSubject=substr($recourceSubject,0,7).'...';
                                     }
                                     $recourceDescription=$courseResourceRecordArray[$i]['description'];
                                     $recourceDescriptionTemp=$recourceDescription;
                                     if(strlen($recourceDescription)>23){
                                      $recourceDescription=substr($recourceDescription,0,20).'...';
                                     }
                                     $downloadCounter=$courseResourceRecordArray[$i]['downloadCount'];
                                     $widget .='<tr class="'.$bg.'">
                                      <td valign="top" class="padding_top contenttab_internal_rows1" align="right">'.($records+$i+1).'</td>
                                      <td class="padding_top contenttab_internal_rows1" valign="top" title="'.$recourceSubjectTemp.'"><nobr>&nbsp;'.$recourceSubject.'</nobr></td>
                                      <td class="padding_top contenttab_internal_rows1" valign="top" title="'.$recourceDescriptionTemp.'">&nbsp;'.$recourceDescription.'</td>
                                      <td class="padding_top contenttab_internal_rows1" valign="top" align="right">'.$downloadCounter.'&nbsp;</td>
                                      </tr>';
                                     }
                                     $widget .='<tr><td colspan="5" align="right" style="padding-right:10px"></td></tr>';                   
                                     $widget .='<tr><td colspan="5" align="right" style="padding-right:10px"><a href="listCourseResource.php"><u>Show all Resources</u></a></td></tr>';                    
                                    }
                                   else {
                                     $widget .='<tr><td colspan="5" align="center">'.NO_DATA_FOUND.'</td></tr>';
                                     }
                                    $widget .='</table>
                                        </div>
                                        </td>
                                        </tr>
                                        </table> 
                                        </form>                  
                                        </td>
                                        </tr>
                                        </table>';
                }
					if($id=='widget5'){//messages
				   $limit      = ' LIMIT 0,7';  //showing first three records
                            $messagesRecordArray = $teacherManager->getAdminMessageList('',$limit,'us.userName');
                            //********For Messages(ends)**************
							//$lim = 
                          $widget .='<table width="100%" style="height:200px"  border="0" id="tMessages">
                                <tr>
                                <td colspan="2" align="left" style="padding-left:10px" valign="top">';
                                $recordCount = count($messagesRecordArray);
                                if($recordCount >0 && is_array($messagesRecordArray) ) { 
                                $widget .='<table width="100%"  border="0" cellspacing="5">';
                                $widget .= "<ul class='myUL'>";
                                for($i=0; $i<$recordCount; $i++ ) {
                                    $title="From : ".UtilityManager::formatDate(strip_slashes($messagesRecordArray[$i]['visibleFromDate']))." To : ".UtilityManager::formatDate(strip_slashes($messagesRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($messagesRecordArray[$i]['message'])),100,2);   
                                    $widget .='<li class="contenttab_internal_rows1"><a href="" name="bubble" onclick="showMessageDetails('.$messagesRecordArray[$i]['messageId'].');return false;" title="'.$title.'" >'
                                               .trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($messagesRecordArray[$i]['subject'])),25)
                                               .'</a></li>';
                                }
                                $widget .= "</ul>";
                                $widget .= '<tr><td align="right" style="padding-right:10px" class="contenttab_internal_rows1"><a href="listAdminMessages.php"><u>Show all admin messages</u></a></td></tr>';
                                $widget .='</table>';
                               }
                               else {
                                $widget .='<tr><td colspan="2" class="contenttab_internal_rows1" align="center" >There are no new Message to show</td></tr>';
                                }
                                $widget .='</table>';
                }
                if($id=='widget4'){//analysis
                  $teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                        $concatStr = '';
                        foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                            $subjectId = $teacherSubjectRecord['subjectId'];
                            $classId = $teacherSubjectRecord['classId'];
                            if ($concatStr != '') {
                                $concatStr .= ',';
                            }
                            $concatStr .= "'$subjectId#$classId'";
                        }
                        if (empty($concatStr)) {
                            $concatStr = "'0#0'";
                        }
                        
                        $attCountArray = $teacherManager->countAttendanceRecords($activeTimeTableLabelId, $concatStr);
                        $attendanceRecordCount = $attCountArray[0]['cnt'];
                        if ($attendanceRecordCount == 0) {
                            $totalStudentCountBelowThreshold = -1;
                        }
                        else {
                            $attCountArray = $teacherManager->countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr);
                            $totalStudentCountBelowThreshold = $attCountArray[0]['cnt'];
                            $strAttendanceThreshold = '';
                            if ($totalStudentCountBelowThreshold > 0) {
                                $teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                                foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                    $subjectId = $teacherSubjectRecord['subjectId'];
                                    $subjectCode = $teacherSubjectRecord['subjectCode'];
                                    $classId = $teacherSubjectRecord['classId'];
                                    $className = $teacherSubjectRecord['className'];
                                    $concatStrSub = "'$subjectId#$classId'";
                                    $concatStrSub2 = "$subjectId#$classId";
                                    $concatStrSub3 = "$subjectCode#$className";
                                    $classSubjectRecordsArray = $teacherManager->countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStrSub);
                                    $classSubjectCount = $classSubjectRecordsArray[0]['cnt'];
                                    if ($classSubjectCount > 0) {
                                        $strAttendanceThreshold .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"attendanceThreshold\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                                    }
                                }
                            }
                        }

                        $strToppers = '';
                        //$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                        foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                            $subjectId = $teacherSubjectRecord['subjectId'];
                            $subjectCode = $teacherSubjectRecord['subjectCode'];
                            $classId = $teacherSubjectRecord['classId'];
                            $className = $teacherSubjectRecord['className'];
                            $concatStrSub = "'$subjectId#$classId'";
                            $concatStrSub2 = "$subjectId#$classId";
                            $concatStrSub3 = "$subjectCode#$className";
                            $toppersRecordArray = $teacherManager->countTopperRecords($concatStrSub);
                            $classSubjectCount = $toppersRecordArray[0]['cnt'];
                            if ($classSubjectCount > 0) {
                                $strToppers .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"toppers\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                            }
                        }

                        /*
                        -----------------------------------------------
                            FOR BELOW AVERAGE
                        -----------------------------------------------
                        */

                        $strBelowAvg = '';
                        foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                            $subjectId = $teacherSubjectRecord['subjectId'];
                            $subjectCode = $teacherSubjectRecord['subjectCode'];
                            $classId = $teacherSubjectRecord['classId'];
                            $className = $teacherSubjectRecord['className'];
                            $concatStrSub = "'$subjectId#$classId'";
                            $concatStrSub2 = "$subjectId#$classId";
                            $concatStrSub3 = "$subjectCode#$className";
                            $toppersRecordArray = $teacherManager->countBelowAvgRecords($concatStrSub);
                            $classSubjectCount = $toppersRecordArray[0]['cnt'];
                            if ($classSubjectCount > 0) {
                                $strBelowAvg .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"belowAvg\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                            }
                        }
                        

                        /*
                        -----------------------------------------------
                            FOR ABOVE AVERAGE
                        -----------------------------------------------
                        */

                        $strAboveAvg = '';
                        foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                            $subjectId = $teacherSubjectRecord['subjectId'];
                            $subjectCode = $teacherSubjectRecord['subjectCode'];
                            $classId = $teacherSubjectRecord['classId'];
                            $className = $teacherSubjectRecord['className'];
                            $concatStrSub = "'$subjectId#$classId'";
                            $concatStrSub2 = "$subjectId#$classId";
                            $concatStrSub3 = "$subjectCode#$className";
                            $toppersRecordArray = $teacherManager->countAboveAvgRecords($concatStrSub);
                            $classSubjectCount = $toppersRecordArray[0]['cnt'];
                            if ($classSubjectCount > 0) {
                                $strAboveAvg .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"aboveAvg\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                            }
                        }
                   $widget .='<div style="overflow:auto; height:484px;">
                              <table border="0">
                              <tr>
                               <td width="100%" align="left" style="padding-left:0px;padding-top:10px;" border="0" valign="top" height="100%">
                               <table width="100%"  border="0" cellspacing="0" height="100%" align="left">
                               <tr>
                               <td valign="top" colspan="1" align="left" class="contenttab_internal_rows1">
                               <ul class="myUL"><li><u><b>Attendance Below Threshold (';
                               if ($attendanceRecordCount == 0) {
                               $widget .=0; 
                               } 
                               else{
                                   $widget .=$totalStudentCountBelowThreshold;
                               }
                               $widget .=' Students)</b></u></li></ul>
                               </td>
                               </tr>';
                               if ($attendanceRecordCount == 0) {
                                $widget .='<tr height="25">
                                 <td  colspan="1" class="contenttab_internal_rows1">Attendance has not been taken yet.</td></tr>';
                               }
                               else if($totalStudentCountBelowThreshold == 0) {
                                $widget .='<tr height="25">
                                           <td  colspan="1" class="contenttab_internal_rows1">No Student found below Threshold.</td></tr>';
                               }
                               else if($totalStudentCountBelowThreshold > 0) {
                                 $widget .= $strAttendanceThreshold;
                               }
                               $widget .='<tr height="25">
                                           <td  colspan="1" class="contenttab_internal_rows1">
                                            <ul class="myUL"><li><u><b>Exams</b></u></li></ul></td></tr>';
                               if ($strToppers != '' or $strBelowAvg != '' or $strAboveAvg != '') {
                               $widget .='<tr height="25">
                                         <td  colspan="1" class="contenttab_internal_rows1"><a href="javascript:showExamStatistics();">Exam Statistics</a></td>                        </tr>
                                         <tr>
                                         <td  colspan="1" class="contenttab_internal_rows1">
                                          <ul class="myUL"><li><u><b>Performance </b></u>';
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                          $widget .=HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_TEACHER_DASHBOARD_PERFROMANCE);
                                          $widget .='</li></ul></td></tr>';
                               }
                               else {
                                 $widget .='<tr height="25">
                                              <td  colspan="1" class="contenttab_internal_rows1">No Test has been taken yet.</td>
                                             </tr>';
                                 }
                               if ($strToppers != '') {
                               $widget .='<tr height="25">
                                        <td  colspan="1" class="contenttab_internal_rows1"><b>Toppers</b></td>
                                        </tr>';
                                        $widget .=$strToppers;
                               }
                               if ($strBelowAvg != '') {
                               $widget .='<tr height="25">
                                           <td  colspan="1" class="contenttab_internal_rows1"><b>Below Average</b></td>
                                          </tr>';
                                          $widget .=$strBelowAvg;
                               }
                               if ($strAboveAvg != '') {
                               $widget .='<tr height="25">
                                          <td  colspan="1" class="contenttab_internal_rows1"><b>Above Average</b></td>
                                          </tr>';
                                          $widget .=$strAboveAvg;
                               }
                               $widget .='</table>';
                               $widget .='</table>';
                               $widget .='</div>';
                }
			

                                      
    return trim($widget);
}

if($widgetId!=''){
   echo widget($widgetId);
}
else{
    echo "Data for this widget not found";
    die;
}

// $History: ajaxCheckTimeTableAlertCookie.php $
?>