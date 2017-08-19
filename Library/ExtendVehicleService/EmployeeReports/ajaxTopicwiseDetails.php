<?php
//-------------------------------------------------
//This file returns the load of teacher 
//
// Author :PArveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');  
    define('ACCESS','view');
   UtilityManager::ifNotLoggedIn(true);     
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");     
    $employeeReportsManager = EmployeeReportsManager::getInstance();     
    
    
    function trim_output($str,$maxlength='250',$rep='...'){
       $ret=chunk_split($str,60);
       if(strlen($ret) > $maxlength){
          $ret=substr($ret,0,$maxlength).$rep; 
       }
      return $ret;  
    }

   
    
      // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $employeeId = add_slashes($REQUEST_DATA['employeeId']);      
    $labelId = add_slashes($REQUEST_DATA['timeTableLabelId']);
    
    
    $orderBy = " ORDER BY $sortField $sortOrderBy ";
    
    
    // Findout Subjects
    $conditions  = " AND sub.hasAttendance = 1 AND tt.employeeId = '".$employeeId."' AND timeTableLabelId = '".$labelId."'";   
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
        
        $conditions  = " AND st.subjectId = '".$subjectId."'";
        $topicValueArray = $employeeReportsManager->getEmployeeTopicsPendingDetails($conditions);    
        if(count($topicValueArray)>0 )  {
           $subjectTopicId = UtilityManager::makeCSList($topicValueArray,'subjectTopicId');
           if($subjectTopicId != "") {
              $condition1   = " AND att.employeeId = '".$employeeId."' AND ttc.timeTableLabelId = '".$labelId."'";
              $condition1  .= " AND att.groupId = '".$groupId."' AND att.subjectId = '".$subjectId."'";
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
                  
                  if($topicList=='') {
                    $topicList = 0;
                  }
                  
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
                 
        $valueArray = array_merge(array('srNo' => ($records+$k+1),
                                        'className' => $className, 
                                        'subjectName' => $subjectName, 
                                        'subjectCode' => $subjectCode,
                                        'groupName' => $groupName,
                                        'topicAbbr'  => $topics , 
                                        'pending' => $pending ) );
          if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
          }
          else {
             $json_val .= ','.json_encode($valueArray);           
          }
          $k++;
    }   
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}';  
    die;
          
          
/*          for($k=0; $k<count($topicValueArray); $k++) {
               //$topicsTaughtId = $topicValueArray[$k]['topicsTaughtId'];
               $subjectTopicId = $topicValueArray[$k]['subjectTopicId'];
               
               // Topic coverd report
               //if($topicsTaughtId != "") {
               if($subjectTopicId != "") {
                   $condition1   = " AND att.employeeId = ".$employeeId." AND ttc.timeTableLabelId = ".$labelId;
                   $condition1  .= " AND att.groupId = ".$groupId." AND att.subjectId = ".$subjectId." AND att.topicsTaughtId = ".$topicsTaughtId;
                   $topicCoverdArray = $employeeReportsManager->getEmployeeTopicsDetails($condition1);    
                   
                   $fromDate = "";
                   if(count($topicCoverdArray)>0) {
                     for($l=0; $l<count($topicCoverdArray); $l++) {  
                        if($fromDate=="") { 
                           $fromDate = UtilityManager::formatDate($topicCoverdArray[$l]['fromDate']);
                        }
                        else {
                           $fromDate .= ", ".UtilityManager::formatDate($topicCoverdArray[$l]['fromDate']);
                        } 
                     }
                     if($topics=="") { 
                       $topics = $topicValueArray[$k]['topicAbbr']. " (". $fromDate.")";
                     }
                     else {
                       $topics .= "<br>".$topicValueArray[$k]['topicAbbr']. " (".$fromDate.")";
                     }
                   }
                   else {
                     if($pending=="") { 
                       $pending = $topicValueArray[$k]['topicAbbr'];
                     }
                     else {
                        $pending .= "<br>".$topicValueArray[$k]['topicAbbr'];
                     }  
                   }
               }
               else {       
                  if($pending=="") { 
                    $pending = $topicValueArray[$k]['topicAbbr'];
                  }
                  else {
                    $pending .= "<br>".$topicValueArray[$k]['topicAbbr'];
                  }
               }
            }
          }
        
                 
          if($pending=="")  { 
            $pending = NOT_APPLICABLE_STRING;       
          } 
                 
          if($topics=="")  { 
            $topics = NOT_APPLICABLE_STRING;       
          } 
                 
          $valueArray = array_merge(array('srNo' => ($records+$m+1),
                                          'subjectName' => $subjectName, 
                                          'subjectCode' => $subjectCode,
                                          'groupName' => $groupName,
                                          'topicAbbr'  => trim_output($topics) , 
                                          'pending' => trim_output($pending) ) );
          if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
          }
          else {
             $json_val .= ','.json_encode($valueArray);           
          }
          $m++;
   }
*/
    
echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}';  

// $History: ajaxTopicwiseDetails.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/21/10    Time: 10:23a
//Updated in $/LeapCC/Library/EmployeeReports
//validation format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/14/09   Time: 12:37p
//Updated in $/LeapCC/Library/EmployeeReports
//subjectTopicId check updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/23/09   Time: 2:33p
//Updated in $/LeapCC/Library/EmployeeReports
//className & format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/23/09   Time: 2:28p
//Created in $/LeapCC/Library/EmployeeReports
//file added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//topicswise report format updated (classname added)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/01/09   Time: 10:55a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//hasAttendance Condition updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/21/09    Time: 12:30p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Library/EmployeeReports
//search & conditions updated
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-08-25   Time: 5:09p
//Updated in $/LeapCC/Library/EmployeeReports
//Updated with Access rights DEFINE
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/26/09    Time: 5:19p
//Updated in $/LeapCC/Library/EmployeeReports
//file right settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/26/09    Time: 5:11p
//Updated in $/LeapCC/Library/EmployeeReports
//function, condition, formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/26/09    Time: 5:03p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/EmployeeReports
//formatting, conditions, validations updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Library/EmployeeReports
//validation, formatting, themes base css templates changes
//

?>
