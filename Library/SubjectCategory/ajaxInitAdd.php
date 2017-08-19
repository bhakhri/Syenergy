<?php
//-------------------------------------------------------
// Purpose: To add subject Category detail
//
// Author : Parveen Sharma
// Created on : (06.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';

  
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage = ENTER_SUBJECT_CATEGORY."\n";
    }
   // if (isset($REQUEST_DATA['parentCategoryId']) || trim($REQUEST_DATA['parentCategoryId']) == '') {
     //   $errorMessage = PARENT_CATEGORY_ITSELF."\n";
	//}
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
        $subjectCategoryManager =  SubjectCategoryManager::getInstance(); 
       
        $categoryName = $REQUEST_DATA['categoryName'];
        $abbr = $REQUEST_DATA['abbr'];   
		$parentCategoryId = $REQUEST_DATA['parentCategoryId'];
        
        $condition = " categoryName = '".$categoryName."'";
		$foundArray = $subjectCategoryManager->getSubjectCategory($condition);

		if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
               $condition = " abbr = '".$abbr."'";
               $foundArray = $subjectCategoryManager->getSubjectCategory($condition);

               if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
			        $returnStatus = $subjectCategoryManager->addSubjectCategory();
					//echo $returnStatus;
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
    
// $History: ajaxInitAdd.php $    
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Library/SubjectCategory
//formatting & role permission added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library/SubjectCategory
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/11/09    Time: 3:51p
//Updated in $/LeapCC/Library/SubjectCategory
//abbr duplicate validation updated
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