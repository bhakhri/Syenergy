<?php
//------------------------------------------------------------------
// THIS FILE IS USED TO Allocate/De-allocate Adv. Feedback Questions
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackProvideAdvancedManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transactions
define('MODULE','ADVFB_ProvideFeedBack');
define('ACCESS','add');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$userId=$sessionHandler->getSessionVariable('UserId');

if($roleId==2){//for teacher
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==3){//for parent
   //not implemented till now
   redirectBrowser(UI_HTTP_PATH.'/Parent/index.php');
}
else if($roleId==4){ //for student
  /*
  if($sessionHandler->getSessionVariable('SuperUserId')!=''){
    echo ACCESS_DENIED;
    die;
  }
  */
  UtilityManager::ifStudentNotLoggedIn(true);
}
else{
  if($roleId==1){
    redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
    die;
  }
  echo SESSION_TIMEOUT;
  die;
}
UtilityManager::headerNoCache();

  function clearSpecialChar($text) {
       if($text!="") {
         //$text=strtolower($text);
         $code_entities_match = array("'",'"',"`");
         $code_entities_replace = array('','',"");
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }

$completeFlag=1;
$labelId                = trim($REQUEST_DATA['surveyId']);
$mappingIds             = trim($REQUEST_DATA['mappingTo']);
$feedbackToQuestionIds  = trim($REQUEST_DATA['questionTo']);
$optionIds              = trim($REQUEST_DATA['optionTo']);
$catIds                 = trim($REQUEST_DATA['catIds']);
$catComments            = trim($REQUEST_DATA['catComments']);
$questionIdsArray       = explode(',',$feedbackToQuestionIds);

$subjectIds1            = trim($REQUEST_DATA['subId1']);
$subjectIds2            = trim($REQUEST_DATA['subId2']);
$employeeIds            = trim($REQUEST_DATA['teacherIds']);

$groupIds               = trim($REQUEST_DATA['groupIds']);
$employeeIdsForComments = trim($REQUEST_DATA['employeeIds']);

$classIds               = trim($REQUEST_DATA['classId']);
$classIds               = str_replace(',undefined','',$classIds);
$classIds               = str_replace('undefined','',$classIds);


$classCommentsIds       = trim($REQUEST_DATA['classCommentsIds']);
$finalIds=trim($REQUEST_DATA['finalIds']);
$subjectIDD=trim($REQUEST_DATA['subId1']).",".trim($REQUEST_DATA['subId2']);

if($labelId=='' or $mappingIds=='' or $feedbackToQuestionIds=='' or $optionIds==''){
    echo 'Required Parameters Missing';
    die;
}



$mappingIdArray              = explode(',',$mappingIds);
$feedbackToQuestionIdArray   = explode(',',$feedbackToQuestionIds);
$optionIdsArray              = explode(',',$optionIds);
$subjectIdsArray1            = explode(',',$subjectIds1);
$subjectIdsArray2            = explode(',',$subjectIds2);
$employeeIdsArray            = explode(',',$employeeIds);
$groupIdsArray              = explode(',',$groupIds);
$employeeIdsArrayForComments = explode(',',$employeeIdsForComments);
$classIdsArray               = explode(',',$classIds);
$classCommentsIdsArray       = explode(',',$classCommentsIds);
$mappingLength=count($mappingIdArray);
$empCnt=count($employeeIdsArray);


//print_r($optionIdsArray); die();
//Checking Number of unanswered answers as they contain -1 
//$searchNoOfUnansweredAnswers=array_search(-1,$optionIdsArray);
$newArrayAnswerCount=array_count_values($optionIdsArray);
$searchNoOfUnansweredAnswers=$newArrayAnswerCount[-1];
    


//checking comments
if($catIds!=''){
    $catIdArray=explode(',',$catIds);
    $catIdlen=count($catIdArray);
    $catCommentsArray=explode('@!$%*%^~!@',$catComments);
    for($i=0;$i<$catIdlen;$i++){
        // AS COMMENTS ARE NOT MANDATORY
        $catCommentsArray[$i] =  trim(clearSpecialChar($catCommentsArray[$i])); 
        $catCommentsArray[$i] =  htmlentities(add_slashes($catCommentsArray[$i]));
	/*        
	if(trim($catCommentsArray[$i])==''){
          echo 'Enter your comments';
          die;
        }
*/
    }
}


$fbMgr = FeedBackProvideAdvancedManager::getInstance();

//find the number of attempts of this user for this survey label
$surveyAttemptsArray = $fbMgr->getNumberOfAttempts($labelId);
if(count($surveyAttemptsArray)>0 and is_array($surveyAttemptsArray)){
    $surveyAttempt=intval($surveyAttemptsArray[0]['noOfAttempts']);
}
else{
    echo ADV_NUMBER_OF_ATTEMPTS_MISSING;
    die;
}

$attemptsArray = $fbMgr->getUserNumberOfAttempts($userId,$questionIdsArray[0]); //we are using 0th as all have the same surveyId
$userAttempts=0;
if($attemptsArray[0]['attempts']!=''){
 $userAttempts  = intval($attemptsArray[0]['attempts']);
}

//******we have decided that attempts : 0,means no restriction**********
if( ( $surveyAttempt==$userAttempts ) and $surveyAttempt!=0){
    echo ADV_ALLOWED_NUMBER_OF_ATTEMPTS_REACHED;
    die;
}

$currentDate=date('Y-m-d');
        
    /********START TRANSACTION*******/
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
       //***************************CODING LOGIC FOR TEACHER ROLE***************************** 
       if($roleId==2) { //for teachers
           
           //check for allocated questions with answered questions
            $totalQuestionsArray=$fbMgr->totalFetchMappedQuestionsForTeachers($labelId,$userId,$roleId);
            $totalQuestions=intval($totalQuestionsArray[0]['cnt']);
            if($mappingLength !=$totalQuestions){
                echo 'Number of questions allocated to this user is mismatched with number of questions answered';
                die;
            }
            
           //first delete existing comments and then make fresh inserts
           if($catIdlen>0){
               $ret=$fbMgr->deleteCategoryCommentsForTeachers($userId,$labelId,$catIds);
               if($ret==false){
                 echo FAILURE;
                 die;  
               }
               $commentInsertString='';
               for($i=0;$i<$catIdlen;$i++){
                  if(trim(add_slashes($comments))!='') {  
                      if($commentInsertString!=''){
                           $commentInsertString .=' , ';
                      } 
                      $comments = trim($catCommentsArray[$i]);
                      $comments = str_replace("'","", $comments);
                      $comments = str_replace('"',"", $comments);
                      
                      //$comments = htmlentities(add_slashes($comments));
                      $commentInsertString .= "( '$labelId' , '$catIdArray[$i]' , NULL , NULL , NULL , '".$currentDate."' , '$userId' , '".trim(add_slashes($comments))."')";
                  }
               }
               if($commentInsertString!=''){
                 $ret=$fbMgr->insertCategoryComments($commentInsertString);
                 if($ret==false){
                   echo FAILURE;
                   die;
                 }
               }
           }
           
           
          //first delete the existing answers and then make fresh inserts
          $ret=$fbMgr->deleteFeedBackAnswersForTeachers($userId,$mappingIds,$feedbackToQuestionIds);
          if($ret==false){
              echo FAILURE;
              die;  
          }
          
          $insertAnswerString='';
          for($i=0;$i<$mappingLength;$i++){
              if($insertAnswerString!=''){
                  $insertAnswerString .=',';
              }
               $ttOptionId=$optionIdsArray[$i];
               if($optionIdsArray[$i]=='-1') {
                  $ttOptionId="NULL";                   
               }
              
              $insertAnswerString .= " ( '$feedbackToQuestionIdArray[$i]' , '$mappingIdArray[$i]' , '$userId' , $ttOptionId , NULL , NULL , NULL, NULL , '".($userAttempts+1)."' , '".$currentDate."' ) ";
          }
          if($insertAnswerString!=''){
            $ret=$fbMgr->insertFeedbackAnswers($insertAnswerString);
            if($ret==false){
             echo FAILURE;
             die;
            }
          }
          
       //if total no. of questions allocated is equal to total no. of quesstions answered for ALL applicable labels
       //then feedback respnse will be partial
       //fetch allocated labels to this user
       $labelArray=CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId);
       $labelCnt=count($labelArray);
       $labelString='';

       $allTotalQuestionCount=0;
       $allTotalQuestionAnsweredCount=0;
       for($i=0;$i<$labelCnt;$i++){
          if($labelString!=''){
              $labelString .=',';
          }
          $labelString .=$labelArray[$i]['feedbackSurveyId'];
       }
       
       $labelString = $labelId;
     
       if($labelString!=''){
        //check for total questions allocated for this userId for all labels in the date range   
        $allTotalQuestionsArray=$fbMgr->totalFetchMappedQuestionsForTeachers($labelString,$userId,$roleId);
        $allTotalQuestionCount=intval($allTotalQuestionsArray[0]['cnt']);
        //check for total questions answered by this user for all labels in the date range----we are using same
        //function for teachers
        $allTotalQuestionsAnsweredArray=$fbMgr->totalAnsweresForMappedQuestionsForStudents($labelString,$userId,$roleId);
        $allTotalQuestionAnsweredCount=intval($allTotalQuestionsAnsweredArray[0]['cnt']);
       }
       
       if($allTotalQuestionCount==$allTotalQuestionAnsweredCount){
          $completeFlag=1;
       }
       else{
          $completeFlag=0;
       }
          
    }
    //***************************CODING LOGIC FOR STUDENT ROLE*****************************
    else{ //for students
    
             $totalQuestions=0;
            //check for allocated questions with answered questions
            $studentClassId=implode(',',array_unique(explode(',',$classIds)));
            if($subjectIds2!=''){
                $subjectIds2=implode(',',array_unique(explode(',',$subjectIds2)));
                $subArray2=explode(',',$subjectIds2);
                $cnt2=count($subArray2);
                
                //fetch total questions and teachers for each subject
                for($x=0;$x<$cnt2;$x++){
                 if($subArray2[$x]==-1){//if it is not subject centric
                    $totalQuestionsArray2=$fbMgr->totalFetchMappedQuestionsForStudentsWithOutTeacher($labelId,$userId,$roleId,-1);
                    $questions=$totalQuestionsArray2[0]['cnt'];
                 }
                 else{
                    $totalQuestionsArray2=$fbMgr->totalFetchMappedQuestionsForStudentsWithTeacher($labelId,$userId,$roleId,$subArray2[$x]);
                    $questions=$totalQuestionsArray2[0]['cnt'];
                    $totalTeacherArray=$fbMgr->fetchAllocatedTeachersForThisSubject($studentClassId,$subArray2[$x],$labelId);
                    $allocatedTeachers=$totalTeacherArray[0]['cnt'];
                    if($allocatedTeachers==0){
                        $allocatedTeachers=1;
                    }
                    //echo $subArray2[$x].'='.$questions.'--'.$allocatedTeachers.'*';
                    $questions =$questions*$allocatedTeachers;
                    
                 }
                 $totalQuestions +=$questions;
                }
            }
            
            //$totalQuestionsArray=$fbMgr->totalFetchMappedQuestionsForStudents($labelId,$userId,$roleId,$subjectIds2);
            //$totalQuestions=intval($totalQuestionsArray[0]['cnt']);
            //echo $mappingLength .'!='.$totalQuestions;
            //die;
            
            /*
            if($mappingLength !=$totalQuestions){
               echo 'Number of questions allocated to this user is mismatched with number of questions answered';
               die;
            }
            */
            
            //check for duplicated answers for teachers[feedback corresponding to one subject must be given for one teacher ONLY]
            $sub2Cnt=count($subjectIdsArray2);
            $subString='';
            $classString='';
            for($i=0;$i<$sub2Cnt;$i++){
                if($subjectIdsArray2[$i]!=-1 and $classIdsArray[$i]!=-1){
                    if($subString!=''){
                        $subString .=',';
                        $classString .=',';
                    }
                    $subString .=$subjectIdsArray2[$i];
                    $classString .=$classIdsArray[$i];
                }
            }
            
            /* THIS CHECK HAS BEEN REMOVED
            if($subString!=''){
                   $previousTeacherAnswerArray=$fbMgr->fetchPreviousAnswersForMappedTeachers($userId,$classString,$subString,$labelId);
                   if(is_array($previousTeacherAnswerArray) and count($previousTeacherAnswerArray)>0){
                       $previousTeacherAnswerCnt=count($previousTeacherAnswerArray);
                       $subCnt=count($subjectIdsArray2);
                       for($i=0;$i<$previousTeacherAnswerCnt;$i++){
                           for($j=0;$j<$subCnt;$j++){
                               if($subjectIdsArray2[$j]==-1){
                                   continue;
                               }
                              if($subjectIdsArray2[$j]==$previousTeacherAnswerArray[$i]['subjectId'] and $classIdsArray[$j]==$previousTeacherAnswerArray[$i]['classId']){
                                 for($k=0;$k<$empCnt;$k++){
                                     $empExploded=explode('_',$employeeIdsArray[$k]);
                                     if($empExploded[1]==$subjectIdsArray2[$j] and $empExploded[0]!=$previousTeacherAnswerArray[$i]['employeeId']){
                                        echo 'You cannot choose different teachers for the same subject after first attempt.';
                                        die;
                                     }
                                 } 
                              }
                           }
                       }
                   }
            }
            */ 
            
           //first delete existing comments and then make fresh inserts
           if($catIdlen>0){
               $deleteString1='';
               $deleteString2='';
               $commentInsertString='';
               for($i=0;$i<$catIdlen;$i++){
                   if($classCommentsIdsArray[$i]!=-1 and $subjectIdsArray1[$i]!=-1 and $employeeIdsArrayForComments[$i]!=-1){
                       if($deleteString1!=''){
                          $deleteString1 .=',';
                       }
                       $deleteString1 .="'".$labelId.'~'.$catIdArray[$i].'~'.$classCommentsIdsArray[$i].'~'.$subjectIdsArray1[$i].'~'.$employeeIdsArrayForComments[$i].'~'.$userId."'";
                       $comments = trim($catCommentsArray[$i]);
                       $comments = str_replace("'","", $comments);
                       $comments = str_replace('"',"", $comments);
                       
                       if(trim(add_slashes($catCommentsArray[$i]))!='') { 
                          if($classCommentsIdsArray[$i]=='') {
                            $classCommentsIdsArray[$i] = 'NULL';  
                          } 
                          if($subjectIdsArray1[$i]=='') {
                            $subjectIdsArray1[$i] = 'NULL';  
                          } 
                          if($employeeIdsArrayForComments[$i]=='') {
                            $employeeIdsArrayForComments[$i] = 'NULL';  
                          } 
                          if($commentInsertString!=''){
                             $commentInsertString .=',';
                          }
                          $commentInsertString .= "( $labelId , $catIdArray[$i] , $classCommentsIdsArray[$i], $subjectIdsArray1[$i], $employeeIdsArrayForComments[$i], '".$currentDate."' , $userId , '".$comments."')";
                       }
                   }
                  else{
                     if($deleteString2!=''){
                       $deleteString2 .=',';
                     }
                     $deleteString2 .="'".$labelId.'~'.$catIdArray[$i].'~'.$userId."'"; 
                     $comments = trim($catCommentsArray[$i]);
                     $comments = str_replace("'","", $comments);
                     $comments = str_replace('"',"", $comments);
                     
                     if(trim(add_slashes($comments))!='') { 
                        if($commentInsertString!=''){
                          $commentInsertString .=',';
                        } 
                        $commentInsertString .= "( $labelId , $catIdArray[$i] , NULL, NULL ,NULL , '".$currentDate."' , '$userId' , '".trim(add_slashes($comments))."')";
                     }
                  } 
               }
               if($deleteString1!=''){
                $ret=$fbMgr->deleteCategoryCommentsForStudents($deleteString1,1);
                if($ret==false){
                 echo FAILURE;
                 die;  
                }
               }
               if($deleteString2!=''){
                $ret=$fbMgr->deleteCategoryCommentsForStudents($deleteString2,2);
                if($ret==false){
                 echo FAILURE;
                 die;  
                }
               }

               if($commentInsertString!=''){
                 $ret=$fbMgr->insertCategoryComments($commentInsertString);
                 if($ret==false){
                  echo FAILURE;
                  die;
                 }
               }
           }
           
           
          //first delete the existing answers and then make fresh inserts(delete answers which are subject and class specific)
          $ret=$fbMgr->deleteFeedBackAnswersForStudents($userId,$mappingIds,$feedbackToQuestionIds,$classIds,$subjectIds2);
          if($ret==false){
              echo FAILURE;
              die;  
          }

		  //first delete the existing answers and then make fresh inserts(delete answers which are not subject and class specific i.e , general type)
          $ret=$fbMgr->deleteFeedBackAnswersForStudents($userId,$mappingIds,$feedbackToQuestionIds,-1,-1,' AND ( classId IS NULL AND subjectId IS NULL ) ' );
          if($ret==false){
              echo FAILURE;
              die;  
          }
          
          $insertAnswerString='';
          $employeeId='';
          for($i=0;$i<$mappingLength;$i++){
              $employeeId='';
              
              $ttAnswerOptionId = $optionIdsArray[$i];   
              
              if($insertAnswerString!=''){
                 $insertAnswerString .=',';
              }
              if($classIdsArray[$i]!=-1 and $subjectIdsArray2[$i]!=-1){
                  /*
                  //extracting empoyeeId and groupId
                  for($k=0;$k<$empCnt;$k++){
                      $empExploded=explode('_',$employeeIdsArray[$k]);
                      if($empExploded[1]==$subjectIdsArray2[$i]){
                          $employeeId=$empExploded[0];
                          $groupId=$empExploded[2];
                          break;
                      }
                  }
                  if($employeeId==''){
                      echo "Choose a teacher";
                      die;
                  }
                  */
                  if($groupIdsArray[$i]=='' or  $employeeIdsArray[$i]==''){
                      die('Required Parametes Missing');
                  }
                  $classIdsArray[$i]=$classIdsArray[$i]=='-1'?NULL:$classIdsArray[$i];  
                  $subjectIdsArray2[$i]=$subjectIdsArray2[$i]=='-1'?NULL:$subjectIdsArray2[$i];
                  $groupIdsArray[$i]=$groupIdsArray[$i]=='-1'?NULL:$groupIdsArray[$i];
                  $employeeIdsArray[$i]=$employeeIdsArray[$i]=='-1'?NULL:$employeeIdsArray[$i];
                  //$insertAnswerString .= " ( $feedbackToQuestionIdArray[$i] , $mappingIdArray[$i] , $userId , $optionIdsArray[$i] , $classIdsArray[$i] , $subjectIdsArray2[$i] , $groupIdsArray[$i] , $employeeIdsArray[$i] , ".($userAttempts+1)." , '".$currentDate."' ) ";      
                  $ttClassId=$classIdsArray[$i];   
                  if($classIdsArray[$i]=='-1') {
                    $ttClassId =NULL;  
                  }
              
                  $ttAnswerOptionId = $optionIdsArray[$i];
                  if($optionIdsArray[$i]=='-1') {
                    $ttAnswerOptionId =NULL;  
                  }
                  
                  if($ttAnswerOptionId=='') {
                     $ttAnswerOptionId='NULL';  
                  }
                   
                  if($userId=='') {
                     $userId='NULL';  
                  }
                  
                  if($ttClassId=='') {
                    $ttClassId='NULL';  
                  }
                  
                  if($subjectIdsArray2[$i]=='') {
                    $subjectIdsArray2[$i]='NULL';  
                  }
                  
                  if($groupIdsArray[$i]=='') {
                    $groupIdsArray[$i]='NULL';  
                  }
                  
                  if($employeeIdsArray[$i]=='') {
                    $employeeIdsArray[$i]='NULL';  
                  }
                  
                  if($mappingIdArray[$i]=='') {
                    $mappingIdArray[$i]='NULL';  
                  }
              
                $insertAnswerString .= " ( $feedbackToQuestionIdArray[$i] , $mappingIdArray[$i] , $userId , $ttAnswerOptionId , ".$ttClassId." , ".$subjectIdsArray2[$i]." , ".$groupIdsArray[$i]." , ".$employeeIdsArray[$i]." , ".($userAttempts+1)." , '".$currentDate."' ) ";
            }
            else{
               if($ttAnswerOptionId=='') {
                 $ttAnswerOptionId='NULL';  
               }
               if($userId=='') {
                 $userId='NULL';  
               }
               
               if($mappingIdArray[$i]=='') {
                 $mappingIdArray[$i]='NULL';  
               }
                
               $insertAnswerString .= " ( $feedbackToQuestionIdArray[$i] , $mappingIdArray[$i] , $userId , $ttAnswerOptionId , NULL, NULL , NULL ,  NULL , ".($userAttempts+1)." , '".$currentDate."' ) ";
            }
          }
          
          if($insertAnswerString!=''){
              $insertAnswerString = str_replace("''","NULL",$insertAnswerString);
              $insertAnswerString = str_replace(",,",",NULL,",$insertAnswerString);
              $insertAnswerString = str_replace("''","'",$insertAnswerString);
              $insertAnswerString = str_replace(", ,",",NULL,",$insertAnswerString);
              $insertAnswerString = str_replace("'NULL'","NULL",$insertAnswerString);
              
              $ret=$fbMgr->insertFeedbackAnswers($insertAnswerString);
              if($ret==false){
                echo FAILURE;
                die;
              }
          }
      


          //Checking that number of question are equal to number of answer or not
          $labelArray=CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId);
          $labelCnt=count($labelArray);
          $unasweredQuestions= $labelCnt-$searchNoOfUnansweredAnswers;
	 // $studentClassId1=implode(',',array_unique(explode(',',$classIds)));
	 
          $getStdId="userId=".$userId;
          $stdId=$fbMgr->getStudentId($getStdId);
          $studentId1=$stdId[0][studentId];
  	  $studentClassId1=$stdId[0][classId];
  	  //If number of answered equal to no of question then bit is 1 otherwise bit 0 for incomplete form
             $isStatus=1;
             if($searchNoOfUnansweredAnswers!=0){
             $isStatus=0;
             }
           
          $stdId1="";
            for($i=0;$i<count($optionIdsArray);$i++){
             $stdId1[$i]=$fbMgr->getStudentWeightage($optionIdsArray[$i]);                         
             }
                        
           $totalAnswerWeightage=0;
           for($ii=0;$ii<count($stdId1);$ii++){
        	$totalAnswerWeightage=$totalAnswerWeightage+$stdId1[$ii][0]['optionPoints'];
	     }
         
         // Delete Old Status
         $returnStatus = $fbMgr->deleteStudentReport($studentId1,$labelId,$studentClassId1);
         if($returnStatus === false) {
            echo FAILURE;  
            die;
         } 
          $ttClassId = $studentClassId1;
          if($studentClassId1=='-1') {
            $ttClassId="Null";  
          }
         // Insert Status
  	     $returnStatus=$fbMgr->insertStudentReport($labelId,$ttClassId,$studentId1,$isStatus,$totalAnswerWeightage,$finalIds,$feedbackToQuestionIds,$employeeIds,$groupIds,$subjectIDD,$optionIds);
  	     if($returnStatus === false) {
            echo FAILURE;  
            die;
         }
  	     
       //************************CODE FOR UPDATING STUDENT STATUS AND LOG TABLE*******************************
      //if total no. of questions allocated is equal to total no. of quesstions answered for ALL applicable labels
      //then make this user unblock else block
      //fetch allocated labels to this user
      /*
      $labelArray=CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId);
      $labelCnt=count($labelArray);
      $labelString='';
      $allTotalQuestionCount=0;
      $allTotalQuestionAnsweredCount=0;
      for($i=0;$i<$labelCnt;$i++){
          if($labelString!=''){
              $labelString .=',';
          }
          $labelString .=$labelArray[$i]['feedbackSurveyId'];
      } */
     
      $labelString = $labelId;
      
      if($labelString!='') {
           //check for total questions allocated for this userId for all labels in the date range   
           //$allTotalQuestionsArray=$fbMgr->totalFetchMappedQuestionsForStudents($labelString,$userId,$roleId,$subjectIds2);
           //$allTotalQuestionCount=intval($allTotalQuestionsArray[0]['cnt']);
           $allTotalQuestionCount=0;
           for($x=0;$x<$cnt2;$x++){
              if($subArray2[$x]==-1){//if it is not subject centric
                $totalQuestionsArray2=$fbMgr->totalFetchMappedQuestionsForStudentsWithOutTeacher($labelString,$userId,$roleId,-1);
                $questions=$totalQuestionsArray2[0]['cnt'];
              }
              else{
                $totalQuestionsArray2=$fbMgr->totalFetchMappedQuestionsForStudentsWithTeacher($labelString,$userId,$roleId,$subArray2[$x]);
                $questions=$totalQuestionsArray2[0]['cnt'];
                $totalTeacherArray=$fbMgr->fetchAllocatedTeachersForThisSubject($studentClassId,$subArray2[$x],$labelId);
                $allocatedTeachers=$totalTeacherArray[0]['cnt'];
                if($allocatedTeachers==0) {
                  $allocatedTeachers=1;  
                }
                $questions =$questions*$allocatedTeachers;
              }
              $allTotalQuestionCount +=$questions;
           }
           
       
           //check for total questions answered by this user for all labels in the date range
           $feedbackToQuestionId =  " AND subjectId IN (SELECT
                                                               DISTINCT ftm.subjectId
                                                        FROM
                                                               feedbackadv_teacher_mapping ftm
                                                        WHERE CONCAT_WS( '~', ftm.feedbackSurveyId, ftm.classId )
                                                         IN (SELECT
                                                               CONCAT_WS( '~', 31, sg.classId ) AS c
                                                             FROM
                                                               student_groups sg, student s
                                                             WHERE
                                                                s.classId = sg.classId
                                                                AND s.studentId = sg.studentId
                                                                AND s.userId IN ($userId)
                                                             GROUP BY ftm.subjectId, ftm.classId, ftm.groupId)) "; 
           $allTotalQuestionsAnsweredArray=$fbMgr->totalAnsweresForMappedQuestionsForStudents($labelString,$userId,$roleId,$feedbackToQuestionId);
           $allTotalQuestionAnsweredCount=intval($allTotalQuestionsAnsweredArray[0]['cnt']);
           
           $allTotalQuestionsAnsweredArray=$fbMgr->totalWithOutTeacher($labelString,$userId,$roleId);
           $allTotalQuestionAnsweredCount=$allTotalQuestionAnsweredCount+intval($allTotalQuestionsAnsweredArray[0]['cnt']);
      }
     
      if($allTotalQuestionCount==$allTotalQuestionAnsweredCount) {
          $completeFlag=1;
          //update student status
          $ret=$fbMgr->updateFeedbackStudentStatus($userId,FEEDBACK_STUDENT_UNBLOCKED);
          if($ret==false){
             echo FAILURE;
             die; 
          }
          //insert new row in log table
          $ret=$fbMgr->insertFeedbackStudentStatusLog($userId,'COMPLETED BY STUDENT',FEEDBACK_STUDENT_COMPLETE);
          if($ret==false){
             echo FAILURE;
             die; 
          }
      }
      else{
          $completeFlag=0;
          //update student status
          $ret=$fbMgr->updateFeedbackStudentStatus($userId,FEEDBACK_STUDENT_BLOCKED);
          if($ret==false){
             echo FAILURE;
             die; 
          }
          //insert new row in log table
          $ret=$fbMgr->insertFeedbackStudentStatusLog($userId,'INCOMPLETED BY STUDENT',FEEDBACK_STUDENT_INCOMPLETE);
          if($ret==false){
             echo FAILURE;
             die; 
          }
      } 
  }
  
       
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         //0 for partial 1 for completed feedback
         if($isStatus==0){
           echo "Partial Feedback Saved";
         }
         else {
           echo "Complete Feedback Saved";
         }
         // echo SUCCESS.'~'.$completeFlag;
         die;
      }
      else {
        echo FAILURE;
        die;
      }
     }
     else{
       echo FAILURE;
       die;
     } 
    /********END TRANSACTION*******/

// $History: ajaxProvideFeedbackForUsers.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 3/05/10    Time: 12:58p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Created "Feedback Comments Report"
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 26/02/10   Time: 15:32
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added the check for "Session Time Out" message
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 25/02/10   Time: 17:50
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Changed internal logic : Now along with class,subject and employee ,
//group information will also be stored in feedback survey answer table.
//This is needed to place add/edit/delete check in teacher mapping
//module.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/02/10   Time: 12:01
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added Features :
//1.Changed the logic of incomplete feedback algorithm.
//2.Showing category description in top of the tabs instead of bottom.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/02/10    Time: 17:40
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Corrected application logic related to survey id check
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 28/01/10   Time: 17:10
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made modifications as instructed by Sachin sir :
//1. Comments are not mandatory
//2. Options should come after questions in a seperate place.
//3. Tab order problem corrected.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:16
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Provide Feedback" Module
?>
