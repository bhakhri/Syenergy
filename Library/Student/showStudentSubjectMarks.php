<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UpdateTotalMarks');
	define('ACCESS','edit');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$studentId = $REQUEST_DATA['studentId'];
	$classId = $REQUEST_DATA['classId'];
	$subjectId = $REQUEST_DATA['subjectId'];

	/*
    $countTestMarksArray = $studentManager->checkTestTransferredMarks($studentId,$classId,$subjectId);
	$count = $countTestMarksArray[0]['cnt'];
	if ($count <= 0) {
    	echo OLD_DATA;
		die;
	}
    */
    
	$countTestMarksArray = $studentManager->checkTestUpdatedMarks($studentId,$classId,$subjectId);
	$count = $countTestMarksArray[0]['cnt'];
	if ($count > 0) {
		echo REAPPEAR_EXAM_TAKEN;
		die;
	}


	$resultArray = $studentManager->getStudentSubjectMarks($studentId,$classId,$subjectId);
	$tabsArray = array();
?>
<form name="marksUpdationForm" action="" method="post" onSubmit="return false;">
	<input type='hidden' name='classId' value='<?php echo $classId;?>' />
	<input type='hidden' name='subjectId' value='<?php echo $subjectId;?>' />
	<input type='hidden' name='studentId' value='<?php echo $studentId;?>' />
	<table border='0' cellspacing='0' cellpadding='0' class="border" height='400' width='650'>
		<tr>
			<td valign='top' colspan='1' class=''>
				<table border='0' cellspacing='0' cellpadding='0' >
					<tr>
						<td valign='top' colspan='1' class='' style='padding-left:10px;'>
							<table border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td valign='' colspan='1' class="contenttab_internal_rows"><strong>Student Name : </strong><?php echo $REQUEST_DATA['studentName'];?><strong>&nbsp;&nbsp;Degree : </strong></td>
									<td valign='' class=''><input type='hidden' name='degreeName' value='<?php echo $resultArray[0]['className'];?>' readonly class='htmlElement' size='40'/><span><?php echo $resultArray[0]['className'];?></span></td>
									<td valign='' colspan='1' class='contenttab_internal_rows'>&nbsp;&nbsp;<strong>Subject : </strong></td>
									<td valign='' class=''><input type='hidden' class='htmlElement' value='<?php echo $resultArray[0]['subjectCode'];?>' readonly name='subjectCode' value='' size='40'/><span><?php echo $resultArray[0]['subjectCode'];?></span></td>
								</tr>
							</table>
						</td>
					</tr>
	<tr>
	<td valign='top' colspan='1'  style='padding-left:10px;'>
	<div id="dhtmlgoodies_tabView1">
		<?php
			$tabsArray[] = 'Attendance';
			$class = $class=='class="row0"' ? 'class="row1"' : 'class="row0"';
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			$htmlFunctions = HtmlFunctions::getInstance();
			require_once(MODEL_PATH . "/StudentManager.inc.php");
			$studentManager = StudentManager::getInstance();
			$studentAttendanceArray = $studentManager->getStudentSubjectAttendance($studentId,$subjectId,$classId);

		?>
	<div class="dhtmlgoodies_aTab" style="height:170px;overflow:auto;">
	<div style="height:170px;overflow:auto;">
		<table border='0' cellspacing='1' cellpadding='0' width='100%'>
			<tr class='highlightPermissionBlue' height='25'>
				<td colspan='1' class=''>Type</td>
				<td  colspan='1' class=''>Date</td>
				<td  colspan='1' class=''>Teacher</td>
				<td  colspan='1' class=''>Lecture Del.</td>
				<td  colspan='1' class=''>Lect. Att. (Old)</td>
				<td colspan='1' class=''>Lect. Att. (New)</td>
				<td colspan='1' class=''>Duty Leave</td>
			</tr>
		<?php
			$totalLecturesDelivered = 0;
			$totalLecturesAttended = 0;
			foreach($studentAttendanceArray as $studentAttendanceRecord) {
						$class = $class=='class="row0"' ? 'class="row1"' : 'class="row0"';
						$attendanceId = $studentAttendanceRecord['attendanceId'];
						$employeeId = $studentAttendanceRecord['employeeId'];
						$employeeName = $studentAttendanceRecord['employeeName'];
						$attendanceType = $studentAttendanceRecord['attendanceType'];
						$periodId = $studentAttendanceRecord['periodId'];
						$attendanceDate = $studentAttendanceRecord['attendanceDate'];
						$toDate = $studentAttendanceRecord['toDate'];
						$lectureDelivered = $studentAttendanceRecord['lectureDelivered'];
						$lectureAttended = $studentAttendanceRecord['lectureAttended'];
						$attendanceCode = $studentAttendanceRecord['attendanceCode'];
						$dutyLeave = $studentAttendanceRecord['dutyLeave'];
						$totalLecturesDelivered += $lectureDelivered;
						$totalLecturesAttended += $lectureAttended;
												$showOldLectureAttended = '';
												if ($attendanceType == 'Daily') {
													$showDate = UtilityManager::formatDate($attendanceDate);
													$showOldLectureAttended = $attendanceCode;
												}
												else {
													$showDate = UtilityManager::formatDate($attendanceDate);
													$showDate .= '<br>To<br>';
													$showDate .= UtilityManager::formatDate($toDate);
													$showOldLectureAttended = $lectureAttended;
												}
											?>
												<tr <?php echo $class;?> style='height:30px;'>
													<td  colspan='1' class=''>
														<?php echo $attendanceType;?>
													</td>
													<td  colspan='1' class=''>
														<?php echo $showDate;?>
													</td>
													<td  colspan='1' class=''>
														<?php echo $employeeName;?>
													</td>
													<td  colspan='1' class=''>
														<?php echo $lectureDelivered;?>
													</td>
													<td  colspan='1' class=''>
														<?php echo $showOldLectureAttended;?>
													</td>
													<td  colspan='1' class=''>
													<?php
														if ($attendanceType == 'Daily') {
													?>
													<select size="1" class="inputbox" name='att_<?php echo $attendanceId;?>' style='width:100px;'>
													<?php
														echo $htmlFunctions->getAttendanceCodeData('',$attendanceCode);
													?>
													</select>
													<?php
														}
														else {
													?>
														<input type='text' class='htmlElement' name='att_<?php echo $attendanceId;?>' value='<?php echo round($lectureAttended,2);?>' size='8' />
													<?php
														}
													?>
													</td>
													<td  colspan='1' class=''>
													<?php
														if ($dutyLeave == 'Daily' or $dutyLeave == 'Bulk') {
															echo NOT_APPLICABLE_STRING;
														}
														else {
															list($dutyLeaveId,$dutyLeaveStatus) = explode('#',$dutyLeave);
													?>
														<select size="1" class="inputbox" name='dutyLeave_<?php echo $dutyLeaveId;?>' style='width:100px;'>
													<?php
															echo $htmlFunctions->makeDutyLeaveSelect($dutyLeaveStatus);
														}
													?>
														</select>
													</td>
												</tr>
											<?php
											}
											?>

										</table>
									</div>
								</div>

							<?php
								$tabsArray[] = 'PreCompre Marks';
								$studentTestMarksArray = $studentManager->getStudentSubjectTests($studentId,$subjectId,$classId);
							?>
				<div class="dhtmlgoodies_aTab">
				<div id="preCompreTab" style="height:170px;overflow:auto;display:none;">
					<table border='0' cellspacing='1' cellpadding='0' width='100%'>
						<tr class='highlightPermissionBlue' height='25'>
						<td  colspan='1' class=''>Test</td>
						<td colspan='1' class=''>Date</td>
						<td colspan='1' class=''>Teacher</td>
						<td  colspan='1' class=''>Max Marks</td>
						<td colspan='1' class=''>Marks Scored</td>
					</tr>
				<?php
				foreach($studentTestMarksArray as $studentTestMarksRecord) {
				$class = $class=='class="row0"' ? 'class="row1"' : 'class="row0"';
				$testId = $studentTestMarksRecord['testId'];
				$testTypeCategoryName = $studentTestMarksRecord['testTypeName'];
				$testTypeCategoryId = $studentTestMarksRecord['testTypeCategoryId'];
				$testDate = $studentTestMarksRecord['testDate'];
				$employeeId = $studentTestMarksRecord['employeeId'];
				$employeeName = $studentTestMarksRecord['employeeName'];
				$maxMarks = $studentTestMarksRecord['maxMarks'];
				$marksScored = $studentTestMarksRecord['marksScored'];
			?>
	<tr <?php echo $class;?>>
		<td  colspan='1' class=''>
	<?php echo $testTypeCategoryName;?>
		</td>
	<td  colspan='1' class=''>
	<?php echo $testDate;?>
												</td>
												<td  colspan='1' class=''>
													<?php echo $employeeName;?>
												</td>
												<td  colspan='1' class=''>
													<?php echo $maxMarks;?>
												</td>
												<td  colspan='1' class=''>
													<input class='htmlElement' type='text' name='test_<?php echo $testId;?>' value='<?php echo $marksScored;?>' size='8'/>
												</td>
											</tr>
											<?php
											}
											?>
										</table>
									</div>
								</div>
							<?php
								$tabsArray[] = 'Compre Marks';
								$studentCompreTestMarksArray = $studentManager->getStudentTestCompreMarks($studentId,$subjectId,$classId);
							?>
				<div class="dhtmlgoodies_aTab">
				<div id="compreTab" style="height:200px;overflow:auto;display:none;">
		<table border='0' cellspacing='2' cellpadding='0' width='100%'>
					<tr class='highlightPermissionBlue' height='25'>
						<td colspan='1'> </td>
						<td  colspan='1' class=''>Max Marks</td>
						<td colspan='1' class=''>Marks Scored</td>
					</tr>
		<tr height='5'>
			<td  colspan='1' class=''>

		</td>
			</tr>
			<?php
						$class = 'class="row0"';
						foreach($studentCompreTestMarksArray as $studentCompreTestMarksRecord) {
							$class = $class=='class="row0"' ? 'class="row1"' : 'class="row0"';
							$testTypeId = $studentCompreTestMarksRecord['testTypeId'];
							$testName = $studentCompreTestMarksRecord['testTypeName'];
							$maxMarks = $studentCompreTestMarksRecord['maxMarks'];
							$marksScored = $studentCompreTestMarksRecord['marksScored'];

			?>
					<tr <?php echo $class;?>>
			<td valign='top' colspan='1' class=''>
	<input type='hidden' name='testName_<?php echo $testTypeId;?>' value='<?php echo $testName;?>' size='8'/>
				<span>&nbsp;<?php echo $testName;?></span>
		</td>
			<td valign='top' colspan='1' class=''>
	<input type='hidden' name='compreMarksTotal_<?php echo $testTypeId;?>' value='<?php echo $maxMarks;?>' size='8'/>
				<span>&nbsp;<?php echo $maxMarks;?></span>
		</td>
			<td valign='top' colspan='1' class=''>
	<input  class='htmlElement' type='text' name='compreMarks_<?php echo $testTypeId;?>' value='<?php echo $marksScored;?>' size='8'/>
												</td>
											</tr>
											<?php
											}
											?>
										</table>
									</div>
								</div>


							<?php
								$tabsArray[] = 'Grade';
								$gradeSetArray = $studentManager->getGradeSet($subjectId,$classId);
								$totalGradeSets = count($gradeSetArray);
								if ($totalGradeSets > 1) {
									echo MORE_THAN_GRADE_SET_APPLIED;
									die;
								}
								$gradeSetId = $gradeSetArray[0]['gradeSetId'];
								$studentGradeArray = $studentManager->getStudentGrade($studentId,$subjectId,$classId);
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								$htmlFunctions = HtmlFunctions::getInstance();
							?>
								<div class="dhtmlgoodies_aTab">
									<div id="gradeTab" style="height:200px;overflow:auto;display:none;">
										<table border='0' cellspacing='0' cellpadding='0' width='100%'>
											<?php
											foreach($studentGradeArray as $studentGradeRecord) {
												$gradeId = $studentGradeRecord['gradeId'];
												$gradeLabel = $studentGradeRecord['gradeLabel'];
											?>
					<tr>
					<td valign='top' colspan='1' class='' align='right'>
					Grade:&nbsp;
						</td>
					<td valign='top' colspan='1' class=''>
						<select name='grade' class='htmlElement' style='width:50px;'>
							<option value = '0'>I</option>
						<?php echo $htmlFunctions->makeGradeSelect($gradeSetId, $gradeId); ?>
													</select>
												</td>
											</tr>
											<?php
											}
											?>
										</table>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign='top' colspan='1' class='' width='600' style='padding-left:10px;'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td height="10px"><input type='button' name='' value='Preview New Marks' onClick='checkNewMarks();' /></td>
					</tr>
				</table>
				<table border='0' cellspacing='1' cellpadding='0' width='600' style='border:1px solid #CCCCCC;'>
					<tr  class="rowheading">
						<td  colspan='1' class='contenttab_internal_rows' width='20%'>&nbsp;<B>Component</B></td>
						<td  colspan='1' class='contenttab_internal_rows' width='20%'>&nbsp;<B>Status</B></td>
						<td colspan='1' class='contenttab_internal_rows' width='20%'>&nbsp;<B>Marks Scored</B></td>
						<td  colspan='1' class='contenttab_internal_rows' width='40%'>&nbsp;<B>Max. Marks</B></td>
					</tr>
					<tr>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Attendance : </strong></td>
						<td valign='' colspan='1'  class=''>
						<select name='attendance' class='htmlElement' onChange='checkAttendance();'>
						<option value='Marks'>Marks</option>
						</select>
						</td>
						<td>
						<input type='text' name='attendanceMarksNew' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
						</td>

						<td valign='' colspan='1' class=''>
						<input type='text' name='attendanceMarksTotal' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
						</td>
					</tr>
					<tr>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Pre Compre : </strong></td>
						<td valign='' colspan='1' class=''>
						<select name='preCompre' class='htmlElement'>
						<?php echo $htmlFunctions->makeMarksSelect(); ?>
						</select>
						</td>
						<td>
						<input type='text' name='preCompreMarksNew' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
						</td>

						<td valign='' colspan='1' class=''>
						<input type='text' name='preCompreMarksTotal' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
						</td>
					</tr>
					<tr>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Compre : </strong></td>
						<td valign='' colspan='1' class=''>
						<select name='compre' class='htmlElement'>
						<?php echo $htmlFunctions->makeMarksSelect(); ?>
						</select>
						</td>
						<td>
						<input type='text' name='compreMarksNewCal' class='htmlElement' style='text-align:right' size='4' readonly/>
						</td>

						<td valign='' colspan='1' class=''>
						<input type='text' name='compreMarksTotalCal' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
					</tr>
					<tr>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Total : </strong></td>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong></strong></td>
						<td valign='' colspan='1' >
						<input type='text' name='sumTotalMarksScored' class='htmlElement' style='text-align:right;' size='4' value='' readonly/>
						</td>
						<td valign='' colspan='1' >
						<input type='text' name='sumTotalMaxMarks' class='htmlElement' style='text-align:right' size='4' value='' readonly/>
						</td>
					</tr>
					<tr>
						<td height="10px" colspan='4'><hr size='1'></td>
					</tr>
					<tr style="display:none">
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Grade : </strong></td>
						<td valign='' colspan='3' class=''>
						<select name='gradeNew' class='htmlElement' style='width:50px;' value= 'readonly'>
						<option value = '0'>I</option>
						<?php echo $htmlFunctions->makeGradeSelect(); ?>
						</select>
						</td>
					</tr>
					<tr>
						<td valign='' colspan='1' class='contenttab_internal_rows'><strong>Reason : </strong></td>
						<td valign='' colspan='3' class=''>
						<textarea name="reason" class='selectfield'></textarea>
						&nbsp;&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateMarksUpdationForm();return false;" />
						<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"
						onclick="javascript:hiddenFloatingDiv('updateMarks');return false;" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php
$stringA = "<!-- initTabs('dhtmlgoodies_tabView1',Array(";
$stringB = "),0,600,200,Array(";
$stringC = "))-->";

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

echo $string = $stringA . $string1 . $stringB . $string2 . $stringC;
?>

<?php
	//echo json_encode($resultArray);

// $History: scShowStudentSubjectMarks.php $
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 3/10/10    Time: 7:09p
//Updated in $/Leap/Source/Library/ScStudent
//fixed query errors.
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 2/02/10    Time: 3:38p
//Updated in $/Leap/Source/Library/ScStudent
//fixed issue of test name not coming.
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 1/12/10    Time: 11:20a
//Updated in $/Leap/Source/Library/ScStudent
//fixed issues: 2576, 2577, 2578
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 1/11/10    Time: 4:10p
//Updated in $/Leap/Source/Library/ScStudent
//fixed bug no.2559
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 9/09/09    Time: 5:40p
//Updated in $/Leap/Source/Library/ScStudent
//improved design, corrected bug in calculation
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 9/01/09    Time: 5:19p
//Updated in $/Leap/Source/Library/ScStudent
//added student name,
//corrected attendance bug
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/31/09    Time: 12:28p
//Updated in $/Leap/Source/Library/ScStudent
//added code for subjective-objective marks.
//added defines.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 5/29/09    Time: 4:12p
//Updated in $/Leap/Source/Library/ScStudent
//added code to take care of daily attendance.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 3/19/09    Time: 2:33p
//Updated in $/Leap/Source/Library/ScStudent
//done the changes for fixing bugs.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/02/09    Time: 4:22p
//Updated in $/Leap/Source/Library/ScStudent
//modified to make it working for last level.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/14/09    Time: 1:11p
//Updated in $/Leap/Source/Library/ScStudent
//applied access rights
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 12:35p
//Updated in $/Leap/Source/Library/ScStudent
//fixed bug found during self testing.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/04/08   Time: 4:37p
//Created in $/Leap/Source/Library/ScStudent
//file made for marks updation for single student
//


?>
