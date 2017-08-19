
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

 require_once(MODEL_PATH . "/EventManager.inc.php");
 $eventManager = EventManager::getInstance();
 
  $id  = htmlentities(trim($REQUEST_DATA['userWishEventId']));  
  $idArray=explode(',',$id);
  
  $isstatus = htmlentities(trim($REQUEST_DATA['isStatus']));
  //$limitCheck=htmlentities(trim($REQUEST_DATA['limitCheck']));

   if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $condition="WHERE userWishEventId IN($id)"; 
        $returnStatus = $eventManager->editEventCheck($isstatus,$condition);
        if($returnStatus == false) {
          echo FAILURE;
          die;
        }

   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 echo SUCCESS;
                 die; }
  }
  else{
    echo FAILURE;
        die;
  }
?>
