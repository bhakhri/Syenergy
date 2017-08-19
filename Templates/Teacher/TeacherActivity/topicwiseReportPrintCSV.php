<?php 
//This file is used as CSV topic details 
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);    
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");     
    $employeeReportsManager = EmployeeReportsManager::getInstance();     
    
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance(); 
    

     // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         $comments = str_ireplace('<br>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments;  
         }
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); 
    $labelId = add_slashes($REQUEST_DATA['timeTableLabelId']);
    
    
    $orderBy = " ORDER BY $sortField $sortOrderBy ";
    
    
    // Findout Subjects
    //$conditions  = " AND tt.employeeId = ".$employeeId." AND timeTableLabelId = ".$labelId;
   $conditions  = " AND sub.hasAttendance = 1 AND tt.employeeId = ".$employeeId." AND timeTableLabelId = ".$labelId;  
	
	if(trim($REQUEST_DATA['classId'])!=''){
		$conditions .=" AND c.classId=".trim($REQUEST_DATA['classId']);
    }
	if(trim($REQUEST_DATA['subjectId'])!=''){
	 $conditions.=" AND tt.subjectId=".trim($REQUEST_DATA['subjectId']);
	} 
	
	if(trim($REQUEST_DATA['groupId'])!=''){
	 $conditions.=" AND tt.groupId=".trim($REQUEST_DATA['groupId']);
	}
    $subjectArray = $employeeReportsManager->getEmployeeSubjectDetails($conditions, $orderBy);   
    $cnt = count($subjectArray);
    
     // Findout Class Name
    if($labelId!='') {
       $classNameArray = $studentReportsManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $labelId");
       $labelName= $classNameArray[0]['labelName'];
    }
    else {
       $labelName = NOT_APPLICABLE_STRING; 
    }
    
    // Findout Class Name
    if($REQUEST_DATA['classId']!='') { 
        $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = ".$REQUEST_DATA['classId']);
        $className = $classNameArray[0]['className'];
        $className2 = str_replace("-",' ',$className);
    }
    else {
       $className2 = NOT_APPLICABLE_STRING; 
    }
    
    // Findout Class Name
    if($REQUEST_DATA['subjectId']!='') {
       $classNameArray = $studentReportsManager->getSingleField('subject', "CONCAT(subjectName,' (',subjectCode,')') AS subject", "WHERE subjectId  = ".$REQUEST_DATA['subjectId']);
       $subject = $classNameArray[0]['subject'];
    }
    else {
       $subject = NOT_APPLICABLE_STRING; 
    }
    
     // Findout Class Name
    if($REQUEST_DATA['groupId']!='') {
       $classNameArray = $studentReportsManager->getSingleField('`group`', "groupName", "WHERE groupId  = ".$REQUEST_DATA['groupId']);
       $group = $classNameArray[0]['groupName'];
    }
    else {
       $group = NOT_APPLICABLE_STRING; 
    }
    
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n";
    $csvData .= "Time Table,".parseCSVComments($labelName).",Class,".parseCSVComments($className2);
    $csvData .= "\n";
    $csvData .= "Subject,".parseCSVComments($subject).",Group,".parseCSVComments($group);
    $csvData .= "\n";
    $csvData .= "Sr. No., Class, Group, Subject, Subject Code, Topics Covered, Pending Topics \n";    
    
    $k=0;
    for($i=0; $i<$cnt; $i++) {
        // Findout Topics & Pending Topics List 
        $className = $subjectArray[$i]['className'];    
        $subjectName = $subjectArray[$i]['subjectName'];
        $subjectCode = $subjectArray[$i]['subjectCode'];
        $groupName   = $subjectArray[$i]['groupName'];
        $groupId     = $subjectArray[$i]['groupId'];    
        $subjectId   = $subjectArray[$i]['subjectId'];    
        
        $pending="";
        $topics="";        
        
        $conditions  = " AND st.subjectId = ".$subjectId;
        $topicValueArray = $employeeReportsManager->getEmployeeTopicsPendingDetails($conditions);    
        if(count($topicValueArray)>0 )  {
           $subjectTopicId = UtilityManager::makeCSList($topicValueArray,'subjectTopicId');
           if($subjectTopicId != "") {
              $condition1   = " AND att.employeeId = ".$employeeId." AND ttc.timeTableLabelId = ".$labelId;
              $condition1  .= " AND att.groupId = ".$groupId." AND att.subjectId = ".$subjectId;
              $topicCoverdArray = $employeeReportsManager->getEmployeeTopicsDetails($condition1);     
              if(count($topicCoverdArray) > 0 ) {
                  for($j=0; $j<count($topicCoverdArray); $j++) {                                                                  
                     $len = strlen($topicCoverdArray[$j]['subjectTopicId']);
                     if($topics=='') {
                       $topics = substr($topicCoverdArray[$j]['subjectTopicId'],1,$len-1);
                     }
                     else {
                       $topics .= substr($topicCoverdArray[$j]['subjectTopicId'],1,$len-1);           
                     }
                  }
                  $topicsTaughtArray = substr($topics,0,strlen($topics)-1);
                  $topicsTaughtArray = explode('~',$topicsTaughtArray);
                  $subjectTopicArr = explode(',',$subjectTopicId); 

                  $topicDif = array_diff($subjectTopicArr, $topicsTaughtArray);
                  $topicList = implode(',', $topicDif);
                  
                  if($topicList=='') {
                    $topicList = 0;
                  }
                  
                  $conditions2  = " AND st.subjectId = ".$subjectId." AND st.subjectTopicId NOT IN ($topicList) ";
                  $topicArr = $employeeReportsManager->getEmployeeTopicsPendingDetails($conditions2);    
                  $topics = '---'.UtilityManager::makeCSList($topicArr,'topicAbbr',' ---');
                  
                  $conditions2  = " AND st.subjectId = ".$subjectId." AND st.subjectTopicId IN ($topicList) ";
                  $pendingArr = $employeeReportsManager->getEmployeeTopicsPendingDetails($conditions2);    
                  $pending = '---'.UtilityManager::makeCSList($pendingArr,'topicAbbr',' ---');  
              }
              else {
                $pending = '---'.UtilityManager::makeCSList($topicValueArray,'topicAbbr',' ---');   
              }
           }
        }
        
        if($pending=="")  { 
          $pending = NOT_APPLICABLE_STRING;       
        } 
                 
        if($topics=="")  { 
          $topics = NOT_APPLICABLE_STRING;       
        } 
                 
       $csvData .= ($k+1).','.parseCSVComments($className).','.parseCSVComments($groupName).','.parseCSVComments($subjectName);
       $csvData .= ','.parseCSVComments($subjectCode).','.parseCSVComments($topics).','.parseCSVComments($pending);
       $csvData .= "\n"; 
       $k++;
    }


	ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream; charset="utf-8"',true);
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="TopicwiseReport.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
 
 

// $History: topicwiseReportPrintCSV.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/14/09   Time: 12:25p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopicId  null check added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/23/09   Time: 2:36p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//sorting order updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//topicswise report format updated (classname added)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/01/09   Time: 10:47a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//check attendance, marks condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/01/09   Time: 10:33a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//file added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/16/09    Time: 6:00p
//Updated in $/LeapCC/Templates/EmployeeReports
//report formatting updated (condition changes)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//issue fix 1519, 1518, 1517, 1473, 1442, 1451 
//validiations & formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/26/09    Time: 4:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//code updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 3:37p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//condition, formatting & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:20p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//initial checkin 
//

?>