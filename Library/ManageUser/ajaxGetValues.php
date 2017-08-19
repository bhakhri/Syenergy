<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE USER LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManageUsers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['userId'] ) != '') {
    require_once(MODEL_PATH . "/ManageUserManager.inc.php");
    $foundArray = ManageUserManager::getInstance()->getUser(' WHERE usr.userId="'.$REQUEST_DATA['userId'].'"');
	$cnt = count($foundArray);

    for($i=0;$i<$cnt;$i++) {
		if($foundArray[$i]['dafaultRole']=='') {
			$foundArray[$i]['dafaultRole']=$foundArray[$i]['roleId'];
		}
        // add userId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$foundArray[$i]);

       if(trim($json_val)==''){

            $json_val = json_encode($valueArray);
       }
       else{

            $json_val .= ','.json_encode($valueArray);
       }
    }

	echo '{"totalRecords":"'.$cnt.'","info" : ['.$json_val.']}';

    /*if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }*/
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Library/ManageUser
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/ManageUser
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:45p
//Updated in $/Leap/Source/Library/ManageUser
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Library/ManageUser
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:08p
//Created in $/Leap/Source/Library/ManageUser
//Initial Checkin
?>