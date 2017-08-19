<?php
//-------------------------------------------------------
// Purpose: To delete Subject Category
//
// Author : Parveen Sharma
// Created on : (07.07.2009)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if(trim($REQUEST_DATA['subjectCategoryId'] ) != '') {
        require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
        $subjectCategoryManager =  SubjectCategoryManager::getInstance(); 

        
        $childCountArray = $subjectCategoryManager->checkChildCount($REQUEST_DATA['subjectCategoryId']);
        $cnt = $childCountArray[0]['cnt'];
        if($cnt > 0) {
            echo SUBJECT_CATEGORY_PARENT_RELATION;
            die;
        }
        
        $parentGroupArr = SubjectCategoryManager::getInstance()->getParentSubject($REQUEST_DATA['subjectCategoryId']);
        if($parentGroupArr[0]['cnt'] > 0) {
          echo DEPENDENCY_CONSTRAINT;
          die;
        }
        
        if($subjectCategoryManager->deleteSubjectCategoryId(trim($REQUEST_DATA['subjectCategoryId']))) {
          echo DELETE;
        }
        else {
          echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo SUBJECT_CATEGORY_NOT_EXIST;
    }
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Library/SubjectCategory
//formatting & role permission added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library/SubjectCategory
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/11/09    Time: 3:44p
//Updated in $/LeapCC/Library/SubjectCategory
//abbr new filed added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/07/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectCategory
//initial checkin
//

?>