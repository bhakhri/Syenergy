<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload institute logo
//
// Author : Pushpender Kumar
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

define('MODULE','InstituteMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
  
   //logError("File Upload starts....");
    $fileObj = FileUploadManager::getInstance('instituteLogo');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Institutes/',$filename)) {
        //update logo image name in institute table
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        InstituteManager::getInstance()->updateLogoFilenameInInstitute($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the image is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        logError($fileObj->message); 
    }
  }
  else {
      logError('Upload Error: Session ID missing.'); 
  }

// $History: fileUpload.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:44p
//Updated in $/Leap/Source/Library/Institute
//Tested the code and refined it
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:22p
//Updated in $/Leap/Source/Library/Institute
//just logerror added for testing, it will be removed later
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:40a
//Created in $/Leap/Source/Templates/Institute
//initial checkin
//
?>