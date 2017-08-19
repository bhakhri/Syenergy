<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();

class TimeTableConflictManagerDayWise {
   private static $instance			=	null;
	private $newPeriodArray				=	array();
	private $monday						=	1;
	private $tuesday					=	2;
	private $wednesday					=	3;
	private $thursday					=	4;
	private $friday						=	5;
	private $saturday					=	6;
	private $sunday						=	7;
	private $errorCounter				=	0;
	private $errorMessageArray			=	array();
	private $tsAllocationArray			=	array();
	private $tBookingArray				=	array();
	private $tAdjustmentArray			=	array();
	private $trAllocationArray			=	array();
	private $rsAllocationArray			=	array();
	private $rBookingArray				=	array();
	private $rAdjustmentArray			=	array();
	private $gsAllocationArray			=	array();
	private $missingEntriesArray		=	array();
	private $grAllocationArray			=	array();
	private $gBookingArray				=	array();
	private $gAdjustmentArray			=	array();
	private $allCombinationArray		=	array();
	private $newDayArray				=	array(
												'mon'=>'mondayPeriod', 'tue'=>'tuesdayPeriod', 'wed'=>'wednesdayPeriod', 
												'thu'=>'thursdayPeriod', 'fri'=>'fridayPeriod', 'sat'=>'saturdayPeriod', 
												'sun'=>'sundayPeriod');
	private $dayNameArray				=	array(1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday', 7=>'Sunday');
	private $teacherArray				=	array();
	private $subjectArray				=	array();
	private $groupArray					=	array();
	private $roomArray					=	array();
	private $gsMappingArray				=	array();
	private $studentAllocationArray	=  array();
	private $teacherIds					=	'';
	private $subjectIds					=	'';
	private $groupIds					=	'';
	private $roomIds					=	'';
	private $teacherNameArray			=	array();
	private $subjectCodeArray			=	array();
	private $groupShortArray			=	array();
	private $roomAbbreviationArray		=	array();
	private $clientEntryArray			=	array();
	private $checkSideArray				=	array('client','server','adjustment');
	private $checkTeacherInstituteArray	=	array();
	private $checkRoomInstituteArray	=	array();
	private $process					=	'';
	private $success					=	'';
	private $errorMessageString			=	'';
	private $missingMessageString		=	'';
	private $source						=	'';
	private $showCalling				=	false;
	private $fromDate					= '';
	private $monArray2				=	array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep","Oct", "Nov", "Dec");
    
    private function __construct() {
    }
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
		  $this->getArray();
        return self::$instance;
    }

	public function setProcess($process) {
		$this->process = $process;
	}


	private function getProcess() {
		if ($this->process == 'show conflicts' or $this->process == 'save' ) {
			return $this->process;
		}
		else {
			echo 'Please select process';
			die;
		}
	}


	public function getNewPeriodArray() {
		global $timeTableManager;
		$periodDetailArray = $timeTableManager->getPeriods();
		foreach($periodDetailArray as $periodRecord) {
			$this->newPeriodArray[$periodRecord['periodSlotId']][$periodRecord['periodNumber']] = array();
			$this->newPeriodArray[$periodRecord['periodSlotId']][$periodRecord['periodNumber']]['startTime'] = $periodRecord['startTime'];
			$this->newPeriodArray[$periodRecord['periodSlotId']][$periodRecord['periodNumber']]['startAmPm'] = $periodRecord['startAmPm'];
			$this->newPeriodArray[$periodRecord['periodSlotId']][$periodRecord['periodNumber']]['endTime'] = $periodRecord['endTime'];
			$this->newPeriodArray[$periodRecord['periodSlotId']][$periodRecord['periodNumber']]['endAmPm'] = $periodRecord['endAmPm'];
		}
	}

	public function makeIds() {
		global $REQUEST_DATA;
		global $FE;
		require_once($FE . "/Library/common.inc.php");
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();

		$this->teacherArray = $studentManager->getSingleField('employee','employeeId,employeeName', " ");
		$this->teacherNameArray = array();
		foreach($this->teacherArray as $teacherRecord) {
			$this->teacherNameArray[$teacherRecord['employeeId']] = $teacherRecord['employeeName'];
		}
		$this->subjectArray = $studentManager->getSingleField('subject','subjectId,subjectCode', " ");
		$this->subjectCodeArray = array();
		foreach($this->subjectArray as $subjectRecord) {
			$this->subjectCodeArray[$subjectRecord['subjectId']] = $subjectRecord['subjectCode'];
		}
		$this->groupArray = $studentManager->getSingleField('`group`,`class`','groupId, groupShort, className', "where `group`.classId = `class`.classId ");
		$this->groupShortArray = array();
		$this->groupClassNameArray = array();
		foreach($this->groupArray as $groupRecord) {
			$this->groupShortArray[$groupRecord['groupId']] = $groupRecord['groupShort'];
			$this->groupClassNameArray[$groupRecord['groupId']] = $groupRecord['className'];
		}

		$this->roomArray = $studentManager->getSingleField('room r, block b, building c',"r.roomId, concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation", " WHERE r.blockId = b.blockId AND b.buildingId = c.buildingId ");
		$this->roomAbbreviationArray = array();
		foreach($this->roomArray as $roomRecord) {
			$this->roomAbbreviationArray[$roomRecord['roomId']] = $roomRecord['roomAbbreviation'];
		}
	}

	//	THIS FUNCTION IS USED FOR CHECKING CONFLICTS
	public function checkConflict($teacherId = '', $subjectId = '', $groupId = '', $roomId = '', $dayCtr = '', $period = '', $postPeriodSlotId = '', $timeTableLabelId = '', $fromDate = '', $toDate = '', $timeTableId = '0', $source = 'Time Table Generation') {
		global $timeTableManager, $sessionHandler;
		$this->makeIds();
		$this->getNewPeriodArray();
		$this->source = 'Time Table';
		global $REQUEST_DATA;
		$periodSlotId = $REQUEST_DATA['periodSlotId'];
		$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
		$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
		$classId = $REQUEST_DATA['studentClass'];
		$totalRecords = count($REQUEST_DATA['teacherId']);
		$fromDate = $REQUEST_DATA['fromDate'];
		list($fromY,$fromM, $fromD) = explode('-',$fromDate);
		$dateValid = checkdate ($fromM,$fromD,$fromY);
		if ($dateValid == false) {
			return 'Date: '.$fromDate.' DOES NOT EXIST';
			die;
		}


		$errorFound = false;
		if (!is_array($this->newPeriodArray[$postPeriodSlotId])) {
			$this->newPeriodArray[$postPeriodSlotId] = array();
		}
		$periodsArray = $REQUEST_DATA['periods'];
		if (!is_array($periodsArray)) {
			$periodsArray = array();
		}
		foreach($periodsArray as $periodRecord) {
			$thisPeriodsArray = explode(',', $periodRecord);
			foreach($thisPeriodsArray as $period) {
				if (empty($period)) {
					return '<font size="2"><b>Please enter values for all periods.</b></font>';
					die;
				}
				if (!array_key_exists($period,$this->newPeriodArray[$postPeriodSlotId])) {
					return 'Period: '.$period.' DOES NOT EXIST';
					die;
				}
			}
		}

		foreach($this->checkSideArray as $checkSide) {
			//check conflicts on client side
			if ($checkSide == 'client') {
				$this->errorCounter = 0;
				$i = 0;
				while ($i < $totalRecords) {
					$teacherId		=	$REQUEST_DATA['teacherId'][$i];
					$subjectId		=	$REQUEST_DATA['subjectId'][$i];
					$groupId		=	$REQUEST_DATA['groupId'][$i];
					if (is_array($REQUEST_DATA['roomId'])) {
						$roomId		=	$REQUEST_DATA['roomId'][$i];
					}
					else {
						$roomId		=	$REQUEST_DATA['roomId'];
					}
					$dayCtr = 0;
					$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
					if (!$teacherId or !$subjectId or !$groupId or !$roomId) {
						return PLEASE_SELECT_ALL_VALUES;
						die;
					}
					$valueFound = false;
					###############################################################
					////foreach($this->newDayArray as $newDayKey => $newDayVal) {
						//$dayCtr++;
						//$$newDayVal = $REQUEST_DATA[$newDayKey][$i];
						//if (!empty($$newDayVal)) {
							//$valueFound = true;
							//$dayArray = explode(',', $$newDayVal);
							//foreach($dayArray as $period) {

							//foreach($periodsArray as $periodRecord) {
								$thisPeriodsArray = explode(',', $REQUEST_DATA['periods'][$i]);
								foreach($thisPeriodsArray as $period) {
									$toDate = '';
									//call conflict checking functions.
									$return = $this->callConflictCheckers($teacherId,$subjectId,$groupId,$roomId,$dayCtr,$period,$checkSide,$postPeriodSlotId, $fromDate, $toDate, $this->source);
									$valueFound = true;
									if ($return === false) {
										$errorFound = true;
										continue;
									}
									
									$this->clientEntryArray[] = "$teacherId#$subjectId#$groupId#$roomId#$fromDate#$period#$postPeriodSlotId#$timeTableLabelId";
									$currentAllocation = $teacherId . '#' .  $subjectId . '#' .  $groupId . '#' .  $roomId . '#' .  $fromDate . '#' . $period;
									if (!is_array($this->allCombinationArray2)) {
										$this->allCombinationArray2 = array();
									}
									if (!in_array($currentAllocation, $this->allCombinationArray2)) {
										$this->allCombinationArray2[] = $currentAllocation;
									}
									else {
										return '<font size="2"><b>Duplicate values found for <BR>Teacher: <u>'.$this->teacherNameArray[$teacherId] . '</u>, Subject: <u>' . $this->subjectCodeArray[$subjectId] . '</u>, Group: <u>' .  $this->groupShortArray[$groupId] . '</u>, Room: <u>' . $this->roomAbbreviationArray[$roomId] . '</u>, Date: <u>' . date('d-M-Y',strtotime($fromDate)) . '</u>, Period: '.$period.' ('.$this->getPeriodTime($periodSlotId, $period).')</b></font>';
										die;
									}
									if (!is_array($this->allCombinationArray)) {
										$this->allCombinationArray = array();
									}
									if (!in_array($currentAllocation, $this->allCombinationArray)) {
										$this->allCombinationArray[] = $currentAllocation;
									}
								}
							//}
					//}
					##################################################################
					if ($valueFound === false) {
						return PLEASE_SELECT_ALL_VALUES;
						die;
					}
					$i++;
				}
				if ($errorFound === true) {
					return $this->showError();
					die;
				}
			}
			#	FOR CHECKING CONFLICTS BETWEEN CLIENT SIDE TIME TABLE DATA AND SAVED TIME TABLE DATA
			else if($checkSide == 'server') {

				$this->errorCounter = 0;
				$this->errorMessageArray = array();
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$conditions = '';
					$conditions2 = '';
					$fromDate = $REQUEST_DATA['fromDate'];
					$return = $timeTableManager->updateCurrentTimeTable2($postPeriodSlotId, $classId, $timeTableLabelId, $fromDate);
					if ($return == false) {
						return ERROR_WHILE_CLEARING_PREVIOUS_TIME_TABLE;
						die;
					}
					# FETCH CURRENT TIME TABLE LABEL DATA, AND CHECK CONFLICTS
					$timeTableArray = $timeTableManager->getTimeTableCurrentData($timeTableLabelId);
					$errorFound = false;
					foreach($timeTableArray as $timeTableRecord) {
						$roomId = $timeTableRecord['roomId'];
						$teacherId = $timeTableRecord['employeeId'];
						$groupId = $timeTableRecord['groupId'];
						$dayId = $timeTableRecord['daysOfWeek'];
						$periodSlotId = $timeTableRecord['periodSlotId'];
						$period = $timeTableRecord['periodId'];
						$periodNumber = $timeTableRecord['periodNumber'];
						$subjectId = $timeTableRecord['subjectId'];
						$startTime = $timeTableRecord['startTime'];
						$startAmPm = $timeTableRecord['startAmPm'];
						$endTime = $timeTableRecord['endTime'];
						$endAmPm = $timeTableRecord['endAmPm'];
						$fromDate = $timeTableRecord['fromDate'];
						$toDate = '';
						$return = $this->callConflictCheckers($teacherId,$subjectId,$groupId,$roomId,$dayId,$periodNumber,$checkSide,$periodSlotId, $fromDate, $toDate, $this->source);
						if ($return === false) {
							$errorFound = true;
							continue;
						}
					}
					if ($errorFound === true) {
						return $this->showError();
						die;
					}
					# FETCH TIME TABLE LABEL DATA OF ALL OTHER ACTIVE TIME TABLE LABELS, AND CHECK CONFLICTS
					$timeTableArray = $timeTableManager->getOtherTimeTableCurrentData($timeTableLabelId);
					$errorFound = false;
					foreach($timeTableArray as $timeTableRecord) {
						$roomId = $timeTableRecord['roomId'];
						$teacherId = $timeTableRecord['employeeId'];
						$groupId = $timeTableRecord['groupId'];
						$dayId = $timeTableRecord['daysOfWeek'];
						$periodSlotId = $timeTableRecord['periodSlotId'];
						$period = $timeTableRecord['periodId'];
						$periodNumber = $timeTableRecord['periodNumber'];
						$subjectId = $timeTableRecord['subjectId'];
						$startTime = $timeTableRecord['startTime'];
						$startAmPm = $timeTableRecord['startAmPm'];
						$endTime = $timeTableRecord['endTime'];
						$endAmPm = $timeTableRecord['endAmPm'];
						$fromDate = $timeTableRecord['fromDate'];
						$toDate = '';
						$return = $this->callConflictCheckers($teacherId,$subjectId,$groupId,$roomId,$dayId,$periodNumber,$checkSide,$periodSlotId, $fromDate, $toDate, $this->source);
						if ($return === false) {
							$errorFound = true;
							continue;
						}
					}
					if ($errorFound === true) {
						return $this->showError();
						die;
					}
					
					if ($this->getProcess() == 'show conflicts') {
						if(!SystemDatabaseManager::getInstance()->rollbackTransaction()) {
							return ERROR_WHILE_CLEANING_TRANSACTION;
							die;
						}
						//return true;
					}
				}
			}
			//FOR CHECKING CONFLICTS BETWEEN CLIENT SIDE TIME TABLE DATA AND SAVED ADJUSTMENT DATA
			else if ($checkSide == 'adjustment') {
				$this->errorCounter = 0;
				$this->errorMessageArray = array();
				$this->missingEntriesArray = Array();
				//get class adjustment data

				//every adjustment side entry of this class must be there on client side.
				$classAdjustmentArray = $timeTableManager->getTimeTableAdjustmentData($timeTableLabelId);
				$errorFound = false;
				foreach($classAdjustmentArray as $timeTableRecord) {
					$roomId = $timeTableRecord['roomId'];
					$teacherId = $timeTableRecord['employeeId'];
					$oldTeacherId = $timeTableRecord['oldEmployeeId'];
					$groupId = $timeTableRecord['groupId'];
					$dayId = $timeTableRecord['daysOfWeek'];
					$periodSlotId = $timeTableRecord['periodSlotId'];
					$period = $timeTableRecord['periodId'];
					$periodNumber = $timeTableRecord['periodNumber'];
					$subjectId = $timeTableRecord['subjectId'];
					$startTime = $timeTableRecord['startTime'];
					$startAmPm = $timeTableRecord['startAmPm'];
					$fromDate = $timeTableRecord['fromDate'];
					$toDate = $timeTableRecord['toDate'];
					$endTime = $timeTableRecord['endTime'];
					$endAmPm = $timeTableRecord['endAmPm'];
					$dbClassId = $timeTableRecord['classId'];
					$adjustmentType = $timeTableRecord['adjustmentType'];
					//to check adjustment of swap and moved entries for current class and current period slotId
					if ($classId == $dbClassId and ($adjustmentType == 1 or $adjustmentType == 3) and $postPeriodSlotId == $periodSlotId) {
						$adjustmentEntry = "$oldTeacherId#$subjectId#$groupId#$roomId#$fromDate#$periodNumber#$periodSlotId#$timeTableLabelId";
						if (!in_array($adjustmentEntry, $this->clientEntryArray)) {
								$this->missingEntriesArray[] = ++$missingCounter.'. Record for Teacher '.$this->teacherNameArray[$oldTeacherId].' for day '.$this->dayNameArray[$dayId].' for period '.$periodNumber.' can not be changed/removed <br> as it is adjusted with Teacher '.$this->teacherNameArray[$teacherId]. ' from '.date('d-M-Y',strtotime($fromDate)).' to '.date('d-M-Y',strtotime($toDate));
								$errorFound = true;
								continue;
						}
					}
					else {
						$return = $this->callConflictCheckers($teacherId,$subjectId,$groupId,$roomId,$dayId,$periodNumber,$checkSide,$periodSlotId, $fromDate, $toDate, 'Adjustment');
						if ($return === false) {
							$errorFound = true;
							continue;
						}
					}
				}
				if ($errorFound === true) {
					return $this->showError();
					die;
				}

				$multipleErrorFoundTeacher = $this->checkTeacherInstituteConflicts();
				$multipleErrorFoundRoom = $this->checkRoomInstituteConflicts();
				$studentTimingError = $this->checkStudentConflicts();

				if ($multipleErrorFoundTeacher == true or $multipleErrorFoundRoom == true) {
					return $this->showError();
					die;
				}

				//checking for student


				if ($this->getProcess() == 'show conflicts' or $this->getProcess() == 'save') {
					return true;
				}
				//$success = true;
			}
		}
	}

	public function checkStudentConflicts() {
		global $timeTableManager, $REQUEST_DATA;
		$fromDate = $REQUEST_DATA['fromDate'];
		$periodSlotId = $REQUEST_DATA['periodSlotId'];
		foreach($this->gsMappingArray as $groupId => $detailsArray) {
			$studentGroupsArray = $timeTableManager->getGroupStudents($groupId);
			foreach($detailsArray as $detailRecord) {
				list($subjectId, $period, $periodStartDateTime, $periodEndDateTime) = explode('#', $detailRecord);
				foreach($studentGroupsArray as $studentGroupRecord) {
					$studentId = $studentGroupRecord['studentId'];
					if (!is_array($this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime])) {
						$this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime] = array();
					}
					if (!in_array($subjectId, $this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime])) {
						if (count($this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime]) > 0) {
							echo '<font size="2"><b><u>Student-Timing Clash found for : </u><BR>Subject: <font color="red"> '. $this->subjectCodeArray[$subjectId] . '</font> with Subject: <font color="red">' .  $this->subjectCodeArray[$this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime][0]] . '</font>, Date: ' . date('d-M-Y',strtotime($fromDate)) . ', Period: '.$period. ' ('. $this->getPeriodTime($periodSlotId, $period) .")</b></font>";
							die;
						}
						else {
							$this->studentAllocationArray[$studentId][$periodStartDateTime . '#' . $periodEndDateTime][] = $subjectId;
						}
					}
				}
			}
		}
	}



	public function saveTimeTable() {
		global $sessionHandler, $timeTableManager, $REQUEST_DATA;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$periodSlotId = $REQUEST_DATA['periodSlotId'];
		$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
		$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
		$classId = $REQUEST_DATA['studentClass'];
		$fromDate = $REQUEST_DATA['fromDate'];
		$periodsArray = $timeTableManager->getAllPeriods($postPeriodSlotId);
		$periodNumberArray = array();
		foreach($periodsArray as $periodRecord) {
			$periodNumberArray[$periodRecord['periodNumber']] = $periodRecord['periodId'];
		}
		$noConflictFound = $this->checkConflict();
		
		if ($noConflictFound === true) {
			if(SystemDatabaseManager::getInstance()->startTransaction()) {
				$conditions = '';
				$conditions2 = '';
				$conditions = " AND fromDate = '".$REQUEST_DATA['fromDate']."'";
				$return = $timeTableManager->updateCurrentTimeTableDayWise($postPeriodSlotId, $classId, $timeTableLabelId, $conditions);
				if ($return == false) {
					return ERROR_WHILE_CLEARING_PREVIOUS_TIME_TABLE;
					die;
				}
				$insertStr = '';
				foreach($this->allCombinationArray as $record) {
					list($teacherId,$subjectId,$groupId,$roomId,$fromDate,$period) = explode('#',$record);
					if ($insertStr != '') {
						$insertStr .= ', ';
					}

					$dayId = date('N',strtotime($fromDate));

					$insertStr .= "($roomId, $teacherId, $groupId, $classId, $instituteId, $sessionId, $dayId, $postPeriodSlotId, ".$periodNumberArray[$period].", $subjectId, '$fromDate', NULL, $timeTableLabelId)";
				}
				if ($insertStr != '') {
					$return = $timeTableManager->addNewTimeTableInTransaction($insertStr);
					if ($return == false) {
						return ERROR_WHILE_ADDING_NEW_TIME_TABLE;
						die;
					}
				}
				//update old timetableId to new timetableId
				/*
				$classAdjustmentArray = $timeTableManager->getClassAdjustments($timeTableLabelId, $classId);
				if (count($classAdjustmentArray)) {
					foreach($classAdjustmentArray as $classAdjustmentRecord) {
						$timeTableId = $classAdjustmentRecord['timeTableId'];
						$roomId = $classAdjustmentRecord['roomId'];
						$employeeId = $classAdjustmentRecord['oldEmployeeId'];
						$groupId = $classAdjustmentRecord['groupId'];
						$instituteId = $classAdjustmentRecord['instituteId'];
						$sessionId = $classAdjustmentRecord['sessionId'];
						$daysOfWeek = $classAdjustmentRecord['daysOfWeek'];
						$periodSlotId = $classAdjustmentRecord['periodSlotId'];
						$periodId = $classAdjustmentRecord['periodId'];
						$subjectId = $classAdjustmentRecord['subjectId'];
						$timeTableLabelId = $classAdjustmentRecord['timeTableLabelId'];
						$newIdArray = $timeTableManager->getNewTimeTableId($roomId, $employeeId, $groupId, $instituteId, $sessionId, $daysOfWeek, $periodSlotId, $periodId, $subjectId, $timeTableLabelId);
						$newId = $newIdArray[0]['timeTableId'];
						if ($newId != '') {
							$return = $timeTableManager->updateAdjustmentInTransaction($timeTableId, $newId);
							if ($return == false) {
								return ERROR_WHILE_UPDATING_TIME_TABLE_ADJUSTMENT;
								die;
							}
						}
					}
				}
				*/
				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					return SUCCESS;
				}
				else {
					return FAILURE;
				}
			}
			else {
				return FAILURE;
			}
		}
		else {
			return $noConflictFound;
		}
	}


	public function checkTeacherInstituteConflicts() {
		global $timeTableManager, $sessionHandler;
		$multipleErrorFound = false;
		foreach($this->checkTeacherInstituteArray as $teacherDay => $periodTimeArray) {
			list($teacherId, $fromDate) = explode('#', $teacherDay);
			$otherInstituteDataArray = $timeTableManager->getTeacherOtherInstituteDayData($teacherId, $fromDate);
			foreach($periodTimeArray as $periodTimeRecord) {
				list($className, $processFrom, $periodSlotId, $subjectId, $period, $periodStartDateTime, $periodEndDateTime) = explode('#', $periodTimeRecord);
				if (is_array($otherInstituteDataArray) and count($otherInstituteDataArray)) {
					foreach($otherInstituteDataArray as $otherInstituteRecord) {
						$errorFound = false;
						$startTime = $otherInstituteRecord['startTime'];
						$startAmPm = $otherInstituteRecord['startAmPm'];
						$endTime = $otherInstituteRecord['endTime'];
						$endAmPm = $otherInstituteRecord['endAmPm'];
						$oGroupId = $otherInstituteRecord['groupId'];
						$oClassName = $this->groupClassNameArray[$oGroupId];
						$oPeriodSlotId = $otherInstituteRecord['periodSlotId'];
						$oPeriodId = $otherInstituteRecord['periodId'];
						$oInstituteId = $otherInstituteRecord['instituteId'];
						$instituteId = $sessionHandler->getSessionVariable('InstituteId');
						$periodsArray = $timeTableManager->getAllPeriods($oPeriodSlotId);
						$periodNumberArray = array();
						foreach($periodsArray as $periodRecord) {
							$periodNumberArray[$periodRecord['periodNumber']] = $periodRecord['periodId'];
						}

						$oPeriod = array_search($oPeriodId, $periodNumberArray);
						$date = date('d-m-Y', strtotime($fromDate));
						list($newDay,$newMonth,$newYear) = explode('-',$date);

						list($startHour, $startMin, $startSec) = explode(':', $startTime);
						$periodStartAMPM = $startAmPm;

						if ($startHour != 12 and $periodStartAMPM == 'PM') {
							$startHour += 12;
						}
						list($endHour, $endMin, $endSec) = explode(':', $endTime);
						$periodEndAMPM = $endAmPm;
						if ($endHour != 12 and $periodEndAMPM == 'PM') {
							$endHour += 12;
						}
						
						$oIperiodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
						$oIperiodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);

						if ($periodStartDateTime == $oIperiodStartDateTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oIperiodStartDateTime and $periodStartDateTime < $oIperiodEndDateTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oIperiodStartDateTime and $periodEndDateTime > $oIperiodStartDateTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorTDI','Teacher-Institute-Day-Timing', array('class2'=>$className, 'class1' => $oClassName, 'institute1'=>$instituteId, 'institute2'=>$oInstituteId, 'teacherId'=>$teacherId, 'source2' => 'TimeTable', 'source1' => 'TimeTable', 'fromDate'=>$fromDate, 'period1'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period2'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							$multipleErrorFound = true;
						}
					}
				}
			}
		}
		return $multipleErrorFound;
	}

	public function checkRoomInstituteConflicts() {
		global $timeTableManager, $sessionHandler;
		$multipleErrorFound = false;
		foreach($this->checkRoomInstituteArray as $roomDay => $periodTimeArray) {
			list($roomId, $fromDate) = explode('#', $roomDay);
			$otherInstituteDataArray = $timeTableManager->getRoomOtherInstituteData($roomId, $fromDate);
			foreach($periodTimeArray as $periodTimeRecord) {
				list($className, $processFrom, $periodSlotId, $subjectId, $period, $periodStartDateTime, $periodEndDateTime) = explode('#', $periodTimeRecord);
				if (is_array($otherInstituteDataArray) and count($otherInstituteDataArray)) {
					foreach($otherInstituteDataArray as $otherInstituteRecord) {
						$errorFound = false;
						$startTime = $otherInstituteRecord['startTime'];
						$startAmPm = $otherInstituteRecord['startAmPm'];
						$endTime = $otherInstituteRecord['endTime'];
						$endAmPm = $otherInstituteRecord['endAmPm'];
						$oGroupId = $otherInstituteRecord['groupId'];
						$oClassName = $this->groupClassNameArray[$oGroupId];
						$oPeriodSlotId = $otherInstituteRecord['periodSlotId'];
						$oPeriodId = $otherInstituteRecord['periodId'];
						$oInstituteId = $otherInstituteRecord['instituteId'];
						$oRoomId = $otherInstituteRecord['roomId'];
						$instituteId = $sessionHandler->getSessionVariable('InstituteId');
						$periodsArray = $timeTableManager->getAllPeriods($oPeriodSlotId);
						$periodNumberArray = array();
						foreach($periodsArray as $periodRecord) {
							$periodNumberArray[$periodRecord['periodNumber']] = $periodRecord['periodId'];
						}

						$oPeriod = array_search($oPeriodId, $periodNumberArray);
						//$date = date('d-m-Y', strtotime("next ".$this->dayNameArray[$dayId]));
						list($newYear,$newMonth,$newDay) = explode('-',$fromDate);

						list($startHour, $startMin, $startSec) = explode(':', $startTime);
						$periodStartAMPM = $startAmPm;

						if ($startHour != 12 and $periodStartAMPM == 'PM') {
							$startHour += 12;
						}
						list($endHour, $endMin, $endSec) = explode(':', $endTime);
						$periodEndAMPM = $endAmPm;
						if ($endHour != 12 and $periodEndAMPM == 'PM') {
							$endHour += 12;
						}
						
						$oIperiodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
						$oIperiodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);

						if ($periodStartDateTime == $oIperiodStartDateTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oIperiodStartDateTime and $periodStartDateTime < $oIperiodEndDateTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oIperiodStartDateTime and $periodEndDateTime > $oIperiodStartDateTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorRDI','Room-Institute-Day-Timing', array('room1'=>$roomId, 'room2' => $oRoomId, 'institute1'=>$instituteId, 'institute2'=>$oInstituteId, 'teacherId'=>$teacherId, 'source2' => 'TimeTable', 'source1' => 'TimeTable', 'fromDate'=>$fromDate, 'period1'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period2'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							$multipleErrorFound = true;
						}
					}
				}
			}
		}
		return $multipleErrorFound;
	}

	private function callConflictCheckers($teacherId,$subjectId,$groupId,$roomId,$dayId,$periodNumber,$checkSide,$periodSlotId, $fromDate='', $toDate='', $processFrom = '') {
		if ($this->showCalling == true) {
			echo "<br>checking for " . $this->teacherNameArray[$teacherId] . "," . $this->subjectCodeArray[$subjectId] . "," . $this->groupShortArray[$groupId] . "," . $this->roomAbbreviationArray[$roomId] . "," . $this->dayNameArray[$dayId] . ",$periodNumber,$checkSide,$periodSlotId, $fromDate, $toDate, $processFrom";
		}
		$functionArray = array('TS', 'TR', 'RS', 'GS', 'GR');
		foreach($functionArray as $function) {
			$function = 'check' . $function . 'Entry';
			$return = $this->$function($teacherId,$subjectId,$groupId,$roomId,$dayId,$periodNumber,$checkSide,$periodSlotId, $fromDate, $toDate, $processFrom);
			if ($return === false) {
				return false;
			}
		}
		return true;
	}

	//This function is used for checking Teacher Subject Conflicts.
	private function checkTSEntry($teacherId,$subjectId,$groupId,$roomId,$dayId,$period, $checkSide, $periodSlotId,$fromDate = '',$toDate = '', $processFrom = '') {
		//$date = date('d-m-Y', strtotime());
		$className = $this->groupClassNameArray[$groupId];
		list($newYear, $newMonth,$newDay) = explode('-',$fromDate);
		list($startHour, $startMin, $startSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['startTime']);
		$periodStartAMPM = $this->newPeriodArray[$periodSlotId][$period]['startAmPm'];

		if ($startHour != 12 and $periodStartAMPM == 'PM') {
			$startHour += 12;
		}
		list($endHour, $endMin, $endSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['endTime']);
		$periodEndAMPM = $this->newPeriodArray[$periodSlotId][$period]['endAmPm'];
		if ($endHour != 12 and $periodEndAMPM == 'PM') {
			$endHour += 12;
		}
		
		$periodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
		$periodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);

		$this->checkTeacherInstituteArray[$teacherId . '#' . $fromDate][] = $className . '#' . $processFrom . '#' . $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
		$this->checkRoomInstituteArray[$roomId . '#' . $fromDate][] = $className . '#' . $processFrom . '#' . $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;


		if ($checkSide == 'client') {
			if (!is_array($this->tsAllocationArray[$teacherId][$fromDate . '#' . $period])) {
				$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->tsAllocationArray[$teacherId][$fromDate . '#' . $period])) {
				if (count($this->tsAllocationArray[$teacherId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorTSC','Teacher-Subject', array('source1'=>$this->source, 'source2'=>$this->tsAllocationSourceArray[$teacherId][$fromDate . '#' . $period][0], 'subject1'=>$subjectId, 'teacherId'=>$teacherId, 'fromDate'=>$fromDate, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'subject2'=>$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period][0]));
					return false;
				}
				else {
					$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period][] = $subjectId;
					$this->tsAllocationSourceArray[$teacherId][$fromDate . '#' . $period][] = $processFrom;
					$this->tBookingArray[$teacherId][$fromDate][] = $className . '#' . $processFrom . '#' . $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
			if (isset($this->tBookingArray[$teacherId][$fromDate])) {
				$errorFound = false;
				foreach($this->tBookingArray[$teacherId][$fromDate] as $oRecord) {
					list($oClassName, $oSource, $oPeriodSlotId, $oSubjectId, $oPeriod, $oStartTime, $oEndTime) = explode('#', $oRecord);
					if ($oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorTSS','Teacher-Subject-Timing', array('class2'=>$className, 'class1' => $oClassName, 'subject2'=>$subjectId, 'subject1'=>$oSubjectId, 'teacherId'=>$teacherId, 'source2' => 'Adjustment', 'source1' => $processFrom, 'fromDate'=>$fromDate, 'period2'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period1'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'server') {
			if (isset($this->tBookingArray[$teacherId][$fromDate])) {
				$errorFound = false;
				foreach($this->tBookingArray[$teacherId][$fromDate] as $oRecord) {
					list($oClassName, $oSource, $oPeriodSlotId, $oSubjectId, $oPeriod, $oStartTime, $oEndTime) = explode('#', $oRecord);
					if ($this->source == 'Adjustment' or $oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorTSS','Teacher-Subject-Timing', array('class1'=>$className, 'class2' => $oClassName, 'subject1'=>$subjectId, 'subject2'=>$oSubjectId, 'teacherId'=>$teacherId, 'source1' => $this->source, 'source2' => $processFrom, 'fromDate'=>$fromDate, 'period1'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period2'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'adjustment') {
			if (!is_array($this->tsAllocationArray[$teacherId][$fromDate . '#' . $fromDate])) {
				$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->tsAllocationArray[$teacherId][$fromDate . '#' . $period])) {
				if (count($this->tsAllocationArray[$teacherId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorTSA','Teacher-Subject-Adjustment', array('subject1'=>$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period][0], 'teacherId'=>$teacherId, 'period'=>$period, 'subject2'=>$subjectId, 'fromDate'=>$fromDate, 'toDate'=>$toDate));
					return false;
				}
				else {
					$this->tsAllocationArray[$teacherId][$fromDate . '#' . $period][] = $subjectId;
					$this->tBookingArray[$teacherId][$fromDate][] = $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
		}
		return true;
	}

	//This function is used for checking Teacher Room Conflicts.
	private function checkTREntry($teacherId,$subjectId,$groupId,$roomId,$dayId,$period, $checkSide, $periodSlotId,$fromDate = '',$toDate = '', $processFrom = '') {
		if ($checkSide == 'client') {
			if (!is_array($this->trAllocationArray[$teacherId][$fromDate . '#' . $period])) {
				$this->trAllocationArray[$teacherId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($roomId, $this->trAllocationArray[$teacherId][$fromDate . '#' . $period])) {
				if (count($this->trAllocationArray[$teacherId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorTRC','Teacher-Room', array('source1'=>$this->source, 'source2'=>$processFrom, 'teacherId'=>$teacherId, 'fromDate'=>$fromDate, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'room2'=>$this->trAllocationArray[$teacherId][$fromDate . '#' . $period][0], 'room1'=>$roomId));
					return false;
				}
				else {
					$this->trAllocationArray[$teacherId][$fromDate . '#' . $period][] = $roomId;
				}
			}
		}
		return true;
	}

	//This function is used for checking Room Subject Conflicts.
	private function checkRSEntry($teacherId,$subjectId,$groupId,$roomId,$dayId,$period, $checkSide, $periodSlotId,$fromDate = '',$toDate = '', $processFrom = '') {
		list($newYear, $newMonth,$newDay) = explode('-',$fromDate);
		list($startHour, $startMin, $startSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['startTime']);
		$periodStartAMPM = $this->newPeriodArray[$periodSlotId][$period]['startAmPm'];

		if ($startHour != 12 and $periodStartAMPM == 'PM') {
			$startHour += 12;
		}
		list($endHour, $endMin, $endSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['endTime']);
		$periodEndAMPM = $this->newPeriodArray[$periodSlotId][$period]['endAmPm'];
		if ($endHour != 12 and $periodEndAMPM == 'PM') {
			$endHour += 12;
		}
		$periodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
		$periodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);


		if ($checkSide == 'client') {
			if (!is_array($this->rsAllocationArray[$roomId][$fromDate . '#' . $period])) {
				$this->rsAllocationArray[$roomId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->rsAllocationArray[$roomId][$fromDate . '#' . $period])) {
				if (count($this->rsAllocationArray[$roomId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorRSC','Room-Subject', array('source1'=>$this->source, 'source2'=>$this->rsAllocationSourceArray[$roomId][$fromDate . '#' . $period][0], 'subject1'=>$subjectId, 'teacherId'=>$teacherId, 'fromDate'=>$fromDate, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'roomId'=>$roomId, 'subject2'=>$this->rsAllocationArray[$roomId][$fromDate . '#' . $period][0]));
					return false;
				}
				else {
					$this->rsAllocationArray[$roomId][$fromDate . '#' . $period][] = $subjectId;
					$this->rsAllocationSourceArray[$roomId][$fromDate . '#' . $period][] = $processFrom;
					$this->rBookingArray[$roomId][$fromDate][] = $teacherId .'#' .  $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
			if (isset($this->rBookingArray[$roomId][$fromDate])) {
				$errorFound = false;
				foreach($this->rBookingArray[$roomId][$fromDate] as $oRecord) {
					list($oTeacherId, $oPeriodSlotId, $oSubjectId, $oPeriod, $oStartTime,$oEndTime) = explode('#', $oRecord);
					if ($oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorRSS','Room-Subject-Timing', array('teacher2'=>$teacherId, 'teacher1'=>$oTeacherId, 'class2'=>$className, 'class1' => $oClassName, 'subject2'=>$subjectId, 'subject1'=>$oSubjectId, 'roomId'=>$roomId, 'source2' => 'Adjustment', 'source1' => $processFrom, 'fromDate'=>$fromDate, 'period2'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period1'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'server') {
			if (isset($this->rBookingArray[$roomId][$fromDate])) {
				$errorFound = false;
				foreach($this->rBookingArray[$roomId][$fromDate] as $oRecord) {
					list($oTeacherId, $oPeriodSlotId, $oSubjectId, $oPeriod, $oStartTime,$oEndTime) = explode('#', $oRecord);
					if ($this->source == 'Adjustment' or $oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true ) {
							$this->addError('ErrorRSS','Room-Subject-Timing', array('teacher1'=>$teacherId, 'teacher2'=>$oTeacherId, 'class1'=>$className, 'class2' => $oClassName, 'subject1'=>$subjectId, 'subject2'=>$oSubjectId, 'roomId'=>$roomId, 'source1' => $this->source, 'source2' => $processFrom, 'fromDate'=>$fromDate, 'period1'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period2'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'adjustment') {
			if (!is_array($this->rsAllocationArray[$roomId][$fromDate . '#' . $period])) {
				$this->rsAllocationArray[$roomId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->rsAllocationArray[$roomId][$fromDate . '#' . $period])) {
				if (count($this->rsAllocationArray[$roomId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorRSA','Room-Subject-Adjustment', array('subject1'=>$this->rsAllocationArray[$roomId][$fromDate . '#' . $period][0], 'dayId'=>$dayId, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'subject2'=>$subjectId, 'roomId'=>$roomId, 'fromDate'=>$fromDate, 'toDate'=>$toDate, 'source1' => $this->source, 'source2' => $processFrom));
					return false;
				}
				else {
					$this->rsAllocationArray[$roomId][$fromDate . '#' . $period][] = $subjectId;
					$this->rBookingArray[$roomId][$fromDate][] = $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
		}
		return true;
	}

	//This function is used for checking Group Subject Conflicts.
	private function checkGSEntry($teacherId,$subjectId,$groupId,$roomId,$dayId,$period, $checkSide, $periodSlotId,$fromDate = '',$toDate = '', $processFrom = '') {
		$className = $this->groupClassNameArray[$groupId];
		list($newYear, $newMonth,$newDay) = explode('-',$fromDate);
		list($startHour, $startMin, $startSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['startTime']);
		$periodStartAMPM = $this->newPeriodArray[$periodSlotId][$period]['startAmPm'];

		if ($startHour != 12 and $periodStartAMPM == 'PM') {
			$startHour += 12;
		}
		list($endHour, $endMin, $endSec) = explode(':', $this->newPeriodArray[$periodSlotId][$period]['endTime']);
		$periodEndAMPM = $newPeriodArray[$periodSlotId][$period]['endAmPm'];
		if ($endHour != 12 and $periodEndAMPM == 'PM') {
			$endHour += 12;
		}
		$periodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
		$periodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);

		if ($checkSide == 'client') {
			if (!is_array($this->gsAllocationArray[$groupId][$fromDate . '#' . $period])) {
				$this->gsAllocationArray[$groupId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->gsAllocationArray[$groupId][$fromDate . '#' . $period])) {
				if (count($this->gsAllocationArray[$groupId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorGSC','Group-Subject', array('source1'=>$this->source, 'source2'=>$this->gsAllocationSourceArray[$groupId][$fromDate . '#' . $period][0], 'subject1'=>$subjectId, 'groupId'=>$groupId, 'fromDate'=>$fromDate, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'subject2'=>$this->gsAllocationArray[$groupId][$fromDate . '#' . $period][0]));
					return false;
				}
				else {
					$this->gsAllocationArray[$groupId][$fromDate . '#' . $period][] = $subjectId;
					$this->gsAllocationSourceArray[$groupId][$fromDate . '#' . $period][] = $processFrom;
					$this->gBookingArray[$groupId][$fromDate][] = $className .'#' . $teacherId .'#' . $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
					$this->gsMappingArray[$groupId][] = $subjectId .'#' . $period . '#'. $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
			if (isset($this->gBookingArray[$groupId][$fromDate])) {
				$errorFound = false;
				foreach($this->gBookingArray[$groupId][$fromDate] as $oRecord) {
					list($oClassName, $oTeacherId, $oPeriodSlotId, $oSubjectId, $oPeriod,$oStartTime,$oEndTime) = explode('#', $oRecord);
					if ($oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true) {
							$this->addError('ErrorGSS','Group-Subject-Timing', array('teacher2'=>$teacherId, 'teacher1'=>$oTeacherId, 'class2'=>$className, 'class1' => $oClassName, 'subject2'=>$subjectId, 'subject1'=>$oSubjectId, 'groupId'=>$groupId, 'source2' => 'Adjustment', 'source1' => $processFrom, 'fromDate'=>$fromDate, 'period2'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period1'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'server') {
			if (isset($this->gBookingArray[$groupId][$fromDate])) {
				$errorFound = false;
				foreach($this->gBookingArray[$groupId][$fromDate] as $oRecord) {
					list($oClassName, $oTeacherId, $oPeriodSlotId, $oSubjectId, $oPeriod,$oStartTime,$oEndTime) = explode('#', $oRecord);
					if ($this->source == 'Adjustment' or $oPeriodSlotId != $periodSlotId or $subjectId != $oSubjectId) {
						if ($periodStartDateTime == $oStartTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime > $oStartTime and $periodStartDateTime < $oEndTime) {
							$errorFound = true;
						}
						elseif ($periodStartDateTime < $oStartTime and $periodEndDateTime > $oStartTime) {
							$errorFound = true;
						}
						if ($errorFound === true) {
							$this->addError('ErrorGSS','Group-Subject-Timing', array('teacher1'=>$teacherId, 'teacher2'=>$oTeacherId, 'class1'=>$className, 'class2' => $oClassName, 'subject1'=>$subjectId, 'subject2'=>$oSubjectId, 'groupId'=>$groupId, 'source1' => $this->source, 'source2' => $processFrom, 'fromDate'=>$fromDate, 'period1'=>$period .' ('.$this->getPeriodTime($periodSlotId, $period).')', 'period2'=>$oPeriod.' ('.$this->getPeriodTime($oPeriodSlotId, $oPeriod).')'));
							return false;
						}
					}
				}
			}
		}
		else if ($checkSide == 'adjustment') {
			if (!is_array($this->gsAllocationArray[$groupId][$fromDate . '#' . $period])) {
				$this->gsAllocationArray[$groupId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($subjectId, $this->gsAllocationArray[$groupId][$fromDate . '#' . $period])) {
				if (count($this->gsAllocationArray[$groupId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorGSA','Group-Subject-Adjustment', array('subject1'=>$this->gsAllocationArray[$groupId][$fromDate . '#' . $period][0], 'fromDate'=>$fromDate, 'period'=>$period, 'subject2'=>$subjectId, 'groupId'=>$groupId, 'fromDate'=>$fromDate, 'toDate'=>$toDate, 'source1' => $this->source, 'source2' => $this->gsAllocationSourceArray[$groupId][$fromDate . '#' . $period][0]));
					return false;
				}
				else {
					$this->gsAllocationArray[$groupId][$fromDate . '#' . $period][] = $subjectId;
					$this->gsAllocationSourceArray[$groupId][$fromDate . '#' . $period][] = $processFrom;
					$this->gBookingArray[$groupId][$fromDate][] = $periodSlotId . '#'. $subjectId . '#' . $period . '#' . $periodStartDateTime . '#' . $periodEndDateTime;
				}
			}
		}
		return true;
	}

	//This function is used for checking Group Room Conflicts.
	private function checkGREntry($teacherId,$subjectId,$groupId,$roomId,$dayId,$period, $checkSide, $periodSlotId,$fromDate = '',$toDate = '', $processFrom = '') {
		if ($checkSide == 'client') {
			if (!is_array($this->grAllocationArray[$groupId][$fromDate . '#' . $period])) {
				$this->grAllocationArray[$groupId][$fromDate . '#' . $period] = array();
			}
			if (!in_array($roomId, $this->grAllocationArray[$groupId][$fromDate . '#' . $period])) {
				if (count($this->grAllocationArray[$groupId][$fromDate . '#' . $period]) > 0) {
					$this->addError('ErrorGRC','Group-Room', array('source1'=>$this->source, 'groupId'=>$groupId, 'source2'=>$processFrom,'fromDate'=>$fromDate, 'period'=>$period.' ('.$this->getPeriodTime($periodSlotId, $period).')', 'room2'=>$this->grAllocationArray[$groupId][$fromDate . '#' . $period][0], 'room1'=>$roomId));
					return false;
				}
				else {
					$this->grAllocationArray[$groupId][$fromDate . '#' . $period][] = $roomId;
				}
			}
		}
		return true;
	}

	private function addError($errorType, $message, $paramArray) {
		$message .= ' clash found <br>';
		$teacherName = $this->getParamValue('Teacher', 'teacherNameArray', 'teacherId', $paramArray);
		$teacher1 = $this->getParamValue('Teacher', 'teacherNameArray', 'teacher1', $paramArray);
		$teacher2 = $this->getParamValue('Teacher', 'teacherNameArray', 'teacher2', $paramArray);
		$dayName = $this->getParamValue('Day', 'dayNameArray', 'dayId', $paramArray);
		$subject1 = $this->getParamValue('Subject', 'subjectCodeArray', 'subject1', $paramArray);
		$subject2 = $this->getParamValue('Subject', 'subjectCodeArray', 'subject2', $paramArray);
		$group = $this->getParamValue('Group', 'groupShortArray', 'groupId', $paramArray);
		$group1 = $this->getParamValue('Group', 'groupShortArray', 'group1', $paramArray);
		$group2 = $this->getParamValue('Group', 'groupShortArray', 'group2', $paramArray);
		$period = $this->getParamValue('Period', '', 'period', $paramArray);
		$period1 = $this->getParamValue('Period', '', 'period1', $paramArray);
		$period2 = $this->getParamValue('Period', '', 'period2', $paramArray);
		$class1 = $this->getParamValue('Class', '', 'class1', $paramArray);
		$class2 = $this->getParamValue('Class', '', 'class2', $paramArray);
		$source1 = $this->getParamValue('From', '', 'source1', $paramArray);
		$source2 = $this->getParamValue('From', '', 'source2', $paramArray);
		$room = $this->getParamValue('Room', 'roomAbbreviationArray', 'roomId', $paramArray);
		$room1 = $this->getParamValue('Room', 'roomAbbreviationArray', 'room1', $paramArray);
		$room2 = $this->getParamValue('Room', 'roomAbbreviationArray', 'room2', $paramArray);

		$institute1 = $this->getParamValue('Institute', 'instituteCodeArray', 'institute1', $paramArray);
		$institute2 = $this->getParamValue('Institute', 'instituteCodeArray', 'institute2', $paramArray);
		$fromDate = $this->getParamValue('fromDate', 'fromDate', 'fromDate', $paramArray);
		$fromDate = date('d-M-Y',strtotime($fromDate));
		$str = ++$this->errorCounter .'. ' . $this->hightlightString($message);

		if ($errorType == 'ErrorTSC') {
			$str .= $this->getErrorTable(array('Conflict','Subject','Teacher', 'Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$subject1, $teacherName,$fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$subject2, $teacherName,$fromDate, $period,$source2));
		}
		elseif ($errorType == 'ErrorTSS') {
			$str .= $this->getErrorTable(array('Conflict','Teacher', 'Subject','Class', 'Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$teacherName, $subject2,$class2, $fromDate, $period2,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$teacherName, $subject1,$class1, $fromDate, $period1,$source2));
		}
		elseif ($errorType == 'ErrorTSA') {
			$str .= $this->getErrorTable(array('Conflict','Subject','Teacher', 'Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$subject1, $teacherName,$fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$subject2, $teacherName,'for all '.$fromDate.'s between <br>'.date('d-M-Y',strtotime($fromDate)).' and '.date('d-M-Y',strtotime($toDate)), $period,$source2));
		}
		elseif ($errorType == 'ErrorTRC') {
			$str .= $this->getErrorTable(array('Conflict','Room','Teacher', 'Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$room1, $teacherName,$fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$room2, $teacherName,$fromDate, $period,$source2));
		}
		elseif ($errorType == 'ErrorTDI') {
			$str .= $this->getErrorTable(array('Conflict','Teacher', 'Date', 'Period','Institute', 'From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$teacherName,$fromDate, $period1, $institute1,$source1));
			$str .= $this->getErrorRow(array($this->clashing(), $teacherName,$fromDate, $period2, $institute2,$source2));
		}
		elseif ($errorType == 'ErrorRSC') {
			$str .= $this->getErrorTable(array('Conflict','Subject','Room','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$subject1, $room,$fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$subject2, $room,$fromDate, $period,$source2));
		}
		elseif ($errorType == 'ErrorRSS') {
			$str .= $this->getErrorTable(array('Conflict','Teacher','Subject','Room','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$teacher2, $subject2,$room, $fromDate, $period2,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$teacher1, $subject1,$room, $fromDate, $period1,$source2));
		}
		elseif ($errorType == 'ErrorRSA') {
			$str .= $this->getErrorTable(array('Conflict','Subject','Room','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$subject1, $room,$fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$subject2, $room,$room, 'for all '.$dayName.'s between <br>'.date('d-M-Y',strtotime($fromDate)).' and '.date('d-M-Y',strtotime($toDate)), $period,$source2));
		}
		elseif ($errorType == 'ErrorRDI') {
			$str .= $this->getErrorTable(array('Conflict','Room', 'Date', 'Period','Institute', 'From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$room1,$fromDate, $period1, $institute1,$source1));
			$str .= $this->getErrorRow(array($this->clashing(), $room2,$fromDate, $period2, $institute2,$source2));
		}
		elseif ($errorType == 'ErrorGSC') {
			$str .= $this->getErrorTable(array('Conflict','Group','Subject','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$group, $subject1, $fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$group, $subject2, $fromDate, $period,$source2));
		}
		elseif ($errorType == 'ErrorGSS') {

			$str .= $this->getErrorTable(array('Conflict','Teacher','Subject','Group','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$teacher2, $subject2,$group, $fromDate, $period2,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$teacher1, $subject1,$group, $fromDate, $period1,$source2));
		}
		elseif ($errorType == 'ErrorGSA') {
			$str .= $this->getErrorTable(array('Conflict','Group','Subject','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$group, $subject1,$dayName, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$group, $subject2,$group, 'for all '.$fromDate.'s between <br>'.date('d-M-Y',strtotime($fromDate)).' and '.date('d-M-Y',strtotime($toDate)), $period,$source2));
		}
		elseif ($errorType == 'ErrorGRC') {
			$str .= $this->getErrorTable(array('Conflict','Room', 'Group','Date', 'Period','From'));
			$str .= $this->getErrorRow(array($this->newAllocation(),$room1, $group, $fromDate, $period,$source1));
			$str .= $this->getErrorRow(array($this->clashing(),$room2, $group, $fromDate, $period,$source2));
		}

		$str .= $this->getErrorTableEnd();
		$str .= $this->addMessageSeparator();
		$this->errorMessageArray[] = $str;
	}

	private function getErrorTable($rowArray = array()) {
		$totalCols = count($rowArray);
		$widthPerCol = intval(100 / $totalCols);
		$str = "<table border='1' align='center' width='98%' rules='all' style='border-collapse:collapse;' bordercolor='#000'><tr>";
		$col = 1;
		foreach($rowArray as $tHead) {
			$align="align = 'left'";
			if ($tHead == "Date") {
				$align="align = 'center'";
			}
			$str .= "<th $align width='".$widthPerCol."%'>".$tHead."</th>";
			$col++;
			if ($col == $totalCols) {
				break;
			}
		}
		$str .= "</tr>";
		return $str;
	}

	
	private function getErrorRow($rowArray = array()) {
		$str .= "<tr>";
		$totalCols = count($rowArray);
		$col = 1;
		foreach($rowArray as $td) {
			$align="align = 'left'";
			foreach($this->monArray2 as $monName) {
				if (stristr($td, $monName) != false) {
					$align="align = 'center'";
				}
			}
			$str .= "<td $align nowrap>".$td."</td>";
			$col++;
			if ($col == $totalCols) {
				break;
			}
		}
		$str .= "</tr>";
		return $str;
	}

	private function getErrorTableEnd() {
		return '</table>';
	}

	private function newAllocation() {
		return '<font color="red">NEW ALLOCATION:</font>';
	}

	private function clashing() {
		return '<font color="red"> IS CLASHING WITH:</font>';
	}

	private function addMessageSeparator() {
		return "<br><hr size='1' color='#000'/>";
	}

	private function getParamValue($returnPart, $memberArray, $key, $paramArray) {
		$str = '';//'<b>'.$returnPart.':</b> ';
		global $sessionHandler;
		if ($memberArray == 'teacherNameArray') {
			$str .= $this->teacherNameArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'dayNameArray') {
			$str .= $this->dayNameArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'subjectCodeArray') {
			$str .= $this->subjectCodeArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'roomAbbreviationArray') {
			$str .= $this->roomAbbreviationArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'groupShortArray') {
			$str .= $this->groupShortArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'instituteCodeArray') {
			$instituteCodeArray = $sessionHandler->getSessionVariable('InstituteCodeArray');
			$str .= $instituteCodeArray[$paramArray[$key]];
		}
		elseif ($memberArray == 'fromDate') {
			$str .= $paramArray[$key];
		}
		else {
			if (array_key_exists($key, $paramArray)) {
				$str .= $paramArray[$key];
			}
		}
		return $str.' ';
	}


	public function showError() {
		if (count($this->errorMessageArray)) {
			$message = '<b><u>Following '.count($this->errorMessageArray).' clashing entries have been found: </u></b><br><br>';//$this->hightlightString($this->errorMessageString);
			$message .= implode("", $this->errorMessageArray);
		}
		elseif (count($this->missingEntriesArray)) {
			$message = '';//$this->hightlightString($this->errorMessageString);
			$message .= implode("<br>", $this->missingEntriesArray);
		}
		return $message;
	}

	private function hightlightString($message) {
		return '<b><u>'.$message.'</u></b><br>';
	}

	private function getPeriodTime($periodSlotId, $period) {
		return date('h:i', strtotime($this->newPeriodArray[$periodSlotId][$period]['startTime'])).' '.$this->newPeriodArray[$periodSlotId][$period]['startAmPm'] . ' to ' .	date('h:i', strtotime($this->newPeriodArray[$periodSlotId][$period]['endTime'])).' '.$this->newPeriodArray[$periodSlotId][$period]['endAmPm'];
	}
}


?>