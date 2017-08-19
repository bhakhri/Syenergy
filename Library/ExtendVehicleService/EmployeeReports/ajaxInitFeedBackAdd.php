<?php
//-------------------------------------------------------
// Purpose: to design the layout for Teacher Feed Back
//
// Author : Parveen Sharma
// Created on : (01.12.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$feedBackManager = TeacherManager::getInstance();

require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
$commonQueryManager = CommonQueryManager::getInstance(); 

              $errorMessage ='';
   
              $teacherId=$REQUEST_DATA['teacherId'];
              $feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];   

              $condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId." AND ft.employeeId = ".$teacherId;  
              
              $filter = " WHERE feedbackSurveyId = ".$feedbackSurveyId;  

              $recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC($filter);
              $recordFeedBackCount = count($recordFeedBackArray);

              for ($k=0;$k<$recordFeedBackCount;$k++) {
                $fieldValue.=' SUM(if(ft.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
              }
              
              $FeedBackArray = $feedBackManager->getFeedBackData($fieldValue,$condition);
			  //echo '<pre>';
			  //print_r($FeedBackArray);
              $recordCount = count($FeedBackArray);
              
             // $studentAssign = 0;
             // $FeedBackStudentArr = $feedBackManager->getStudentAssign($condition);
             // if(count($FeedBackStudentArr)>0) {
             //    $studentAssign =  $FeedBackStudentArr[0]['cnt'];
             // }
              
              
              if($recordCount > 0 ) {                
                 /*  "<table width='100%' border='0' cellspacing='0px' cellpadding='0px'>
                    <tr>
                        <td width='100%' class='contenttab_internal_rows' valign='bottom' height='35px' style='text-align:center'>
                        <b>Total no. of students registered in the course in sections allocated to you : ".$studentAssign.",&nbsp;&nbsp;&nbsp;&nbsp;
                        No. of students who responded to the feedback : ".$FeedBackArray[0]['totalStudent']."</b></td>
                    </tr>
                  </table>
                */  
              echo "<table width='100%' border='0' cellspacing='2px' cellpadding='0'>  
                    <tr class='rowheading'>
                        <td width='5%' class='searchhead_text' style='padding-left:15px'><b>#</b></td>
                        <td width='40%' class='searchhead_text' align='left'><strong>Contents</strong></td>
                        <td width='45%' class='searchhead_text' align='center' colspan='$recordFeedBackCount'>
                        <strong>Comments</strong></td>
                        <td width='10%' class='searchhead_text' align='right' style='padding-right:15px'>
                          <strong>Score</strong></td>
						<td width='8%' class='searchhead_text' align='right' style='padding-right:15px'>
                          <strong>Statistics</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>";
                    if($recordFeedBackCount > 0 ) {
                     $fieldValue='';
                        for ($k=0;$k<$recordFeedBackCount;$k++) {
                           echo "<td class='searchhead_text' align='right' valign='top' ><b>".$recordFeedBackArray[$k]['feedbackGradeLabel']; 
                                echo "</b></td>";

								$strText .= $recordFeedBackArray[$k]['feedbackGradeLabel'].'~';
                           }
                        } 
                     echo "<td></td>
                    </tr>";
                    
                        for($k=0;$k<$recordCount;$k++) {
                            $bg = $bg =='trow0' ? 'trow1' : 'trow0';      
                            echo "<tr class='$bg'>
                                     <td valign='top' class='padding_top' style='padding-left:15px'>".($records+$k+1)."</td>  
                                     <td valign='top' class='padding_top' >".
                                        strip_slashes($FeedBackArray[$k]['feedbackQuestion'])."</td>";    
                                     for ($i=0;$i<$recordFeedBackCount;$i++) {   
                                        echo "<td valign='top' align='right' class='padding_top' style='padding-left:15px'>".
                                            strip_slashes($FeedBackArray[$k]['feedGrade'.($recordFeedBackArray[$i]['feedbackGradeId'])]); 
                                         echo "</td>";

										 $strValue .= $FeedBackArray[$k]['feedGrade'.($recordFeedBackArray[$i]['feedbackGradeId'])].'~';
                                      }
                                      echo "<td valign='top' align='right' class='padding_top' style='padding-right:15px'>".
                                      number_format(($FeedBackArray[$k]['ratio']),2,'.','')."</td>";    
									   echo "<td valign='top' align='center' class='padding_top' style='padding-right:15px'><a href='Javascript:void(0)'  onClick='printStatistics(\"".$strText."\",\"".$strValue."\",\"".$FeedBackArray[$k]['feedbackQuestion']."\");return false;'><img src='".IMG_HTTP_PATH."/zoom.gif'></a></td></tr>"; 
									   $strValue='';
                        }
                       echo '<tr><td height="20"></td></tr><tr><td colspan="9" align="right"><div id = "saveDiv">
<input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport();return false;"/>&nbsp;&nbsp; 
<input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/excel.gif" onClick="printReportCSV();return false;"/>                        
                       </td></tr>'; 
                  }  
                  else {
                    echo "<tr><td colspan='10' align='center'>No record found</td></tr>";
                }
                  
            echo "</table>";
     
// $History: ajaxInitFeedBackAdd.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Updated in $/LeapCC/Library/EmployeeReports
//Added Feedback Survey reports
?>