<?php
//-------------------------------------------------------
// This File contains Validation and ajax function used for group change
// Author :Dipanjan Bhattacharjee
// Created on : 07-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

$groupIds='';
foreach($REQUEST_DATA as $key=> $val){
  if(strpos('"'.$key.'"','chkop_')){
      if($groupIds!=''){
          $groupIds .=',';
      }
      $gr=explode('chkop_',$key);
      $groupIds .=$gr[1]; 
  }    
}

if($groupIds==''){
   echo NO_OPTIONAL_GROUP_DATA_FOUND;
   die;
}

$userGroupArray=explode(',',$groupIds);
$userGrCnt=count($userGroupArray);


//fetch groupShort Names
$newGroupNameArrays=$studentManager->getGroupsInformation(' WHERE groupId IN ('.$groupIds.')');
$newGroupArray=array();
foreach($newGroupNameArrays as $group){
    $newGroupArray[$group['groupId']]=trim($group['groupShort']);
}

//get subject-group information
$subjectGrArray=$studentManager->getStudentSubjectOptionalGroups($studentId,$classId);
$cnt=count($subjectGrArray);
if($cnt==0){
   echo NO_OPTIONAL_GROUP_DATA_FOUND;
   die; 
}

//this array stores new values of groups
$userSubGroupArray=array();
$k=0;
for($i=0;$i<$cnt;$i++){
    $fl=0;
    $gfl=0;
    $grName='';
    $subjectId=$subjectGrArray[$i]['subjectId'];
    $groups=explode(',',$subjectGrArray[$i]['groupIds']);
    for($j=0;$j<$userGrCnt;$j++){
        if(in_array($userGroupArray[$j],$groups)){
            $gfl=$userGroupArray[$j];
            $fl++;
        }
    }
    if($fl!=1){
        echo INVALID_OPTIONAL_GROUP_COUNT;
        die;
    }
    $userSubGroupArray[$subjectId]=$gfl;
}


//this array stores subject codes
$dbSubjectNameArray=array();
$dbGroupNameArray=array();
//this array stores old assigned values of groups
$dbSubjectGrArray=array();
$dbSubjectGrArray2=$studentManager->getStudentSubjectOptionalGroupsAssigned($studentId,$classId);
$cnt1=count($dbSubjectGrArray2);
$k=0;
for($i=0;$i<$cnt1;$i++){
   $dbSubjectGrArray[$dbSubjectGrArray2[$i]['subjectId']]=$dbSubjectGrArray2[$i]['groupId'];
   $dbSubjectNameArray[$dbSubjectGrArray2[$i]['subjectId']]=trim($dbSubjectGrArray2[$i]['subjectCode']);
   $dbGroupNameArray[$dbSubjectGrArray2[$i]['subjectId']]=trim($dbSubjectGrArray2[$i]['groupShort']);
}

$tabsArray = array();
$internalTabs = array();
?>
<table border='0' cellspacing='0' cellpadding='0' height='450'>
	<tr>
		<td valign='top' colspan='1' class='' style="padding-left:10px;height:400px;">
			<div id="subjectTabs">
			<?php
			foreach($dbSubjectGrArray as $key=>$val) {
				$subjectId=$key;
                $oldGroupId=$val;
                $subjectCode=$dbSubjectNameArray[$subjectId];
                $oldGroupName=$dbGroupNameArray[$subjectId];
                $newGroupId=$userSubGroupArray[$subjectId];
                $newGroupName=$newGroupArray[$newGroupId];               
                
				$tabsArray[] = $subjectCode;
				$internalTabs[] = $subjectCode;
				?>
				<div class="dhtmlgoodies_aTab">	
					<div id=<?php echo $subjectCode;?>>
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
												if ($oldGroupId != $newGroupId) {
													$oldAttendanceArray = $studentManager->getStudentOldAttendance($studentId, $classId, $subjectId, $oldGroupId);
													$oldLectureDelivered = $oldAttendanceArray[0]['lectureDelivered']!=''?$oldAttendanceArray[0]['lectureDelivered']:NOT_APPLICABLE_STRING;
													$oldLectureAttended = $oldAttendanceArray[0]['lectureAttended']!=''?$oldAttendanceArray[0]['lectureAttended']:NOT_APPLICABLE_STRING;
													$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$newGroupId);
													$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered']!=''?$newAttendanceArray[0]['lectureDelivered']:NOT_APPLICABLE_STRING;
												?>
   												<tr>
													<td valign='top' colspan='1' class=''><?php echo $oldGroupName;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''><?php echo $oldLectureAttended;?></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class=''><?php echo $newGroupName;?></td>
													<td valign='top' colspan='1' class=''><?php echo $newLectureDelivered;?></td>
													<td valign='top' colspan='1' class=''>
                                                    <?php
                                                     if($newLectureDelivered!=NOT_APPLICABLE_STRING){
                                                    ?>     
                                                     <input type='text' size='6' class='inputbox' style="width:60px;"  name='att_<?php echo $newGroupId.'_'.$subjectId;?>' value='' /></td>
                                                    <?php
                                                     }
                                                    else{
                                                        echo NOT_APPLICABLE_STRING;
                                                    } 
                                                    ?>  
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
								<table border='0' cellspacing='2' cellpadding='0' align='center' width='100%'>
									<tr>
										<td valign='top' colspan='1' class=''>
											<?php
											$counter = 0;
											$counter2 = 0;
											$showRow = "<td><b>Max Marks</b></td><td><b>Marks Scored</b></td>";
											if ($oldGroupId != $newGroupId) {
											?>
											<table border='1' cellspacing='2' cellpadding='2' width='100%' rules='all' bordercolor='#ffffff'>
												<tr>
													<td valign='top' colspan='2' class='' height='10'></td>
												</tr>
												<tr>
													<td valign='top' colspan='1' class='' width='50%'>
														<table border='0' cellspacing='2' cellpadding='0' width='100%'>
															<tr class='highlightPermission'>
																<td valign='top' colspan='1' class=''>
																<B><?php echo $oldGroupName;?></B>	
																</td>
																<?php
																	if ($counter == 0) {
																		echo $showRow;
																		$counter++;
																	}
																?>
															</tr>
															<?php
															$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $oldGroupId);
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
															<tr class='highlightPermission'>
																<td valign='top' colspan='0' class=''>
																<B><?php echo $newGroupName;?></B>	
																</td>
																<?php
																	if ($counter2 == 0) {
																		echo $showRow;
																		$counter2++;
																	}
																?>
															</tr>
															<?php
															$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$newGroupId);
															foreach($newTestArray as $newTestRecord) {
																$newTestId = $newTestRecord['testId'];
																$newTestName = $newTestRecord['testName'];
																$newMaxMarks = $newTestRecord['maxMarks'];
															?>
																<tr>
																	<td valign='top' colspan='1' class='' width='50%'><?php echo $newTestName;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><?php echo $newMaxMarks;?></td>
																	<td valign='top' colspan='1' class='' width='25%'><input type='text' size='6' class='inputbox' style="width:60px;"  name='test_<?php echo $newTestId.'_'.$subjectId;?>' value='' /></td>
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
			<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo $themePath;?>/save.gif" onClick="saveData2();" />
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
?>