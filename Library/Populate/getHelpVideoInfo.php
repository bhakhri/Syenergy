<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$fileName=trim($REQUEST_DATA['fileName']);
$fileType=trim($REQUEST_DATA['fileType']);
if($fileName==''){
	die(HELP_MEDIA_FILE_NOT_EXISTS);
}
if($fileType!=1 and $fileType!=2){
	die(INVALID_HELP_MEDIA_REQUEST);
}

if($fileType==1){
	$textHelpFile=STORAGE_HTTP_PATH.'/Help/TextHelp/'.$fileName;
	if(@fopen($textHelpFile,"r")){
     die(trim(file_get_contents($textHelpFile)));
   }
   else{
    die(HELP_FILE_NOT_FOUND);
  }
}
else
	{
	$videoHelpFile=STORAGE_HTTP_PATH.'/Help/VideoHelp/'.$fileName;
	if(@fopen($videoHelpFile,"r")){
    die(trim($videoHelpFile));
   }
  else{
    die(HELP_FILE_NOT_FOUND);
  }
}

/*
$videoFileIP="http://220.227.67.118/cdn/";

$moduleArray=array(
                   "CityMaster"=>$videoFileIP."/test.flv",
                   "StateMaster"=>$videoFileIP."barsandtone.flv",
                   "TeacherDashBoard"=>$videoFileIP."barsandtone.flv"
                  );
$videoPath=$moduleArray[$moduleName];
if(fopen($videoPath,"r")){
    echo $videoPath;
    die;
}
else{
    echo -1;
    die;
}
*/
// $History: ajaxGetValues.php $
?>