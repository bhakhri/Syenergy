<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Jaineesh
// Created on : (16.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

if(trim($REQUEST_DATA['serviceValue'] ) != '') {
	$serviceValue = $REQUEST_DATA['serviceValue'];
	$x = 1;
	if ($serviceValue > 0 ) {
	$serviceDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
	$serviceDiv .= '<tr><td width="10%"></td><td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Due Date</b></td><td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;<b>KM Run</b></td></tr>';
	while ($x <= $serviceValue) {
		 $serviceDiv .= '<tr><td width="10%">Free service no.'.$x.'</td>
						 <td width="10%">&nbsp;&nbsp;<b>:&nbsp;</b>'.$htmlFunctions->datePicker2('serviceDate_'.$x,date('Y-m-d')).'</td>';
		 $serviceDiv .= '<td width="10%">&nbsp;<input class="inputbox" type="text" maxlength = "9" name="kmRun_'.$x.'" /></td></tr>';
		 $x++;
	 }
		$serviceDiv .= '</table>';
	}

	echo ($serviceDiv);
    
}
// $History: getVehicleService.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/28/09   Time: 12:24p
//Updated in $/Leap/Source/Library/Vehicle
//fixed bug nos. 0002378, 0002377, 0002376, 0002374, 0002373
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:54p
//Created in $/Leap/Source/Library/Vehicle
//new ajax file for vehicle service
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/07/09   Time: 12:43p
//Created in $/Leap/Source/Library/Vehicle
//initial file check-in
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Library/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Library/Bus
//Created Bus Master Module
?>