<?php
//It contains the time table according store bus pass details    
//
// Author :Parveen Sharma
// Created on : 12-Jun-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    $commonQueryManager = CommonQueryManager::getInstance();    
    $employeeManager = EmployeeManager::getInstance();   
  
    define('MODULE','EmployeeBusPass');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    $errorMessage ='';
    $busPassId= trim($REQUEST_DATA['busPassId']);
    $employeeId= trim($REQUEST_DATA['employeeId']);
    
    if($busPassId=='') {
      $busPassId= 0; 
    }
    if($employeeId=='') {
      $employeeId= 0;   
    }
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
       $returnStatus = $employeeManager->editBusPass($busPassId,$employeeId);
       if($returnStatus===false) {
           echo FAILURE;
           die; 
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             echo SUCCESS;
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
    

//$History: ajaxInitEdit.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/23/09    Time: 6:42p
//Updated in $/LeapCC/Library/Icard
//formatting & deactive code update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/22/09    Time: 5:45p
//Updated in $/LeapCC/Library/Icard
//validation format, condition updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/18/09    Time: 10:37a
//Updated in $/LeapCC/Library/Icard
//messaged settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:53p
//Created in $/LeapCC/Library/Icard
//initial checkin
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/04/09    Time: 4:40p
//Updated in $/LeapCC/Library/Subject
//allowed special characters & subject abbr. blank
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/07/09    Time: 2:40p
//Updated in $/LeapCC/Library/Subject
//issue fix subject code space allow, sorting format setting
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Subject
//added common messages
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/11/08    Time: 1:26p
//Updated in $/Leap/Source/Library/Subject
//modified the paramter in getsubejct
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:24p
//Created in $/Leap/Source/Library/Subject
//Added new files
?>

