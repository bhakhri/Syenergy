<?php
//-------------------------------------------------------
// Purpose: To display the records of display "Notices in Parents" in array from the database, pagination and search  functionality
//
// Author : Arvind Singh Rawat
// Created on : 14-07-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
    define('MODULE','ParentTeacherComments');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    /////////////////////////

    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (tc.comments LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tc.subject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tc.postedOn LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR emp.employeeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'postedOn';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $parentManager->getTotalComments($filter);
    $parentRecordArray = $parentManager->getCommentsList($filter,$limit,$orderBy);
    
    $cnt = count($parentRecordArray);
	//$totalArray[0]['totalRecords']=$cnt;
   function trim_output($str,$maxlength,$mode=1,$rep='...'){
        $ret=($mode==2?chunk_split($str,30):$str);

        if(strlen($ret) > $maxlength){
            $ret=substr($ret,0,$maxlength).$rep;
        }
        return $ret;
    }
	 
    for($i=0;$i<$cnt;$i++) {
       
       $subject =html_entity_decode(strip_slashes($parentRecordArray[$i]['subject']));
       $comments =html_entity_decode(strip_slashes($parentRecordArray[$i]['comments'])); 
       
       $parentRecordArray[$i]['subject']  = $subject;
       $parentRecordArray[$i]['comments'] = $comments;
       
       if(strlen($subject) >=90) {
         $parentRecordArray[$i]['subject']  = trim_output($subject,90)."...";
       }
       
       if(strlen($comments) >=80) {
         $parentRecordArray[$i]['comments']  = trim_output($comments,80)."...";
       }
       
       $parentRecordArray[$i]['postedOn']=UtilityManager::formatDate($parentRecordArray[$i]['postedOn']);
        
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'Action' => '<img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" onClick="editWindow('.$parentRecordArray[$i]['commentId'].',\'ViewComments\',400,400); return false;"/>' ),$parentRecordArray[$i]);
    
          if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
          }
          else {
               $json_val .= ','.json_encode($valueArray);           
          }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>