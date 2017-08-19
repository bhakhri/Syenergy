<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  require_once(MODEL_PATH . "/AdminTasksManager.inc.php");  
  
  define('MODULE','COMMON');
  define('ACCESS','view');
  UtilityManager::ifNotLoggedIn(true);
  UtilityManager::headerNoCache();


  $timeTableLabelId =  trim($REQUEST_DATA['timeTableLabelId']);
  if($timeTableLabelId=='') {
    $timeTableLabelId=0;  
  }
  
  
 // Fetch Classes
    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses(' AND ttl.timeTableLabelId="'.$timeTableLabelId.'"'," className");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }

// $History: ajaxGetExternalTimeTableClass.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/21/10    Time: 1:06p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//

?>