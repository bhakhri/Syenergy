<?php
/*
  This File calls addFunction used in adding Grade Records
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
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
        require_once(MODEL_PATH . "/GradeManager.inc.php");
        
        $foundArray = GradeManager::getInstance()->getGradeLabel(' WHERE UCASE(gradeLabel)="'.add_slashes(strtoupper($REQUEST_DATA['gradeLabel'])).'" AND g.gradeSetId = '.$REQUEST_DATA['gradeSetId']);  
        if(trim($foundArray[0]['gradeLabel'])=='') {  //DUPLICATE CHECK
			//check for gradeLabel duplicacy
				$returnStatus = GradeManager::getInstance()->addGrade();

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
    
?>


