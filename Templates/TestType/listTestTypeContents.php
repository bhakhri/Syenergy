<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TESTTYPE LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddTestType','',690,250,200,150);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
                                            </td>
                    </tr>
                    </table>
                </td>
             </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddTestType','Add Test Type'); ?>
<form name="AddTestType" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
	<tr>
		<td valign="top">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="22%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Test Type Name</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td width="78%" class="padding"><nobr>:&nbsp;<input type="text" id="testtypeName" name="testtypeName" class="inputbox" maxlength="30" />
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Test Type Code</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td class="padding"><nobr>:&nbsp;<input type="text" id="testtypeCode" name="testtypeCode" class="inputbox" maxlength="10" />
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Abbr.</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td class="padding"><nobr>:&nbsp;<input type="text" id="testtypeAbbr" name="testtypeAbbr" class="inputbox" maxlength="10" />
					</nobr></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr>&nbsp;<b>Test Type Category</b></nobr><?php echo REQUIRED_FIELD; ?></td>
					<td class="padding" style="padding-right:30px"><nobr>:
					<select class="selectfield" name="testType" id="testType" size="1">
					<option value="" selected="selected">SELECT</option>
					<?php 
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getTestTypeCategory();
					?>
					</select></nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>University</strong></nobr><?php echo REQUIRED_FIELD; ?></td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="university" name="university" class="selectfield">
					<option value="" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['university']==''? $testtypeRecordArray[0]['universityId'] : $REQUEST_DATA['university'] );
					?>
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong>&nbsp;Degree</strong></td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="degree" name="degree" class="selectfield">
					<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getDegreeData($REQUEST_DATA['degree']==''? $testtypeRecordArray[0]['degreeId'] : $REQUEST_DATA['degree'] );
					?>    
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong>&nbsp;Branch </strong> </td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="branch" name="branch" class="selectfield">
					<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branch']==''? $testtypeRecordArray[0]['branchId'] : $REQUEST_DATA['branch'] );
					?>  
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong><nobr>&nbsp;Study Period  </nobr></strong></td>
					<td width="79%" class="padding">:&nbsp;<select  class="selectfield" id="studyPeriod" name="studyPeriod">
					<<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['studyPeriod']==''? $testtypeRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriod'] );
					?>
					</select></nobr>
					</td>
				</tr>
<!--
<tr>
<td class="contenttab_internal_rows">&nbsp;<nobr><?php echo REQUIRED_FIELD; ?><strong>Weightage Percentage </nobr></strong></td>
<td width="79%" class="padding"><nobr>:&nbsp;<input type="text" id="weightage" name="weightagePercentage" class="inputbox" maxlength="5" />
</nobr></td>
</tr>-->
				</table>
		</td>
<td valign="top">
	<table>
		<tr>
			
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Select Time Table</b></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="labelId" id="labelId" onBlur="getTimeTableSubject();">
			<option value="">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getTimeTableLabelData();
		
			?>
			</select></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong><nobr>&nbsp;Subject</nobr></strong> </td>
			<td width="79%" class="padding">:&nbsp;<select  class="selectfield" id="subject" name="subject">
			<option value="NULL" selected="selected">SELECT</option>
			<!--
			<?php
	
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getSubjectTimeTableData();
			?>-->
			</select></nobr>
			</td>
		</tr>

		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Weightage Amount </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding"><nobr>:&nbsp;<input type="text" id="weightage" name="weightageAmount" class="inputbox"/>
			</nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Evaluation Criteria  </nobr></strong><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<select id="evaluationCriteria" name="evaluationCriteria" class="selectfield" onchange="evaluationCriteriaAction(this.options[this.selectedIndex].text,'Add');" >
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getEvaluationCritieriaData($REQUEST_DATA['evaluationCriteria']==''? $testtypeRecordArray[0]['evaluationCriteriaId'] : $REQUEST_DATA['evaluationCriteria'] );
			?>     
			</select></nobr>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>&nbsp;Count <?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="cnt" name="cnt" style="width:50px" class="inputbox" maxlength="6" /></nobr>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sort Order </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="sortOrder" name="sortOrder" style="width:100px"  class="inputbox" maxlength="11" />
			</td></nobr>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Subject Type </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<select id="subjectType" name="subjectType" class="selectfield">
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getSubjectTypeData($REQUEST_DATA['subjectType']==''? $testtypeRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectType'] );
			?>    
			</select></nobr> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Conducting Authority</b><?php echo REQUIRED_FIELD; ?></nobr></td>
			<td width="79%" class="padding"><nobr>:&nbsp;<select  id="conductingAuthority" name="conductingAuthority" class="selectfield"> 
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getConductingAuthorityData();
			?> 
			</select>
			</nobr>
			</td>
		</tr>
		<tr>
		<td class="contenttab_internal_rows" colspan="2">&nbsp;</td>
		</tr>
	</table>
</td>
</tr>
<tr colspan="2">
<td height="5"></td></tr>

<tr colspan="2">
<td align="center" style="padding-right:10px" colspan="2">
<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
<input type="image" name="addCancel"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTestType');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
</td>
</tr>
<tr colspan="2">
<td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



<?php floatingDiv_Start('EditTestType','Edit Test Type'); ?>
<form name="EditTestType" action="" method="post">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="border">
  <input type="hidden" name="testtypeId" id="testtypeId" value="" />
  <tr>
  <td valign="top">
  <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="22%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Test Type Name</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td width="78%" class="padding"><nobr>:&nbsp;<input type="text" id="testtypeName" name="testtypeName" class="inputbox" maxlength="30" />
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Test Type Code</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td class="padding"><nobr>:&nbsp;<input type="text" id="testtypeCode" name="testtypeCode" class="inputbox" maxlength="10" />
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Abbr.</nobr><?php echo REQUIRED_FIELD; ?></strong></td>
					<td class="padding"><nobr>:&nbsp;<input type="text" id="testtypeAbbr" name="testtypeAbbr" class="inputbox" maxlength="10" />
					</nobr></td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><nobr>&nbsp;<b>Test Type Category</b></nobr><?php echo REQUIRED_FIELD; ?></td>
					<td class="padding" style="padding-right:30px"><nobr>:
					<select class="selectfield" name="testType" id="testType" size="1">
					<option value="" selected="selected">SELECT</option>
					<?php 
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getTestTypeCategory();
					?>
					</select></nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows">&nbsp;<nobr><strong>University</strong></nobr><?php echo REQUIRED_FIELD; ?></td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="university" name="university" class="selectfield">
					<option value="" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['university']==''? $testtypeRecordArray[0]['universityId'] : $REQUEST_DATA['university'] );
					?>
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong>&nbsp;Degree</strong> </td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="degree" name="degree" class="selectfield">
					<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getDegreeData($REQUEST_DATA['degree']==''? $testtypeRecordArray[0]['degreeId'] : $REQUEST_DATA['degree'] );
					?>    
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong>&nbsp;Branch </strong></td>
					<td width="79%" class="padding"><nobr>:&nbsp;<select id="branch" name="branch" class="selectfield">
					<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branch']==''? $testtypeRecordArray[0]['branchId'] : $REQUEST_DATA['branch'] );
					?>  
					</select>
					</nobr>
					</td>
				</tr>
				<tr>
					<td class="contenttab_internal_rows"><strong><nobr>&nbsp;Study Period  </nobr></strong></td>
					<td width="79%" class="padding">:&nbsp;<select  class="selectfield" id="studyPeriod" name="studyPeriod">
					<<option value="NULL" selected="selected">SELECT</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['studyPeriod']==''? $testtypeRecordArray[0]['studyPeriodId'] : $REQUEST_DATA['studyPeriod'] );
					?>
					</select></nobr>
					</td>
				</tr>
<!--
<tr>
<td class="contenttab_internal_rows">&nbsp;<nobr><?php echo REQUIRED_FIELD; ?><strong>Weightage Percentage </nobr></strong></td>
<td width="79%" class="padding"><nobr>:&nbsp;<input type="text" id="weightage" name="weightagePercentage" class="inputbox" maxlength="5" />
</nobr></td>
</tr>-->
				</table>
		</td>
<td valign="top">
	<table>
		<tr>
			
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Select Time Table</b></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td class="padding">:&nbsp;<select size="1" class="selectfield" name="labelId" id="labelId" onblur="getTimeTableEditSubject();">
			<option value="">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getTimeTableLabelData();
		
			?>
			</select></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong><nobr>&nbsp;Subject</nobr></strong></td>
			<td width="79%" class="padding">:&nbsp;<select  class="selectfield" id="subject" name="subject" >
			<option value="NULL" selected="selected">SELECT</option>
			<!--
			<?php
	
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getSubjectTimeTableData();
			?>-->
			</select></nobr>
			</td>
		</tr>

		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Weightage Amount </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding"><nobr>:&nbsp;<input type="text" id="weightage" name="weightageAmount" class="inputbox"/>
			</nobr></td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Evaluation Criteria  </nobr></strong><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<select id="evaluationCriteria" name="evaluationCriteria" class="selectfield" onchange="evaluationCriteriaAction(this.options[this.selectedIndex].text,'Edit');" >
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getEvaluationCritieriaData($REQUEST_DATA['evaluationCriteria']==''? $testtypeRecordArray[0]['evaluationCriteriaId'] : $REQUEST_DATA['evaluationCriteria'] );
			?>     
			</select></nobr>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>&nbsp;Count <?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="cnt" name="cnt" style="width:50px" class="inputbox" maxlength="6" /></nobr>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sort Order </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<input type="text" id="sortOrder" name="sortOrder" style="width:100px"  class="inputbox" maxlength="11" />
			</td></nobr>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Subject Type </strong></nobr><?php echo REQUIRED_FIELD; ?></td>
			<td width="79%" class="padding">:&nbsp;<select id="subjectType" name="subjectType" class="selectfield">
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getSubjectTypeData($REQUEST_DATA['subjectType']==''? $testtypeRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectType'] );
			?>    
			</select></nobr> 
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows">&nbsp;<nobr><b>Conducting Authority</b><?php echo REQUIRED_FIELD; ?></nobr></td>
			<td width="79%" class="padding"><nobr>:&nbsp;<select  id="conductingAuthority" name="conductingAuthority" class="selectfield"> 
			<option value="" selected="selected">SELECT</option>
			<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getConductingAuthorityData();
			?> 
			</select>
			</nobr>
			</td>
		</tr>
		<tr>
		<td class="contenttab_internal_rows" colspan="2">&nbsp;</td>
		</tr>
	</table>
</td>
</tr>
    <tr colspan="2">
        <td height="5"></td></tr>
    <tr>

<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditTestType');return false;" />
   </td>
</tr>
<tr colspan="2">
    <td height="5px"></td></tr>
<tr>
</table>
</form>     
<?php floatingDiv_End(); ?>
<!--End Add Div-->
                                                           



<?php
// $History: listTestTypeContents.php $
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/27/09    Time: 5:10p
//Updated in $/LeapCC/Templates/TestType
//Gurkeerat: resolved issue 1272
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:35
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug id---0000749
//
//*****************  Version 9  *****************
//User: Administrator Date: 12/06/09   Time: 11:08
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug ids----0000032,0000036,0000043
//
//*****************  Version 8  *****************
//User: Administrator Date: 12/06/09   Time: 10:55
//Updated in $/LeapCC/Templates/TestType
//Done bug fixing.
//bug ids---0000046,0000048,0000050
//
//*****************  Version 7  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Templates/TestType
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 28/04/09   Time: 18:15
//Updated in $/LeapCC/Templates/TestType
//Modified "cnt" field's display logic
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/25/09    Time: 6:34p
//Updated in $/LeapCC/Templates/TestType
//modified to show test type category 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Templates/TestType
//modified for test type & put test type category
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/01/09    Time: 10:52
//Updated in $/LeapCC/Templates/TestType
//Make university selection compulsory
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Updated in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/TestType
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/18/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/TestType
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/TestType
//corrected breadcrumb and reset button height and width
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:55p
//Updated in $/Leap/Source/Templates/TestType
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/07/08    Time: 11:22a
//Updated in $/Leap/Source/Templates/TestType
//Modified count box selection based upon evaluation criteria selection
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/02/08    Time: 11:32a
//Updated in $/Leap/Source/Templates/TestType
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/01/08    Time: 10:26a
//Updated in $/Leap/Source/Templates/TestType
//Correct speling mistake
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/29/08    Time: 7:35p
//Updated in $/Leap/Source/Templates/TestType
//Corrected JavaScript Code
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 7/24/08    Time: 4:59p
//Updated in $/Leap/Source/Templates/TestType
//Modified so that
//university,degree,branch,subject,study period and evaluation criteria
//becomes optional
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/TestType
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Templates/TestType
//Modified DataBase Column names
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/TestType
//Solved TabOrder Problem
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/30/08    Time: 12:18p
//Updated in $/Leap/Source/Templates/TestType
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Templates/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/TestType
//Added AjaxEnabled Delete functionality
//Added Input Data validation using javascript
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/19/08    Time: 3:01p
//Updated in $/Leap/Source/Templates/TestType
//Adding extra fields done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:05a
//Updated in $/Leap/Source/Templates/TestType
//Modifying functions Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:42p
//Created in $/Leap/Source/Templates/TestType
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:20p
//Updated in $/Leap/Source/Templates/Institute
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:31p
//Created in $/Leap/Source/Templates/Institute
//Initial Checkin
?>