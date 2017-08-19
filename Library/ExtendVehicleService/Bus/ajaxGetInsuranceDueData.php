<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','InsuranceDueReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusManager.inc.php");
    $busManager = BusManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    if(isset($REQUEST_DATA['busId']) and $REQUEST_DATA['busId']!=''){
     $filter = ' AND ( bs.busId IN ('.$REQUEST_DATA['busId'].') AND bi.insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
    }
    /*else{
      $filter = ' WHERE ( insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
    }*/
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $busManager->getTotalBus($filter);
    $busRecordArray = $busManager->getBusList($filter,$limit,$orderBy);
    $cnt = count($busRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        if($busRecordArray[$i]['insuranceDueDate']!='0000-00-00'){
         $busRecordArray[$i]['insuranceDueDate']=UtilityManager::formatDate($busRecordArray[$i]['insuranceDueDate']);
        }
        else{
            $busRecordArray[$i]['insuranceDueDate']=NOT_APPLICABLE_STRING;
        }

        $busRecordArray[$i]['modelNumber']=$busRecordArray[$i]['modelNumber']!=''? strip_slashes($busRecordArray[$i]['modelNumber']) : NOT_APPLICABLE_STRING;
        
        $busRecordArray[$i]['busName']=strip_slashes($busRecordArray[$i]['busName']);
        $busRecordArray[$i]['busNo']=strip_slashes($busRecordArray[$i]['busNo']);
        $busRecordArray[$i]['seatingCapacity']=strip_slashes($busRecordArray[$i]['seatingCapacity']);
        $busRecordArray[$i]['insuringCompany']=strip_slashes($busRecordArray[$i]['insuringCompany']);
        
        
        $valueArray = array_merge(array('action' => $busRecordArray[$i]['busId'] , 'srNo' => ($records+$i+1) ),$busRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetInsuranceDueData.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Bus
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Library/Bus
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/Bus
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:25
//Created in $/SnS/Library/Bus
//Added "InsuranceDue Report" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:37
//Updated in $/SnS/Library/Bus
//Enhanced bus master module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Library/Bus
//Created Bus Master Module
?>