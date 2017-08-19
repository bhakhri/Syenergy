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
    define('MODULE','ClassWiseHeadMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

    require_once(MODEL_PATH . "/ClassWiseHeadMappingManager.inc.php");
    $classWiseHeadValuesManager = ClassWiseHeadValuesManager::getInstance();
    
   
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
    $csvData .= "#,Fee Concession Category,Fee Head,Applicable To,Concession Value,Concession Type \n";
                                                                                                  
    $condition = " fcc.classId=$classId  AND ff.isConsessionable=1 ";
    $foundArray = $classWiseHeadValuesManager->getClassWiseHeadList($condition);
    
    for($i=0;$i<count($foundArray);$i++) {
      $amt = parseCSVComments($foundArray[$i]['concessionAmount'],1);  
      if($foundArray[$i]['concessionType']==1) {
        $cType = "%age";
      }  
      if($foundArray[$i]['concessionType']==2) { 
        $cType = "Fixed";
      }  
      $csvData .= ($i+1).",".parseCSVComments($foundArray[$i]['categoryName']).",".parseCSVComments($foundArray[$i]['HeadName']);
      $csvData .= ",".parseCSVComments($foundArray[$i]['isLeetName']).",".parseCSVComments($amt,1).",".parseCSVComments($cType)."\n";     
    }
    
    if($i==0) {
       $csvData .= ",,No Data Found"; 
    }

    UtilityManager::makeCSV($csvData,'ClassWiseHead.csv'); 
    die;    
?>