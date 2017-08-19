<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories
// Author : Gurkeerat Sidhu
// Created on : (15.02.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedbackTeacherFinalReportAdvanced');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbFinalMgr=FeedBackReportAdvancedManager::getInstance();
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $catId=trim($REQUEST_DATA['catId']);
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');



    $headerString='<table border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tr class="rowheading">
                         <td class="searchhead_text" align="left" width="2%">#</td>
                         <td class="searchhead_text" align="left" width="50%">Feedback Description</td>
                         <td class="searchhead_text" align="left" >Feedback Response</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Responce Count</td>
                    </tr>';
     $tableString='<tr><td colspan="4" align="center">'.NO_DATA_FOUND.'<td></tr>';

     $foundArray=$fbFinalMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId );
     $countFoundArray=count($foundArray);
      if($countFoundArray>0){
         $tableString='';
     }
     for($i=0;$i<$countFoundArray;$i++){
        $employeeId2=$foundArray[$i]['employeeId'];
        //$employeeName2=$foundArray[$i]['employeeName'];
        $foundArray2=$fbFinalMgr->getCategory($labelId,$employeeId2,$timeTableLabelId,$catId);
        $countFoundArray2=count($foundArray2);
     for($k=0;$k<$countFoundArray2;$k++){
        $catId2=$foundArray2[$k]['feedbackCategoryId'];
        $catName=$foundArray2[$k]['feedbackCategoryName'];
        $finalInfoArray=$fbFinalMgr->getOptions($employeeId2,$catId2,$labelId);
        $finalCnt=count($finalInfoArray);
        $questionId=-1;
        $x=0;
        //$tableString .='<tr  ><td>&nbsp;</td><td colspan="3" class="searchhead_text">'.$employeeName2.' ( '.$catName.' )</td></tr>';
        $tableString .='<tr  ><td>&nbsp;</td><td colspan="3" class="searchhead_text">'.$catName.'</td></tr>';
     for($j=0;$j<$finalCnt;$j++){
         if($questionId!=$finalInfoArray[$j]['feedbackQuestionId']){
             $question=$finalInfoArray[$j]['feedbackQuestion'];
             $questionId=$finalInfoArray[$j]['feedbackQuestionId'];
             $x++;
             $temp=$x;
            // $tableString .='<tr><td colspan="1">&nbsp;</td><td colspan="3"><hr/></td></tr>';
         }
         else{
             $question='';
             $x=$x;
             $temp='';
         }
         $option=$finalInfoArray[$j]['optionLabel'];
         $optionId=$finalInfoArray[$j]['answerSetOptionId'];
         $answerCount=$finalInfoArray[$j]['answerCount'];
        // $resultCount = $fbFinalMgr->getTotalCount($feedbackToQuestionId,$employeeId,$optionId);
         $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
         $tableString .='<tr '.$bg.'>
                         <td class="padding_top" align="left">'.$temp.'</td>
                         <td class="padding_top" align="left">'.$question.'</td>
                         <td class="padding_top" align="left" >'.$option.'</td>
                         <td class="padding_top" align="right" style="padding-right:3px;">'.$answerCount.'</td>
                         </tr>';
       }
      }
     }
     echo $headerString.$tableString;
}
else{
    echo 'Required Parameters Missing';
    die;
}

// $History: ajaxGetTeacherFinalReport.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/03/10   Time: 16:45
//Created in $/LeapCC/Library/FeedbackAdvanced/Teacher
//Created Feedback Teacher Final Report (Advanced) for Teacher login
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/19/10    Time: 5:02p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//added message "No data found"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/02/10   Time: 15:55
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Uncomment set_time_limit(0) as this report will take much time to
//execute when Teacher : ALL and Category: ALL are choosen.
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:08p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback teacher final report
//

?>