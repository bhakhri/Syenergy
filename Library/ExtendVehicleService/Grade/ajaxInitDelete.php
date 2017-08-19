<?php
//-------------------------------------------------------
// Purpose: To delete grade
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['gradeId']) || trim($REQUEST_DATA['gradeId']) == '') {
        $errorMessage = GRADE_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GradeManager.inc.php");
        $gradeManager =  GradeManager::getInstance();
        
        $foundArray = GradeManager::getInstance()->getCheckTotalTransferMarks($REQUEST_DATA['gradeId']);
        if($foundArray[0]['totalRecords']>0) {  //DUPLICATE grade NAME CHECK
          echo DEPENDENCY_CONSTRAINT; 
          die;
        }

        if($gradeManager->deleteGrade($REQUEST_DATA['gradeId']) ) {
            echo DELETE;
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
//*****************  Version 3  *****************
//User: Parveen      Date: 10/22/09   Time: 12:53p
//Updated in $/Leap/Source/Library/Grade
//gradeSetId (FOREIGN KEY checks updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 10:59a
//Updated in $/Leap/Source/Library/Grade
//add define access in module
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/22/08   Time: 4:59p
//Created in $/Leap/Source/Library/Grade
//file added for grade masters

?>