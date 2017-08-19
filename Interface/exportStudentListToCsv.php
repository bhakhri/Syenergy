<?php 
//------------------------------------------------------------------------
//  This File contains creates downloads Records into Excel File 
//
// Author :Arvind Singh Rawat
// Created on : 10-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/StudentReports/initListStudentListsReports.php");

$mm_type="application/octet-stream";
header("Cache-Control: public, must-revalidate");
//header("Pragma: hack");
header("Content-Type: " . $mm_type);
header("Content-Length: " .strlen($Records) );
header('Content-Disposition: attachment; filename="'.'studentReport.csv'.'"');
header("Content-Transfer-Encoding: binary\n");
echo $Records;

// $History: exportStudentListToCsv.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 11:45a
//Created in $/Leap/Source/Interface
//added a new file for studentlistreport module
?>

