<?php
//---------------------------------------------------------------------------------
// Purpose: To disply adv. category list with pagination and search , edit & delete 
// Author : Dipanjan Bbhattacharjee
// Created on : (09.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_TeacherMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    $fbMgr = FeedBackTeacherMappingManager::getInstance();
    
    
    $orderBy = " subjectCode ASC, employeeName ASC, groupName ASC";
    // $timeTableLabelId.'_'.$feedbackSurveyId.'_'.$classId;    
    $id = add_slashes(trim($REQUEST_DATA['id']));  
    
    
    $filter = " AND (CONCAT_WS('_',ftm.timeTableLabelId,ftm.feedbackSurveyId,ftm.classId) LIKE '$id') ";   
    $fbRecordArray = $fbMgr->getMappedTeachersDetail($filter,$orderBy);
    $cnt = count($fbRecordArray);
    
    $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                   <tr class='rowheading'>
                        <td width='2%'  valign='middle' class='searchhead_text' ><b>#</b></td>
                        <td width='10%' valign='middle' class='searchhead_text' align='left'><strong>Teacher</strong></td>
                        <td width='5%'  valign='middle' class='searchhead_text' align='left'><strong>Group</strong></td>
                        <td width='5%'  valign='middle' class='searchhead_text' align='left'><strong>Subject Code</strong></td>
                        <td width='10%'  valign='middle' class='searchhead_text' align='left'><strong>Subject Name</strong></td>
                        <td width='10%'  valign='middle' class='searchhead_text' align='left'><strong>Action</strong></td>                        
                   </tr>";
    
    $tableDataMsg  ='';
    if($cnt>0) {
       $tableDataMsg  = "<br>".$fbRecordArray[0]['className']."  (".$fbRecordArray[0]['labelName'].")<br>".$fbRecordArray[0]['feedbackSurveyLabel']."<br>";
       
       $id = $fbRecordArray[$i]['teacherMappingId'];
       $actionString='&nbsp;<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteTeacher(\''.$id.'\');"/></a>&nbsp;';
 
       for($i=0;$i<$cnt;$i++) {
          $bg = $bg =='trow0' ? 'trow1' : 'trow0';   
          
          $timeTableLabelId=$fbRecordArray[$i]['timeTableLabelId'];
          $feedbackSurveyId=$fbRecordArray[$i]['feedbackSurveyId'];
          $classId=$fbRecordArray[$i]['classId'];
          $subjectId=$fbRecordArray[$i]['subjectId']; 
          $groupId=$fbRecordArray[$i]['groupId']; 
        
          $uniqueString=$timeTableLabelId.'_'.$feedbackSurveyId.'_'.$classId.'_'.$subjectId.'_'.$groupId;
        
          $link = '<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" onClick="editWindow(\''.$uniqueString.'\');"/></a>&nbsp;';
           
          $tableData .= "<tr class='$bg'>
                           <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
                           <td valign='top' class='padding_top' align='left'>".$fbRecordArray[$i]['employeeNameCode']."</td>
                           <td valign='top' class='padding_top' align='left'>".$fbRecordArray[$i]['groupName']."</td>
                           <td valign='top' class='padding_top' align='left'>".$fbRecordArray[$i]['subjectCode']."</td>
                           <td valign='top' class='padding_top' align='left'>".$fbRecordArray[$i]['subjectName']."</td>
                           <td valign='top' class='padding_top' align='left'>".$link."</td>"; 
       }
    }    
    else {
      $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
      $tableData .= "<tr class='$bg'>
                       <td valign='top' colspan='4' class='padding_top' align='center'>No Data Found</td>
                     </tr>";      
    }
    
    
    echo $tableDataMsg.'~~'.$tableData;
die;    
    