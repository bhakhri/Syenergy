<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author : Parveen Sharma
// Created on : (29.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
define('MODULE','MessagesList');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::headerNoCache();

require_once($FE . "/Library/HtmlFunctions.inc.php");
$htmlManager  = HtmlFunctions::getInstance();

  $smsdetailManager  = SMSDetailManager::getInstance();

  $messageId =  trim($REQUEST_DATA['messageId']);
  
  if($messageId=='') {
    $messageId=0;  
  }

  $foundArray = $smsdetailManager->getMessageDetail($messageId);    
  if(is_array($foundArray) && count($foundArray)>0 ) {
        // $msg= $htmlManager->removePHPJS($foundArray[0]['message'],'','1'); 
        /*$msg = (str_ireplace(array('<?php','<?','?>','<script','</script>'),$rep,html_entity_decode($input))); */
        $msg = $foundArray[0]['message'];
        $msg = html_entity_decode($msg);
        $foundArray[0]['message']= $msg;
        echo json_encode($foundArray[0]);                                   
    }
    else {
        echo 0;
    }
?>