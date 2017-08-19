<?php
//-------------------------------------------------------
// Purpose: To get values of Groups
//
// Author : PArveen Sharma
// Created on : 02-06-09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWiseTopicTaught');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");          
    
    if(trim($REQUEST_DATA['subject']) != '' ) {
       $subjectId = $REQUEST_DATA['subject'];
    }
    else {
       $subjectId = -1 ;
    }
    
    $foundArray = CommonQueryManager::getInstance()->getSubjectTopic(' topic'," st.subjectId = '".$subjectId."'");
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTable  = '';
        for($s = 0; $s<$resultsCount; $s++) {
           if($foundArray[$s]['hasAttendance']==1)  {
             $jsonTopic .= json_encode($foundArray[$s]). ( $s==($resultsCount-1) ? '' : ',' );                
           }
        }
    }
    echo '{"topicArr":['.$jsonTopic.']}';


// $History: ajaxGetTopics.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/19/09   Time: 6:14p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//condition format updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//class added, look & feel formating 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/01/09   Time: 12:40p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Extra brackets remove
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:28p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//

?>