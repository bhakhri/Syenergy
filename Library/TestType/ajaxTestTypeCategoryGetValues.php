<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE TESTTYPE CATEGORY LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/TestTypeManager.inc.php");
define('MODULE','TestTypeCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
     
$testTypeManager =  TestTypeManager::getInstance();
if(trim($REQUEST_DATA['testTypeCategoryId'] ) != '') {

	$find=0;
	$message=null;
	$checkTestTypeCategory = $testTypeManager -> checkTestTypeCategory('WHERE testTypeCategoryId="'.$REQUEST_DATA['testTypeCategoryId'].'"');
		if ($checkTestTypeCategory[0]['foundRecord'] > 0){
			$find=1;
			$msg="NOTE: Exam Conducted By and Subject Type could not be edited(are disabled)</br>due to records existing in linked tables";
                 }

	$checkTestCategory = $testTypeManager -> checkTestCategory('WHERE testTypeCategoryId="'.$REQUEST_DATA['testTypeCategoryId'].'"');
		if ($checkTestCategory[0]['foundRecord'] > 0) {
			$find=1;
			$msg="NOTE: Exam Conducted By and Subject Type could not be edited(are disabled)</br>due to records existing in linked tables";
                 }

    	$foundArray = $testTypeManager -> getTestTypeCategory(' AND testTypeCategoryId="'.$REQUEST_DATA['testTypeCategoryId'].'"');
    	if(is_array($foundArray) && count($foundArray)>0 ) {  
		$valueArray = array_merge(array('find' => $find),array('msg' => $msg) ,$foundArray[0]);	
        	echo json_encode($valueArray);
		die();
    	}
    	else {
        	echo 0;
    	}
}
// $History: ajaxTestTypeCategoryGetValues.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/24/09    Time: 2:46p
//Updated in $/LeapCC/Library/TestType
//fixed issue no.0001212
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Library/TestType
//modified for test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/17/09    Time: 4:52p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:32a
//Created in $/Leap/Source/Library/TestType
//new ajax files for test type category
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Library/TestType
//Modified DataBase Column names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Library/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:26a
//Updated in $/Leap/Source/Library/TestType
//Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:41p
//Created in $/Leap/Source/Library/TestType
//Initial CheckIn
?>
