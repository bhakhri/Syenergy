<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSSTOP 
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
define('MODULE','FuelMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

function checkValues($str,$val,$condition){
    $arr=explode(',',$str);
    $cnt=count($arr);
    for($i=0;$i<$cnt;$i++){
        if($condition==1){ // <=
           if($val <= $arr[$i]){
               return $i;
           }
        }
        else{  // >=
           if($val >= $arr[$i]){
               return $i;
           }
        }
    }
   
   return -1; 
}
    $errorMessage ='';
    if (!isset($REQUEST_DATA['busId']) || trim($REQUEST_DATA['busId']) == '') {
        $errorMessage .= SELECT_BUS_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['staffId']) || trim($REQUEST_DATA['staffId']) == '')) {
        $errorMessage .= SELECT_STAFF."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['litres']) || trim($REQUEST_DATA['litres']) == '')) {
        $errorMessage .= ENTER_LITRES."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['amount']) || trim($REQUEST_DATA['amount']) == '')) {
        $errorMessage .= ENTER_AMOUNT."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['lastMilege']) || trim($REQUEST_DATA['lastMilege']) == '')) {
        $errorMessage .= ENTER_LAST_MILEGE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['currentMilege']) || trim($REQUEST_DATA['currentMilege']) == '')) {
        $errorMessage .= ENTER_CURRENT_MILEGE."\n";    
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FuelManager.inc.php");
        //check for backdate entry
        $foundArray = FuelManager::getInstance()->getFuel(' AND fuel.busId="'.trim($REQUEST_DATA['busId']).'" AND fuel.dated <= "'.$REQUEST_DATA['dated'].'" ORDER BY currentMilege ASC');
        if(trim($foundArray[0]['currentMilege'])!=0 OR trim($foundArray[0]['currentMilege'])!=''){
         $found1 = UtilityManager::makeCSList($foundArray,'currentMilege');
         $r1=checkValues($found1,trim($REQUEST_DATA['currentMilege']),1);
         if($r1!=-1){
           echo BACK_DATE_ENTRY_VALIDATION.'~!~!~'.trim($foundArray[$r1]['currentMilege']).'~!~!~'.UtilityManager::formatDate($foundArray[$r1]['dated']);
           die;   
         }
        }
        
        //check for future entry
        $foundArray2 = FuelManager::getInstance()->getFuel(' AND fuel.busId="'.trim($REQUEST_DATA['busId']).'" AND fuel.dated > "'.$REQUEST_DATA['dated'].'" ORDER BY currentMilege ASC');
        if(trim($foundArray2[0]['currentMilege'])!=0 OR trim($foundArray2[0]['currentMilege'])!=''){
         $found2= UtilityManager::makeCSList($foundArray2,'currentMilege');
         
         $r2=checkValues($found2,trim($REQUEST_DATA['currentMilege']),2);
         if($r2!=-1){
           echo FUTURE_DATE_ENTRY_VALIDATION.'~!~!~'.trim($foundArray2[$r2]['currentMilege']).'~!~!~'.UtilityManager::formatDate($foundArray2[$r2]['dated']);
           die;   
         }
        }
        
        $returnStatus = FuelManager::getInstance()->addFuel();
        //updating future dates's record
        $ret=FuelManager::getInstance()->getFuel(' AND fuel.busId='.trim($REQUEST_DATA['busId']).' AND fuel.dated > "'.$REQUEST_DATA['dated'].'" ORDER BY dated ASC');
        if(is_array($ret) and count($ret)>0){
          $retUpdate=FuelManager::getInstance()->updatedFuelLastMileageRecords($ret[0]['fuelId'],trim($REQUEST_DATA['currentMilege']));  
        }
          if($returnStatus === false) {
           echo FAILURE;
           die;
          }
        else {
         echo SUCCESS;
         die;
        } 
    }  
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Library/Fuel
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:28p
//Updated in $/Leap/Source/Library/Fuel
//changes in fuel as database changed
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/08/09    Time: 16:04
//Updated in $/Leap/Source/Library/Fuel
//Corrected validation code for fuel module when we add a fuel entry
//which  is between two existing dates.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Library/Fuel
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 3  *****************
//User: Administrator Date: 4/06/09    Time: 11:39
//Updated in $/Leap/Source/Library/Fuel
//Fixed bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/Fuel
//Updated fleet mgmt file in Leap 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Library/Fuel
//Created Fuel Master
?>