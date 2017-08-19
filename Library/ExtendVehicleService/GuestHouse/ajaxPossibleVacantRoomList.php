<?php
//-------------------------------------------------------
// Purpose: To make list view for guest house requests
// Author : Dipanjan Bbhattacharjee
// Created on : (17.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GuestHouseAuthorization');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $fromDate = trim($REQUEST_DATA['fromDate']);
    $toDate   = trim($REQUEST_DATA['toDate']);
    
    if($fromDate=='' or $toDate==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    //////
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    $date1=explode('~',$fromDate);
    $date2=explode('~',$toDate);
    
    $arrDate=$date1[0];
    $arrDatePart=explode('-',$date1[0]);
    $arrTimeArray=explode('*',$date1[1]);
    $arrTime=$arrTimeArray[0];
    $arrAmPm=$arrTimeArray[1];
    
    $deptDate=$date2[0];
    $deptDatePart=explode('-',$date2[0]);
    $deptTimeArray=explode('*',$date2[1]);
    $deptTime=$deptTimeArray[0];
    $deptAmPm=$deptTimeArray[1];
    
    $startHours = intval(substr($arrTime,0,2));
    $endHours = intval(substr($deptTime,0,2));
    $startMins = intval(substr($arrTime,3));
    $endMins = intval(substr($deptTime,3));
    $startSec=0;
    $endSec=0;
    
    if ($startHours != 12 and $arrAmPm == 'PM') {
        $startHours += 12;
    }
    else if ($startHours == 12 and $arrAmPm == 'AM') {
        $startHours = 0;
    }
    if ($endHours != 12 and $deptAmPm == 'PM') {
        $endHours += 12;
    }
    else if ($endHours == 12 and $deptAmPm == 'AM') {
        $endHours = 0;
    }
    
    $startDateTime = mktime($startHours, $startMins, $startSec, $arrDatePart[1], $arrDatePart[2], $arrDatePart[0]);
    $endDateTime = mktime($endHours, $endMins, $endSec, $deptDatePart[1], $deptDatePart[2], $deptDatePart[0]);
    
	$guestHouseRecordArray = $guestHouseManager->getPossibleVacantRoomList2($filter,' ',$orderBy);
	$roomRecordArray = array();
	$conflictsFound = array();

	foreach ($guestHouseRecordArray as $record) {
        
		$hostelRoomId = $record['hostelRoomId'];
		$roomCapacity = $record['roomCapacity'];
        $roomRecordArray[$hostelRoomId]['hostelRoomId'] = $hostelRoomId;
		$roomRecordArray[$hostelRoomId]['capacity'] = $roomCapacity;
        $roomRecordArray[$hostelRoomId]['roomName'] = $record['roomName'];
        $roomRecordArray[$hostelRoomId]['hostelName'] = $record['hostelName'];
        $roomRecordArray[$hostelRoomId]['hostelId'] = $record['hostelId'];
        
        if (!isset($conflictsFound[$hostelRoomId])) {
		    $conflictsFound[$hostelRoomId] = 0;
        }
        
        if($record['arrivalDate']==-1){
            continue;
        }

		$tempArrivalDayArray = explode('-',$record['arrivalDate']);
		$tempStartHours = intval(substr($record['arrivalTime'],0,2));
		$tempStartMins = intval(substr($record['arrivalTime'],3));
		$tempArrivalTimeAmPm = $record['arrivalAmPm'];

		$tempDepartureDayArray = explode('-',$record['departureDate']);
		$tempEndHours = intval(substr($record['departureTime'],0,2));
		$tempEndMins = intval(substr($record['departureTime'],3));
		$tempDepartureTimeAmPm = $record['departureAmPm'];
		$tempStartSec=0;
		$tempEndSec=0;

		if($tempStartHours != 12 and $tempArrivalTimeAmPm == 'PM') {
			$tempStartHours += 12;
		}
		else if ($startHours == 12 and $tempArrivalTimeAmPm == 'AM') {
			$tempStartHours = 0;
		}
		if($tempEndHours != 12 and $tempDepartureTimeAmPm == 'PM') {
			$tempEndHours += 12;
		}
		else if ($tempEndHours == 12 and $tempDepartureTimeAmPm == 'AM') {
			$tempEndHours = 0;
		}

		$tempStartDateTime = mktime($tempStartHours, $tempStartMins, $tempStartSec, $tempArrivalDayArray[1], $tempArrivalDayArray[2], $tempArrivalDayArray[0]);
		$tempEndDateTime = mktime($tempEndHours, $tempEndMins, $tempEndSec, $tempDepartureDayArray[1], $tempDepartureDayArray[2], $tempDepartureDayArray[0]);


		if ($startDateTime == $tempStartDateTime) {
			$conflictsFound[$hostelRoomId]++;
		}
		elseif ($startDateTime > $tempStartDateTime and $startDateTime < $tempEndDateTime) {
			$conflictsFound[$hostelRoomId]++;
		}
		elseif ($startDateTime < $tempStartDateTime and $endDateTime > $tempStartDateTime) {
			$conflictsFound[$hostelRoomId]++;
		}
	}
	$vacantCount=0;
    foreach($roomRecordArray as $hostelRoomId => $detailsArray){
        $roomCapacity = $detailsArray['capacity'];
        $conflicts = $conflictsFound[$hostelRoomId];
        if ($roomCapacity > $conflicts) {
            $vacantCount++;
            $actionString='<a href="#" title="Selecy"><img src="'.IMG_HTTP_PATH.'/select2.gif"  border="0" alt="Select"   onclick="selectHostelRoom('.$detailsArray['hostelId'].",".$detailsArray['hostelRoomId'].');return false;"></a>';
            $valueArray = array_merge(array('actionString' => $actionString , 'srNo' => ($vacantCount) ),$detailsArray);
            if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
            }
            else {
             $json_val .= ','.json_encode($valueArray);           
             }
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$vacantCount.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
?>