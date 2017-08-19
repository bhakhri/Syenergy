<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','BookIssue');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']);
$students=trim($REQUEST_DATA['studentIds']);

$notSelectedStudentIds = trim($REQUEST_DATA['notSelectedStudentIds']);

if($classId==''){
    die('Required Parameters Missing');
}

require_once(MODEL_PATH . "/BookIssueManager.inc.php");
$bookMgr = BookIssueManager::getInstance();

//check for usage in issuing table
if($notSelectedStudentIds!=''){
   $studentArray=explode(',',$notSelectedStudentIds);
   $cnt=count($studentArray);
   $searchString='';
   for($i=0;$i<$cnt;$i++){
      if(trim($studentArray[$i])==''){
          die('Student id is missing');
      } 
      if($searchString!=''){
          $searchString .=',';
      }
      $searchString .="'".$classId.'~'.trim($studentArray[$i])."'";
   }
   
   $bookUsageArray=$bookMgr->checkBookPackedDispatchedOrNot($searchString);
   if(is_array($bookUsageArray) and count($bookUsageArray)>0){
      $studentIds=UtilityManager::makeCSList($bookUsageArray,'studentId');
      //now find student name and roll no of these students and show it to users
      $notSelectedStudentArray=$bookMgr->getStudentList($studentIds);
      if(is_array($notSelectedStudentArray) and count($notSelectedStudentArray)>0){
          $errorString='';
          foreach($notSelectedStudentArray as $key=>$val){
              if($errorString!=''){
                  $errorString .=" \n";
              }
              if(trim($notSelectedStudentArray[$key]['rollNo'])==''){
                  $notSelectedStudentArray[$key]['rollNo']=NOT_APPLICABLE_STRING;
              }
              $errorString .=($key+1).". ".$notSelectedStudentArray[$key]['studentName']." (".$notSelectedStudentArray[$key]['rollNo'].")";
          }
          die("These student can be de-allocated as books are already packed or dispatched to them : \n".$errorString);
      }
   }
}


if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
  $userId=$sessionHandler->getSessionVariable('UserId');
  
  //delete all mappings corresponding to this class
  $ret=$bookMgr->deleteBookIssue($classId);
  if($ret==false){
      die(FAILURE);
  }
  
  if($students!=''){
      
      $studentArray=explode(',',$students);
      $cnt=count($studentArray);
      
      //get books mapped with this class and if noOfBooks are less than
      //selected students then,generate error
      $allBooksArray=$bookMgr->getMappedBooksInfo($classId);
      if(is_array($allBooksArray) and count($allBooksArray)>0){
         $bookCnt=count($allBooksArray);
         for($i=0;$i<$bookCnt;$i++){
             if($allBooksArray[$i]['noOfBooks']<$cnt){
                die('No. of books('.$allBooksArray[$i]['noOfBooks'].') for '.$allBooksArray[$i]['bookNo'].' is less than selected ('.$cnt.') students');
             }
         }    
      }
      else{
          die('No books are mapped with this class');
      }
      
      $allBookIdArray=explode(',',UtilityManager::makeCSList($allBooksArray,'bookId'));
      $bookCnt=count($allBookIdArray); //re-initializing this variable
      
      $insertString='';
      $lCnt=0;
      for($i=0;$i<$cnt;$i++){
          if(trim($studentArray[$i])==''){
              die('Student id is missing');
          }
          for($j=0;$j<$bookCnt;$j++){
            if(trim($allBookIdArray[$j])==''){
              die('Book id is missing');
            }
            if($insertString!=''){
              $insertString .=',';
            }
            $insertString .=" ( ".trim($allBookIdArray[$j]).",$classId,".trim($studentArray[$i]).",".BOOK_ISSUED." ) ";
            $lCnt++;
            if($lCnt>20){ //so that insert query does not becomes too big
              $ret = $bookMgr->insertBookIssueData($insertString);
              if($ret==false){
               die(FAILURE);
              }
              $lCnt=0;
              $insertString='';
            }
          }
      }
      
      if($insertString!=''){
          //do the mapping
          $ret = $bookMgr->insertBookIssueData($insertString);
          if($ret==false){
              die(FAILURE);
          }
      }
  }
    
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
    die(SUCCESS);
  }
  else {
   die(FAILURE);
  }
}
else {
  die(FAILURE);
}

    
?>