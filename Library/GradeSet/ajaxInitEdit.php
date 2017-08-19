<?php
//
//  This File calls Edit Function used in adding Bank Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeSetMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/GradeSetManager.inc.php");

    $errorMessage ='';
    if (!isset($REQUEST_DATA['gradeSetName']) || trim($REQUEST_DATA['gradeSetName']) == '') {
       $errorMessage .=  ENTER_GRADESET_NAME."\n"; 
    }
   
    if(trim($REQUEST_DATA['isActive'])==0) {
      $foundArray = GradeSetManager::getInstance()->getGradeSet(' WHERE gradeSetId != '.$REQUEST_DATA['gradeSetId'] .' AND isActive = 1');
      if(trim($foundArray[0]['gradeSetName'])=='') {  //DUPLICATE CHECK
         echo ACTIVE_GRADESET_UPDATE;
         die;
      }
    }
     
    if (trim($errorMessage) == '') {
       $gradeSetId =  $REQUEST_DATA['gradeSetId'];
       $foundArray = GradeSetManager::getInstance()->getGradeSetList(' WHERE LCASE(gradeSetName)="'.add_slashes(trim(strtolower($REQUEST_DATA['gradeSetName']))).'" AND gradeSetId != '.$gradeSetId);
       if(trim($foundArray[0]['gradeSetName'])=='') {  //DUPLICATE CHECK
            $returnStatus = GradeSetManager::getInstance()->editGradeSet($gradeSetId);
            if($returnStatus === false) {
              $errorMessage = FAILURE;
            }
            else {
               if(trim($REQUEST_DATA['isActive'])==1){
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
    
//$History: ajaxInitEdit.php $	
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/23/09   Time: 10:18a
//Updated in $/Leap/Source/Library/GradeSet
//condition updated (active condition check updated)
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