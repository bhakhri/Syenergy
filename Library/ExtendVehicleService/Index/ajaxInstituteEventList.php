<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Dipanjan Bbhattacharjee
// Created on : (28.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
    

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (e.eventTitle LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : ' DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : ' e.startDate,e.endDate';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $dashboardManager->getTotalEvent($filter);
    $eventRecordArray = $dashboardManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($eventRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $act ='<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" onClick="editWindow('.$eventRecordArray[$i]['eventId'].',\'ViewEvents\',400,400); return false;"/></a>';
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array( 'act' => $act,'srNo' => ($records+$i+1),
                                        'eventTitle' => strip_slashes($eventRecordArray[$i]['eventTitle']),
                                        'shortDescription' => strip_slashes($eventRecordArray[$i]['shortDescription']),
                                        'longDescription' => strip_slashes($eventRecordArray[$i]['longDescription']),
                                        'startDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate'])),
                                        'endDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate']))
                                      )
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
// $History: ajaxInstituteEventList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Index
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:22p
//Updated in $/Leap/Source/Library/Index
//updated icons for zoom and close
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/04/08    Time: 2:12p
//Updated in $/Leap/Source/Library/Index
//updated formatting for ajax based list
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:36p
//Created in $/Leap/Source/Library/Index
//intial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:54p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
