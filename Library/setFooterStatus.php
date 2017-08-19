

<?php 
 //-------------------------------------------------------
//  This File contains code to set the status of still footer in session variable
//
//
// Author :Rahul Nagpal
// Created on : 03-Nov-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	$sessionHandler->setSessionVariable('staticfooter',$REQUEST_DATA['status']);
	echo SUCCESS;

	//$History: setFooterStatus.php $
//
//*****************  Version 1  *****************
//User: Rahul.nagpal Date: 11/03/09   Time: 5:07p
//Created in $/LeapCC/Library
//File Contains the php code to set the status of the footer
?>