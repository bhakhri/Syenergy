
<?php 

////  This File checks  whether record exists in Book Form Table
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    $showEmployee = $REQUEST_DATA['showEmployee'];
    
    $condition = "";
    if($showEmployee=='1') {
      $condition = " WHERE e.isTeaching = '1' " ; 
    }
    else if($showEmployee=='2') {
      $condition = " WHERE e.isTeaching = '0' " ; 
    }
    
    
    $results = $commonQueryManager->getAllEmployees($condition);
    if(isset($results) && is_array($results)) {
      echo json_encode($results);    
    }
    else {
      echo 0; 
    }
    die;
    /*
    if(isset($results) && is_array($results)) {
      $count = count($results);
      for($i=0;$i<$count;$i++) {
         $isActive = $results[$i]['isActive'];
         $style = 'style="color:black;"';
         if ($isActive == '0' or $isActive == 0) {
           $style = 'style="color:red;"';
         }
         $results[$i]['employeeName1'] = "<span $style>".$results[$i]['employeeName1']."</span>";
      }
      echo json_encode($results);    
      die;
    }
    else {
      echo 0;
    }
   */ 
     
   
   