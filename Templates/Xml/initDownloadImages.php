<?php
//-------------------------------------------------------
//  This File contains php code for download images
//
//
// Author :Parveen Sharma
// Created on : 05-June-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    set_time_limit(0);
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','add');
	require_once(BL_PATH . '/AdminTasks/ajaxDownloadImages.php');


//$History: initDownloadImages.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/04/09   Time: 6:49p
//Created in $/LeapCC/Templates/Xml
//file added
//

?>