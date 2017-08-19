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
    define('MODULE','TransportStaffMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
    $tranportManager = TransportStaffManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='DRIVER' ){
         $trType=1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='CONDUCTOR'){
         $trType=2;  
    }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='OTHER'){
         $trType=3;  
    }
    else{
        $trType=-1;
    }
    
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='YES' ){
         $verificationDone = 1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='NO'){
     $verificationDone = 0;
    }
    else{
      $verificationDone = -1;
    }
        
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $filter = ' WHERE  ( name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffType LIKE "'.$trType.'%" OR DATE_FORMAT(dlExpiryDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  DATE_FORMAT(joiningDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR dlNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR verificationDone LIKE "'.$verificationDone.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray     = $tranportManager->getTotalTransportStaff($filter);
    $transportRecordArray = $tranportManager->getTransportStaffList($filter,$limit,$orderBy);
    $cnt = count($transportRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $transportRecordArray[$i]['dlExpiryDate'] = UtilityManager::formatDate($transportRecordArray[$i]['dlExpiryDate']);
		if ($transportRecordArray[$i]['medicalExaminationDate'] != '--') {
			$transportRecordArray[$i]['medicalExaminationDate'] = UtilityManager::formatDate($transportRecordArray[$i]['medicalExaminationDate']);
		}
        $transportRecordArray[$i]['joiningDate']  = UtilityManager::formatDate($transportRecordArray[$i]['joiningDate']);
        $transportRecordArray[$i]['staffType']    = $transportStaffTypeArr[$transportRecordArray[$i]['staffType']];
        
        $valueArray = array_merge(array('action' => $transportRecordArray[$i]['staffId'] , 'srNo' => ($records+$i+1) ),$transportRecordArray[$i]);

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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/21/10    Time: 4:07p
//Updated in $/Leap/Source/Library/TransportStaff
//Add new field medical examination date
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Library/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Library/TransportStaff
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Library/TransportStuff
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/04/09   Time: 10:58
//Updated in $/SnS/Library/TransportStuff
//Done bug fixing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>
