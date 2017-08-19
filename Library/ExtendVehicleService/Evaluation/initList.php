<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET EVALUATION CRITIERIA LIST
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once(MODEL_PATH . "/EvaluationCriteriaManager.inc.php");
	define('MODULE','EvaluationCrieteria');
	define('ACCESS','view');
    $EvaluationCriteriaManager = EvaluationCriteriaManager::getInstance();
    $totalArray = $EvaluationCriteriaManager->getTotalEvaluationCriteriaType($filter);
	$orderBy = " evaluationCriteriaId";
    $evalutionCritieriaArray = $EvaluationCriteriaManager->getEvaluationCriteriaList($filter,$limit,$orderBy);
 
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Evaluation
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Evaluation
//added access right "define paramater"
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/12/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Evaluation
//updated the query
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 2:30p
//Updated in $/Leap/Source/Library/Evaluation
//removed unnecessary code
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/05/08    Time: 1:54p
//Updated in $/Leap/Source/Library/Evaluation
//updated the file
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/25/08    Time: 7:05p
//Updated in $/Leap/Source/Library/Evaluation
//updated the defects and comments
 