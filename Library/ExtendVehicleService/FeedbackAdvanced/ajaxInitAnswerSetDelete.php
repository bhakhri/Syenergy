<?php
//-------------------------------------------------------
// Purpose: To delete complaint category detail
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AnswerSet');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['answerSetId']) || trim($REQUEST_DATA['answerSetId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackAnswerSetManager.inc.php");
		$feedbackAnswerSetManager =  FeedbackAnswerSetManager::getInstance();
        $id = $REQUEST_DATA['answerSetId'];
        $foundArray = $feedbackAnswerSetManager -> checkExistanceAnswerSet('WHERE answerSetId='.$REQUEST_DATA['answerSetId']);
		if ($foundArray[0]['foundRecord'] <= 0 ) {
            $foundArray = $feedbackAnswerSetManager -> checkExistanceQuestion('WHERE answerSetId='.$REQUEST_DATA['answerSetId']); 
		}
        if ($foundArray[0]['foundRecord'] <= 0 ) {
            $foundArray = $feedbackAnswerSetManager -> checkExistanceAnswer($id); 
        }
        if ($foundArray[0]['foundRecord'] > 0 ) {
            echo DEPENDENCY_CONSTRAINT; 
        }
		else {
			if($feedbackAnswerSetManager->deleteAnswerSet($REQUEST_DATA['answerSetId'])) {
				echo DELETE;
			}
		}
    }
    else {
        echo $errorMessage;
    }
   
    

?>

