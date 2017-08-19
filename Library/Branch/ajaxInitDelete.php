<?php
//-------------------------------------------------------
// Purpose: To delete country detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BranchMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['branchId']) || trim($REQUEST_DATA['branchId']) == '') {
        $errorMessage = 'Invalid branch';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BranchManager.inc.php");
        $branchManager =  BranchManager::getInstance();
        
        $found = $branchManager->checkClassBranch($REQUEST_DATA['branchId']);
        if($found[0]['cnt']==0) { 
          $found = $branchManager->checkEmployeeBranch($REQUEST_DATA['branchId']);
          if($found[0]['cnt']==0) {   
             if($branchManager->deleteBranch($REQUEST_DATA['branchId']) ) {
               echo DELETE;
             }
             else {
               echo DEPENDENCY_CONSTRAINT;
             }
          }
          else {
            echo DEPENDENCY_CONSTRAINT;
          }  
       }
       else {
          echo DEPENDENCY_CONSTRAINT;
       }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitDelete.php $    
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/21/09   Time: 11:28a
//Updated in $/LeapCC/Library/Branch
//employee master table branchId check updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/12/09    Time: 5:29p
//Updated in $/LeapCC/Library/Branch
//checkClassBranch condition updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/02/09    Time: 1:24p
//Updated in $/LeapCC/Library/Branch
//checkBranch function added 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Branch
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/05/08   Time: 4:47p
//Updated in $/Leap/Source/Library/Branch
//Define Module-Access - Added
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/27/08    Time: 4:40p
//Updated in $/Leap/Source/Library/Branch
//removed spaces
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:36a
//Updated in $/Leap/Source/Library/Branch
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:32p
//Created in $/Leap/Source/Library/Branch
//added two new files 
//1) ajaxInitList performs ajax table listing function
//2) ajaxInitDelete perform ajax delete function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/25/08    Time: 11:54a
//Created in $/Leap/Source/Library/Country
//added new file which is used for deleting a record through ajax
?>