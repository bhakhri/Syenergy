<?php
//-------------------------------------------------------
// Purpose: To update subject category table data
//
// Author : Parveen Sharma
// Created on : (06.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
			
    $errorMessage ='';
    
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage = ENTER_SUBJECT_CATEGORY."\n";
    }
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
        $subjectCategoryManager =  SubjectCategoryManager::getInstance(); 
       
        $subjectCategoryId = $REQUEST_DATA['subjectCategoryId'];   
        $categoryName = $REQUEST_DATA['categoryName'];
        $abbr = $REQUEST_DATA['abbr'];  
        $parentCategoryId = $REQUEST_DATA['parentCategoryId'];

        if($subjectCategoryId == $parentCategoryId) {
          echo PARENT_CATEGORY_ITSELF;
          die;
        }

        $childCountArray = $subjectCategoryManager->checkChildCount($subjectCategoryId);
        $cnt = $childCountArray[0]['cnt'];
        if($cnt > 0) {
            echo SUBJECT_CATEGORY_PARENT_RELATION;
            die;
        }
        
        $condition = " WHERE categoryName = '".$categoryName."' AND subjectCategoryId != ".$subjectCategoryId;
        $foundArray = $subjectCategoryManager->checkSelfParent($condition);
		
        if(trim($foundArray[0]['cnt'])=='0') {  //DUPLICATE CHECK
            $condition = " WHERE abbr = '".$abbr."' AND subjectCategoryId != ".$subjectCategoryId;
            $foundArray = $subjectCategoryManager->checkSelfParent($condition);
			
            if(trim($foundArray[0]['cnt'])=='0') {  //DUPLICATE CHECK
                $returnStatus = $subjectCategoryManager->editSubjectCategory($subjectCategoryId);
				
                if($returnStatus === false) {
                  echo FAILURE;
                }
                else {
                  echo SUCCESS;           
                }
            }
            else {
                echo SUBJECT_CATEGORY_ABBR_EXIST; 
            }
        }
        else {
            echo SUBJECT_CATEGORY_EXIST;  
        }
    }
	else {
	  echo $errorMessage;
	}
    
// $History: ajaxInitEdit.php $
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