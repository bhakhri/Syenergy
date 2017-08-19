<?php
//-------------------------------------------------------
// Purpose: To get values of parent category name from the database
//
// Author : Parveen Sharma
// Created on : (07.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);    
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
    $subjectCategoryManager =  SubjectCategoryManager::getInstance(); 
     
    $foundArray = $subjectCategoryManager->getParentSubjectCategory();
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0; // no record found
    }
// $History: ajaxInitCategoryName.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Library/SubjectCategory
//formatting & role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/07/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectCategory
//initial checkin
//

?>