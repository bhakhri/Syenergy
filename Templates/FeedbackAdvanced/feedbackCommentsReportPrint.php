<?php
//--------------------------------------------------------------------
// This file is used as CSV version for display feedback comments
// Author :Dipanjan Bhattacharjee
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbManager = FeedBackReportAdvancedManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

	$classId          = trim($REQUEST_DATA['classId']);
    $labelId          = trim($REQUEST_DATA['labelId']);
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    $subjectId        = trim($REQUEST_DATA['subjectId']);

    if($labelId=='' or $timeTableLabelId==''){
        echo 'Required Pamameters Missing';
        die;
    }

    //check type of label.if it is of "subject",then classes can be fetched otherwise not
    $typeArray=$fbManager->getSurveyLabelType($labelId);

    if($typeArray[0]['cnt']!=0){
      if($classId==''){
         echo 'Required Pamameters Missing';
         die;
      }
    }

    if($classId!=''){
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND f.classId='.$classId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    }
    else{
      $filter=' AND f.feedbackSurveyId='.$labelId.' AND fs.timeTableLabelId='.$timeTableLabelId;
    }
    if($subjectId!=''){
        $filter .=' AND f.subjectId='.$subjectId;
    }
    $filter .=" AND trim(f.comments)!=''";

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    if($classId!=''){
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    }
    else{
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';
    }

    $orderBy = " $sortField $sortOrderBy";

    ////////////
    /*
     if($classId!=''){
      $fbRecordArray = $fbManager->getFeedbackCommentsList($filter,' ',$orderBy);
     }
     else{
      $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,' ',$orderBy);
     }
    */
    $fbRecordArray = $fbManager->getFeedbackCommentsFromEmployeesList($filter,' ',$orderBy);
    $cnt = count($fbRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if(trim($fbRecordArray[$i]['comments'])==''){
            $fbRecordArray[$i]['comments']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['className'])==''){
            $fbRecordArray[$i]['className']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['subjectCode'])==''){
            $fbRecordArray[$i]['subjectCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($fbRecordArray[$i]['employeeName'])==''){
            $fbRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$fbRecordArray[$i]);
    }

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Feedback Comments Report (Advanced)');

    if($classId!=''){
	 $reportManager->setReportInformation("Time table : ".trim($REQUEST_DATA['timeTableName'])." Label : ".trim($REQUEST_DATA['labelName'])." Class : ".trim($REQUEST_DATA['className'])." Subject : ".trim($REQUEST_DATA['subjectName']));
    }
    else{
     $reportManager->setReportInformation("Time table : ".trim($REQUEST_DATA['timeTableName'])." Label : ".trim($REQUEST_DATA['labelName']));
    }


    $reportTableHead                        =    array();
    $reportTableHead['srNo']				=    array('#','width="2%" align="left"', "align='left'");
    if($classId!=''){
       $reportTableHead['className']		=    array('Class',' width=20% align="left" ','align="left" ');
       $reportTableHead['subjectCode']	    =    array('Subject',' width="10%" align="left" ','align="left"');
	   $reportTableHead['employeeName']		=    array('Employee',' width="15%" align="left" ','align="left"');
    }
    else{
       $reportTableHead['feedbackSurveyLabel']       =    array('Label',' width=15% align="left" ','align="left" ');
       $reportTableHead['feedbackCategoryName']      =    array('Category',' width="15%" align="left" ','align="left"');
    }
    if($classId!=''){
      $reportTableHead['comments']            =    array('Comments',' width="30%" align="left" ','align="left"');
    }
    else{
      $reportTableHead['comments']            =    array('Comments',' width="40%" align="left" ','align="left"');
    }
	 $reportTableHead['rollNo']       =    array('Roll No',' width=10% align="left" ','align="left" ');
	 $reportTableHead['studentName']      =    array('Student Name',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>
