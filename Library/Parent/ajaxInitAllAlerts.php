<?php

//The file contains data base functions work on dashboard
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


        require_once($FE . "/Library/common.inc.php"); //for studentId
        require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	    require_once(BL_PATH . "/UtilityManager.inc.php");
        define('MODULE','ParentAlerts');
        define('ACCESS','view');
	    UtilityManager::ifParentNotLoggedIn(true);
	    UtilityManager::headerNoCache();

	    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
        $scStudentInformationManager = StudentInformationManager::getInstance();

        //if ($REQUEST_DATA['startDate']=="" && $REQUEST_DATA['toDate']==""){
	    //$timeTableMessages = $scStudentInformationManager->getScStudentTimeTable1($classId);
	    $classId = $REQUEST_DATA['class1'];

	    $totalFeeStatus = $scStudentInformationManager->getFeeStatus1();
	    $attendanceShortArray = $scStudentInformationManager->getShortAttendance($classId);

        // to limit records per page
        $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
        $records    = ($page-1)* RECORDS_PER_PAGE;
        $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

        $foundArray = array();
        $k=0;
	    $totalAlerts = 0;

        $json_val = "";

	    //$j=0;
	    $recordCount=count($totalFeeStatus);
	    if ($recordCount >0 && is_array($totalFeeStatus)) {
		    for ($i=0; $i<$recordCount;$i++) {
                $foundArray[$k]['srNo']= ($k+1);
			    $foundArray[$k]['alert1'] = '<span>Fee Due for :'.strip_slashes($totalFeeStatus[$i]['periodName']).' Rs. '.strip_slashes(number_format($totalFeeStatus[$i]['pending']),2,'.','').'</span>';
                $k++;
                $totalAlerts++;
		    }
	    }


	    $cnt=$timeTableMessages[0]['cnt'];
	    if ($cnt>0 ) {
          $foundArray[$k]['srNo']= ($k+1);
	      $foundArray[$k]['alert1'] = '<span><a class="allParentAlertLink" href="displayTimeTable.php">The time table has been changed</a></span>';
          $totalAlerts++;
          $k++;
	    }

	    $recordCount=count($attendanceShortArray);
	    if ($recordCount >0 && is_array($attendanceShortArray)) {
		    for ($i=0; $i<$recordCount;$i++) {
			    $subCode = $attendanceShortArray[$i]['subjectCode'];
			    $per = $attendanceShortArray[$i]['per'];
		        $foundArray[$k]['srNo']= ($k+1);
                $foundArray[$k]['alert1'] = '<span>Attendance Short in '.$subCode.' ('.$per.'%)</span>';
                $k++;
                $totalAlerts++;
		    }
	    }
		if($sessionHandler->getSessionVariable('MARKS') == 1){
			$testMartsArray = $scStudentInformationManager->getStudentMarks2($classId);
			$recordCount=count($testMartsArray);
			if ($recordCount >0 && is_array($testMartsArray)) {
				for ($i=0; $i<$recordCount;$i++) {
					$subCode = $testMartsArray[$i]['subject'];
					$marksScored = $testMartsArray[$i]['obtained'];
					$totalMarks = $testMartsArray[$i]['totalMarks'];
					$testTopic = $testMartsArray[$i]['testTopic'];
					$foundArray[$k]['srNo']= ($k+1);
					$foundArray[$k]['alert1'] = '<span><a class="allParentAlertLink" href="displayMarks.php?rClass='.$testMartsArray[$i]['classId'].'" title="For more detail click on the link">'.$subCode.' - '.$testTopic.' -  Marks Scored:  '.$marksScored.'/'.$totalMarks.'</a></span>';
					$k++;
					$totalAlerts++;
				}
			}
		}

      $i=$records;
      $j=0;
      if(count($foundArray)>0) {
          while($j<RECORDS_PER_PAGE) {

            if(trim($json_val)=='') {
               $json_val = json_encode($foundArray[$i]);
            }
            else {
               $json_val .= ','.json_encode($foundArray[$i]);
            }
            $i++;
            if(($totalAlerts)==$i)
              break;
            $j++;
          }
      }

      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalAlerts.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
<?php
//$History: ajaxInitAllAlerts.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/21/09   Time: 11:46a
//Updated in $/LeapCC/Library/Parent
//link class name updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/20/09   Time: 12:47p
//Updated in $/LeapCC/Library/Parent
//Anchor tag  class name added
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/23/09    Time: 3:43p
//Updated in $/LeapCC/Library/Parent
//condition added (no record found)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/15/09    Time: 5:50p
//Updated in $/LeapCC/Library/Parent
//displayMarks links updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/04/09    Time: 2:54p
//Created in $/LeapCC/Library/Parent
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/Leap/Source/Library/ScParent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/03/09    Time: 3:07p
//Created in $/LeapCC/Library/Parent
//file added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/Leap/Source/Library/ScParent
//issue fix format & conditions & alignment updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/26/09    Time: 6:13p
//Updated in $/Leap/Source/Library/ScParent
//1108 issue fix (Data is populating click on Alert Link on “Alert
//Detail” page)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/14/09    Time: 6:40p
//Updated in $/Leap/Source/Library/ScParent
//issue fix 1070, 1003, 346, 344, 1076, 1075, 1073,
//1072, 1071, 1069, 1068, 1067, 1064,
//1063, 1061, 1060, 438 1001, 1004
//alignment & formating, validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Library/ScParent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/07/09    Time: 6:20p
//Created in $/Leap/Source/Library/ScParent
//initial checkin
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/04/08   Time: 10:35a
//Updated in $/Leap/Source/Library/ScStudent
//show subject name also
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/03/08   Time: 4:28p
//Created in $/Leap/Source/Library/ScStudent
//file is used to get alerts semester wise detail
//

?>
