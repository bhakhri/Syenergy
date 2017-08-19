<?php 
//This file is used as printing CSV version for subject topic
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
   //define('MODULE','SubjectTopic');
   //define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
   
    //--------------------------------------------------------       
    //Purpose:To escape any newline or comma present in data
    //--------------------------------------------------------   
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
    
    $subjectId = add_slashes(trim($REQUEST_DATA['tSubjectId']));
    
    if($subjectId=='') {
      $subjectId=0;  
    }
   
    // Search filter /////  
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

    $record = $subjecttopicManager->getSubjectTopicList($filter,'',$orderBy);
    $cnt = count($record);
  
    $subjectArray = $studentManager->getSingleField('`subject`', "CONCAT(subjectCode,' (',subjectName,')') as subjectName", "WHERE subjectId = $subjectId");
    $subjectName = $subjectArray[0]['subjectName'];
    
    $csvData = '';
    $csvData = "Search By : ".parseCSVComments($subjectName);
    $csvData .= "\n";
    //$csvData .= "#, Subject Code, Topic, Abbr. \n";
    $csvData .= "#, Topic, Abbr. \n";
    $cnt = count($record);
    for($i=0;$i<$cnt;$i++) {
       $csvData .= ($i+1).','.parseCSVComments($record[$i]['topic']).',';
       $csvData .= parseCSVComments($record[$i]['topicAbbr'])."\n";
    }
    if($cnt==0){
      $csvData .=",".NO_DATA_FOUND;  
    }

ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="SubjectTopicReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;    
?>
    
<?php 
// $History: listSubjectTopicPrintCSV.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/20/09   Time: 10:45a
//Updated in $/LeapCC/Templates/SubjectTopic
//search condition parameter added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
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
