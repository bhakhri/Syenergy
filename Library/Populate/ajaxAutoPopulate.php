<?php
//-------------------------------------------------------
// THIS FILE IS USED TO AUTO POPULATE STATE,CITY FIELDS
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//UtilityManager::ifNotLoggedIn();
require_once(BL_PATH.'/HtmlFunctions.inc.php');
require_once(MODEL_PATH."/CommonQueryManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);

$results = '';
$results1 = '';
if($REQUEST_DATA['type']=="states"){
    $results = CommonQueryManager::getInstance()->getStatesCountry("WHERE countryId='".$REQUEST_DATA['id']."'");
}
else if($REQUEST_DATA['type']=="periodSlot"){
   $id = $REQUEST_DATA['id']; 
   if($id=='') {
     $id='0';  
   }
   $results = HtmlFunctions::getInstance()->showPeriodSlotList($id);   
}  
  
else if($REQUEST_DATA['type']=="city"){
    $results = CommonQueryManager::getInstance()->getCityState("WHERE stateId='".$REQUEST_DATA['id']."'");
  }
 else if($REQUEST_DATA['type']=="subjectTypeId"){
    $results = CommonQueryManager::getInstance()->getSubjectTypeUniversity("WHERE universityId='".$REQUEST_DATA['id']."'");
  }
 else if($REQUEST_DATA['type']=="hostel"){
    $results = CommonQueryManager::getInstance()->getHostelRoom(' roomName',"WHERE hostelId='".$REQUEST_DATA['id']."'");
  }
  else if($REQUEST_DATA['type']=="busRoute"){
    $results = CommonQueryManager::getInstance()->getBusStop(' stopName',"WHERE busRouteId='".$REQUEST_DATA['id']."'");
  }
  else if($REQUEST_DATA['type']=="topicSubject"){
    $results = CommonQueryManager::getInstance()->getSubjectTopic(' topic',"WHERE subjectId='".$REQUEST_DATA['id']."'");
  }
  else if($REQUEST_DATA['type']=="groupType"){
	  if ($REQUEST_DATA['classId'] != '' && $REQUEST_DATA['id'] != '') {
		$results = CommonQueryManager::getInstance()->getGroupParent(' groupName',"WHERE classId=".$REQUEST_DATA['classId']." AND groupTypeId IN (".$REQUEST_DATA['id'].")");
	  }
  }
  else if($REQUEST_DATA['type']=="groupTypeClassName"){
	  if ($REQUEST_DATA['classId'] != '' && $REQUEST_DATA['id'] != '') {
		$results = CommonQueryManager::getInstance()->getGroupParentClassName(' g.groupName',"WHERE g.classId=".$REQUEST_DATA['classId']." AND g.groupTypeId IN (".$REQUEST_DATA['id'].") and g.classId = c.classId");
	  }
  }
  else if($REQUEST_DATA['type']=="getStudyPeriod"){

	$concatDegreeId = $REQUEST_DATA['id'];
	$concatArr		= explode("-", $concatDegreeId);
	$universityID	= $concatArr[0];
	$degreeID		= $concatArr[1];
	$branchID		= $concatArr[2];

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId = $sessionHandler->getSessionVariable('SessionId');

    $results = CommonQueryManager::getInstance()->getClassStudyPeriod(' studyPeriodId',"  WHERE cls.universityId = '$universityID' and cls.degreeId = '$degreeID' and cls.branchId = '$branchID' and cls.instituteId = '$instituteId' and cls.sessionId = '$sessionId'  and cls.studyPeriodId= sp.studyPeriodId");
  }

  else if($REQUEST_DATA['type']=="subject"){
    $results = CommonQueryManager::getInstance()->getClassSubject(' sub.subjectName'," WHERE classId='".$REQUEST_DATA['id']."' AND sub.subjectId = subTocls.subjectId");

	$resultsCount = count($results);
    if(is_array($results) && $resultsCount>0) {
        $jsonSubjects  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSubjects .= json_encode($results[$s]). ( $s==($resultsCount-1) ? '' : ',' );
		}
    }

	$results1 = CommonQueryManager::getInstance()->getLastLevelGroups(' a.groupName'," AND a.classId='".$REQUEST_DATA['id']."'");
	$results1Count = count($results1);
    if(is_array($results1) && $results1Count>0) {
        $jsonGroups  = '';
        for($s = 0; $s<$results1Count; $s++) {
            $jsonGroups .= json_encode($results1[$s]). ( $s==($results1Count-1) ? '' : ',' );                }
    }

	$results2 = CommonQueryManager::getInstance()->getSubjectTypeClass(' st.subjectTypeName'," AND cls.classId='".$REQUEST_DATA['id']."'");
	$results2Count = count($results2);
    if(is_array($results2) && $results2Count>0) {
        $jsonType  = '';
        for($s = 0; $s<$results2Count; $s++) {
            $jsonType .= json_encode($results2[$s]). ( $s==($results2Count-1) ? '' : ',' );                }
    }

  }
  else if($REQUEST_DATA['type']=="subjectTimeTable"){
    $results = CommonQueryManager::getInstance()->getClassSubject(' sub.subjectName'," WHERE classId='".$REQUEST_DATA['id']."' AND sub.subjectId = subTocls.subjectId");

	$resultsCount = count($results);
    if(is_array($results) && $resultsCount>0) {
        $jsonSubjects  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSubjects .= json_encode($results[$s]). ( $s==($resultsCount-1) ? '' : ',' );
		}
    }
 }
  else if($REQUEST_DATA['type']=="groupTimeTable"){
    $condition = " AND gr.classId='".$REQUEST_DATA['id']."' AND sub.subjectId = '".$REQUEST_DATA['subject']."'";
    $orderBy = ' gr.groupName';

    $results1 = CommonQueryManager::getInstance()->getTimeTableClassGroups($condition,$orderBy);
    $results1Count = count($results1);
    if(is_array($results1) && $results1Count>0) {
        $jsonGroups  = '';
        for($s = 0; $s<$results1Count; $s++) {
            $jsonGroups .= json_encode($results1[$s]). ( $s==($results1Count-1) ? '' : ',' );
        }
    }
 }

  // for studentlist report for sc class

    else if($REQUEST_DATA['type']=="scStudentLists"){

	$arr=explode("-",$REQUEST_DATA['id']);

 // $scClassId = CommonQueryManager::getInstance()->getScClassBy(' classId',"  universityId='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' ");
 //   print_r($scClassId);
  $results = CommonQueryManager::getInstance()->getScClassBatch(' bat.batchName',"  AND cls.universityId='".$arr[0]."' AND cls.degreeId='".$arr[1]."' AND cls.branchId='".$arr[2]."'");

	$resultsCount = count($results);
    if(is_array($results) && $resultsCount>0) {
        $jsonBatch  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonBatch .= json_encode($results[$s]). ( $s==($resultsCount-1) ? '' : ',' );
		}
    }

	$results1 = CommonQueryManager::getInstance()->getScClassStudyPeriod(' std.periodName'," AND cls.universityId='".$arr[0]."' AND cls.degreeId='".$arr[1]."' AND cls.branchId='".$arr[2]."'");
	$results1Count = count($results1);
    if(is_array($results1) && $results1Count>0) {
        $jsonStudyPeriod  = '';
        for($s = 0; $s<$results1Count; $s++) {
            $jsonStudyPeriod .= json_encode($results1[$s]). ( $s==($results1Count-1) ? '' : ',' );
			}
    }
	//PRINT_R($results1);
  }
  //function gets the optional subject
  else if($REQUEST_DATA['type']=="subjectOptional"){

  $results = CommonQueryManager::getInstance()->getClassSubject(' sub.subjectName'," WHERE classId='".$REQUEST_DATA['id']."' AND sub.subjectId = subTocls.subjectId AND subTocls.optional=1 AND subTocls.offered=1 ");

	$resultsCount = count($results);
    if(is_array($results) && $resultsCount>0) {
        $jsonSubjects  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSubjects .= json_encode($results[$s]). ( $s==($resultsCount-1) ? '' : ',' );
		}
    }

	$results1 = CommonQueryManager::getInstance()->getLastLevelGroups(' a.groupName'," AND a.classId='".$REQUEST_DATA['id']."'");
	$results1Count = count($results1);
    if(is_array($results1) && $results1Count>0) {
        $jsonGroups  = '';
        for($s = 0; $s<$results1Count; $s++) {
            $jsonGroups .= json_encode($results1[$s]). ( $s==($results1Count-1) ? '' : ',' );
			}
    }

  }
  else if($REQUEST_DATA['type']=="checkClass"){
    $results = CommonQueryManager::getInstance()->getValidClass($REQUEST_DATA['id1'],$REQUEST_DATA['id2'],$REQUEST_DATA['id3']);
  }
else{
}
if($REQUEST_DATA['type']=="subject"){

	echo '{"subjectArr":['.$jsonSubjects.'],"groupArr":['.$jsonGroups.'],"typeArr":['.$jsonType.']}';
}
else if($REQUEST_DATA['type']=="subjectTimeTable"){
    echo '{"subjectArr":['.$jsonSubjects.']}';
	//echo '{"subjectArr":['.$jsonSubjects.'],"groupArr":['.$jsonGroups.']}';
}
else if($REQUEST_DATA['type']=="groupTimeTable"){

    echo '{"groupArr":['.$jsonGroups.']}';
}
else if($REQUEST_DATA['type']=="subjectOptional"){

    echo '{"subjectArr":['.$jsonSubjects.'],"groupArr":['.$jsonGroups.']}';
}
else if($REQUEST_DATA['type']=="scStudentLists"){

    echo '{"batchArr":['.$jsonBatch.'],"studyArr":['.$jsonStudyPeriod.']}';
}
else{

	echo  json_encode($results);
}

//function used for optional subejct mapping



 // for VSS
// $History: ajaxAutoPopulate.php $
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Populate
//added access defines for management login
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/03/09   Time: 3:45p
//Updated in $/LeapCC/Library/Populate
//fixed bug no. 0001682
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Library/Populate
//worked on role to class
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/15/09    Time: 3:08p
//Updated in $/LeapCC/Library/Populate
//role permission added
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 6/13/09    Time: 3:13p
//Updated in $/LeapCC/Library/Populate
//removed ifNotLoggedIn
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/01/09    Time: 3:51p
//Updated in $/LeapCC/Library/Populate
//topicSubject condition added
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 4/22/09    Time: 10:21a
//Updated in $/LeapCC/Library/Populate
//Updated for conslidated student report
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/07/09    Time: 12:47p
//Updated in $/LeapCC/Library/Populate
//getTimeTableClassGroups function added
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/06/09    Time: 12:22p
//Updated in $/LeapCC/Library/Populate
//called different function for subject and group population
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/19/08   Time: 4:06p
//Updated in $/LeapCC/Library/Populate
//removed login check.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Populate
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 11/21/08   Time: 3:09p
//Updated in $/Leap/Source/Library/Populate
//added Ajax functionality on hostel and bus route
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/19/08    Time: 3:04p
//Updated in $/Leap/Source/Library/Populate
//added autopopulate() for batch and studty period by selecting class
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/28/08    Time: 8:03p
//Updated in $/Leap/Source/Library/Populate
//added new condition for subjectOptional
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:01p
//Updated in $/Leap/Source/Library/Populate
//added function to fetch studyperiod based on class
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:25p
//Updated in $/Leap/Source/Library/Populate
//updated with new function to fetch subject and group in timetable
//module
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/12/08    Time: 2:50p
//Updated in $/Leap/Source/Library/Populate
//updated getHostelRoom function
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/10/08    Time: 5:16p
//Updated in $/Leap/Source/Library/Populate
//added a common function to fetch valid class from given parameters
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/09/08    Time: 12:59p
//Updated in $/Leap/Source/Library/Populate
//added ajax function for hostel room
?>
