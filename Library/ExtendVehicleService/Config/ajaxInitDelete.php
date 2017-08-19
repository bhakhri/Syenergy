<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['configId']) || trim($REQUEST_DATA['configId']) == '') {
        $errorMessage = CONFIG_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ConfigManager.inc.php");
        $configManager =  ConfigManager::getInstance();
  
            if($configManager->deleteConfig($REQUEST_DATA['configId']) ) {
                echo DELETE;
            }
       
       		else {
         	 echo DEPENDENCY_CONSTRAINT;
       		}
	}
	else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Config
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/05/08    Time: 5:41p
//Created in $/Leap/Source/Library/Config
//file added for config masters
//

?>