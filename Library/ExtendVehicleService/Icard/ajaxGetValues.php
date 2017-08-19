<?php
//--------------------------------------------------------  
//It contains the time table according findout student details
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    
    $commonQueryManager = CommonQueryManager::getInstance();    
    $studentManager = StudentReportsManager::getInstance();
  
    define('MODULE','StudentBusPass');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    
    if(trim($REQUEST_DATA['busPassId']) != '') {   
      $conditions = " AND bpass.busPassId = '".trim($REQUEST_DATA['busPassId'])."'";   
      $foundArray = $studentManager->getStudentICardDetails($conditions,'studentName');
      if(is_array($foundArray) && count($foundArray)>0 ) {
         echo json_encode($foundArray[0]);
      }
      else {
         echo 0;
      }   
    }
?>
<?php
//$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:53p
//Created in $/LeapCC/Library/Icard
//initial checkin
//

?>