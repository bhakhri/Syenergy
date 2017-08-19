<?php

//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if(trim($REQUEST_DATA['studentId'])==''){
        echo "Invalid student roll no.";
        die;
    }
    
    if (!isset($REQUEST_DATA['offenseId']) || trim($REQUEST_DATA['offenseId']) == '') {
        $errorMessage .=  SELECT_OFFENSE."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['remarks']) || trim($REQUEST_DATA['remarks']) == '')) {
        $errorMessage .= ENTER_REMARKS."\n";  
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DisciplineManager.inc.php");
        $foundArray = DisciplineManager::getInstance()->getDiscipline(' AND sc.studentId='.$REQUEST_DATA['studentId'].' AND offenseId='.$REQUEST_DATA['offenseId'].' AND offenseDate="'.$REQUEST_DATA['offenseDate'].'" AND disciplineId!='.$REQUEST_DATA['disciplineId']);
        if(trim($foundArray[0]['offenseDate'])=='') {  //DUPLICATE CHECK
            $returnStatus = DisciplineManager::getInstance()->editDiscipline($REQUEST_DATA['disciplineId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
           echo STUDENT_OFFENCE_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/09   Time: 10:15
//Updated in $/LeapCC/Library/Discipline
//Done bug fixing.
//Bug ids---
//0002339,0002340
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:04
//Created in $/LeapCC/Library/Discipline
//Created 'Discipline' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Library/Discipline
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Library/Discipline
//Created module 'Discipline'
?>
