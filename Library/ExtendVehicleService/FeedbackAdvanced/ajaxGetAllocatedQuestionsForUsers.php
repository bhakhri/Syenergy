<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_ProvideFeedBack');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$userId=$sessionHandler->getSessionVariable('UserId');
if($roleId==2){//for teacher
  UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==3){//for parent
 //not implemented till now
 redirectBrowser(UI_HTTP_PATH.'/Parent/index.php');
}
else if($roleId==4){ //for student
  UtilityManager::ifStudentNotLoggedIn();
}
else{
  redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['surveyId'] ) != '' and trim($REQUEST_DATA['catId'] ) != '' ) {
     require_once(MODEL_PATH . "/FeedBackProvideAdvancedManager.inc.php");
     $fbMgr = FeedBackProvideAdvancedManager::getInstance();
     
     $surveyId=trim($REQUEST_DATA['surveyId']);
     $catId=trim($REQUEST_DATA['catId']);
     $rSubjectId=trim($REQUEST_DATA['subjectId']);
     $classId=-1;
     $subjectId=-1;
     
     $mainHeaderString ='<table border="0" cellpadding="0" cellspacing="0"  width="100%" height="100%">';
     $mainFooterString ='</table>';
     
     //check whether this category has comments
     $hasCommentsArray=$fbMgr->checkCategoryHasComments($catId);
     $hasComments=0;
     $hasDescription=0;
     if($hasCommentsArray[0]['hasFeedbackComments']==1){
         $hasComments=1;
         $commentsArray=$fbMgr->fetchGivenCategoryComments($surveyId,$catId,$userId,$rSubjectId);
         
         $commentsTextBoxString='<table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows"><b>Enter Your Comments</b></td>
                                  <td class="padding">:</td>
                                  <td class="padding">
                                   <textarea name="categoryComments" id="textarea_'.$catId.'_'.$rSubjectId.'_-1_-1" rows="3" cols="80" class="textarea_class"  maxlength="200" onkeyup="return ismaxlength(this);">'.trim($commentsArray[0]['comments']).'</textarea>
                                  </td>
                                  </tr>
                                  </table>'; 
     }
     if(trim($hasCommentsArray[0]['description'])!=''){
        $hasDescription=1; 
        $descriptionTextBoxString='<table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows" valign="top"><b>Description</b></td>
                                  <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                                  <td class="contenttab_internal_rows" valign="top">
                                   <b>'.nl2br(trim($hasCommentsArray[0]['description'])).
                                   '</b>
                                  </td>
                                  </tr>
        </table>';  
         
     }
     //***************************CODING LOGIC FOR TEACHER ROLE*****************************
     if($roleId==2){//for teachers
         //get mapped questions for given surveyId,catId,roleId and userId
         $questionSetArray=$fbMgr->fetchMappedQuestionsForTeachers($surveyId,$catId,$userId,$roleId);
         if(count($questionSetArray)==0 or !is_array($questionSetArray)){
             echo 0;
             die;
         }
         //fetch answer set options related to questions
         $questionSetIds=UtilityManager::makeCSList($questionSetArray,'feedbackQuestionId');
         if($questionSetIds!=''){
          $answerSetArray=$fbMgr->fetchAnswerSetRecords($questionSetIds);
          if(count($answerSetArray)==0 or !is_array($answerSetArray)){
              echo 0;
              die;
          }
          
          $mappingIds        = UtilityManager::makeCSList($questionSetArray,'feedbackMappingId');
          $mappedQuestionIds = UtilityManager::makeCSList($questionSetArray,'feedbackToQuestionId');
          
          /*NOW FETCH THE FILED ANSWERS(IF ANY) AND PRE-SELECT RADIO BUTTONS*/
          $preFilledAnswersArray    = $fbMgr->fetchPreviousAnswersForTeachers($mappingIds,$mappedQuestionIds,$userId);
          $preFilledAnswersCnt      = count($preFilledAnswersArray);
          
          //*****NOW BUILD THE TABLE STRUCTURE*****
          $tableString='';
          $questionsCnt=count($questionSetArray);
          $optionsCnt=count($answerSetArray);
          $tdCountFlag1=0;
          $tdCountFlag2=0;
          for($i=0;$i<$questionsCnt;$i++){
              $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
              $questionId=$questionSetArray[$i]['feedbackQuestionId'];
              $tableString .='<tr '.$bg.'>';
              $tableString .='<td class="padding_top" align="right" valign="top">'.($i+1).'</td>';
              $tdId=$questionId.'_-1_-1_'.$catId;
              $tableString .='<td class="padding_top" align="left" style="padding-left:3px;" id="'.$tdId.'">'.nl2br($questionSetArray[$i]['feedbackQuestion']).'</td>';
              $questionId=$questionSetArray[$i]['feedbackQuestionId'];
              $feedbackToQuestionId=$questionSetArray[$i]['feedbackToQuestionId'];
              $feedbackMappingId=$questionSetArray[$i]['feedbackMappingId'];
              $tdCountFlag2=0;
              $chkFlag=0;
              $naChk='';
              
              $tableString .='</tr><tr><td>&nbsp;</td><td valign="top" align="left">';
              $innerTableString='';
              $bg1='';
              for($j=0;$j<$optionsCnt;$j++){
                  $checked='';
                  if($questionId==$answerSetArray[$j]['feedbackQuestionId']){
                      $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
                      $innerTableString .='<tr '.$bg1.'>';
                      
                      $uId=$questionId.'_'.$answerSetArray[$j]['answerSetId'].'_'.$catId;
                      for($k=0;$k<$preFilledAnswersCnt;$k++){
                          if($preFilledAnswersArray[$k]['feedbackToQuestionId']==$feedbackToQuestionId and $preFilledAnswersArray[$k]['feedbackMappingId']==$feedbackMappingId and $preFilledAnswersArray[$k]['answerSetOptionId']==$answerSetArray[$j]['answerSetOptionId'] and $preFilledAnswersArray[$k]['userId']==$userId  ){
                             $chkFlag=1;
                             $checked='checked="checked"';
                              break;
                          }
                      }
                      $radioString ='<input onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.',-1,1); id="'.$uId.'_'.$j.'" type="radio" '.$checked.' name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_'.$answerSetArray[$j]['answerSetOptionId'].'" />&nbsp;';
                      $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">'.$answerSetArray[$j]['optionLabel'].'</label></td>';
                      $innerTableString .='</tr>';
                      $tdCountFlag2 ++;
                  }
              }
              
              if(!$chkFlag){
                  $naChk='checked="checked"';
              }
              $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
              $innerTableString .='<tr '.$bg1.'>';
              $radioString ='<input onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.',-1,0); alt="'.$tdId.'" id="'.$uId.'_'.$j.'" type="radio" name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_-1" '.$naChk.' />&nbsp;';
              $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">NA</label></td>';
              $innerTableString .='</tr>';
              $tdCountFlag2++;
              $innerTableString ='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
                                  $innerTableString
                                  .'</table>';
              
              if($tdCountFlag1<$tdCountFlag2){
                  $tdCountFlag1=$tdCountFlag2;
              }
              $tableString .= $innerTableString.'</td></tr><tr><td colspan="2" height="5px">&nbsp;</td></tr>';
          }
          $headerString ='<table border="0" cellpadding="1" cellspacing="1"  width="100%">
                          <tr class="rowheading">
                            <td class="searchhead_text" align="right" width="2%">#</td>
                            <td class="searchhead_text" align="left" width="70%" style="padding-left:3px;">Questions</td>
                          </tr>';
          $footerString = '</table>';
          
          //main table
          echo  $mainHeaderString;
         if($hasDescription==1){
              echo '<tr><td valign="top">'.$descriptionTextBoxString.'</td></tr>';
           } 
          echo '<tr><td valign="top">';
           //question table
           echo  $headerString.$tableString.$footerString;
          echo '</tr>'; 
           if($hasComments==1){
              echo '<tr><td valign="bottom">'.$commentsTextBoxString.'</td></tr>';
           }
          echo  $mainFooterString;
          
          //*****TABLE STRUCTURE ENDS*****
         }
         else{
             echo 0;
             die;
         }
         
     }
     //***************************CODING LOGIC FOR STUDENT ROLE*****************************
     else if($roleId==4){//for students
     
        //get mapped questions for given surveyId,catId,roleId and userId
         $questionSetArray=$fbMgr->fetchMappedQuestionsForStudents($surveyId,$catId,$userId,$roleId,$rSubjectId);
         if(count($questionSetArray)==0 or !is_array($questionSetArray)){
             echo 0;
             die;
         }
         //fetch answer set options related to questions
         $questionSetIds=UtilityManager::makeCSList($questionSetArray,'feedbackQuestionId');
         if($questionSetIds!=''){
          $answerSetArray=$fbMgr->fetchAnswerSetRecords($questionSetIds);
          if(count($answerSetArray)==0 or !is_array($answerSetArray)){
              echo 0;
              die;
          }
          
          $mappingIds        = UtilityManager::makeCSList($questionSetArray,'feedbackMappingId');
          $mappedQuestionIds = UtilityManager::makeCSList($questionSetArray,'feedbackToQuestionId');
          $mappedClassIds    = UtilityManager::makeCSList($questionSetArray,'genClassId');
          $mappedSubjectIds  = UtilityManager::makeCSList($questionSetArray,'genSubjectId');
          
          //now fetch teacher information[for category relation : Subject]
          $teacherArray=$fbMgr->fetchMappedTeacher($mappedClassIds,$mappedSubjectIds,$surveyId);
          $teacherCnt=0;
          if(is_array($teacherArray) and count($teacherArray)>0){
              $teacherCnt=count($teacherArray);
              
              /*$teacherAnswerArray=$fbMgr->fetchAnswersForMappedTeaches($mappedClassIds,$mappedSubjectIds,$surveyId);
              $selectedTeacherId='';
              if(is_array($teacherAnswerArray) and count($teacherAnswerArray)>0){
                  $selectedTeacherId=$teacherAnswerArray[0]['employeeId'];
              }
              
              //create teacher drop down
              $teacherString='';
              $selectedTeacherFlagString='';
              for($i=0;$i<$teacherCnt;$i++){
                if($selectedTeacherId==$teacherArray[$i]['employeeId']){ 
                  $teacherString .='<option value="'.$teacherArray[$i]['employeeId'].'" class="'.$teacherArray[$i]['groupId'].'_'.$teacherArray[$i]['classId'].'" selected="selected">'.trim($teacherArray[$i]['employeeName']).'</option>';
                  $selectedTeacherFlagString='disabled="disabled"';     
                }
                else{
                    $teacherString .='<option value="'.$teacherArray[$i]['employeeId'].'" class="'.$teacherArray[$i]['groupId'].'_'.$teacherArray[$i]['classId'].'">'.trim($teacherArray[$i]['employeeName']).'</option>';
                }
              }
              if($teacherString!=''){
                 $teacherString ='<select '.$selectedTeacherFlagString.' name="teacherId" id="teacherId_'.$catId.'_'.$teacherArray[0]['subjectId'].'" class="inputbox" style="width:220px;">'.$teacherString.'</select>';   
              }
              */
          }
          
          /*NOW FETCH THE FILED ANSWERS(IF ANY) AND PRE-SELECT RADIO BUTTONS*/
          $preFilledAnswersArray    = $fbMgr->fetchPreviousAnswersForStudents($mappingIds,$mappedQuestionIds,$userId,$mappedClassIds,$mappedSubjectIds);
          $preFilledAnswersCnt      = count($preFilledAnswersArray);
          
          //*****NOW BUILD THE TABLE STRUCTURE*****
          $tableString='';
          $questionsCnt=count($questionSetArray);
          $optionsCnt=count($answerSetArray);
          $tdCountFlag1=0;
          $tdCountFlag2=0;
          //$tempSubjectId=-1;
          for($i=0;$i<$questionsCnt;$i++){
              $questionId=$questionSetArray[$i]['feedbackQuestionId'];
              $genSubjectId=$questionSetArray[$i]['genSubjectId'];
              $genClassId=$questionSetArray[$i]['genClassId'];
              
              $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
              $tableString .='<tr '.$bg.'>';
              $tableString .='<td class="padding_top" align="right">'.($i+1).'</td>';
              $tdId=$questionId.'_'.$genClassId.'_'.$genSubjectId.'_'.$catId;
              
              $tableString .='<td class="padding_top" align="left" valign="top" style="padding-left:3px;" id="'.$tdId.'">'.nl2br($questionSetArray[$i]['feedbackQuestion']).'</td>';
              
              $feedbackToQuestionId=$questionSetArray[$i]['feedbackToQuestionId'];
              $feedbackMappingId=$questionSetArray[$i]['feedbackMappingId'];
              
              $tdCountFlag2=0;
              $chkFlag=0;
              $naChk='';
              
              $tableString .='</tr><tr><td>&nbsp;</td><td valign="top" align="left">';
              $innerTableString='';
              $bg1='';
              if($teacherCnt==0){ //if questions are not "subject type" specific
                  for($j=0;$j<$optionsCnt;$j++){
                      $checked='';
                      if($questionId==$answerSetArray[$j]['feedbackQuestionId']){
                          $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
                          $innerTableString .='<tr '.$bg1.'>';
                          $uId=$questionId.'_'.$answerSetArray[$j]['answerSetId'].'_'.$catId.'_'.$genClassId.'_'.$genSubjectId.'_-1_-1';
                          for($k=0;$k<$preFilledAnswersCnt;$k++){
                              if($preFilledAnswersArray[$k]['feedbackToQuestionId']==$feedbackToQuestionId and $preFilledAnswersArray[$k]['feedbackMappingId']==$feedbackMappingId and $preFilledAnswersArray[$k]['answerSetOptionId']==$answerSetArray[$j]['answerSetOptionId'] and $preFilledAnswersArray[$k]['classId']==$genClassId and $preFilledAnswersArray[$k]['subjectId']==$genSubjectId and  $preFilledAnswersArray[$k]['userId']==$userId  ){
                                 $chkFlag=1;
                                 $checked='checked="checked"';
                                  break;
                              }
                          }
                          $radioString ='<input onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.','.$genSubjectId.',1); type="radio" id="'.$uId.'_'.$j.'" '.$checked.' name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_'.$answerSetArray[$j]['answerSetOptionId'].'_'.$genClassId.'_'.$genSubjectId.'_-1_-1" />&nbsp;';
                          $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">'.$answerSetArray[$j]['optionLabel'].'</label></td>';
                          $innerTableString .='</tr>';
                          $tdCountFlag2 ++;
                      }
                  }
                  
                  if(!$chkFlag){
                      $naChk='checked="checked"';
                  }
                  $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
                  $innerTableString .='<tr '.$bg1.'>'; 
                  $radioString ='<input alt="'.$tdId.'" onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.','.$genSubjectId.',0); type="radio" id="'.$uId.'_'.$j.'" name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_-1_'.$genClassId.'_'.$genSubjectId.'_-1_-1" '.$naChk.' />&nbsp;';
                  $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">NA'.'</label></td>';
                  $innerTableString .='</tr>';
                  $tdCountFlag2++;
                  $innerTableString ='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
                                      $innerTableString
                                      .'</table>'; 
                  
                  if($tdCountFlag1<$tdCountFlag2){
                      $tdCountFlag1=$tdCountFlag2;
                  }
                  $tableString .= $innerTableString.'</td></tr><tr><td colspan="2" height="5px">&nbsp;</td></tr>';
              }
              else{  //if questions are "subject type" specific
                 $innerTableString=''; 
                 
                 $innerTableString='<tr class="rowheading">';
                 for($x=0;$x<$teacherCnt;$x++){
                    $innerTableString .='<td class="padding_top" align="left"><b>'.$teacherArray[$x]['employeeName'].'</b></td>';
                 }
                 $innerTableString .='</tr><tr>';
                 
                 for($x=0;$x<$teacherCnt;$x++){ //loop through each teacher
                  $innerTableString .='<td align="left" width="'.round(100/$teacherCnt).'%">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">';
                      $chkFlag=0;
                      for($j=0;$j<$optionsCnt;$j++){
                          $checked='';
                          if($questionId==$answerSetArray[$j]['feedbackQuestionId']){
                              $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
                              $innerTableString .='<tr '.$bg1.'>';
                              $uId=$questionId.'_'.$answerSetArray[$j]['answerSetId'].'_'.$catId.'_'.$genClassId.'_'.$genSubjectId.'_'.$teacherArray[$x]['employeeId'].'_'.$teacherArray[$x]['groupId'];
                              for($k=0;$k<$preFilledAnswersCnt;$k++){
                                  if($preFilledAnswersArray[$k]['feedbackToQuestionId']==$feedbackToQuestionId and $preFilledAnswersArray[$k]['feedbackMappingId']==$feedbackMappingId and $preFilledAnswersArray[$k]['answerSetOptionId']==$answerSetArray[$j]['answerSetOptionId'] and $preFilledAnswersArray[$k]['classId']==$genClassId and $preFilledAnswersArray[$k]['subjectId']==$genSubjectId and  $preFilledAnswersArray[$k]['userId']==$userId and $preFilledAnswersArray[$k]['employeeId']==$teacherArray[$x]['employeeId']  ){
                                     $chkFlag=1;
                                     $checked='checked="checked"';
                                     break;
                                  }
                              }
                              $radioString ='<input onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.','.$genSubjectId.',1); type="radio" id="'.$uId.'_'.$j.'" '.$checked.' name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_'.$answerSetArray[$j]['answerSetOptionId'].'_'.$genClassId.'_'.$genSubjectId.'_'.$teacherArray[$x]['employeeId'].'_'.$teacherArray[$x]['groupId'].'" />&nbsp;';
                              $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">'.$answerSetArray[$j]['optionLabel'].'</label></td>';
                              $innerTableString .='</tr>';
                              $tdCountFlag2 ++;
                          }
                      }
                      
                      if(!$chkFlag){
                          $naChk='checked="checked"';
                      }
                      $bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
                      $innerTableString .='<tr '.$bg1.'>'; 
                      $radioString ='<input alt="'.$tdId.'" onclick=checkIncompleteAnswer("'.$tdId.'",'.$catId.','.$genSubjectId.',0); type="radio" id="'.$uId.'_'.$j.'" name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_-1_'.$genClassId.'_'.$genSubjectId.'_'.$teacherArray[$x]['employeeId'].'_'.$teacherArray[$x]['groupId'].'" '.$naChk.' />&nbsp;';
                      $innerTableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'<label for="'.$uId.'_'.$j.'">NA'.'</label></td>';
                      $innerTableString .='</tr>';
                      $tdCountFlag2++;
                      $innerTableString .='</table></td>'; 
                  
                    if($tdCountFlag1<$tdCountFlag2){
                      $tdCountFlag1=$tdCountFlag2;
                    }
                  
                }
                $innerTableString .='</tr></table>';
                $tableString .= '<table border="0" cellpadding="0" cellspacing="2" width="100%">'.$innerTableString.'</td></tr><tr><td colspan="2" height="5px">&nbsp;</td></tr>';
              }
          }
          $headerString ='<table border="0" cellpadding="1" cellspacing="1"  width="100%">
                          <tr class="rowheading">
                            <td class="searchhead_text" align="right" width="2%">#</td>
                            <td class="searchhead_text" align="left" width="70%" style="padding-left:3px;">Questions</td>
                          </tr>';
          $footerString = '</table>';
          
          //main table
          echo  $mainHeaderString;
          if($hasDescription==1){
              echo '<tr><td valign="top" colspan="2" align="left">'.$descriptionTextBoxString.'</td></tr>';
          }
          /*
          if($teacherString!=''){
            echo '<tr><td class="contenttab_internal_rows" width="8%"><nobr><b>Choose Teacher : </b></nobr></td>';
            echo '<td class="padding">';
             echo $teacherString;
            echo '</tr>';   
          }
          */
          echo '<tr><td valign="top" colspan="2">';
           //question table
          echo  $headerString.$tableString.$footerString;
          echo '</tr>'; 
           if($hasComments==1){
            if($teacherCnt==0){   
              echo '<tr><td valign="bottom" colspan="2" align="left">'.$commentsTextBoxString.'</td></tr>';
            }
            else{
              echo '<tr><td valign="bottom" colspan="2" align="left" width="100%">';
              echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
              echo '<tr>';
              for($z=0;$z<$teacherCnt;$z++){
                  echo '<td class="contenttab_internal_rows" style="padding-left:5px;"  nowrap="nowrap"><b>Enter Your Comments For '.$teacherArray[$z]['employeeName'].'</b></td>';
              }
              echo '</tr>';
              echo '<tr>';
              for($z=0;$z<$teacherCnt;$z++){
                 $commentsArray=$fbMgr->fetchGivenCategoryComments($surveyId,$catId,$userId,$rSubjectId,$teacherArray[$z]['employeeId'],$teacherArray[$z]['classId']);
         
                 $commentsTextBoxString='<table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows">
                                   <textarea name="categoryComments" id="textarea_'.$catId.'_'.$rSubjectId.'_'.$teacherArray[$z]['employeeId'].'_'.$teacherArray[$z]['classId'].'" rows="3" cols="50" class="textarea_class"  maxlength="200" onkeyup="return ismaxlength(this);">'.trim($commentsArray[0]['comments']).'</textarea>
                                  </td>
                                  </tr>
                                  </table>'; 
                 echo '<td class="padding">'.$commentsTextBoxString.'</td>';
              }
              echo '</tr></table>';
              echo '</td></tr>';
            }
           }
           
          echo  $mainFooterString;
          
          //*****TABLE STRUCTURE ENDS*****
         }
         else{
             echo 0;
             die;
         }
     }
    }
    else {
        echo 0;
    }
    


/*************OLD TABLE STRUCTURE------ALL OPTIONS ARE IN SAME ROW WITH EACH QUESTIONS************
if(trim($REQUEST_DATA['surveyId'] ) != '' and trim($REQUEST_DATA['catId'] ) != '' ) {
     require_once(MODEL_PATH . "/FeedBackProvideAdvancedManager.inc.php");
     $fbMgr = FeedBackProvideAdvancedManager::getInstance();
     
     $surveyId=trim($REQUEST_DATA['surveyId']);
     $catId=trim($REQUEST_DATA['catId']);
     $rSubjectId=trim($REQUEST_DATA['subjectId']);
     $classId=-1;
     $subjectId=-1;
     
     $mainHeaderString ='<table border="0" cellpadding="0" cellspacing="0"  width="100%" height="100%">';
     $mainFooterString ='</table>';
     
     //check whether this category has comments
     $hasCommentsArray=$fbMgr->checkCategoryHasComments($catId);
     $hasComments=0;
     $hasDescription=0;
     if($hasCommentsArray[0]['hasFeedbackComments']==1){
         $hasComments=1;
         $commentsArray=$fbMgr->fetchGivenCategoryComments($surveyId,$catId,$userId,$rSubjectId);
         
         $commentsTextBoxString='<table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows"><b>Enter Your Comments</b></td>
                                  <td class="padding">:</td>
                                  <td class="padding">
                                   <textarea name="categoryComments" id="textarea_'.$catId.'_'.$rSubjectId.'" rows="3" cols="80" class="textarea_class"  maxlength="200" onkeyup="return ismaxlength(this);">'.trim($commentsArray[0]['comments']).'</textarea>
                                  </td>
                                  </tr>
                                  </table>'; 
     }
     if(trim($hasCommentsArray[0]['description'])!=''){
        $hasDescription=1; 
        $descriptionTextBoxString='<table border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                  <td class="contenttab_internal_rows"><b>Description</b></td>
                                  <td class="padding">:</td>
                                  <td class="contenttab_internal_rows">
                                   <b>'.trim($hasCommentsArray[0]['description']).
                                   '</b>
                                  </td>
                                  </tr>
        </table>';  
         
     }
     //***************************CODING LOGIC FOR TEACHER ROLE*****************************
     if($roleId==2){//for teachers
         //get mapped questions for given surveyId,catId,roleId and userId
         $questionSetArray=$fbMgr->fetchMappedQuestionsForTeachers($surveyId,$catId,$userId,$roleId);
         if(count($questionSetArray)==0 or !is_array($questionSetArray)){
             echo 0;
             die;
         }
         //fetch answer set options related to questions
         $questionSetIds=UtilityManager::makeCSList($questionSetArray,'feedbackQuestionId');
         if($questionSetIds!=''){
          $answerSetArray=$fbMgr->fetchAnswerSetRecords($questionSetIds);
          if(count($answerSetArray)==0 or !is_array($answerSetArray)){
              echo 0;
              die;
          }
          
          $mappingIds        = UtilityManager::makeCSList($questionSetArray,'feedbackMappingId');
          $mappedQuestionIds = UtilityManager::makeCSList($questionSetArray,'feedbackToQuestionId');
          
          /*NOW FETCH THE FILED ANSWERS(IF ANY) AND PRE-SELECT RADIO BUTTONS*/
          //$preFilledAnswersArray    = $fbMgr->fetchPreviousAnswersForTeachers($mappingIds,$mappedQuestionIds,$userId);
          //$preFilledAnswersCnt      = count($preFilledAnswersArray);
          
          //*****NOW BUILD THE TABLE STRUCTURE*****
        /*  
          $tableString='';
          $questionsCnt=count($questionSetArray);
          $optionsCnt=count($answerSetArray);
          $tdCountFlag1=0;
          $tdCountFlag2=0;
          for($i=0;$i<$questionsCnt;$i++){
              $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
              $tableString .='<tr '.$bg.'>';
              $tableString .='<td class="padding_top" align="right">'.($i+1).'</td>';
              $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$questionSetArray[$i]['feedbackQuestion'].'</td>';
              $questionId=$questionSetArray[$i]['feedbackQuestionId'];
              $feedbackToQuestionId=$questionSetArray[$i]['feedbackToQuestionId'];
              $feedbackMappingId=$questionSetArray[$i]['feedbackMappingId'];
              $tdCountFlag2=0;
              $chkFlag=0;
              $naChk='';
              
              for($j=0;$j<$optionsCnt;$j++){
                  $checked='';
                  if($questionId==$answerSetArray[$j]['feedbackQuestionId']){
                      $uId=$questionId.'_'.$answerSetArray[$j]['answerSetId'].'_'.$catId;
                      for($k=0;$k<$preFilledAnswersCnt;$k++){
                          if($preFilledAnswersArray[$k]['feedbackToQuestionId']==$feedbackToQuestionId and $preFilledAnswersArray[$k]['feedbackMappingId']==$feedbackMappingId and $preFilledAnswersArray[$k]['answerSetOptionId']==$answerSetArray[$j]['answerSetOptionId'] and $preFilledAnswersArray[$k]['userId']==$userId  ){
                             $chkFlag=1;
                             $checked='checked="checked"';
                              break;
                          }
                      }
                      $radioString ='<input type="radio" '.$checked.' name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_'.$answerSetArray[$j]['answerSetOptionId'].'" />&nbsp;';
                      $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.$answerSetArray[$j]['optionLabel'].'</td>';
                      $tdCountFlag2 ++;
                  }
              }
              
              if(!$chkFlag){
                  $naChk='checked="checked"';
              }
              $radioString ='<input type="radio" name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_-1" '.$naChk.' />&nbsp;';
              $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'NA'.'</td>';
              $tableString .='</tr>';
              $tdCountFlag2++;
              
              if($tdCountFlag1<$tdCountFlag2){
                  $tdCountFlag1=$tdCountFlag2;
              }
          }
          $headerString ='<table border="0" cellpadding="1" cellspacing="1"  width="100%">
                          <tr class="rowheading">
                            <td class="searchhead_text" align="right" width="2%">#</td>
                            <td class="searchhead_text" align="left" width="70%" style="padding-left:3px;">Questions</td>
                            <td class="searchhead_text" colspan="'.$tdCountFlag1.'" align="center" width="28%">Options</td>
                          </tr>';
          $footerString = '</table>';
          
          //main table
          echo  $mainHeaderString;
          echo '<tr><td valign="top">';
           //question table
           echo  $headerString.$tableString.$footerString;
          echo '</tr>'; 
           if($hasComments==1){
              echo '<tr><td valign="bottom">'.$commentsTextBoxString.'</td></tr>';
           }
           if($hasDescription==1){
              echo '<tr><td valign="bottom">'.$descriptionTextBoxString.'</td></tr>';
           }
          echo  $mainFooterString;
          
          //*****TABLE STRUCTURE ENDS*****
         }
         else{
             echo 0;
             die;
         }
         
     }
     //***************************CODING LOGIC FOR STUDENT ROLE*****************************
     else if($roleId==4){//for students
     
        //get mapped questions for given surveyId,catId,roleId and userId
         $questionSetArray=$fbMgr->fetchMappedQuestionsForStudents($surveyId,$catId,$userId,$roleId,$rSubjectId);
         if(count($questionSetArray)==0 or !is_array($questionSetArray)){
             echo 0;
             die;
         }
         //fetch answer set options related to questions
         $questionSetIds=UtilityManager::makeCSList($questionSetArray,'feedbackQuestionId');
         if($questionSetIds!=''){
          $answerSetArray=$fbMgr->fetchAnswerSetRecords($questionSetIds);
          if(count($answerSetArray)==0 or !is_array($answerSetArray)){
              echo 0;
              die;
          }
          
          $mappingIds        = UtilityManager::makeCSList($questionSetArray,'feedbackMappingId');
          $mappedQuestionIds = UtilityManager::makeCSList($questionSetArray,'feedbackToQuestionId');
          $mappedClassIds    = UtilityManager::makeCSList($questionSetArray,'genClassId');
          $mappedSubjectIds  = UtilityManager::makeCSList($questionSetArray,'genSubjectId');
          
          /*NOW FETCH THE FILED ANSWERS(IF ANY) AND PRE-SELECT RADIO BUTTONS*/
          //$preFilledAnswersArray    = $fbMgr->fetchPreviousAnswersForStudents($mappingIds,$mappedQuestionIds,$userId,$mappedClassIds,$mappedSubjectIds);
          //$preFilledAnswersCnt      = count($preFilledAnswersArray);
          
          //*****NOW BUILD THE TABLE STRUCTURE*****
          /*
          $tableString='';
          $questionsCnt=count($questionSetArray);
          $optionsCnt=count($answerSetArray);
          $tdCountFlag1=0;
          $tdCountFlag2=0;
          //$tempSubjectId=-1;
          for($i=0;$i<$questionsCnt;$i++){
              $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
              $tableString .='<tr '.$bg.'>';
              $tableString .='<td class="padding_top" align="right">'.($i+1).'</td>';
              $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$questionSetArray[$i]['feedbackQuestion'].'</td>';
              $questionId=$questionSetArray[$i]['feedbackQuestionId'];
              $feedbackToQuestionId=$questionSetArray[$i]['feedbackToQuestionId'];
              $feedbackMappingId=$questionSetArray[$i]['feedbackMappingId'];
              $genSubjectId=$questionSetArray[$i]['genSubjectId'];
              $genClassId=$questionSetArray[$i]['genClassId'];
              $tdCountFlag2=0;
              $chkFlag=0;
              $naChk='';
              
              for($j=0;$j<$optionsCnt;$j++){
                  $checked='';
                  if($questionId==$answerSetArray[$j]['feedbackQuestionId']){
                      $uId=$questionId.'_'.$answerSetArray[$j]['answerSetId'].'_'.$catId.'_'.$genClassId.'_'.$genSubjectId;
                      for($k=0;$k<$preFilledAnswersCnt;$k++){
                          if($preFilledAnswersArray[$k]['feedbackToQuestionId']==$feedbackToQuestionId and $preFilledAnswersArray[$k]['feedbackMappingId']==$feedbackMappingId and $preFilledAnswersArray[$k]['answerSetOptionId']==$answerSetArray[$j]['answerSetOptionId'] and $preFilledAnswersArray[$k]['classId']==$genClassId and $preFilledAnswersArray[$k]['subjectId']==$genSubjectId and  $preFilledAnswersArray[$k]['userId']==$userId  ){
                             $chkFlag=1;
                             $checked='checked="checked"';
                              break;
                          }
                      }
                      $radioString ='<input type="radio" '.$checked.' name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_'.$answerSetArray[$j]['answerSetOptionId'].'_'.$genClassId.'_'.$genSubjectId.'" />&nbsp;';
                      $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.$answerSetArray[$j]['optionLabel'].'</td>';
                      $tdCountFlag2 ++;
                  }
              }
              
              if(!$chkFlag){
                  $naChk='checked="checked"';
              }
              $radioString ='<input type="radio" name="radio_'.$uId.'" value="'.$feedbackToQuestionId.'_'.$feedbackMappingId.'_-1_'.$genClassId.'_'.$genSubjectId.'" '.$naChk.' />&nbsp;';
              $tableString .='<td class="padding_top" align="left" style="padding-left:3px;">'.$radioString.'NA'.'</td>';
              $tableString .='</tr>';
              $tdCountFlag2++;
              
              if($tdCountFlag1<$tdCountFlag2){
                  $tdCountFlag1=$tdCountFlag2;
              }
          }
          $headerString ='<table border="0" cellpadding="1" cellspacing="1"  width="100%">
                          <tr class="rowheading">
                            <td class="searchhead_text" align="right" width="2%">#</td>
                            <td class="searchhead_text" align="left" width="70%" style="padding-left:3px;">Questions</td>
                            <td class="searchhead_text" colspan="'.$tdCountFlag1.'" align="center" width="28%">Options</td>
                          </tr>';
          $footerString = '</table>';
          
          //main table
          echo  $mainHeaderString;
          echo '<tr><td valign="top">';
           //question table
           echo  $headerString.$tableString.$footerString;
          echo '</tr>'; 
           if($hasComments==1){
              echo '<tr><td valign="bottom">'.$commentsTextBoxString.'</td></tr>';
           }
           if($hasDescription==1){
              echo '<tr><td valign="bottom">'.$descriptionTextBoxString.'</td></tr>';
           }
          echo  $mainFooterString;
          
          //*****TABLE STRUCTURE ENDS*****
         }
         else{
             echo 0;
             die;
         }
     }
    }
    else {
        echo 0;
    }
*/


// $History: ajaxGetAllocatedQuestionsForUsers.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:58p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Created "Feedback Comments Report"
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/02/10   Time: 17:50
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Changed internal logic : Now along with class,subject and employee ,
//group information will also be stored in feedback survey answer table.
//This is needed to place add/edit/delete check in teacher mapping
//module.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 22/02/10   Time: 12:20
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modifications :
//1.Showing Yes/No/Partial status for student feedback status in report.
//2.Highlight tabs and questions when NA is selected.
//3.Changed status message when partial feedback is given
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/02/10   Time: 12:01
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added Features :
//1.Changed the logic of incomplete feedback algorithm.
//2.Showing category description in top of the tabs instead of bottom.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/02/10    Time: 17:40
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Corrected application logic related to survey id check
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 28/01/10   Time: 17:10
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made modifications as instructed by Sachin sir :
//1. Comments are not mandatory
//2. Options should come after questions in a seperate place.
//3. Tab order problem corrected.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/01/10   Time: 17:19
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added printOrder checks for answer set options 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:16
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Provide Feedback" Module
?>