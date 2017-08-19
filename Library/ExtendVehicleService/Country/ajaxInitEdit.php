<?php 

//  This File calls Edit Function used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CountryMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 
    $errorMessage ='';
    if (!isset($REQUEST_DATA['countryCode']) || trim($REQUEST_DATA['countryCode']) == '') {
        $errorMessage .= ENTER_COUNTRY_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['countryName']) || trim($REQUEST_DATA['countryName']) == '')) {
        $errorMessage .= ENTER_COUNTRY_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['nationalityName']) || trim($REQUEST_DATA['nationalityName']) == '')) {
        $errorMessage .= ENTER_NATIONALITY."\n";
    }
   
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CountryManager.inc.php");
        $foundArray = CountryManager::getInstance()->getCountry(' WHERE UCASE(countryName)="'.add_slashes(strtoupper($REQUEST_DATA['countryName'])).'" AND countryId!='.$REQUEST_DATA['countryId']);
        if(trim($foundArray[0]['countryCode'])=='') {  //DUPLICATE CHECK
           $foundArray = CountryManager::getInstance()->getCountry(' WHERE UCASE(countryCode)="'.add_slashes(strtoupper($REQUEST_DATA['countryCode'])).'" AND countryId!='.$REQUEST_DATA['countryId']);
            if(trim($foundArray[0]['countryCode'])=='') {  //DUPLICATE CHECK
               $foundArray = CountryManager::getInstance()->getCountry(' WHERE UCASE(nationalityName)="'.add_slashes(strtoupper($REQUEST_DATA['nationalityName'])).'" AND countryId!='.$REQUEST_DATA['countryId']);
                if(trim($foundArray[0]['countryCode'])=='') {  //DUPLICATE CHECK
                    $returnStatus = CountryManager::getInstance()->editCountry($REQUEST_DATA['countryId']);            
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
                        echo SUCCESS;           
                    }
              }
              else {
                echo COUNTRY_NATIONALITY_ALREADY_EXISTS;
            }
        }
        else {
            echo COUNTRY_CODE_ALREADY_EXISTS;
        }
      }
      else {
          echo COUNTRY_NAME_ALREADY_EXISTS;
      }      
    }
    else {
        echo $errorMessage;
    }
    
//$History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/08/09    Time: 6:04p
//Updated in $/LeapCC/Library/Country
//country master validation & required fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Country
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/05/08   Time: 4:42p
//Updated in $/Leap/Source/Library/Country
//define MODULE, ACCESS - added
//
//*****************  Version 6  *****************
//User: Arvind       Date: 9/09/08    Time: 6:38p
//Updated in $/Leap/Source/Library/Country
//ADDED Common messages 
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/05/08    Time: 12:47p
//Updated in $/Leap/Source/Library/Country
//added a new field nationalityName
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/24/08    Time: 4:04p
//Updated in $/Leap/Source/Library/Country
//modified files
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/14/08    Time: 7:16p
//Updated in $/Leap/Source/Library/Country
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Country
//New Files Added in Country Folder

?>


