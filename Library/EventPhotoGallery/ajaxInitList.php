<?php
//-------------------------------------------------------
// Purpose: To store the records of Subject in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PhotoGallery');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/PhotoManager.inc.php");
    $photoManager = PhotoManager::getInstance();
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter .= ' ';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visibleFrom';
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $photoManager->getTotalEventsPhoto($filter);
    $photoRecordArray = $photoManager->getEventsPhotoList($filter,$orderBy,$limit);
	$cnt = count($photoRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $checkall = "";  
       $photoGalleryId = trim($photoRecordArray[$i]['photoGalleryId']); 
       $roleId = trim($photoRecordArray[$i]['roleId']); 
       $totalPhotoGraphs = trim($photoRecordArray[$i]['totalPhotographs']);

       $photoRecordArray[$i]['visibleFrom'] = UtilityManager::formatDate(strip_slashes($photoRecordArray[$i]['visibleFrom'])); 
       $photoRecordArray[$i]['visibleTo'] = UtilityManager::formatDate(strip_slashes($photoRecordArray[$i]['visibleTo']));
     
       $checkall = '<input type="checkbox" name="chb[]" align="right" value="'.strip_slashes($photoGalleryId).'">';  
       
  $link1 = "<a  href='' alt='View Photo Gallery' tag='View Photo Gallery' onClick='getPhotoGalleryList(\"$photoGalleryId\",\"divMessage\",300,200); return false;'>$totalPhotoGraphs</a>";
	   
       $valueArray = array_merge(array('action' => $photoRecordArray[$i]['photoGalleryId'] , 
                                        'checkAll' =>  $checkall,  
                                        'srNo' => ($records+$i+1), 
                                        'photo' => $link1),
                                         $photoRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
