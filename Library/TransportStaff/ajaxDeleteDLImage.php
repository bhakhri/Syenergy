<?php
//-------------------------------------------------------
// Purpose: To delete staff image
//
// Author : Jaineesh
// Created on : (31.08.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','TransportStaffMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['staffId']) || trim($REQUEST_DATA['staffId']) == '') {
        $errorMessage = INVALID_STAFF;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
        $transportStaffManager =  TransportStaffManager::getInstance();
		//to get the information of uploaded file
        $fileInfoArr = $transportStaffManager->getDLImageDetail(' WHERE staffId='.$REQUEST_DATA['staffId']);
		
            if ($REQUEST_DATA['staffId']!="") {
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$ret1 = $transportStaffManager->deleteDLImage($REQUEST_DATA['staffId']);
					if($ret1===false){
					 echo FAILURE;
					 die;  
				    }
					//echo DELETE;
					//}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					//delete the image associated with this tracker id
					  if(UtilityManager::notEmpty($fileInfoArr[0]['dlPhoto'])) {
						if(file_exists(IMG_PATH.'/TransportStaff/DLImage/'.$fileInfoArr[0]['dlPhoto'])) {
							@unlink(IMG_PATH.'/TransportStaff/DLImage/'.$fileInfoArr[0]['dlPhoto']);
						  }
					  }
					  echo DELETE;
					  die;
				   }
				   else {
					  echo FAILURE;
					  die;
				   }
				}
		else {
            echo FAILURE;
            die;
      }
    }
	else {
            echo FAILURE;
            die;
      }
	}
    else {
        echo $errorMessage;
        die;
    }
   
    
// $History: ajaxDeleteDLImage.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/11/09   Time: 2:18p
//Created in $/Leap/Source/Library/TransportStaff
//new ajax file to delete driving license image
//
//
//
?>