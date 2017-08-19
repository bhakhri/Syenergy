
<?php 
//--------------------------------------------------------------
// This File checks  whether record exists in Country Form Table
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PublicationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from country table
 
if(trim($REQUEST_DATA['publicationId'] ) != '') {
    require_once(MODEL_PATH . "/PublicationManager.inc.php");
    $foundArray = PublicationManager::getInstance()->getPublication(' WHERE publicationId="'.$REQUEST_DATA['publicationId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>