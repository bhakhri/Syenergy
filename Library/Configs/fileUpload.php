<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload institute logo
//
// Author : Pushpender Kumar
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

define('MODULE','ConfigMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

  require_once(MODEL_PATH . "/ConfigsManager.inc.php");
  $configsManager = ConfigsManager::getInstance();
  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToStudentFileUpload'))) {
  
    global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	$imgSrc=-1; 
	$imgSrc2=-1; 
	$imgSrc3=-1; 
	$imgSrc4=-1;
	$imgSrc5=-1; 
	$imgSrc6=-1;
	$imgSrc7=-1; 
    
	$fileObj1 = FileUploadManager::getInstance('emailStudentFile');
    $fileObj2 = FileUploadManager::getInstance('emailEmployeeFile');
	$fileObj3 = FileUploadManager::getInstance('busPassLogo');
    $fileObj4 = FileUploadManager::getInstance('iCardLogo');
	$fileObj5 = FileUploadManager::getInstance('employeeiCardLogo');
    $fileObj6 = FileUploadManager::getInstance('employeeiCardSignature');
	$fileObj7 = FileUploadManager::getInstance('printReportLogo');

	$ret1 ='';
    if($fileObj1->fileExtension){

		$filename1 = 'emailStudentFile'.$instituteId.'.'.$fileObj1->fileExtension;
		$ret1=$fileObj1->upload(IMG_PATH.'/Reminder/',$filename1);
    }
	if($ret1){

		$imgSrc=IMG_HTTP_PATH.'/Reminder/'.$filename1;
		$configsManager->updateImage("IMAGE_SEND_BIRTHDAY_GREETING_STUDENT",$filename1);
    }

	$ret2 ='';
    if($fileObj2->fileExtension){

		$filename2 = 'emailEmployeeFile'.$instituteId.'.'.$fileObj2->fileExtension;
		$ret2=$fileObj2->upload(IMG_PATH.'/Reminder/',$filename2); 
    }
    if($ret2){

		$imgSrc2=IMG_HTTP_PATH.'/Reminder/'.$filename2;
        $configsManager->updateImage("IMAGE_SEND_BIRTHDAY_GREETING_EMPLOYEE",$filename2);
    }

	$ret3 ='';
    if($fileObj3->fileExtension){

		$filename3 = 'buslogo'.$instituteId.'.'.$fileObj3->fileExtension;
		$ret3=$fileObj3->upload(IMG_PATH.'/BusPass/',$filename3);
    }
	if($ret3){

		$imgSrc3=IMG_HTTP_PATH.'/BusPass/'.$filename3;
		$configsManager->updateConfigImage("BUS_PASS_LOGO",$filename3);
    }
	
	$ret4 ='';
    if($fileObj4->fileExtension){

		$filename4 = 'iCardlogo'.$instituteId.'.'.$fileObj4->fileExtension;
		$ret4=$fileObj4->upload(IMG_PATH.'/Icard/',$filename4); 
    }
    if($ret4){

		$imgSrc4=IMG_HTTP_PATH.'/Icard/'.$filename4;
        $configsManager->updateConfigImage("I_CARD_LOGO",$filename4);
    }

	$ret5 ='';
    if($fileObj5->fileExtension){

		$filename5 = 'employeeICardlogo'.$instituteId.'.'.$fileObj5->fileExtension;
		$ret5=$fileObj5->upload(IMG_PATH.'/Icard/',$filename5); 
    }
    if($ret5){

		$imgSrc5=IMG_HTTP_PATH.'/Icard/'.$filename5;
        $configsManager->updateConfigImage("EMPLOYEE_I_CARD_LOGO",$filename5);
    }

	$ret6 ='';
    if($fileObj6->fileExtension){

		$filename6 = 'employeeICardSignature'.$instituteId.'.'.$fileObj6->fileExtension;
		$ret6=$fileObj6->upload(IMG_PATH.'/Icard/',$filename6); 
    }
    if($ret6){

		$imgSrc6=IMG_HTTP_PATH.'/Icard/'.$filename6;
        $configsManager->updateConfigImage("EMPLOYEE_I_CARD_SIGNATURE",$filename6);
    }

	$ret7 ='';
    if($fileObj7->fileExtension){

		$filename7 = 'printReportLogo'.$instituteId.'.'.$fileObj7->fileExtension;
		$ret7=$fileObj7->upload(IMG_PATH.'/Institutes/',$filename7); 
    }
    if($ret7){

		$imgSrc7=IMG_HTTP_PATH.'/Institutes/'.$filename7;
        $configsManager->updateConfigImage("PRINT_REPORT_LOGO",$filename7);
    }
	/*$sessionHandler->setSessionVariable('IdToStudentFileUpload',NULL);
	$sessionHandler->setSessionVariable('IdToEmployeeFileUpload',NULL);
	$sessionHandler->setSessionVariable('IdBusPassFileUpload',NULL);
	$sessionHandler->setSessionVariable('IdICardFileUpload',NULL);
	$sessionHandler->setSessionVariable('employeeICardLogo',NULL);
	$sessionHandler->setSessionVariable('employeeICardSignature',NULL);
*/
	echo '<script language="javascript">parent.photoUpload("'.$imgSrc.'","'.$imgSrc2.'","'.$imgSrc3.'","'.$imgSrc4.'","'.$imgSrc5.'","'.$imgSrc6.'","'.$imgSrc7.'")</script>'; 
  }
  else {
      logError('Upload Error: Session ID missing.'); 
  }

// $History: fileUpload.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-12-24   Time: 3:27p
//Updated in $/LeapCC/Library/Configs
//config we can change the print report image if print report image is
//not available.
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/10/09    Time: 5:40p
//Updated in $/LeapCC/Library/Configs
//Added InstituteId in Config Table and Updated code 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/09    Time: 10:30a
//Updated in $/LeapCC/Library/Configs
//Updated with bus pass and i card parameters
?>