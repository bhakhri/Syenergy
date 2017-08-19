<?php 
//This file is used as Print topic details 
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();    
    
    function trim_output($str,$maxlength='250',$rep='...'){
       $ret=chunk_split($str,60);
       if(strlen($ret) > $maxlength){
          $ret=substr($ret,0,$maxlength).$rep; 
       }
      return $ret;  
    }


   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); 
    $labelId = add_slashes($REQUEST_DATA['timeTableLabelId']);
    
    
    $orderBy = " ORDER BY $sortField $sortOrderBy ";
    
    
    // Findout Subjects
	
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
    
    $k=0;
    for($i=0; $i<$cnt; $i++) {
        // Findout Topics & Pending Topics List 
        $className   = $subjectArray[$i]['className'];
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
                 
        $valueArray[] = array_merge(array('srNo' => ($records+$k+1),
                                          'className'   => $className,
                                          'subjectName' => $subjectName, 
                                          'subjectCode' => $subjectCode,
                                          'groupName' => $groupName,
                                          'topicAbbr'  => $topics , 
                                          'pending' => $pending ) );
          $k++;
    }

    
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
    
    
         
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= "&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
    $reportHead .= "<br>Time Table&nbsp;:&nbsp;$labelName&nbsp;&nbsp;Class&nbsp;:&nbsp;$className2
                    <br>Subject&nbsp;:&nbsp;$subject&nbsp;&nbsp;Group&nbsp;:&nbsp;$group";
                 
     
    $reportManager->setReportWidth(780);
    $reportManager->setReportHeading('Topicwise Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']          =    array('#',      'width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['className']     =    array('Class',   'width=15%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['groupName']     =    array('Group',    'width="10%" align="left" ','align="left" valign="top" ');
    $reportTableHead['subjectName']   =    array('Subject',  'width=15%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['subjectCode']   =    array('Subject Code',  'width="10%" align="left" ','align="left" valign="top" ');
    $reportTableHead['topicAbbr']     =    array('Topics Covered', 'width="25%" align="left" ','align="left" valign="top" ');
    $reportTableHead['pending']       =    array('Pending Topics',       'width="25%" align="left" ','align="left" valign="top" ');


    $reportManager->setRecordsPerPage(10);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: topicwiseReportPrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/14/09   Time: 12:25p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopicId  null check added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/01/09   Time: 11:48a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//report width updated
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
//*****************  Version 7  *****************
//User: Parveen      Date: 9/16/09    Time: 6:00p
//Updated in $/LeapCC/Templates/EmployeeReports
//report formatting updated (condition changes)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/26/09    Time: 4:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 6:01p
//Updated in $/LeapCC/Templates/EmployeeReports
//report heading updated (employeeName, employeeCode Added)
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