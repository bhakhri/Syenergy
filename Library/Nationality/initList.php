<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "nationality" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/NationalityManager.inc.php");
    $nationalityManager = NationalityManager::getInstance();
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['nationId']) && $REQUEST_DATA['act']=='del') {
            
        /* *****As Nationality table is independent*****
        $recordArray = $nationalityManager->checkInInstitute($REQUEST_DATA['cityId']);
        if($recordArray[0]['found']==0) {
            if($cityManager->deleteCity($REQUEST_DATA['cityId']) ) {
                $message = DELETE;
            }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        } 
        */
        if($nationalityManager->deleteNationality($REQUEST_DATA['nationId']) ) {
                $message = DELETE;
            }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' WHERE (nt.nationName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $nationalityManager->getTotalNationality($filter);
    $nationalityRecordArray = $nationalityManager->getNationalityList($filter,$limit);
?>
<?php
  // $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Nationality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:12p
//Updated in $/Leap/Source/Library/Nationality
//Complted Comments
?>