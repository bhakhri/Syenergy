<?php
//This file sends the data, creates the image on runtime
//
// Author :Ajinder Singh
// Created on : 12-Feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ini_set('memory_limit','20M');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1); 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
    
   UtilityManager::ifNotLoggedIn();
}
else if($roleId==2){
   UtilityManager::ifTeacherNotLoggedIn();
}



$a = $_REQUEST;
$imageName = 'Test Wise Performance Report';
if (isset($a['name']) and $a['name'] != '') {
	$imageName = $a['name'];
}


$imageWidth = $a['width'];
$imageHeight = $a['height'];
$gd = imagecreatetruecolor($imageWidth, $imageHeight) or die(' File: '.__FILE__.'	Line: '.__LINE__);
$bg = imagecolorallocate($gd, 255, 255, 255);

$startHeight = 0;
while($startHeight < $imageHeight) {
	$thisArray = explode(',', $a['r'.$startHeight]);
	$thisPixel = 1;
	foreach($thisArray as $thisColorKey => $thisColorVal) {
		list($thisColorCode,$repeater) = explode(':',$thisColorVal);
		if (empty($repeater)) {
			$colorArray = UtilityManager::getRGB($thisColorCode);
			$pixelColor = imagecolorallocate($gd, $colorArray[0], $colorArray[1], $colorArray[2]); 
			imagesetpixel($gd, $thisPixel,$startHeight, $pixelColor);
			$thisPixel++;
		}
		else {
			$ctr = 1;
			while($ctr <= $repeater) {
				$colorArray = UtilityManager::getRGB($thisColorCode);
				$pixelColor = imagecolorallocate($gd, $colorArray[0], $colorArray[1], $colorArray[2]); 
				imagesetpixel($gd, $thisPixel,$startHeight, $pixelColor);
				$thisPixel++;
				$ctr++;
			}
		}
	}
	$startHeight++;
}

//header("Content-type: image/jpeg");
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header("Pragma: hack"); // WTF? oh well, it works...
header("Content-Type: image/jpeg");
header("Content-Length: ".$imageWidth * $imageHeight);
header('Content-Disposition: attachment; filename="'.$imageName.'.jpeg"');
//header("Content-Transfer-Encoding: text\n");
imagejpeg($gd);

//$History: image.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Interface/Teacher
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/11/09   Time: 11:12
//Updated in $/LeapCC/Interface/Teacher
//Corrected bugs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/10/09   Time: 18:01
//Updated in $/LeapCC/Interface/Teacher
//Corrected php code
?>