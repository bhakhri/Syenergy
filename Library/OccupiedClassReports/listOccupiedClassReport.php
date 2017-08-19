<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','OccupiedFreeClass');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/OccupiedClassManager.inc.php");
    $occupiedClassManager = OccupiedClassManager::getInstance();


	/////////////////////////
    
	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$daysOfWeek = $REQUEST_DATA['day'];
	$getPeriod = $REQUEST_DATA['periods'];
	$showStatus = $REQUEST_DATA['show'];
	$showReportStatus = $REQUEST_DATA['showReport'];
	$getDate = $REQUEST_DATA['startDate'];
	//print_r($REQUEST_DATA);
	//die;
	
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	//$weekDays = explode(',',$daysOfWeek);
	if($daysOfWeek != '' ) {
		$countWeekDays = count($daysOfWeek);
	}
	
	//$periodArray = explode(',',$periods);
	if($getPeriod != '') {
		$countPeriodArray = count($getPeriod);
	}

	$getTimeTableType = $occupiedClassManager->getTimeTableType($timeTableLabelId);

	$timeTableType = $getTimeTableType[0]['timeTableType'];

	
	$freeClassGroupArray = array();

	if($timeTableType != ''){
	  if($timeTableType == 1) {
		if($showStatus == 'free' && $showReportStatus == 'classwise') {
			$getGroups = $occupiedClassManager->getTimeTableGroups($timeTableLabelId);
			$timeTableTotalGroups = count($getGroups);
			
			if($timeTableTotalGroups > 0 ) {
				for($i=0;$i<$timeTableTotalGroups;$i++) {
					$timeTableGroupId = $getGroups[$i]['groupId'];
					
					if($countWeekDays > 0 ) {
						for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeGroups = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$timeTableGroupId);
									
									if ($getTimeTableFreeGroups[0]['totalRecords'] == 0 ) {
										$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
										$periodNameArray = $occupiedClassManager->getPeriodName($getPeriod);
										$periodNumber = $periodNameArray[0]['periodNumber'];
										
										$parentGroupId = $classFreeArray[0]['parentGroupId'];
										if ($parentGroupId != 0) {
											$getTimeTableFreeGroupsParent = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$parentGroupId);
											if ($getTimeTableFreeGroupsParent[0]['totalRecords'] == 0 ) {
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'],
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);

											}
											else {
												//
											}
										}
										else {
											$parentFreeArray = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$timeTableGroupId);
											if ($parentFreeArray[0]['totalRecords'] == 0 ) {
												$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		else {
			$getGroups = $occupiedClassManager->getTimeTableGroups($timeTableLabelId);
			$timeTableTotalGroups = count($getGroups);
			
			if($timeTableTotalGroups > 0 ) {
				for($i=0;$i<$timeTableTotalGroups;$i++) {
					$timeTableGroupId = $getGroups[$i]['groupId'];
					
					if($countWeekDays > 0 ) {
						for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeGroups = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$timeTableGroupId);
									
									if ($getTimeTableFreeGroups[0]['totalRecords'] > 0 ) {
										$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
										$periodNameArray = $occupiedClassManager->getPeriodName($getPeriod);
										$periodNumber = $periodNameArray[0]['periodNumber'];
										
										$parentGroupId = $classFreeArray[0]['parentGroupId'];
										if ($parentGroupId != 0) {
											$getTimeTableFreeGroupsParent = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$parentGroupId);
											if ($getTimeTableFreeGroupsParent[0]['totalRecords'] > 0 ) {
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);

											}
											else {
												//
											}
										}
										else {
											$parentFreeArray = $occupiedClassManager->getTimeTableFreeGroups($daysOfWeek,$getPeriod,$timeTableGroupId);
											if ($parentFreeArray[0]['totalRecords'] > 0 ) {
												$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 
																				'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

	$freeRoomsArray = array();

		if($showStatus == 'free' && $showReportStatus == 'roomwise') {
			$getRooms = $occupiedClassManager->getTimeTableRooms($timeTableLabelId);
			$timeTableTotalRooms = count($getRooms);
			
			if($timeTableTotalRooms > 0 ) {
				for($i=0;$i<$timeTableTotalRooms;$i++) {
					$timeTableRoomId = $getRooms[$i]['roomId'];
					
					if($countWeekDays > 0 ) {
						for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeRooms = $occupiedClassManager->getTimeTableFreeRooms($daysOfWeek,$getPeriod,$timeTableRoomId);
									if ($getTimeTableFreeRooms[0]['totalRecords'] == 0 ) {
										$roomsFreeArray = $occupiedClassManager->getFreeRooms($timeTableRoomId);
										$freeRoomsArray[] = array('roomId'=>$roomsFreeArray[0]['roomId'], 'roomName'=>$roomsFreeArray[0]['roomName'],'capacity'=>$roomsFreeArray[0]['capacity'],'examCapacity'=>$roomsFreeArray[0]['examCapacity'],'blockName'=>$roomsFreeArray[0]['blockName'],'buildingName'=>$roomsFreeArray[0]['buildingName']);
									}
								}
							}
						}
					}
				}
			}
		}
		else {
			$getRooms = $occupiedClassManager->getTimeTableRooms($timeTableLabelId);
			$timeTableTotalRooms = count($getRooms);
			
			if($timeTableTotalRooms > 0 ) {
				for($i=0;$i<$timeTableTotalRooms;$i++) {
					$timeTableRoomId = $getRooms[$i]['roomId'];
					
					if($countWeekDays > 0 ) {
						for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeRooms = $occupiedClassManager->getTimeTableFreeRooms($daysOfWeek,$getPeriod,$timeTableRoomId);
									if ($getTimeTableFreeRooms[0]['totalRecords'] > 0 ) {
										$roomsFreeArray = $occupiedClassManager->getFreeRooms($timeTableRoomId);
										$freeRoomsArray[] = array('roomId'=>$roomsFreeArray[0]['roomId'], 'roomName'=>$roomsFreeArray[0]['roomName'],'capacity'=>$roomsFreeArray[0]['capacity'],'examCapacity'=>$roomsFreeArray[0]['examCapacity'],'blockName'=>$roomsFreeArray[0]['blockName'],'buildingName'=>$roomsFreeArray[0]['buildingName']);
									}
								}
							}
						}
					}
				}
			}
		}


		if($showStatus == 'free' && $showReportStatus == 'classwise' || $showStatus == 'occupied' && $showReportStatus == 'classwise') {
			$recordCount = count($freeClassGroupArray);
			//echo($recordCount);
			for($s=0;$s<$recordCount;$s++) {
				$valueArray = array_merge(array('srNo' => ($s+1)),$freeClassGroupArray[$s]);
				$i++;

				if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
				}
				else {
					$json_val .= ','.json_encode($valueArray);           
				}
			}
		}



		if($showStatus == 'free' && $showReportStatus == 'roomwise' || $showStatus == 'occupied' && $showReportStatus == 'roomwise') {
			$recordRoomsCount = count($freeRoomsArray);
			for($s=0;$s<$recordRoomsCount;$s++) {
				$valueArray = array_merge(array('srNo' => ($s+1)),$freeRoomsArray[$s]);

				if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
				}
				else {
					$json_val .= ','.json_encode($valueArray);           
				}
			}
		}
	  }
	 else {
		if($showStatus == 'free' && $showReportStatus == 'classwise') {
			$getGroups = $occupiedClassManager->getTimeTableGroups($timeTableLabelId);
			$timeTableTotalGroups = count($getGroups);
			
			if($timeTableTotalGroups > 0 ) {
				for($i=0;$i<$timeTableTotalGroups;$i++) {
					$timeTableGroupId = $getGroups[$i]['groupId'];
					
					if($getDate != '' ) {
						//for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeGroups = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$timeTableGroupId);
									
									if ($getTimeTableFreeGroups[0]['totalRecords'] == 0 ) {
										$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
										$periodNameArray = $occupiedClassManager->getPeriodName($getPeriod);
										$periodNumber = $periodNameArray[0]['periodNumber'];
										$parentGroupId = $classFreeArray[0]['parentGroupId'];
										if ($parentGroupId != 0) {
											$getTimeTableFreeGroupsParent = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$parentGroupId);
											if ($getTimeTableFreeGroupsParent[0]['totalRecords'] == 0 ) {
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'],
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);

											}
											else {
												//
											}
										}
										else {
											$parentFreeArray = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$timeTableGroupId);
											if ($parentFreeArray[0]['totalRecords'] == 0 ) {
												$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);
											}
										}
									}
								}
							}
						//}
					}
				}
			}
		}
		else {
			$getGroups = $occupiedClassManager->getTimeTableGroups($timeTableLabelId);
			$timeTableTotalGroups = count($getGroups);
			
			if($timeTableTotalGroups > 0 ) {
				for($i=0;$i<$timeTableTotalGroups;$i++) {
					$timeTableGroupId = $getGroups[$i]['groupId'];
					
					if($getDate != '' ) {
						//for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeGroups = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$timeTableGroupId);
									
									if ($getTimeTableFreeGroups[0]['totalRecords'] > 0 ) {
										$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
										$periodNameArray = $occupiedClassManager->getPeriodName($getPeriod);
										$periodNumber = $periodNameArray[0]['periodNumber'];
										
										$parentGroupId = $classFreeArray[0]['parentGroupId'];
										if ($parentGroupId != 0) {
											$getTimeTableFreeGroupsParent = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$parentGroupId);
											if ($getTimeTableFreeGroupsParent[0]['totalRecords'] > 0 ) {
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);

											}
											else {
												//
											}
										}
										else {
											$parentFreeArray = $occupiedClassManager->getTimeTableDailyFreeGroups($getDate,$getPeriod,$timeTableGroupId);
											if ($parentFreeArray[0]['totalRecords'] > 0 ) {
												$classFreeArray = $occupiedClassManager->getFreeClasses($timeTableGroupId);
												$freeClassGroupArray[] = array(	'className'=>$classFreeArray[0]['className'], 
																				'groupName'=>$classFreeArray[0]['groupName'], 
																				'day'=>$daysArr[$daysOfWeek], 
																				'period'=>$periodNumber,
																				'groupStudent'=>$classFreeArray[0]['groupStudent']);
											}
										}
									}
								}
							}
						//}
					}
				}
			}
		}

	$freeRoomsArray = array();

		if($showStatus == 'free' && $showReportStatus == 'roomwise') {
			$getRooms = $occupiedClassManager->getTimeTableRooms($timeTableLabelId);
			$timeTableTotalRooms = count($getRooms);
			
			if($timeTableTotalRooms > 0 ) {
				for($i=0;$i<$timeTableTotalRooms;$i++) {
					$timeTableRoomId = $getRooms[$i]['roomId'];
					
					if($getDate != '' ) {
						//for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeRooms = $occupiedClassManager->getTimeTableDailyFreeRooms($getDate,$getPeriod,$timeTableRoomId);
									if ($getTimeTableFreeRooms[0]['totalRecords'] == 0 ) {
										$roomsFreeArray = $occupiedClassManager->getFreeRooms($timeTableRoomId);
										$freeRoomsArray[] = array('roomId'=>$roomsFreeArray[0]['roomId'], 'roomName'=>$roomsFreeArray[0]['roomName'],'capacity'=>$roomsFreeArray[0]['capacity'],'examCapacity'=>$roomsFreeArray[0]['examCapacity'],'blockName'=>$roomsFreeArray[0]['blockName'],'buildingName'=>$roomsFreeArray[0]['buildingName']);
									}
								}
							}
						//}
					}
				}
			}
		}
		else {
			$getRooms = $occupiedClassManager->getTimeTableRooms($timeTableLabelId);
			$timeTableTotalRooms = count($getRooms);
			
			if($timeTableTotalRooms > 0 ) {
				for($i=0;$i<$timeTableTotalRooms;$i++) {
					$timeTableRoomId = $getRooms[$i]['roomId'];
					
					if($getDate != '' ) {
						//for($j=0;$j<$countWeekDays;$j++) {
							if($countPeriodArray > 0 ) {
								for($k=0;$k<$countPeriodArray;$k++) {
									$getTimeTableFreeRooms = $occupiedClassManager->getTimeTableDailyFreeRooms($getDate,$getPeriod,$timeTableRoomId);
									if ($getTimeTableFreeRooms[0]['totalRecords'] > 0 ) {
										$roomsFreeArray = $occupiedClassManager->getFreeRooms($timeTableRoomId);
										$freeRoomsArray[] = array('roomId'=>$roomsFreeArray[0]['roomId'], 'roomName'=>$roomsFreeArray[0]['roomName'],'capacity'=>$roomsFreeArray[0]['capacity'],'examCapacity'=>$roomsFreeArray[0]['examCapacity'],'blockName'=>$roomsFreeArray[0]['blockName'],'buildingName'=>$roomsFreeArray[0]['buildingName']);
									}
								}
							}
						//}
					}
				}
			}
		}


		if($showStatus == 'free' && $showReportStatus == 'classwise' || $showStatus == 'occupied' && $showReportStatus == 'classwise') {
			$recordCount = count($freeClassGroupArray);
			//echo($recordCount);
			for($s=0;$s<$recordCount;$s++) {
				$valueArray = array_merge(array('srNo' => ($s+1)),$freeClassGroupArray[$s]);
				$i++;

				if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
				}
				else {
					$json_val .= ','.json_encode($valueArray);           
				}
			}
		}



		if($showStatus == 'free' && $showReportStatus == 'roomwise' || $showStatus == 'occupied' && $showReportStatus == 'roomwise') {
			$recordRoomsCount = count($freeRoomsArray);
			for($s=0;$s<$recordRoomsCount;$s++) {
				$valueArray = array_merge(array('srNo' => ($s+1)),$freeRoomsArray[$s]);

				if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
				}
				else {
					$json_val .= ','.json_encode($valueArray);           
				}
			}
		}
	 }
   }
	
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$offenseRecordArray.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	//echo json_encode($freeClassGroupArray);
   //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($freeClassGroupArray).'","page":"'.$page.'","'.$json_val.'"}'; 
    
// for VSS
// $History: listOccupiedClassReport.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/07/10    Time: 1:27p
//Updated in $/LeapCC/Library/OccupiedClassReports
//show building & block 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:17p
//Created in $/LeapCC/Library/OccupiedClassReports
//new ajax files for occupied/free class reports
//
//
?>