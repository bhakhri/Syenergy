<?php 

////  Get the data from notice table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAdminMessages');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['messageId']) != '') {
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visibleFromDate';
    
    $orderBy = " $sortField $sortOrderBy";
    $foundArray = StudentInformationManager::getInstance()->getAdminMessages('AND messageId="'.$REQUEST_DATA['messageId'].'"',$orderBy,'');
    if(is_array($foundArray) && count($foundArray)>0 ) {
		//print_r($foundArray);
		$foundArray[0][message]=html_entity_decode(strip_slashes($foundArray[0][message]));
		$foundArray[0][subject]=html_entity_decode(strip_slashes($foundArray[0][subject]));
		$foundArray[0][visibleFromDate]=$foundArray[0][visibleFromDate];
		$foundArray[0][visibleToDate]=$foundArray[0][visibleToDate];
		echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

//$History: ajaxAdminGetValues.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Library/Student
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:04a
//Created in $/Leap/Source/Library/Student
//ajax file to show the admin message in div
//
?>