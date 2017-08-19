<?php
//
//  This File calls Edit Function used in adding Bank Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/GradeManager.inc.php");

    $errorMessage ='';
    if (!isset($REQUEST_DATA['gradeLabel']) || trim($REQUEST_DATA['gradeLabel']) == '') {
         $errorMessage .= ENTER_GRADE; 
    }
    
      if (trim($REQUEST_DATA['gradePoints']) == '') {
            $errorMessage .=  ENTER_GRADE_POINTS ;
    }
    
    if (!isset($REQUEST_DATA['gradePoints']) || trim($REQUEST_DATA['gradePoints']) == '') {
        $errorMessage .= GRADE_RANGE_POINTS;
    }
    
    if ($REQUEST_DATA['gradePoints'] < 0 || $REQUEST_DATA['gradePoints'] > 127)  {
        $errorMessage .= GRADE_RANGE_POINTS;
    }
    
    if (!isset($REQUEST_DATA['gradeSetId']) || trim($REQUEST_DATA['gradeSetId']) == '') {
        $errorMessage .= SELECT_GRADESET;
    }
     
    if (trim($errorMessage) == '') {
       
        /* 
        $foundArray = GradeManager::getInstance()->getCheckTotalTransferMarks($REQUEST_DATA['gradeId']);
        if($foundArray[0]['totalRecords']>0) {  //DUPLICATE grade NAME CHECK
          echo DEPENDENCY_CONSTRAINT_EDIT; 
          die;
        }
       */
        
       	$foundArray2 = GradeManager::getInstance()->getGrade(' WHERE (UCASE(gradeLabel)= "'.add_slashes(strtoupper($REQUEST_DATA['gradeLabel'])).'" AND g.gradeSetId = '.$REQUEST_DATA['gradeSetId'].') AND gradeId!='.$REQUEST_DATA['gradeId']);
			if(trim($foundArray2[0]['gradeLabel'])=='') {  //DUPLICATE Bank YEAR CHECK
				$returnStatus = GradeManager::getInstance()->editGrade($REQUEST_DATA['gradeId']);
				if($returnStatus === false) {
					$errorMessage = FAILURE;
				}
				else {
					echo SUCCESS;
				}
			}
			else {
				echo GRADE_ALREADY_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxInitEdit.php $	
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/22/09   Time: 12:53p
//Updated in $/Leap/Source/Library/Grade
//gradeSetId (FOREIGN KEY checks updated)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/22/09   Time: 11:51a
//Updated in $/Leap/Source/Library/Grade
//Grade Set Id filed added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/13/08   Time: 10:17a
//Updated in $/Leap/Source/Library/Grade
//bug fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 3:36p
//Updated in $/Leap/Source/Library/Grade
//New column added Grade Points
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
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 11:20a
//Updated in $/Leap/Source/Library/Bank
//done the common messaging
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>