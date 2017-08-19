<?php 
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
   
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
  

    //to parse csv values    
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
    

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'total';
    $orderBy = " $sortField $sortOrderBy";

    
    $condition='';
    $foundArray = $studentManager->pollReport($condition,$orderBy);
    $cnt = count($foundArray);
   
    $csvData = "#, Teacher, Adorable Teacher, Dedicated Teacher,Interactive Teacher,Ever-smiling Teacher,Charismatic Teacher (based on personality),Total \n"; 
    for($i=0;$i<$cnt;$i++) {
       $csvData .= ($i+1).",".parseCSVComments($foundArray[$i]['employeeNameCode']).",".parseCSVComments($foundArray[$i]['q1']);
       $csvData .= ",".parseCSVComments($foundArray[$i]['q2']).",".parseCSVComments($foundArray[$i]['q3']);
       $csvData .= ",".parseCSVComments($foundArray[$i]['q4']);
       $csvData .= ",".parseCSVComments($foundArray[$i]['q5']).",".parseCSVComments($foundArray[$i]['total'])." \n";  
    }
       
    if($i==0) {
      $csvData .= ",,No Data Found";   
    }
    
    
    UtilityManager::makeCSV($csvData,'TeacherPollReport.csv');
    die;
        
?>