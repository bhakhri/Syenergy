<?php
//-------------------------------------------------------
// Purpose: To display the records of display Notices in Parents in array from the database, pagination and search  functionality
//
// Author : Jaineesh
// Created on : 10-09-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    define('MODULE','ParentAdminMessages');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);     
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    /////////////////////////
    
    $filter ="";
    
    // Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (am.message LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visibleFromDate';
    
    $sortField1 = $sortField.", messageId";
    
    $orderBy = " $sortField1 $sortOrderBy";  
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $condition = $filter;
    $totalAdminArray = $parentManager->getTotalAdminMessages($condition);
    $adminRecordArray = $parentManager->getAdminMessages($condition,$orderBy,$limit);         
    
    $cnt = count($adminRecordArray);
    function trim_output($str,$maxlength,$mode=1,$rep='...'){
        $ret=($mode==2?chunk_split($str,30):$str);

        if(strlen($ret) > $maxlength){
          $ret=substr($ret,0,$maxlength).$rep;
        }
        return $ret;
    }

    
    for($i=0;$i<$cnt;$i++) {
       $adminMessage = trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($adminRecordArray[$i]['message']))),175);
       $adminRecordArray[$i]['visibleFromDate'] = UtilityManager::formatDate($adminRecordArray[$i]['visibleFromDate']);
       $adminRecordArray[$i]['visibleToDate'] = UtilityManager::formatDate($adminRecordArray[$i]['visibleToDate']);
       $adminRecordArray[$i]['message'] = $adminMessage;
       $fileName = IMG_PATH."/AdminMessage/".$adminRecordArray[$i]['messageFile'];
       if(file_exists($fileName) && ($adminRecordArray[$i]['messageFile']!="")) {
         $fileName1 = IMG_HTTP_PATH."/AdminMessage/".$adminRecordArray[$i]['messageFile'];
         $adminRecordArray[$i]['attachment'] =  '<a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a>';
       }
       else { 
         $adminRecordArray[$i]['attachment'] = NOT_APPLICABLE_STRING;
       }
        // add countryId in actionId to populate edit/delete icons in User Interface   
       $valueArray = array_merge(array('Action'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" width="15" onClick="editWindow('.$adminRecordArray[$i]['messageId'].',\'ViewAdmin\',600,600); return false;"/></a>','action' => $adminRecordArray[$i]['messageId'] , 'srNo' => ($records+$i+1) ),$adminRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalAdminArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>