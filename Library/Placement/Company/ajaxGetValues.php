<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE UNIVERSITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['companyId'] ) != '') {
    require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
    $foundArray = CompanyManager::getInstance()->getCompany(' AND companyId="'.trim($REQUEST_DATA['companyId']).'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo (json_encode($foundArray[0])); 
    }
    else {
        echo 0;
    }
    
}
// $History: ajaxGetValues.php $
?>