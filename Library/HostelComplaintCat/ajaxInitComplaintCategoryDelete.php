<?php
//-------------------------------------------------------
// Purpose: To delete complaint category detail
//
// Author : Gurkeerat Sidhu
// Created on : (23.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ComplaintCategory');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['complaintCategoryId']) || trim($REQUEST_DATA['complaintCategoryId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelComplaintCatManager.inc.php");
		$complaintManager =  ComplaintManager::getInstance();
        $foundArray = $complaintManager -> checkExistanceComplaintCategory('WHERE complaintCategoryId='.$REQUEST_DATA['complaintCategoryId']);
		if ($foundArray[0]['foundRecord'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT; 
		}
		else {
			if($complaintManager->deleteComplaintCategory($REQUEST_DATA['complaintCategoryId'])) {
				echo DELETE;
			}
		}
    }
    else {
        echo $errorMessage;
    }
   
    

?>

