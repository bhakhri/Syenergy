<?php
//---------------------------------------------------------------------------------
// Purpose: To disply adv. category list with pagination and search , edit & delete 
// Author : Dipanjan Bbhattacharjee
// Created on : (09.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------
       
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ADVFB_TeacherMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    $fbMgr = FeedBackTeacherMappingManager::getInstance();
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $filter = ' AND ( labelName LIKE "%'.$search.'%" OR TRIM(className) LIKE "%'.$search.'%" OR 
                         feedbackSurveyLabel LIKE "%'.$search.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'labelName';
    
    $orderBy = " $sortField $sortOrderBy";         

    $totalArray    = $fbMgr->getTotalMappedTeachersNew($filter);
    $fbRecordArray = $fbMgr->getMappedTeachersListNew($filter,$limit,$orderBy);
    $cnt = count($fbRecordArray);
    
  
    
    for($i=0;$i<$cnt;$i++) {
       
        $timeTableLabelId=$fbRecordArray[$i]['timeTableLabelId'];
        $feedbackSurveyId=$fbRecordArray[$i]['feedbackSurveyId'];
        $classId=$fbRecordArray[$i]['classId'];
        
        $uniqueString=$timeTableLabelId.'_'.$feedbackSurveyId.'_'.$classId;
        
       //if this is used then do not allow to edit/delete
        $actionString='&nbsp;<a href="#" title="Detail">
                            <img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Detail" onclick="detailWindow(\''.$uniqueString.'\');return false;"></a>&nbsp;';
       
       $valueArray = array_merge(array('actionString' => $actionString, 
                                       'srNo' => ($records+$i+1)),
                                 $fbRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
    
?>