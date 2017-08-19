<?php
//-------------------------------------------------------
// Purpose: to design the Feed Back Survey
//
// Author : Jaineesh
// Created on : (17.11.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherFeedBack');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$feedBackManager = StudentInformationManager::getInstance();

$errorMessage ='';

$feedBackSurveyId=$REQUEST_DATA['feedBackSurveyId'];

		require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
		$commonQueryManager = CommonQueryManager::getInstance(); 

		$recordFeedBackArray = $commonQueryManager -> getFeedBackGrade(' feedbackGradeId'," AND feedback_grade.feedbackSurveyId='".$feedBackSurveyId."'");
		$recordFeedBackCount = count($recordFeedBackArray);
?>			
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
         <tr class="rowheading">
			  <td width="10%" class="unsortable" style="padding-left:15px"><b>#</b></td>
			   
			  <td width="40%"   class="searchhead_text" align="left"><strong>Contents</strong></td>
			  <td width="50%"   class="searchhead_text" align="center" colspan="<?php echo $recordFeedBackCount?>"><strong>Comments</strong></td>
			  
			 <!-- <td width="27%"   class="searchhead_text"><strong>Action</strong></t d>-->
			  </tr>
              <?php  
				
				require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
				
				$studentInformationManager = StudentInformationManager::getInstance();
				

				$studentFeedBackArray = $studentInformationManager->getFeedBackData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
				
				$recordCount = count($studentFeedBackArray);

				
				/*echo '<pre>';
				print_r($studentFeedBackArray);
				echo '</pre>';*/
                
				if($recordCount >0 && is_array($studentFeedBackArray) ) { 

                   //  $j = $records;
				   ?>
				   <tr>
						<td></td>
						<td></td>
						<?php if ($recordFeedBackCount > 0 && is_array($studentFeedBackArray)) {

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
						$catName1 = strip_slashes($studentFeedBackArray[$i]['feedbackCategoryName']);
						$catName1 = ($catName == $catName1)?$catName1 = "": $catName1;
					  ?>
					<input type="hidden" name="feedbackCategory" value="<?php echo $studentFeedBackArray[$i]['feedbackCategoryId'] ?>"/>
					<input type="hidden" name="feedbackQuestion" value="<?php echo $studentFeedBackArray[$i]['feedbackQuestionId'] ?>"/>
					
					<tr>
						<td colspan="2"><b><?php echo $catName1?></b></td>
					</tr>
	                <tr>
                        <td valign="top" class="padding_top" style="padding-left:15px"><?php echo $records+$i+1 ?></td>
						
						<input type="hidden" name="feedbackQuestionId_<?php echo $i;?>" value="<?php echo strip_slashes($studentFeedBackArray[$i]['feedbackQuestionId'])?>" /> 
						<td class="padding_top" valign="top" ><?php echo strip_slashes($studentFeedBackArray[$i]['feedbackQuestion'])?></td>
						<?php if ($recordFeedBackCount > 0 && is_array($studentFeedBackArray)) {
						 
						  for ($k=0;$k<$recordFeedBackCount;$k++) {
							  ?>
							<td class="padding_top" valign="top" align="center">	  
								<input name="radio_<?php echo $i;?>" type="radio" value="<?php echo $recordFeedBackArray[$k]['feedbackGradeId']?>">
							</td>
							<?php } ?>
							
							<?php } ?>
							


                    </tr>
					
                <?php
					 if($catName1 != "")
						$catName = $catName1;
					 
				}
				}
                else {
                    echo '<tr><td colspan="10" align="center">No record found</td></tr>';
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
            </table>
			
<?php

// $History: ajaxFeedBackSurvey.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Library/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/26/09    Time: 1:20p
//Created in $/LeapCC/Library/Student
//copy file from sc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:16p
//Updated in $/Leap/Source/Library/ScStudent
//modified code for showing questions related to selected survey
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/30/08   Time: 5:50p
//Created in $/Leap/Source/Library/ScStudent
//add file to see feedback form
//

?>