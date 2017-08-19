
<?php 

////  This File checks  whether record exists in "Fee Head" Form Table
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeads');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
    //Function gets data from FeeHead table
    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
    
    
    $id = $REQUEST_DATA['feeHeadId'];
    if($id=='') {
      $id=0;  
    }
    
    $idArr = explode('~',$id);
    if(is_array($idArr) && count($idArr)>1 ) {  
      $foundArray = FeeHeadManager::getInstance()->getFeeHeadCaption(' headCaptionId="'.$idArr[0].'"');
    }
    else {
      $foundArray = FeeHeadManager::getInstance()->getFeeHead(' AND c.feeHeadId="'.$REQUEST_DATA['feeHeadId'].'"');
    }
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0;
    }

?>
