<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Arvind Singh Rawat
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BatchMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['batchId']) || trim($REQUEST_DATA['batchId']) == '') {
        $errorMessage = 'Invalid batch ';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BatchManager.inc.php");
        $batchManager =  BatchManager::getInstance();
  
        $found = $batchManager->checkClassBatch($REQUEST_DATA['batchId']);
        if($found[0]['cnt']==0) { 
            if($batchManager->deleteBatch($REQUEST_DATA['batchId']) ) {
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
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/12/09    Time: 5:47p
//Updated in $/LeapCC/Library/Batch
//issue fix 604 Dependency checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Batch
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 10:14a
//Updated in $/Leap/Source/Library/Batch
//Added the module, access
//
//*****************  Version 4  *****************
//User: Arvind       Date: 10/14/08   Time: 1:28p
//Updated in $/Leap/Source/Library/Batch
//corrected the name of BatchManager.inc.php
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/27/08    Time: 3:21p
//Updated in $/Leap/Source/Library/Batch
//removed spaces
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:35a
//Updated in $/Leap/Source/Library/Batch
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:06p
//Created in $/Leap/Source/Library/Batch
//added ajax listing file and ajax deletes file 
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>