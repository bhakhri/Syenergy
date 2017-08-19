<?php
//---------------------------------------------------------------------------------------------------------
// Purpose: To store the records of subject topic in array from the database, pagination and search, delete 
// functionality
//
// Author : Parveen Sharma
// Created on : 15.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    $mod = $REQUEST_DATA['mod'];
	if (empty($mod)) {
		$mod = $sessionHandler->getSessionVariable('Module');
	}
	define('MODULE',$mod);
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();
	  $subjectId = add_slashes(trim($REQUEST_DATA['tSubjectId']));
    
    if($subjectId=='') {
      $subjectId=0;  
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    /// Search filter /////  
    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (sub.subjectCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR st.topic LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR st.topicAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    */
    $filter = " AND sub.subjectId = $subjectId";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    $totalArray = $subjecttopicManager->getTotalSubjectTopic($filter);
    $subjecttopicRecordArray = $subjecttopicManager->getSubjectTopicList($filter,$limit,$orderBy);
    $cnt = count($subjecttopicRecordArray);
    
    $valueArray  = array();
    
    for($i=0;$i<$cnt;$i++) {
          $id = $subjecttopicRecordArray[$i]['subjectTopicId'];
          
          $subjecttopicRecordArray[$i]['topic']=htmlentities($subjecttopicRecordArray[$i]['topic']);
          $subjecttopicRecordArray[$i]['topicAbbr']=htmlentities($subjecttopicRecordArray[$i]['topicAbbr']);
          
		  $subjecttopicRecordArray[$i]['subjectCode'] = '<span title="'.strip_slashes($subjecttopicRecordArray[$i]['subjectName']).' ('.strip_slashes($subjecttopicRecordArray[$i]['subjectCode']).')">'.strip_slashes($subjecttopicRecordArray[$i]['subjectCode']).'</span>';
          if($subjecttopicRecordArray[$i]['sEmployeeId']==-1){ //if this class is not used in student table then user can edit/delete it
            $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$subjecttopicRecordArray[$i]['subjectTopicId'].',480,480);return false;"></a>
                        <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteSubjectTopic('.$subjecttopicRecordArray[$i]['subjectTopicId'].');"/></a>';
        }
        else{
            $actionStr=NOT_APPLICABLE_STRING;
        }
        
        if(strlen(add_slashes($subjecttopicRecordArray[$i]['topic'])) >70) {
           $subjecttopicRecordArray[$i]['topic'] = '<a href="" name="bubble" onclick="showTopicDetails('.$id.',\'divTopic\',400,200);return false;" title="Berif Information" >'.substr(add_slashes($subjecttopicRecordArray[$i]['topic']),0,70).'...</a>';
        }

        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$subjecttopicRecordArray[$i]
                                 );
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 4/22/10    Time: 1:27p
//Updated in $/LeapCC/Library/SubjectTopic
//show subject name & code tool tip
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Library/SubjectTopic
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/30/09    Time: 4:15p
//Updated in $/LeapCC/Library/SubjectTopic
//updated role permission
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectTopic
//added define variable for Role Permission
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/13/09    Time: 3:46p
//Updated in $/LeapCC/Library/SubjectTopic
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/06/09    Time: 6:01p
//Updated in $/LeapCC/Library/SubjectTopic
//issue fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/30/09    Time: 10:38a
//Updated in $/LeapCC/Library/SubjectTopic
//search condition subject code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectTopic
//subject topic file added
//

?>