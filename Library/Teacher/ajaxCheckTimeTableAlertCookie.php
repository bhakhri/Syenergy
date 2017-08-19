<?php
//----------------------------------------------------------------------------------
// THIS FILE IS USED TO check whether the cookie is set for time table alert
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    if(!isset($_COOKIE['TimeTableAlertCookie'])){
       $t2=23*60*60+59*60+59; //last time of this day
       $c=explode("-",date('H-i-s')); //get the login time
       $t1= $c[0]*60*60 +$c[1]*60+$c[2]; //convert it to seconds
       $timeLeft=abs($t2-$t1);
       setcookie("TimeTableAlertCookie", 1, time()+$timeLeft,'/');
       echo 1;
       die;
    }
    else{
       echo 0;
       die;
    }
// $History: ajaxCheckTimeTableAlertCookie.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/09/09    Time: 15:03
//Created in $/LeapCC/Library/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/09/09    Time: 11:38
//Created in $/Leap/Source/Library/Teacher
//Added the check : If the user clicks on time table alert link then it
//will not reappear again on that day
?>