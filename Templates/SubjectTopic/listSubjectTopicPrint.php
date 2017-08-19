<?php 
//This file is used as printing version for subject topic
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    //define('MODULE','SubjectTopic');
    //define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

     /// Search filter /////  
	$subjectId = add_slashes(trim($REQUEST_DATA['tSubjectId']));
    
    if($subjectId=='') {
      $subjectId=0;  
    }
    /*
    $search = $REQUEST_DATA['searchbox'];
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.topic LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.topicAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    } 
    */
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    
    $orderBy = " $sortField $sortOrderBy";

    $filter = " AND sub.subjectId = $subjectId";
    
    $subjectArray = $studentManager->getSingleField('`subject`', "CONCAT(subjectCode,' (',subjectName,')') as subjectName", "WHERE subjectId = $subjectId");
    $subjectName = $subjectArray[0]['subjectName'];
    
    ////////////
    $subjecttopicRecordArray = $subjecttopicManager->getSubjectTopicList($filter,'',$orderBy);
    $cnt = count($subjecttopicRecordArray);
    
    $valueArray  = array();
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('srNo' => ($i+1)),$subjecttopicRecordArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Subject Topic Report');
    $reportManager->setReportInformation("Search By: $subjectName");

    $reportTableHead                        =    array();
                    //associated key                  col.label,       col. width,      data align
    $reportTableHead['srNo']                =    array('#',            'width="4%" align="left"', "align='left'");
    //$reportTableHead['subjectCode']         =    array('Subject Code', 'width=20%   align="left"', 'align="left"');
    $reportTableHead['topic']               =    array('Topic',        'width="50%" align="left"', 'align="left"  style="padding-right:10px"');
    $reportTableHead['topicAbbr']           =    array('Abbr.',        'width="20%" align="left"', 'align="left"  style="padding-right:10px"');


    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>

<?php 
// $History: listSubjectTopicPrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectTopic
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/30/09    Time: 1:40p
//Updated in $/LeapCC/Templates/SubjectTopic
//Fixed - 0000780: Bulk Subject Topic Master - Admin> “#” field is right
//aligned by default in print report. It must be left aligned.
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/01/09    Time: 3:22p
//Updated in $/LeapCC/Templates/SubjectTopic
//formatting & spelling correct
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/30/09    Time: 10:38a
//Updated in $/LeapCC/Templates/SubjectTopic
//search condition subject code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Created in $/LeapCC/Templates/SubjectTopic
//print & csv file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 1:21p
//Created in $/Leap/Source/Templates/SubjectTopic
//print & CSV files added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 4:01p
//Created in $/Leap/Source/Templates/ScTimeTable
//inital checkin
//


?>

