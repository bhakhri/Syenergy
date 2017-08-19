<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);         
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    require_once(MODEL_PATH . "/OptionalSubjectGroupManager.inc.php");
    $groupManager = OptionalSubjectGroupManager::getInstance();

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
    $orderBy = " $sortField $sortOrderBy";         

  
     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
  
    $foundArray = $groupManager->getRegistrationClassList();

    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
    $reportHead = "As On,".parseCSVComments($formattedDate);
    
    $csvData  = $reportHead;
    $csvData .= "\n";
    $csvData .= "#,Class Name,Subject Name,Career,Elective,Total Students,Group Name,Create Group? ";
    $csvData .= "\n";  
    
    $k=0;             
    $className = '';                                 
    for($i=0;$i<count($foundArray);$i++) {
         
         $groupId = $foundArray[$i]['groupId'];
         
         $checkStatus ="N";
         if($groupId!=-1) {
           $checkStatus="Y";  
         }
          
         if($className!=$foundArray[$i]['className']) {
           $tclassName = strip_slashes($foundArray[$i]['className']);
           $k=0;
         }  
         else {
           $tclassName = '';  
         }
          
         $csvData .= ($k+1).",".parseCSVComments($tclassName).",".parseCSVComments(strip_slashes($foundArray[$i]['subjectName']));
         $csvData .= ",".strip_slashes($foundArray[$i]['careerStudent']).",".strip_slashes($foundArray[$i]['electiveStudent']);
         $csvData .= ",".strip_slashes($foundArray[$i]['totalStudent']).",".parseCSVComments(strip_slashes($foundArray[$i]['subjectCode']));
         $csvData .= ",".$checkStatus;
         $csvData .= "\n"; 
         
         $className = strip_slashes($foundArray[$i]['className']);          
         $k++;           
    }

   
    UtilityManager::makeCSV($csvData,'SubjectWiseOptionalGroupReport.csv');
    die;     
 
 ?>