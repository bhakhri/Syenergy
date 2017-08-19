<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A QUOTA
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['quotaName']) || trim($REQUEST_DATA['quotaName']) == '')) {
        $errorMessage .=  ENTER_QUOTA_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['quotaAbbr']) || trim($REQUEST_DATA['quotaAbbr']) == '')) {
        $errorMessage .=  ENTER_QUOTA_ABBR."\n"; 
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/QuotaManager.inc.php");
        $parentQuotaCondition='';
        $foundArray = QuotaManager::getInstance()->getQuota(' WHERE ( UCASE(quotaAbbr)="'.add_slashes(strtoupper(trim($REQUEST_DATA['quotaAbbr']))).'" OR UCASE(quotaName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['quotaName']))).'" ) AND quotaId!='.$REQUEST_DATA['quotaId']);
        if(trim($foundArray[0]['quotaAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = QuotaManager::getInstance()->editQuota($REQUEST_DATA['quotaId']);
            if($returnStatus === false) {
                die(FAILURE);
            }
            else {
                die(SUCCESS);
            }
        }
        else {
           if(count($foundArray)>0){
               $parentQuotaIdArray=explode(',',UtilityManager::makeCSList($foundArray,'parentQuotaId'));
               if(!in_array(trim($REQUEST_DATA['parentQuotaId']),$parentQuotaIdArray)){
                $returnStatus = QuotaManager::getInstance()->editQuota($REQUEST_DATA['quotaId']);
                if($returnStatus === false) {
                 die(FAILURE);
                }
                else {
                 die(SUCCESS);
                }  
               }
           }
           if(strtoupper(trim($foundArray[0]['quotaName']))==strtoupper(trim($REQUEST_DATA['quotaName']))){ 
             echo QUOTA_NAME_ALREADY_EXIST;
             die;
           }
           
           if(strtoupper(trim($foundArray[0]['quotaAbbr']))==strtoupper(trim($REQUEST_DATA['quotaAbbr']))){ 
             echo QUOTA_ALREADY_EXIST;
             die;
           }
        }
    }
    else {
        echo $errorMessage;
    }
?>

<?php
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Library/Quota
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:38p
//Updated in $/Leap/Source/Library/Quota
//Added standard messages
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/08    Time: 1:14p
//Updated in $/Leap/Source/Library/Quota
//Modified according to Task: 6 
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/02/08    Time: 11:44a
//Updated in $/Leap/Source/Library/Quota
//Removed State Field from the quota master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/16/08    Time: 4:31p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 3:50p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Quota
//Completed Comments Insertion
?>