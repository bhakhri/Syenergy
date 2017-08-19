<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

//--------------------------------------------------------
//Puppose: To implode a n-dimentional array
//Author: Dipanjan Bhattacharjee(src:php.net)
//Date:03.11.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function array_implode($arrays, &$target = array()) {
    foreach ($arrays as $item) {
        if (is_array($item)) {
            array_implode($item, $target);
        } else {
            $target[] = $item;
        }
    }
    return $target;
}
    
if(trim($REQUEST_DATA['testId'] ) != '' and trim($REQUEST_DATA['sectionId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    
    //checking if these test belongs to other sections or not
    $foundArray = ScTeacherManager::getInstance()->checkTestSections($REQUEST_DATA['testId'],$REQUEST_DATA['sectionId']);

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        //this test exists in more than one section.
        //so delete only marks
        $classIds=join(',', array_implode($foundArray)); //determine the classIds(delete records corresponding to these 
                                                         // classIds only          
        $ret1=ScTeacherManager::getInstance()->deleteTestMarks($REQUEST_DATA['testId']," AND classId IN(".$classIds.")");        
        echo 1;
    }
    else {
        //this test does not exists in more than one section.
        //so delete both marks and test
        $ret1=ScTeacherManager::getInstance()->deleteTestMarks($REQUEST_DATA['testId']);        
        $ret2=ScTeacherManager::getInstance()->deleteTest($REQUEST_DATA['testId']);        
        echo 2;
    }
}
// $History: scAjaxDeleteTestData.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/03/08   Time: 5:35p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created this file for test and marks deletion functionality
?>