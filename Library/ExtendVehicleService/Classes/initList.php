<?php

//  This File calls Edit Function used in adding class Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classManager = ClassesManager::getInstance();
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  (className LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'% OR classDescription LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $orderBy = " $sortField $sortOrderBy";         


    $totalArray = $classManager->getTotalClasses($filter);
    $classesRecordArray = $classManager->getClassesList($filter,$limit, $orderBy);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:41p
//Created in $/Leap/Source/Library/Classes
//file added for showing list
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>