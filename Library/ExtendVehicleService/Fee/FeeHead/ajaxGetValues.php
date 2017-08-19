
<?php 

////  This File checks  whether record exists in "Fee Head" Form Table
//
// Author :Nishu Bindal
// Created on : 2-feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadsNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
    //Function gets data from FeeHead table
    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    
    
    $id = $REQUEST_DATA['feeHeadId'];
    if($id=='') {
      $id=0;  
    }
    
      $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND feeHeadId="'.$id.'"'); 
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0;
    }

?>
