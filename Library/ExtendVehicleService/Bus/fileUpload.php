<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload bus photo
//
// Author : Dipanjan Bhattacharjee
// Created on : (04.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

define('MODULE','BusCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
   //logError("File Upload starts....");
    $fileObj = FileUploadManager::getInstance('busPhoto');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Bus/',$filename)) {
        //update logo image name in institute table
        require_once(MODEL_PATH . "/BusManager.inc.php");
        BusManager::getInstance()->updateLogoFilenameInBus($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Library/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Created in $/SnS/Library/Bus
//Enhanced bus master module
?>