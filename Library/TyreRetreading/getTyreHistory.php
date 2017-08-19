<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TyreRetreading');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$tyreNo = $REQUEST_DATA['tyreNo'];
    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
	if ($tyreNo != '') {
		$tyreRetreadingArray = $tyreRetreadingManager->getTyreHistory($tyreNo);
        if(count($tyreRetreadingArray) > 0 && is_array($tyreRetreadingArray)) {
        echo '<table border="1"><b><th width="20%">Bus Number</th><th width="40%">Bus Number</th><th>No. of times tyre has been retreaded</th></b>';    
        for($i=0;$i<count($tyreRetreadingArray);$i++)  {
        
        echo '<table  border="1"><tr><td width="20%">'.$tyreRetreadingArray[$i]['busNo'].'</td><td width="40%">'.$tyreRetreadingArray[$i]['busName'].'</td><td>'.$tyreRetreadingArray[$i]['noOfRetreading'].'</td></tr></table></table>';
    }                                                                          
}
   else {
            echo 0;
        }   
    }  

// $History: getTyreRetreading.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>