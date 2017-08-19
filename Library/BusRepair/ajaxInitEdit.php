<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSSTOP
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRepairCourse');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['busId']) || trim($REQUEST_DATA['busId']) == '') {
        $errorMessage .= SELECT_BUS_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stuffId']) || trim($REQUEST_DATA['stuffId']) == '')) {
        $errorMessage .= SELECT_STUFF."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['serviceFor']) || trim($REQUEST_DATA['serviceFor']) == '')) {
        $errorMessage .= ENTER_SERVICE_REASON."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cost']) || trim($REQUEST_DATA['cost']) == '')) {
        $errorMessage .= ENTER_SERVICE_COST."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusRepairManager.inc.php");
        //$foundArray = BusRepairManager::getInstance()->getBusRepair(' WHERE ( UCASE(busName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['busName']))).'" OR UCASE(busNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['busNo']))).'") AND busId!='.$REQUEST_DATA['busId']);
        $foundArray = BusRepairManager::getInstance()->getBusRepair(' WHERE ( UCASE(billNumber)="'.add_slashes(trim(strtoupper($REQUEST_DATA['billNumber']))).'" AND UCASE(workshopName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['workShop']))).'" ) AND repairId!='.$REQUEST_DATA['repairId'] );
        if(trim($foundArray[0]['billNumber'])=='') {  //DUPLICATE CHECK
            $returnStatus = BusRepairManager::getInstance()->editBusRepair($REQUEST_DATA['repairId']);
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                if(trim($REQUEST_DATA['actionIds'])!=''){

                 $actionIds=explode(',',trim($REQUEST_DATA['actionIds']));
                 $dueDates=explode(',',trim($REQUEST_DATA['dueDates']));
                 $len=count($actionIds);
                 $insStr='';
                 for($k=0;$k<$len;$k++){
                     if($insStr!=''){
                         $insStr .=',';
                     }
                     $insStr .=" ($REQUEST_DATA[repairId],$actionIds[$k],'".$dueDates[$k]."' ) ";
                 }
                 if($insStr!=''){
                   $ret2=BusRepairManager::getInstance()->addActionRepaired($REQUEST_DATA['repairId'],$insStr);
                   if($ret2===false){
                       echo FAILURE;
                       die;
                   }
                   else{
                       echo SUCCESS;
                       die;
                   }
                 }
                }
                echo SUCCESS;           
            }
        }
        else {
            echo BUS_REPAIR_BILL_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Library/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/BusRepair
//Updated fleet mgmt file in Leap 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/04/09    Time: 15:15
//Updated in $/SnS/Library/BusRepair
//Enhanced bus repair module by adding action (Engine Oil Change,Gear Box
//Oil Change etc) and their due dates
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/04/09    Time: 11:25
//Updated in $/SnS/Library/BusRepair
//Enhanced bus repair module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Library/BusRepair
//Created Bus Repair Module
?>
