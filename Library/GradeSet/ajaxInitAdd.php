<?php
/*
  This File calls addFunction used in adding Gradeset Records
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeSetMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GradeSetManager.inc.php");

    if (!isset($REQUEST_DATA['gradeSetName']) || trim($REQUEST_DATA['gradeSetName']) == '') {
       $errorMessage .=  ENTER_GRADESET_NAME."\n"; 
    }

    if (trim($errorMessage) == '') {
       $foundArray = GradeSetManager::getInstance()->getGradeSetList(' WHERE LCASE(gradeSetName)="'.add_slashes(trim(strtolower($REQUEST_DATA['gradeSetName']))).'"');
       if(trim($foundArray[0]['gradeSetName'])=='') {  //DUPLICATE CHECK
            $returnStatus = GradeSetManager::getInstance()->addGradeSet();
            if($returnStatus === false) {
              $errorMessage = FAILURE;
            }
            else {
                if(trim($REQUEST_DATA['isActive'])==1){
                   $gradeSetId=SystemDatabaseManager::getInstance()->lastInsertId();
                   $activePeriodSlotArray=GradeSetManager::getInstance()->makeAllGradeSetInActive(" AND ps.gradeSetId !=".$gradeSetId); //make previous entries inactive
                }
                echo SUCCESS;           
            }
       }
       else {
          echo GRADESET_ALREADY_EXIST;
       }
    }
    else {
       echo $errorMessage;
    }

//$History: ajaxInitAdd.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/22/09   Time: 12:53p
//Updated in $/Leap/Source/Library/GradeSet
//gradeSetId (FOREIGN KEY checks updated)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/21/09   Time: 6:08p
//Created in $/Leap/Source/Library/GradeSet
//file added
//

?>    


