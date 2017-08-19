<?php
//This file sends the data, creates the image on runtime
//
// Author :Ajinder Singh
// Created on : 12-Feb-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ini_set('memory_limit','20M');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//define('MODULE','CourseMarksTransferredReport');
//define('ACCESS','view');
//UtilityManager::ifNotLoggedIn();

$testArray = $REQUEST_DATA;

$newArray = explode('&',$testArray['imageData']);
$actualArray = array();
foreach($newArray as $key => $value) {
	$internalArray = explode('=',$value);
	$actualArray[$internalArray[0]] = $internalArray[1];

}


$a = $actualArray;

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
/*
$fileName = "Histogram.jpg";
ob_end_clean();
header("Cache-Control: public, must-revalidate");
header("Pragma: hack"); // WTF? oh well, it works...
header("Content-Type: image/jpeg");
header("Content-Length: 67000");
header('Content-Disposition: attachment; filename="'.$fileName.'"');
//header("Content-Transfer-Encoding: text\n");

imagejpeg($gd);
*/

 
$imageName = time().'.jpeg';
imagejpeg($gd,IMG_PATH . '/'. $imageName,100);
?>
<SCRIPT LANGUAGE="JavaScript">
 
<!--
	parent.printActualReport('<?php echo $imageName;?>');
//-->
</SCRIPT>
<?php
//$History: imageSave.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/30/09    Time: 6:49p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 2/20/09    Time: 4:17p
//Created in $/SnS/Interface
//file added to create image from amcharts
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/12/09    Time: 6:09p
//Updated in $/Leap/Source/Interface
//changed default image name
//





?>