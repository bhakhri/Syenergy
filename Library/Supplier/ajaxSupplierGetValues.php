<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE SUPPLIER LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (06.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SupplierMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['supplierId'] ) != '') {
    require_once(MODEL_PATH . "/SupplierManager.inc.php");
    $foundArray = SupplierManager::getInstance()->getSupplier(' AND supplierId="'.$REQUEST_DATA['supplierId'].'"');
    
     // to populate city, state dropdowns as per stored countryId & State Id
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonManager = CommonQueryManager::getInstance();
    $statesArray = $commonManager->getStatesCountry(' WHERE countryId='.$foundArray[0]['countryId']);
    $cityArray   = $commonManager->getCityState(' WHERE stateId='.$foundArray[0]['stateId']);
    
    $stateCount = count($statesArray);
    if(is_array($statesArray) && $stateCount>0) {
        $jsonStates  = '';
        for($s = 0; $s<$stateCount; $s++) {
            $jsonStates .= json_encode($statesArray[$s]). ( $s==($stateCount-1) ? '' : ',' );                }
    }
    $cityCount = count($cityArray);
    if(is_array($cityArray) && $cityCount>0) {
        $jsonCity  = '';
        for($s = 0; $s<$cityCount; $s++) {
            $jsonCity .= json_encode($cityArray[$s]). ( $s==($cityCount-1) ? '' : ',' );
        }    
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo '{"edit":['.json_encode($foundArray[0]).'],"state":['.$jsonStates.'],"city":['.$jsonCity.']}'; 
    }
    else {
        echo 0;
    }
}

?>