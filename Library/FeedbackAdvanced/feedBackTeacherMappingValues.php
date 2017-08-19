<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE TEACHER MAPPING LIST
//
// Author : Gurkeerat Sidhu
// Created on : (08.02.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
//UtilityManager::headerNoCache();
    

    require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
    $feedBackManager = FeedbackLabelManager::getInstance();
    $foundArray = $feedBackManager->getValueForTeacherMapping();
    $cnt=count($foundArray);
    for($i=0;$i<$cnt;$i++) {
        if($value!=''){
            $value .=' , ';
        }  
        $value .='( '.$foundArray[$i]['timeTableLabelId'].','. $foundArray[$i]['classId'].','. $foundArray[$i]['groupId'].','.$foundArray[$i]['subjectId'].','.$foundArray[$i]['employeeId'].' )'; 
    }
   if($value!=''){ 
    $returnStatus = $feedBackManager->addTeacherMappingData($value);
   }
    
    
    

// $History: feedBackTeacherMappingValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/02/10   Time: 16:41
//Updated in $/LeapCC/Library/FeedbackAdvanced
//done bug fixing.
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/09/10    Time: 7:35p
//Created in $/LeapCC/Library/FeedbackAdvanced
//added file to autoinsert data in teacher mapping module
//


?>