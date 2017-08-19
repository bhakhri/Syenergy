<?php
//-------------------------------------------------------
// Purpose: To delete Fee Head detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','ClassFineSetUp');     
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();



require_once(MODEL_PATH . "/Fee/FineSetUpManager.inc.php");   
$fineSetUpManager = FineSetUpManager::getInstance(); 
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

	$feeFineId = $REQUEST_DATA['feeFineId'];

	if($feefineId==''){
	$feefineId=0;
	}

	$recordArray = $fineSetUpManager->deleteFineDetail($feeFineId);
	if($recordArray === false) {
		 echo 0;
	   }
	   else {
		 echo "Data deleted successfully";           
	   }
   
    

?>

