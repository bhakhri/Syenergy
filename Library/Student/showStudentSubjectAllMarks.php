<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

	$countTestMarksArray = $studentManager->checkTestTransferredMarks($studentId,$classId,$subjectId);
	$count = $countTestMarksArray[0]['cnt'];
	/*
	if ($count < 3) {
		echo OLD_DATA;
		die;
	}
	*/

	#		000001


	$resultArray = $studentManager->getStudentSubjectMarks($studentId,$classId,$subjectId);
?>
<form name="marksReappearForm" action="" method="post" onSubmit="return false;">
	<input type='hidden' name='classId' value='<?php echo $classId;?>' />
	<input type='hidden' name='subjectId' value='<?php echo $subjectId;?>' />
	<input type='hidden' name='studentId' value='<?php echo $studentId;?>' />
	<table border='0' cellspacing='0' cellpadding='0' class="border" height='400' width='650'>
		<tr>
			<td valign='top' colspan='1' class=''>
				<table border='0' cellspacing='0' cellpadding='0' width="100%">
					<tr>
						<td valign='top' colspan='1' class='' style='padding-left:10px;'>
							<table border='0' cellspacing='2' cellpadding='0' width="100%">
								<tr>
									<td valign='' colspan='1' class="contenttab_internal_rows"><strong>Student Name : </strong><?php echo $REQUEST_DATA['studentName'];?></td><td align="right"><strong>Degree &nbsp;:&nbsp; </strong></td>
									<td valign='' class=''><input type='hidden' name='degreeName' value='<?php echo $resultArray[0]['className'];?>' readonly class='htmlElement' size='40'/><span><?php echo $resultArray[0]['className'];?></span></td>
									<td colspan='1' class='contenttab_internal_rows' style="text-align:right;"><strong>Subject&nbsp; : &nbsp;</strong></td>
									<td valign='' class=''><input type='hidden' class='htmlElement' value='<?php echo $resultArray[0]['subjectCode'];?>' readonly name='subjectCode' value='' size='40'/><span><?php echo $resultArray[0]['subjectCode'];?></span></td>
									<td valign="top" class="" align="right">
									<?php
										$reExamCountArray = $studentManager->countReExamMarks($classId, $studentId, $subjectId);
										$reExamCount = $reExamCountArray[0]['cnt'];
										if ($reExamCount > 0) {
									?>
										<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/finalize_marks.gif" onClick="return finalizeMarks();return false;" />
									<?php
										}
									?>

									</td>
								</tr>
								<tr style="display:none;">
									<td valign="top" colspan="6" class="">
									<hr size="1" color="#CC0000">
									</td>
								</tr>
								<tr>
									<td valign="top" colspan="6" class="">
										<table border="0" cellspacing="2" cellpadding="0" width="100%">

								<tr>
									<td valign="top" colspan="6" class="">
										<u><b>Previously Finalized Marks</b></u>
									</td>
								</tr>
								<tr class="rowheading" style="height:10px;">
									<td  colspan="1" class="searchhead_text">Type</td>
									<td  colspan="2" class="searchhead_text">Component</td>
									<td  colspan="1" class="searchhead_text">Marks Scored</td>
									<td  colspan="1" class="searchhead_text">Max Marks</td>
									<td  colspan="1" class="searchhead_text">Grade</td>
								</tr>
								<?php
									$activeMarksArray = $studentManager->getStudentActiveMarks($classId, $studentId, $subjectId);
									$total = count($activeMarksArray);
									$i = 0;
									foreach($activeMarksArray as $record) {
										?>
										<tr>
										<?php
										if ($i == 0) {
											?>
											<td colspan='1' rowspan='<?php echo $total;?>' style='color:red;'><?php echo $record['type'];?></td>
											<?php
										}
										?>
										<td colspan='2' class=''>
										<?php
										$conductingAuthority = $record['conductingAuthority'];
										if ($conductingAuthority == PRECOMPRE) {
											echo 'PreCompre';
										}
										elseif ($conductingAuthority == COMPRE) {
											echo 'Compre';
										}
										elseif ($conductingAuthority == ATTENDANCE) {
											echo 'Attendance';
										}
										elseif ($conductingAuthority == PRECOMPRE_REAPPEAR) {
											echo 'PreCompre ';
										}
										elseif ($conductingAuthority == COMPRE_REAPPEAR) {
											echo 'Compre';
										}
										elseif ($conductingAuthority == ATTENDANCE_REAPPEAR) {
											echo 'Attendance';
										}
										elseif ($conductingAuthority == PRECOMPRE_COMPRE_REAPPEAR) {
											echo 'PreCompre + Compre';
										}
										elseif ($conductingAuthority == PRECOMPRE_ATTENDANCE_REAPPEAR) {
											echo 'PreCompre + Attendance';
										}
										elseif ($conductingAuthority == COMPRE_ATTENDANCE_REAPPEAR) {
											echo 'Compre + Attendance';
										}
										elseif ($conductingAuthority == PRECOMPRE_ATTENDANCE_COMPRE_REAPPEAR) {
											echo 'PreCompre + Compre + Attendance';
										}
										?>
										</td>
										<td  colspan="1" class="">
											<?php echo $record['marksScored']; ?>
										</td>
										<td  colspan="1" class="">
											<?php echo $record['maxMarks']; ?>
										</td>
								<?php
										if ($i == 0) {
								?>
										<td colspan="1" class="" rowspan="<?php echo $total;?>" align="left">
											&nbsp;&nbsp;<?php echo $record['gradeLabel']; ?>
										</td>
								<?php
										}
								?>
								</tr>
								<?php
										$i++;
									}
								?>

										</table>
									</td>
								</tr>

					<tr>
						<td valign="top" colspan="6" class="contenttab_internal_rows"  width="100%">

							<table border="1" cellspacing="0" cellpadding="0"  width="100%" rules="all" bordercolor="#CCCCCC">
								<tr>
									<td valign="top" colspan="4" class="">&nbsp;Re-exam Type :
									</td>
								</tr>
								<tr>
									<td valign="top" colspan="1" class="contenttab_internal_rows" width="100%">
										<table border="0" cellspacing="2" cellpadding="0" width="100%" rules="rows" bordercolor="#CCCCCC">
											<tr class="row1">
												<td valign="top" colspan="1" class="" width="10%"><input type="radio" checked name="reappearExam" value="1" onClick="cleanReappearTable()"></td>
												<td valign="top" colspan="1" class="" width="90%">Separate marks will be entered for Attendance, PreCompre and Compre</td>
											</tr>
											<tr class="row0" style="height:25px;">
												<td valign="top" colspan="1" class=""><input type="radio" name="reappearExam" value="2" onClick="cleanReappearTable()"></td>
												<td valign="top" colspan="1" class="">Separate marks will be entered for Attendance. <br>Combined marks will be entered for PreCompre and Compre</td>
											</tr>
											<tr  class="row1" style="height:25px;">
												<td valign="top" colspan="1" class=""><input type="radio" name="reappearExam" value="3" onClick="cleanReappearTable()"></td>
												<td valign="top" colspan="1" class="">Separate marks will be entered for Compre. <br>Combined marks will be entered for PreCompre and Attendance</td>
											</tr>
											<tr class="row0" style="height:25px;">
												<td valign="top" colspan="1" class=""><input type="radio" name="reappearExam" value="4" onClick="cleanReappearTable()"></td>
												<td valign="top" colspan="1" class="">Separate marks will be entered for PreCompre. <br>Combined marks will be entered for Compre and Attendance</td>
											</tr>
											<tr  class="row1">
												<td valign="top" colspan="1" class=""><input type="radio" name="reappearExam" value="5" onClick="cleanReappearTable()"></td>
												<td valign="top" colspan="1" class="">Combined marks will be entered for Attendance, PreCompre and Compre</td>
											</tr>
										</table>
									</td>
									<td valign="bottom" class="" align="center">
										<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/enter_marks.gif" onClick="return makeReappearTable();return false;" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="6" class="" width="100%">
							<div id="reappearTable"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id='hiddenGrades' style='display:none;'>
<?php
$getOldGradeArray = $studentManager->getGradeSetGrades($classId, $studentId, $subjectId); //use grades, grades_set to fetch the grades of used grade set
echo json_encode($getOldGradeArray);
?>
</div>