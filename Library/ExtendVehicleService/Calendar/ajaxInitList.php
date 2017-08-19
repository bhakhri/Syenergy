<?php
//-------------------------------------------------------
// Purpose: To store the records of events in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (4.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','AddEvent');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/CalendarManager.inc.php");
    $calendarManager = CalendarManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    //get the day component
    $day=explode("-",$REQUEST_DATA['sdate']);
    if($day[1]<10){
        $day[1]="0".intval($day[1]); //so that months become:08
    }
    
    if(trim($REQUEST_DATA[sdate])!=""){
     if(intval($day[2])!=0){   // for a specific day
       $filter = " AND ( '$REQUEST_DATA[sdate]' >=ev.startDate AND '$REQUEST_DATA[sdate]'<=ev.endDate) "; 
      }
     else{ //for the whole month
         $daysMonth=date("t",mktime(0,0,0,$m,1,$y));
         //build the query
         $filter="AND ( (ev.startDate BETWEEN '$day[0]-$day[1]-01' AND '$day[0]-$day[1]-$daysMonth')
                OR 
                (ev.endDate between '$day[0]-$day[1]-01' AND '$day[0]-$day[1]-$daysMonth')
                OR
                (ev.startDate <= '$day[0]-$day[1]-01' AND ev.endDate>= '$day[0]-$day[1]-$daysMonth') )";
                //echo "1";
         //$filter = " AND (ev.startDate LIKE '".$day[0]."-".$day[1]."-%' AND ev.endDate LIKE '".$day[0]."-".$day[1]."-%') ";     
      }
    }
 
    $search=trim(add_slashes($REQUEST_DATA['searchbox']));
    if($search!=''){
		$visibleDate = date('Y-m-d',strtotime($REQUEST_DATA['searchbox']));
        $filter .=" AND ( ev.eventTitle LIKE '%".$search."%' OR  ev.shortDescription LIKE '%".$search."%' OR ev.startDate LIKE '%".$visibleDate."%' OR  ev.endDate LIKE '%".$visibleDate."%' OR (select roleId from role where roleName like '%".$search."%' and FIND_IN_SET(roleId,(replace(ev.roleIds,'~',',')))))";
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $calendarManager->getTotalEvent($filter);
    $eventRecordArray = $calendarManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($eventRecordArray);
        
        $query="SELECT roleId, roleName from role";
        $roleArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query ");
      
        $j=count($roleArr);
        
    
    for($i=0;$i<$cnt;$i++) {
        
        $eventRecordArray[$i]['eventTitle']=htmlentities($eventRecordArray[$i]['eventTitle']);
        $eventRecordArray[$i]['shortDescription']=htmlentities($eventRecordArray[$i]['shortDescription']);
        
        // add stateId in actionId to populate edit/delete icons in User Interface 
        $roleIds=explode("~",$eventRecordArray[$i]['roleIds']);
        $eventRecordArray[$i]['roleIds']="";
        $l=count($roleIds);
        $m=0;
        $roles=array();
        for($k=0 ; $k<$j ;$k++){
            for($m=0 ; $m < $l ; $m++){
                if($roleArr[$k]['roleId']==$roleIds[$m]){
                        if($eventRecordArray[$i]['roleIds']){
                          $eventRecordArray[$i]['roleIds'].=", ".$roleArr[$k]['roleName'];
                        }
                       else{
                           $eventRecordArray[$i]['roleIds'].=$roleArr[$k]['roleName'];
                       } 
                }
            }
        }
        
        $actionString='<a href="#" title="Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].');return false;"></a>
                    <a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$eventRecordArray[$i]['eventId'].');return false;"></a>
                    <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteEvent('.$eventRecordArray[$i]['eventId'].');"/></a>';   
        
        $eventRecordArray[$i]['startDate']=UtilityManager::formatDate($eventRecordArray[$i]['startDate']);
        $eventRecordArray[$i]['endDate']=UtilityManager::formatDate($eventRecordArray[$i]['endDate']);
        $valueArray = array_merge(array('actionString' => $actionString ,'srNo' => ($records+$i+1) ),$eventRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
