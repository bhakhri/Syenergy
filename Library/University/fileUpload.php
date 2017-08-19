<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload university logo
//
// Author : Pushpender Kumar
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

define('MODULE','UniversityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
  
   //logError("File Upload starts....");
    $fileObj = FileUploadManager::getInstance('universityLogo');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/University/',$filename)) {
        //update logo image name in university table
        require_once(MODEL_PATH . "/UniversityManager.inc.php");
        UniversityManager::getInstance()->updateLogoFilenameInUniversity($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
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
//Created in $/LeapCC/Library/University
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Library/University
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:44p
//Updated in $/Leap/Source/Library/University
//Tested the code and refined it
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/16/08    Time: 7:22p
//Updated in $/Leap/Source/Library/University
//just logerror added for testing, it will be removed later
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:40a
//Created in $/Leap/Source/Templates/University
//initial checkin
//
?>