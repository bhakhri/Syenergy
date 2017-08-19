<?php 
//This file is used as printing version for TestType.
//
// Author :Parveen sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeHeadValues');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
    $feeHeadValuesManager = FeeHeadValuesManager::getInstance();
    
   
    $classId = add_slashes(trim($REQUEST_DATA['classId']));
    
    if($classId=='') {
      $classId=0;  
    }
   
    //to parse csv values    
    function parseCSVComments($comments,$extra='') {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            if($extra=='') { 
              return $comments.chr(160); 
            }
            else {
              return $comments;   
            }
         }
    }       
    
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    
    $csvData = '';
    $csvData .= "Class,".parseCSVComments($className).",\n";
    $csvData .= "#,Fee Head,Quota,Applicable To,Amount \n";
                                                                                                  
    $condition = " fh.classId=$classId  AND ff.isVariable=0 ";
    $foundArray = $feeHeadValuesManager->getFeeHeadList($condition);
    
    for($i=0;$i<count($foundArray);$i++) {
      $csvData .= ($i+1).",".parseCSVComments($foundArray[$i]['feeHeadName']).",".parseCSVComments($foundArray[$i]['quotaName']);
      $csvData .= ",".parseCSVComments($foundArray[$i]['isLeetName']).",".parseCSVComments($foundArray[$i]['feeHeadAmount'],1)."\n";     
    }
    
    if($i==0) {
       $csvData .= ",,No Data Found"; 
    }

    UtilityManager::makeCSV($csvData,'FeeHeadValues.csv'); 
die;    
 

?>