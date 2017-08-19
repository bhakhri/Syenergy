<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author : Rajeev Aggarwal
// Created on : (10.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/DashBoardManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler;  
$userId=$sessionHandler->getSessionVariable('UserId');  
$roleId=$sessionHandler->getSessionVariable('RoleId');  
$instituteId=$sessionHandler->getSessionVariable('InstituteId');  
$sessionId=$sessionHandler->getSessionVariable('SessionId');  
if($roleId=='4') { 
  UtilityManager::ifStudentNotLoggedIn(true);
}
else if($roleId=='3') { 
  UtilityManager::ifParentNotLoggedIn(true);  
}
else if($roleId=='5') { 
  UtilityManager::ifManagementNotLoggedIn(true);   
}
else if($roleId=='2') { 
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else {
  UtilityManager::ifNotLoggedIn(true); 
}
UtilityManager::headerNoCache();
    
    $isReadStatus=0;
    $recordArray = DashBoardManager::getInstance()->getNoticeReadList($REQUEST_DATA['noticeId']);   
    if(is_array($recordArray) && count($recordArray)>0 ) { 
       if($recordArray[0]['cnt'] > 0) { 
         $isReadStatus=1; 
       }
    }
           
    
    if(trim($REQUEST_DATA['noticeId'] ) != '') {
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
           $recordArray = DashBoardManager::getInstance()->getDownloadCount($REQUEST_DATA['noticeId']);  
           
           if($isReadStatus==0) {
             $recordArray = DashBoardManager::getInstance()->getNoticeReadInsert($REQUEST_DATA['noticeId']);    
           }
           else {
             $recordArray = DashBoardManager::getInstance()->getNoticeReadUpdate($REQUEST_DATA['noticeId']);    
           }
           
           if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
             
           } 
        }
    }
    
    $foundArray = DashBoardManager::getInstance()->getNoticeDetail($REQUEST_DATA['noticeId']);
	$cnt = count($foundArray);
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = trim($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }

?>
