<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Ajinder Singh
// Created on : 07-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
define('MODULE','UpdateStudentGroups');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$studentManager = StudentManager::getInstance();
$rollNo = $REQUEST_DATA['rollNo'];


$themePath=trim($REQUEST_DATA['themePath']);
if($themePath==''){
    $themePath=IMG_HTTP_PATH;
}

$studentDetailArray = $studentManager->getStudentDetail($rollNo);
if (!isset($studentDetailArray[0]['studentId']) or empty($studentDetailArray[0]['studentId'])) {
	echo INVALID_ROLL_NO;
	die;
}
$studentId = $studentDetailArray[0]['studentId'];
$classId = $studentDetailArray[0]['classId'];
$subjectsArray = $studentManager->getClassSubjects($classId);

$classThGroups = $studentManager->getClassGroupTypeGroups($classId,3);//Theory Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$thGroupId = 0;
foreach($classThGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$thGroupId = $classRecord['groupId'];
		$thGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}

$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,3);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo THEORY_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}
/*
if ($noGroupSelected == true) {
	echo NO_THEORY_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_THEORY_GROUP_SELECTED;
	die;
}
*/
$classTutGroups = $studentManager->getClassGroupTypeGroups($classId,1);//Tut Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$tutGroupId = 0;
foreach($classTutGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$tutGroupId = $classRecord['groupId'];
		$tutGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}

$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,1);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo TUTORIAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}

/*
if ($noGroupSelected == true) {
	echo NO_TUTORIAL_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_TUTORIAL_GROUP_SELECTED;
	die;
}
*/
$classPrGroups = $studentManager->getClassGroupTypeGroups($classId,2);//Pr Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$prGroupId = 0;
foreach($classPrGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$prGroupId = $classRecord['groupId'];
		$prGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}
/*
if ($noGroupSelected == true) {
	echo NO_PRACTICAL_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_PRACTICAL_GROUP_SELECTED;
	die;
}
*/
$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,2);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo PRACTICAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}



$studentThGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 3)");
$studentThGroupId = $studentThGroups[0]['groupId'];
$studentThGroupShort = $studentThGroups[0]['groupShort'];

$studentTutGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 1)");
$studentTutGroupId = $studentTutGroups[0]['groupId'];
$studentTutGroupShort = $studentTutGroups[0]['groupShort'];

$studentPrGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 2)");
$studentPrGroupId = $studentPrGroups[0]['groupId'];
$studentPrGroupShort = $studentPrGroups[0]['groupShort'];

$tabsArray = array();
$internalTabs = array();
?>
<table border='0' cellspacing='0' cellpadding='0' height='450'>
	<tr>
		<td valign='top' colspan='1' class='' style="padding-left:10px;height:400px;">
			<div id="subjectTabs">
			<?php
			foreach($subjectsArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				$subjectCode = $subjectRecord['subjectCode'];
				$tabsArray[] = $subjectCode;
				$internalTabs[] = $subjectCode;
				?>
				<div class="dhtmlgoodies_aTab">
					<div id="<?php echo $subjectCode;?>">
						<div class="dhtmlgoodies_aTab">
							<div style='height:300px;overflow:auto;'>
								<table border='0' cellspacing='2' cellpadding='0' align='center' width='100%'>
									<tr>
										<td valign='top' colspan='1' class=''>
											<table border='1' cellspacing='2' cellpadding='2' width='100%' rules='all' bordercolor='#ffffff'>
												<tr class='highlightPermission'>
													<td valign='top' colspan='1' class='' width='40%'>&nbsp;<B>Group</B></td>
													<td valign='top' colspan='1' class='' width='30%'>&nbsp;<B>Lectures Delivered</B></td>
													<td valign='top' colspan='1' class='' width='30%'>&nbsp;<B>Lectures Attended</B></td>
												</tr>
												<tr>
													<td valign='top' colspan='3' class='' height='10'></td>
												</tr>
												<?php
												if ($studentThGroupId != $thGroupId) {
													$oldAttendanceArray = $studentManager->getStudentOldAttendance($studentId, $classId, $subjectId, $studentThGroupId);
													$oldLectureDelivered = $oldAttendanceArray[0]['lectureDelivered'];
													$oldLectureAttended = $oldAttendanceArray[0]['lectureAttended'];
													$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$thGroupId);
													$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
												?>
												<tr class='highlightPermission'>
													<td valign='top' colspan='3' class='' height='10'>&nbsp;<B>Theory</B></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $studentThGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureAttended;?></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $thGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $newLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><input type='text' size='6' class='inputbox' style="width:50px;"  name='att_<?php echo $thGroupId.'_'.$subjectId;?>' value='' /></td>
												</tr>
												<?php
												}
												if ($studentTutGroupId != $tutGroupId) {
													$oldAttendanceArray = $studentManager->getStudentOldAttendance($studentId, $classId, $subjectId, $studentTutGroupId);
													$oldLectureDelivered = $oldAttendanceArray[0]['lectureDelivered'];
													$oldLectureAttended = $oldAttendanceArray[0]['lectureAttended'];
													$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$tutGroupId);
													$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
												?>
												<tr class='highlightPermission'>
													<td valign='top' colspan='3' class='' height='10'>&nbsp;<B>Tutorial</B></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $studentTutGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureAttended;?></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $tutGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $newLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><input type='text' size='6' class='inputbox' style="width:50px;"  name='att_<?php echo $tutGroupId.'_'.$subjectId;?>' value='' /></td>
												</tr>
												<?php
												}
												if ($studentPrGroupId != $prGroupId) {
													$oldAttendanceArray = $studentManager->getStudentOldAttendance($studentId, $classId, $subjectId, $studentPrGroupId);
													$oldLectureDelivered = $oldAttendanceArray[0]['lectureDelivered'];
													$oldLectureAttended = $oldAttendanceArray[0]['lectureAttended'];
													$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$prGroupId);
													$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
												?>
												<tr class='highlightPermission'>
													<td valign='top' colspan='3' class='' height='10'>&nbsp;<B>Practical</B></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $studentPrGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureAttended;?></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $prGroupShort;?></td>
													<td valign='top' colspan='1' class=''><?php echo $newLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><input type='text' size='6' class='inputbox' style="width:50px;"  name='att_<?php echo $prGroupId.'_'.$subjectId;?>' value='' /></td>
												</tr>
												<?php
													}
												?>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="dhtmlgoodies_aTab">
							<div style='height:300px;overflow:auto;'>
								<table border='0' cellspacing='2' cellpadding='0' align='center' width='80%'>
									<tr>
										<td valign='top' colspan='1' class=''>
											<?php
											$counter = 0;
											$counter2 = 0;
											$showRow = "<td>Max Marks</td><td>Marks Scored</td>";
											if ($studentThGroupId != $thGroupId) {
											?>
											<table border='1' cellspacing='2' cellpadding='2' width='100%' rules='all' bordercolor='#ffffff'>
												<tr>
													<td valign='top' colspan='2' class='' height='10'></td>
												</tr>
												<tr class='highlightPermission'>
													<td valign='top' colspan='2' class='' height='10'>&nbsp;<B>Theory</B></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class='' width='50%'>
														<table border='0' cellspacing='2' cellpadding='0' width='100%'>
															<tr>
																<td valign='top' colspan='1' class=''>
																<B><?php echo $studentThGroupShort;?></B>
																</td>
																<?php
																	if ($counter == 0) {
																		echo $showRow;
																		$counter++;
																	}
																?>
															</tr>
															<?php
															$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentThGroupId);
															foreach($oldTestArray as $oldTestRecord) {
																$oldTestName = $oldTestRecord['testName'];
																$oldMaxMarks = $oldTestRecord['maxMarks'];
																$oldMarksScored = $oldTestRecord['marksScored'];
															?>
																<tr>
																	<td valign='top' colspan='1' class=''  width='50%'><?php echo $oldTestName;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMaxMarks;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMarksScored;?></td>
																</tr>
															<?php
															}
															?>
														</table>
													</td>
													<td valign='top' colspan='1' class='' width='50%'>
														<table border='0' cellspacing='2' cellpadding='0' width='100%'>
															<tr>
																<td valign='top' colspan='0' class=''>
																<B><?php echo $thGroupShort;?></B>
																</td>
																<?php
																	if ($counter2 == 0) {
																		echo $showRow;
																		$counter2++;
																	}
																?>
															</tr>
															<?php
															$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$thGroupId);
															foreach($newTestArray as $newTestRecord) {
																$newTestId = $newTestRecord['testId'];
																$newTestName = $newTestRecord['testName'];
																$newMaxMarks = $newTestRecord['maxMarks'];
															?>
																<tr>
																	<td valign='top' colspan='1' class='' width='50%'><?php echo $newTestName;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><?php echo $newMaxMarks;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><input type='text' size='6' class='inputbox' style="width:50px;"  name='test_<?php echo $newTestId.'_'.$subjectId;?>' value='' /></td>
																</tr>
															<?php
															}
															?>
														</table>
													</td>
												</tr>
											</table>
											<?php
											}
											if ($studentTutGroupId != $tutGroupId) {
											?>
												<table border='0' cellspacing='2' cellpadding='0' width='100%'>
													<tr class='highlightPermission'>
														<td valign='top' colspan='2' class='' height='10'>&nbsp;<B>Tutorial</B></td>
													</tr>
													<tr>
														<td valign='top' colspan='1' class='' width='50%'>
															<table border='0' cellspacing='2' cellpadding='0' width='100%'>
																<tr>
																	<td valign='top' colspan='1' class=''>
																	<B><?php echo $studentTutGroupShort;?></B>
																	</td>
																	<?php
																		if ($counter == 0) {
																			echo $showRow;
																			$counter++;
																		}
																	?>
																</tr>
																<?php
																$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentTutGroupId);
																foreach($oldTestArray as $oldTestRecord) {
																	$oldTestName = $oldTestRecord['testName'];
																	$oldMaxMarks = $oldTestRecord['maxMarks'];
																	$oldMarksScored = $oldTestRecord['marksScored'];
																?>
																	<tr>
																		<td valign='top' colspan='1' class='' width='50%'><?php echo $oldTestName;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMaxMarks;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMarksScored;?></td>
																	</tr>
																<?php
																}
																?>
															</table>
														</td>
														<td valign='top' colspan='1' class='' width='50%'>
															<table border='0' cellspacing='2' cellpadding='0' width='100%'>
																<tr>
																	<td valign='top' colspan='1' class=''>
																	<B><?php echo $tutGroupShort;?></B>
																	</td>
																<?php
																	if ($counter2 == 0) {
																		echo $showRow;
																		$counter2++;
																	}
																?>
																</tr>
																<?php
																$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$tutGroupId);
																foreach($newTestArray as $newTestRecord) {
																	$newTestId = $newTestRecord['testId'];
																	$newTestName = $newTestRecord['testName'];
																	$newMaxMarks = $newTestRecord['maxMarks'];
																?>
																	<tr>
																		<td valign='top' colspan='1' class='' width='50%'><?php echo $newTestName;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $newMaxMarks;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><input type='text' size='6' class='inputbox' style="width:50px;"  name='test_<?php echo $newTestId.'_'.$subjectId;?>' value='' /></td>
																	</tr>
																<?php
																}
																?>
															</table>
														</td>
													</tr>
												</table>
											<?php
											}
											if ($studentPrGroupId != $prGroupId) {
											?>
												<table border='0' cellspacing='2' cellpadding='0' width='100%'>
													<tr class='highlightPermission'>
														<td valign='top' colspan='2' class='' height='10'>&nbsp;<B>Practical</B></td>
													</tr>
													<tr>
														<td valign='top' colspan='1' class='' width='50%'>
															<table border='0' cellspacing='2' cellpadding='0' width='100%'>
																<tr>
																	<td valign='top' colspan='1' class=''>
																	<B><?php echo $studentPrGroupShort;?></B>
																	</td>
																	<?php
																		if ($counter == 0) {
																			echo $showRow;
																			$counter++;
																		}
																	?>
																</tr>
																<?php
																$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentPrGroupId);
																foreach($oldTestArray as $oldTestRecord) {
																	$oldTestName = $oldTestRecord['testName'];
																	$oldMaxMarks = $oldTestRecord['maxMarks'];
																	$oldMarksScored = $oldTestRecord['marksScored'];
																?>
																	<tr>
																		<td valign='top' colspan='1' class='' width='50%'><?php echo $oldTestName;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMaxMarks;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $oldMarksScored;?></td>
																	</tr>
																<?php
																}
																?>
															</table>
														</td>
														<td valign='top' colspan='1' class='' width='50%'>
															<table border='0' cellspacing='2' cellpadding='0' width='100%'>
																<tr>
																	<td valign='top' colspan='1' class=''>
																	<B><?php echo $prGroupShort;?></B>
																	</td>
																	<?php
																		if ($counter2 == 0) {
																			echo $showRow;
																			$counter2++;
																		}
																	?>
																</tr>
																<?php
																$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$prGroupId);
																foreach($newTestArray as $newTestRecord) {
																	$newTestId = $newTestRecord['testId'];
																	$newTestName = $newTestRecord['testName'];
																	$newMaxMarks = $newTestRecord['maxMarks'];
																?>
																	<tr>
																		<td valign='top' colspan='1' class='' width='50%'><?php echo $newTestName;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><?php echo $newMaxMarks;?></td>
																		<td valign='top' colspan='1' class='' width='25%'><input type='text' size='6' class='inputbox' style="width:50px;"  name='test_<?php echo $newTestId.'_'.$subjectId;?>' value='' /></td>
																	</tr>
																<?php
																}
																?>
															</table>
														</td>
													</tr>
												</table>
											<?php
											}
											?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			</div>
		</td>
	</tr>
	<tr>
		<td valign='top' colspan='1' class='' style="padding-left:10px;">
			<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo $themePath;?>/save.gif" onClick="saveData()" />
		</td>
	</tr>
</table>
<?php
$stringA = "<!-- initTabs('subjectTabs',Array(";
$stringB = "),0,720,350,Array(";
$stringC = "));";
$string1 = "";
$string2 = "";
foreach($tabsArray as $tabName) {
	if (!empty($string1)) {
		$string1 .= ",";
		$string2 .= ",";
	}
	$string1 .= "'$tabName'";
	$string2 .= "false";
}

$stringOuterTabs = $stringA . $string1 . $stringB . $string2 . $stringC;

foreach($internalTabs as $internalArray) {
	$stringA = "initTabs('".$internalArray."',Array(";
	$string1 = "'$internalArray Attendance','$internalArray Tests'";
	$string2 = "false,false";
	$stringB = "),0,700,300,Array(";
	$stringC = "));";
	$stringOuterTabs .= $stringA . $string1 . $stringB . $string2 . $stringC;
}

$stringOuterTabs .= " --> ";
echo $stringOuterTabs;

//$History: makeGroupTabs.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/15/09    Time: 3:41p
//Updated in $/LeapCC/Library/Student
//done changes to allow user to save data without selecting tutorial
//group/practical group
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:30a
//Updated in $/LeapCC/Library/Student
//fixed bug no.1204
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:38p
//Created in $/LeapCC/Library/Student
//file added for group change.
//






?>