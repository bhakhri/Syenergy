<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transaction support
define('MODULE','AssignFinetoRoles');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['roleFineId']) || trim($REQUEST_DATA['roleFineId']) == '') {
        echo 'Invalid Record';
        die;
    }
    
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager =  FineManager::getInstance();
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if($fineManager->deleteRoletoFineMapping($REQUEST_DATA['roleFineId'])) {
            //echo DELETE;
        }
       else {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
       
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
          echo DELETE;
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
   
    
// $History: ajaxDeleteFineRoleMapping.php $    
//
//*****************  Version 1  *****************
//User: Administrator Date: 3/07/09    Time: 18:23
//Created in $/LeapCC/Library/Fine
//Created "Assign Role to Fines" module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/City
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:35p
//Updated in $/Leap/Source/Library/City
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:28p
//Updated in $/Leap/Source/Library/City
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan   Date: 6/25/08    Time: 11:31 a
//Updated in $/Leap/Source/Library/City
//added code to delete city
//
?>

