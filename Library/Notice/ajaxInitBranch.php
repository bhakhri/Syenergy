<?php
//  This File calls getBranch function  used in getting branch Records
//
// Author :Meenakshi
// Created on : 10-november-2010
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");

define('MODULE','COMMON');     
define('ACCESS','add');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true); 
UtilityManager::headerNoCache(); 
$reportManager = StudentReportsManager::getInstance();
$degreeId = $REQUEST_DATA['degreeId'];
//echo $degreeId;

  $groupRecordArray=$reportManager->getBranches($degreeId);
 //print_r($groupRecordArray);
   echo json_encode($groupRecordArray);
?>