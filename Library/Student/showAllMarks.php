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

	$countTestMarksArray = $studentManager->checkTotalUpdatedMarks($studentId,$classId,$subjectId);
	$count = $countTestMarksArray[0]['cnt'];
	if ($count == 0) {
		echo NO_REAPPEAR_EXAM_TAKEN;
		die;
	}
	/*
	$countTestMarksArray = $studentManager->checkTestTransferredMarks($studentId,$classId,$subjectId);
	$count = $countTestMarksArray[0]['cnt'];

	if ($count < 3) {
		echo OLD_DATA;
		die;
	}
	*/

	$marksArray = $studentManager->getRegularMarks($studentId,$classId,$subjectId);
?>
<form name="finalMarksForm" method="post" action="" onsubmit="return false;">
	<input type='hidden' name='classId' value='<?php echo $classId;?>' />
	<input type='hidden' name='subjectId' value='<?php echo $subjectId;?>' />
	<input type='hidden' name='studentId' value='<?php echo $studentId;?>' />
	<div style="height:300px;width:480px;overflow:auto;">
	<table border="0" cellpadding="1" cellspacing="1" style="border:1px solid #CCC;width:450px;">
		<tr class="rowheading">
			<td  colspan="1" class="searchhead_text">Type</td>
			<td  colspan="1" class="searchhead_text">Component</td>
			<td  colspan="1" class="searchhead_text">Marks Scored</td>
			<td  colspan="1" class="searchhead_text">Max Marks</td>
			<td  colspan="1" class="searchhead_text">Grade</td>
			<td  colspan="1" class="searchhead_text">Final Marks</td>
		</tr>
<?php
	$total = count($marksArray);
	$i = 0;

	foreach($marksArray as $record) {
		$isActive = $record['isActive'];
		$checked = "";
		if ($isActive == 1) {
			$checked = " checked ";
		}
		$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
?>
		<tr <?php echo $bg;?>>
<?php
		if ($i == 0) {
?>
			<td  rowspan="<?php echo $total;?>" class="" style="color:red;"><u>Regular</u></td>
<?php
		}
?>
		<td  colspan="1" class="">
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
		<td colspan="1" class="" rowspan="<?php echo $total;?>">
			<?php echo $record['gradeLabel']; ?>
		</td>
		<td  rowspan="<?php echo $total;?>" class=""><input type="radio" name="finalMarks" value="regular" <?php echo $checked;?>/></td>
<?php
		}
?>
	</tr>
<?php
		$i++;
	}
?>
	<tr>
		<td  colspan="6" class=""><hr size="1" color="#CC0000"></td>
	</tr>

<?php
	$updateTimeCountArray = $studentManager->getReappearMarksCount($studentId,$classId,$subjectId);
	$updateArray = array();
	foreach($updateTimeCountArray as $record) {
		$updateArray[$record['updateDateTime']] = $record['cnt'];
	}
	$marksArray2 = $studentManager->getReappearMarks($studentId,$classId,$subjectId);
?>
<?php
	$total = count($marksArray2);
	$dateTime = '';
?>

<?php
	$i = 0;
	foreach($marksArray2 as $record) {
		$i++;
		$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
		$newDateTime = $record['updateDateTime'];
		$isActive = $record['isActive'];
		$checked = "";
		$marksUpdationId = $record['marksUpdationId'];
		if ($isActive == 1) {
			$checked = " checked ";
		}
?>
<?php
		if ($dateTime != $newDateTime and $i > 1) {
?>
	<tr>
		<td  colspan="6" class=""><hr size="1" color="#CC0000"></td>
	</tr>
<?php
		}
?>		<tr <?php echo $bg;?>>
<?php
		if ($dateTime != $newDateTime) {

?>
			<td  rowspan="<?php echo $updateArray[$newDateTime];?>" class="" style="color:red;"><u>Re-Exam</u></td>
<?php
		}
?>
		<td  colspan="1" class="">
			<?php
					$conductingAuthority = $record['conductingAuthority'];
					if ($conductingAuthority == PRECOMPRE_REAPPEAR) {
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
		if ($dateTime != $newDateTime) {
?>
			<td colspan="1" class="" rowspan="<?php echo $updateArray[$newDateTime];?>">
				<?php echo $record['gradeLabel']; ?>
			</td>
			<td  rowspan="<?php echo $updateArray[$newDateTime];?>" class=""><input type="radio" name="finalMarks" value="<?php echo $marksUpdationId;?>" <?php echo $checked;?>/></td>
<?php
		}
?>
	</tr>
<?php
	$dateTime = $newDateTime;
	}
?>
</table>
</div>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td valign="top" colspan="1" class="" align="left">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return saveFinalMarks();return false;" />

		</td>
		<td valign="top" colspan="1" class="" align="right">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/enter_marks.gif" onClick="return updateReAppear2('<?php echo $studentId;?>','<?php echo $classId;?>','<?php echo $subjectId;?>');return false;" />
		</td>
	</tr>
</table>
</tr>
</form>