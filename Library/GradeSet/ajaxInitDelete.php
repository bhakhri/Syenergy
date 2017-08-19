<?php
//-------------------------------------------------------
// Purpose: To delete gradeset
//
//--------------------------------------------------------

   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','GradeSetMaster');
   define('ACCESS','delete');
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();
   require_once(MODEL_PATH . "/GradeSetManager.inc.php");
   $gradeSetManager = GradeSetManager::getInstance(); 

   if(!isset($REQUEST_DATA['gradeSetId']) || trim($REQUEST_DATA['gradeSetId']) == '') {
     $errorMessage .=  INVALID_GRADESET_ID."\n"; 
   }
    
   if (trim($errorMessage) == '') {
       $foundArray = $gradeSetManager->getCheckGrades($REQUEST_DATA['gradeSetId']);
       if($foundArray[0]['totalRecords']==0) {
            $foundArray = $gradeSetManager->getGradeSet(' WHERE gradeSetId='.$REQUEST_DATA['gradeSetId']);
            if($foundArray[0]['isActive']==0) {
               if($gradeSetManager->deleteGradeSet($REQUEST_DATA['gradeSetId'])) {
                  echo DELETE;
               }
               else {
                 echo DEPENDENCY_CONSTRAINT; 
               }
            }
            else{
              echo ACTIVE_GRADESET_DELETE;
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
//*****************  Version 3  *****************
//User: Parveen      Date: 10/22/09   Time: 12:53p
//Updated in $/Leap/Source/Library/GradeSet
//gradeSetId (FOREIGN KEY checks updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/22/09   Time: 10:32a
//Updated in $/Leap/Source/Library/GradeSet
//search condition & formatting paramter updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/21/09   Time: 6:08p
//Created in $/Leap/Source/Library/GradeSet
//file added
//
//
?>