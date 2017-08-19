<?php
//-------------------------------------------------------
// Purpose: to design the Feed Back Survey
//
// Author : Jaineesh
// Created on : (17.11.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ProvideGeneralSurvey');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$feedBackManager = TeacherManager::getInstance();

$errorMessage ='';

$feedBackSurveyId=$REQUEST_DATA['feedBackSurveyId'];


require_once(MODEL_PATH."/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

$recordFeedBackArray = $commonQueryManager -> getFeedBackGrade(' feedbackGradeId'," AND feedback_grade.feedbackSurveyId='".$feedBackSurveyId."'");
$recordFeedBackCount = count($recordFeedBackArray);

			$feedbackAttemptArray = $feedBackManager->getSurveyAttempts("WHERE feedbackSurveyId='".$feedBackSurveyId."'");
			$feedbackAttempt = $feedbackAttemptArray[0]['noAttempts'];

			$feedbackArray = $feedBackManager->getAttempts("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
			$attempt = $feedbackArray[0]['attempts'];

			$employeeFeedBackArray = $feedBackManager->getFeedBackEmpData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
			$recordCount = count($employeeFeedBackArray);

            if ($attempt == $feedbackAttempt){
				echo("Your feedback against this survey is finished");
				die;
			}
			else if($recordCount >0 && is_array($employeeFeedBackArray) ) {

               //  $j = $records;

			   ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
     <tr class="rowheading">
		  <td width="10%" class="unsortable" style="padding-left:15px"><b>#</b></td>

		  <td width="40%"   class="searchhead_text" align="left"><strong>Contents</strong></td>
		  <td width="50%"   class="searchhead_text" align="center" colspan="<?php echo $recordFeedBackCount?>"><strong>Comments</strong></td>

		 <!-- <td width="27%"   class="searchhead_text"><strong>Action</strong></t d>-->
		  </tr>
			   <tr>
					<td></td>
					<td></td>

					<?php if ($recordFeedBackCount > 0 && is_array($employeeFeedBackArray)) {

					  for ($k=0;$k<$recordFeedBackCount;$k++) {
						  ?>

						<td class="padding_top" valign="top" align="center"><b>
							<?php echo $recordFeedBackArray[$k]['feedbackGradeLabel']?>
						</b></td>
						<?php } ?>
						<?php } ?>
				</tr>
			   <input type="hidden" name="totalQuestions" value="<?php echo $recordCount;?>" />
			   <input type="hidden" name="totalFeedBackAnswers" value="<?php echo $recordFeedBackCount;?>" />

			   <?php
				   $catName ='';
                for($i=0; $i<$recordCount; $i++ ) {
					$catName1 = strip_slashes($employeeFeedBackArray[$i]['feedbackCategoryName']);
					$catName1 = ($catName == $catName1)?$catName1 = "": $catName1;
					$questionId = $employeeFeedBackArray[$i]['feedbackQuestionId'];
					$questionGradeIdArray = $feedBackManager->getStudentQuestionGradeId($questionId);
					$questionGradeId = $questionGradeIdArray[0]['feedbackGradeId'];
				  ?>
				<input type="hidden" name="feedbackCategory" value="<?php echo $employeeFeedBackArray[$i]['feedbackCategoryId'] ?>"/>
				<input type="hidden" name="feedbackQuestion" value="<?php echo $employeeFeedBackArray[$i]['feedbackQuestionId'] ?>"/>

				<tr>
					<td colspan="2"><b><?php echo $catName1?></b></td>
				</tr>
	            <tr>
                    <td valign="top" class="padding_top" style="padding-left:15px"><?php echo $records+$i+1 ?></td>

					<input type="hidden" name="feedbackQuestionId_<?php echo $i;?>" value="<?php echo strip_slashes($employeeFeedBackArray[$i]['feedbackQuestionId'])?>" />
					<td class="padding_top" valign="top" ><?php echo strip_slashes($employeeFeedBackArray[$i]['feedbackQuestion'])?></td>
					<?php if ($recordFeedBackCount > 0 && is_array($employeeFeedBackArray)) {

					  for ($k=0;$k<$recordFeedBackCount;$k++) {
						  $selected = "";
						  if($recordFeedBackArray[$k]['feedbackGradeId'] == $questionGradeId) {
							  $selected =  "checked";
						  }
						  ?>
						<td class="padding_top" valign="top" align="center">
							<input name="radio_<?php echo $i;?>" type="radio" value="<?php echo $recordFeedBackArray[$k]['feedbackGradeId']?>" <?php echo $selected;?>>
						</td>
						<?php } ?>

						<?php } ?>



                </tr>

            <?php
                     if($catName1 != "")
                        $catName = $catName1;

                }
                ?>
                <tr>
                <td align="right" colspan="10" height="10px" >

                </td>
                </tr>
                <tr>
                    <td align="right" colspan="10" style="padding-right:18px">
                        <input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/submit.gif"  onclick="return validateAddForm();return false;"/>
                    </td>
                </tr>
                <?php
                }
                else {
                    echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" >';
                    echo '<tr><td colspan="10" align="center" ><b>No record found</b></td></tr>';
                }

                ?>
            </table>

<?php
// $History: ajaxGeneralFeedBackSurvey.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/01/09    Time: 11:20
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Corrected database field names
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/09    Time: 11:05
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Corrected display logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/01/09    Time: 17:42
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created teacher feedback functionaility
?>