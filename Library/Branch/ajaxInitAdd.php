<?php
/*
  This File calls addFunction used in adding Branch Records

 Author :Arvind Singh Rawat
 Created on : 12-June-2008
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BranchMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['branchCode']) || trim($REQUEST_DATA['branchCode']) == '') {
        $errorMessage .= ENTER_BRANCH_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['branchName']) || trim($REQUEST_DATA['branchName']) == '')) {
        $errorMessage .= ENTER_BRANCH_NAME."\n";
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BranchManager.inc.php");
         $foundArray = BranchManager::getInstance()->getBranch(' WHERE UCASE(branchName)="'.add_slashes(strtoupper($REQUEST_DATA['branchName'])).'"');
        if(trim($foundArray[0]['branchCode'])=='') {  //DUPLICATE CHECK
          $foundArray = BranchManager::getInstance()->getBranch(' WHERE UCASE(branchCode)="'.add_slashes(strtoupper($REQUEST_DATA['branchCode'])).'"');
            if(trim($foundArray[0]['branchCode'])=='') {  //DUPLICATE CHECK
                $returnStatus = BranchManager::getInstance()->addBranch();  
                if($returnStatus === false) {
                    echo FAILURE;
                }
                else {
                    echo SUCCESS;
                }
            }
            else {
                echo ABBR_ALREADY_EXIST;
            }
        }
        else {
            echo BRANCH_ALREADY_EXIST;
       }
    }
    else {
        echo $errorMessage;
    }  
/*

//$History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/29/09    Time: 1:23p
//Updated in $/LeapCC/Library/Branch
//add validation for branchName, branchCode (duplicate Checks)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Branch
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/05/08   Time: 4:47p
//Updated in $/Leap/Source/Library/Branch
//Define Module-Access - Added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/12/08    Time: 2:32p
//Updated in $/Leap/Source/Library/Branch
//added common functions
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/27/08    Time: 4:40p
//Updated in $/Leap/Source/Library/Branch
//removed spaces
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/14/08    Time: 7:16p
//Updated in $/Leap/Source/Library/Branch
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:03p
//Updated in $/Leap/Source/Library/Branch
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Branch
//NEw Files Added in Branch Folder
*/
?>