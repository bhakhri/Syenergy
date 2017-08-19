<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRepairCourse');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busId'] ) != '') {
    require_once(MODEL_PATH . "/BusRepairManager.inc.php");
    $foundArray = BusRepairManager::getInstance()->getRepairCostData(' AND bs.busId IN ('.$REQUEST_DATA['busId'].') AND dated BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'"');
    $cnt = count($foundArray);
    $strList ="";

    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
    $strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
    } 
    $strList .="</series>\n";

    //$branchRecordArray = $studentAdmissionManager->getDistinctBranch();
    //$cnt = count($branchRecordArray);
    
    $strList .="<graphs>\n\t<graph gid=\"Repair Cost\" title=\"Repair Cost\">";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['totalCost']."</value>";
    } 
    $strList .="\n\t</graph>\n"; 
    $strList .="\n</graphs>\n</chart>";
    
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/repairCostData.xml";
   UtilityManager::writeXML($strList, $xmlFilePath);
}
// $History: ajaxGetRepairCost.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/BusRepair
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 28/08/09   Time: 13:14
//Updated in $/LeapCC/Library/BusRepair
//Done bug fixing.
//Bug ids---
//00001337,00001336,00001335,00001334,
//00001332,00001333,00001339,00001265,
//00001267,00001257,00001256,00001266,
//00001232,00001231
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Library/BusRepair
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Library/BusRepair
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/BusRepair
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/16/09    Time: 12:28p
//Updated in $/SnS/Library/BusRepair
//Updated XML file and print report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:16
//Created in $/SnS/Library/BusRepair
//Added "Bus Repair Cost Report" module
?>