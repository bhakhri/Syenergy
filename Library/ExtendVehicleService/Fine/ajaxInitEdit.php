<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A Fine Category
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCategoryMaster');
define('ACCESS','edit');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    $errorMessage ='';
    
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .= ENTER_FINE_CATEGORY_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryAbbr']) || trim($REQUEST_DATA['categoryAbbr']) == '')) {
        $errorMessage .= ENTER_FINE_CATEGORY_ABBR."\n"; 
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['fineType']) || trim($REQUEST_DATA['fineType']) == '')) {
        $errorMessage .= ENTER_FINE_CATEGORY_ABBR."\n"; 
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FineManager.inc.php");
        $foundArray = FineManager::getInstance()->getFineCategory(' WHERE ( UCASE(fineCategoryName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['categoryName']))).'" OR UCASE(fineCategoryAbbr)="'.add_slashes(strtoupper(trim($REQUEST_DATA['categoryAbbr']))).'" ) AND fineCategoryId!='.$REQUEST_DATA['fineCategoryId']);
        if(trim($foundArray[0]['fineCategoryName'])=='') {  //DUPLICATE CHECK
            $returnStatus = FineManager::getInstance()->editFineCategory($REQUEST_DATA['fineCategoryId']);
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
           if(strtoupper(trim($REQUEST_DATA['categoryName']))==trim(strtoupper($foundArray[0]['fineCategoryName']))){ 
             echo FINE_CATEGORY_NAME_ALREADY_EXIST ;
             die;
           }
           if(strtoupper(trim($REQUEST_DATA['categoryAbbr']))==trim(strtoupper($foundArray[0]['fineCategoryAbbr']))){ 
             echo FINE_CATEGORY_ABBR_ALREADY_EXIST ;
             die;
           }  
          echo FINE_CATEGORY_NAME_ALREADY_EXIST ;
          die; 
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:46
//Updated in $/LeapCC/Library/Fine
//Changes html and model files names in "Fine  Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:30
//Created in $/LeapCC/Library/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Library/FineCategory
//Created "Fine Category Master" module
?>