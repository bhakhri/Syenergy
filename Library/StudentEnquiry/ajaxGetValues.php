<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AddStudentEnquiry');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['studentId'] ) != '') {
    require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    $foundArray = StudentEnquiryManager::getInstance()->getStudentData($REQUEST_DATA['studentId']);
    
    if(is_array($foundArray) && count($foundArray)>0 ) {
       if($foundArray[0]['corrCountryId']!='' && $foundArray[0]['corrCountryId']!=NULL) {
            $stateArray=StudentEnquiryManager::getInstance()->getStudentStates($foundArray[0]['corrCountryId']);
       }
       if($foundArray[0]['corrStateId']!='' && $foundArray[0]['corrStateId']!=NULL) {
            $cityArray=StudentEnquiryManager::getInstance()->getStudentCities($foundArray[0]['corrStateId']);
       }
       foreach($foundArray[0] as &$value){
           if(trim($value)==''){
               $value='';
           }
       }  
        echo json_encode($foundArray[0]).'~!~!~!~'.json_encode($stateArray).'~!~!~!~'.json_encode($cityArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/02/09    Time: 5:30p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation modify & formatting update 
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Library/StudentEnquiry
//Created "Student Enquiry" module
?>