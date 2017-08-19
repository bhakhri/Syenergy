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
UtilityManager::ifNotLoggedIn();


$a = $_REQUEST;
$imageName = 'Histogram';
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
header('Content-Disposition: attachment; filename="'.$imageName.'"');
//header("Content-Transfer-Encoding: text\n");
imagejpeg($gd);

//$History: image.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 4/20/09    Time: 2:01p
//Updated in $/Leap/Source/Interface
//added code for fetching image name, if exists
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/12/09    Time: 6:09p
//Updated in $/Leap/Source/Interface
//changed default image name
//





?>