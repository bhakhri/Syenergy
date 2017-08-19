<?php
//-------------------------------------------------------
// Purpose: To store the records of Notice in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','EventMaster');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EventManager.inc.php");
    $eventManager =EventManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = '';
        /*  $roleName = '  OR t.eventRoleName ';
            $arr = explode(",",add_slashes($REQUEST_DATA['searchbox']));  
            $cnt = count($arr);
            if($cnt>=2) {
              $roleName .= " IN ('".trim(add_slashes($arr[0]))."'"; 
              for($i=1; $i<$cnt; $i++) {
                  $roleName .= ", '".trim(add_slashes($arr[$i]))."'";
              }
              $roleName .= ")"; 
            }
            else {
              $roleName .= " LIKE  '%".add_slashes($REQUEST_DATA['searchbox'])."%' ";
            }
       */ 
        $filter = ' WHERE (DATE_FORMAT(eventWishDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          comments LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          IF(isStatus=1,"Yes","No") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          abbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$roleName.')';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventWishDate';
    $orderBy = " $sortField $sortOrderBy";

    $totalArray = $eventManager->getEventList($filter);
    $eventRecordArray = $eventManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($eventRecordArray);

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $isstatus=$eventRecordArray[$i]['isStatus'];
        $id = $eventRecordArray[$i]['userWishEventId'];
        
         $msg="<span id='lbl$id'>No</span>";
         $checked='';
         if($isstatus == '1'){
           $msg="<span id='lbl$id'>Yes</span>";
           $checked='checked';
         }
         $zz=rand(0,500);
         $checkall = "<input $checked type='checkbox' name='chb[]' id='$id' value='$isstatus'  onclick='sendCheck(\"$id\",\"s\"); return false;'>$msg";

        $eventRecordArray[$i]['eventWishDate'] = UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['eventWishDate']));
        $fileName = IMG_HTTP_PATH.'/Event/'.$eventRecordArray[$i]['eventPhoto']."?zz=$zz";
        $eventRecordArray[$i]['eventPhoto'] = (strip_slashes($eventRecordArray[$i]['eventPhoto'])=='' ? NOT_APPLICABLE_STRING :
            '<img src="'.$fileName.'" name="'.strip_slashes($eventRecordArray[$i]['eventPhoto']).'" height="40px" width="50px" onClick="download(this.name);" title="Download Photo" alt="Download Photo"/>');    
        $valueArray = array_merge(array('action' => $eventRecordArray[$i]['userWishEventId'] ,'checkAll' => $checkall, 
                                        'srNo' => ($records+$i+1) ),
                                         $eventRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
 
?>
