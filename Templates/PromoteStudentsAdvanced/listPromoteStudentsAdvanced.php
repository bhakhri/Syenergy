<?php 
//-------------------------------------------------------
//  This File contains html code for marks transfer
////// Author :Ajinder Singh
// Created on : 31-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
////--------------------------------------------------------?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
		<?php	 require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									<table border='0' cellspacing='0' cellpadding='0' style="height:600px;width:800px;">
										<tr>
											<td valign='top' colspan='1' class=''>
												<div id="dhtmlgoodies_tabView1" style="height:600px;width:780px;overflow:none;">
												<!-- promotion tab -->
													<div class="dhtmlgoodies_aTab" style="height:500px;width:940px;overflow:none;">
														<form name="promotionForm" id="promotionForm" action="" method="post" target="" class="border" onsubmit="return false;">
															<table border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td valign='top' colspan='1' class=''>
																		<b>Classes matching with any of the below mentioned criterias are selected for Promotion:</b>
																		<table border='0' cellspacing='0' cellpadding='0'>
																			<tr>
																				<td valign='top' colspan='1' class=''>
																					1. The class is Active and marks have been transferred for that class Or 
																				</td>
																			</tr>
																			<tr>
																				<td valign='top' colspan='1' class=''>
																					2. If the marks are not transferred then marks for its previous class have been transferred Or
																				</td>
																			</tr>
																			<tr>
																				<td valign='top' colspan='1' class=''>
																					3. The class is in first semester.
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td valign='top' colspan='1' class=''><div style="height:460px;width:940px; overflow:auto;" id="getClassForPromotionDiv"></div>
																	</td>
																</tr>
																<tr>
																	<td align="center" style="padding-right:10px" colspan="2">
																		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/promote_selected_class.gif" onClick="return promoteClasses('promote');return false;" />
																	</td>
																</tr>
															</table>
														</form>
													</div>
													<!-- Class subject tab -->
													<div class="dhtmlgoodies_aTab" style="height:500px;width:940px;overflow:none;">
														<form action="" method="POST" name="listForm" id="listForm" onsubmit="return false;">
															<table border='0' cellspacing='0' cellpadding='0' width="930">
																<tr>
																	<td valign='top' colspan='1' class=''>
																		<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			<tr>
																				<td class="contenttab_border2" colspan="2">
																					<table  border="0" cellspacing="0" cellpadding="0" align="center">
																						<tr>
																							<td height="10"></td>
																						</tr>
																						<tr>
																							<td class="contenttab_internal_rows">
																								<nobr><b>Select Class<?php echo REQUIRED_FIELD?>: </b></nobr>
																							</td>
																							<td class="padding">
																								<select size="1" class="inputbox1" name="classId" id="classId" onChange="clearText()">
																									<option value="">Select</option>
																									<?php 
																									
																									require_once(BL_PATH.'/HtmlFunctions.inc.php');
																									echo HtmlFunctions::getInstance()->getClassData();
																									
																									?>
																								</select>
																							</td>
																							<td class="contenttab_internal_rows">
																								<nobr><b>Subject Code/Name: </b></nobr>
																							</td>
																							<td class="padding">
																								<input style="width: 240px" type="text" id="subjectDetail" name="subjectDetail" class="inputbox1"/>
																							</td>
																							<td class="contenttab_internal_rows">
																								<nobr><b>All Subjects: </b></nobr>
																							</td>
																							<td class="padding">
																								<input type="checkbox" id="allSubject" name="allSubject" value="1" checked/>
																							</td>
																							<td  align="right" style="padding-right:5px;">
																								<input type="hidden" name="listSubject" value="1">
																								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getSubject(); return false;"/>
																							</td>
																						</tr>
																						<tr>
																							<td colspan="4" height="5px"></td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																			<tr style="display:none" id="showTitle">
																				<td class="contenttab_border" height="20" colspan="2">
																					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
																						<tr>
																							<td class="content_title">Subjects To Class Detail : </td>
																							<td align="right"   id = 'saveDiv2' style='display:none;padding-right:5px;'><img src="<?php echo IMG_HTTP_PATH;?>/add.gif"  align="right" onClick="displayWindow('AddSubject',300,300);blankSubjectForm();return false;" />&nbsp;&nbsp;&nbsp;</td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																			<tr style="display:none" id="showData">
																				<td class="contenttab_row" valign="top" colspan="2">
																					<div id="scroll" style="OVERFLOW: auto; HEIGHT:294px; width:100%; TEXT-ALIGN: justify;padding-right:10px" class="scroll">
																						<div id="results"></div>
																					</div>
																				</td>
																			</tr>
																			<tr>
																				<td height="10" colspan="2"></td>
																			</tr>
																			<tr  id = 'saveDiv1' style='display:none;'>
																				<td align="right" width="55%">
																					<input type="hidden" name="submitSubject" value="1">
																					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return promoteClasses('classSubjects');return false;" />
																				</td>
																				<td colspan='1' align='right' valign="middle" id = 'saveDiv' style='display:none;'>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</form>
													</div>
													<!-- time table tab -->
													<div class="dhtmlgoodies_aTab" style="height:800px;width:940px;overflow:auto;">
														<div style="height:550px;width:940px; overflow:auto;">
															<table width="100%" border="0" cellspacing="0" cellpadding="0" >
																<tr>
																	<td valign="top">
																		<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			<tr>
																				<td valign="top" class="content">
																					<table width="100%" border="0" cellspacing="0" cellpadding="0">
																						<tr>
																							<td class="contenttab_border" height="20">
																								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
																									<tr>
																										<td class="content_title">Time Table Label Detail : </td>
																										<td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif"  align="right" onClick="displayWindow('AddTimeTableLabel',300,300);blankTimeTableLabelValues();return false;" />&nbsp;</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																						<tr>
																							<td class="contenttab_row" valign="top" >
																								<div id="timeTableLabelResuts"></div>
																							</td>
																						</tr>
																					</table>
																					<table width="100%" border="0" cellspacing="0" cellpadding="0">
																						<tr>
																							<td class="contenttab_row" valign="top" >
																								<table width="100%" border="0" cellspacing="0" cellpadding="0">
																									<tr>
																										<td valign="top">
																											<table width="100%" border="0" cellspacing="0" cellpadding="0">
																												<tr>
																													<td valign="top" class="content">
																														<form action="" method="POST" name="listTimeTableClassesForm" id="listTimeTableClassesForm">
																															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																																<tr>
																																	<td class="contenttab_border2" colspan="2">
																																		<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
																																			<tr>
																																				<td height="10"></td>
																																			</tr>
																																			<tr>
																																				<td class="contenttab_internal_rows"><nobr><b>Select Time Table: </b></nobr></td>
																																				<td class="padding">
																																					<select size="1" class="inputbox1" name="labelId" id="labelId" onChange="clearTimeTableText()">
																																						<option value="">Select</option>
																																					</select>
																																				</td>
																																				<td  align="right" style="padding-right:5px">
																																					<input type="hidden" name="listSubject" value="1">
																																					<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getTimeTableLabelClasses(); return false;"/>
																																				</td>
																																			</tr>
																																			<tr>
																																				<td colspan="4" height="5px"></td>
																																			</tr>
																																		</table>
																																	</td>
																																</tr>
																																<tr style="display:none" id="showTitle2">
																																	<td class="contenttab_border" height="20" colspan="2">
																																		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
																																			<tr>
																																				<td class="content_title">Associate Time Table To Class : </td>
																																				<td align="right" width="55%">
																																						<input type="hidden" name="submitSubject" value="1">
																																						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateTimeTableClassForm();return false;" />
																																				</td>
																																			</tr>
																																		</table>
																																	</td>
																																</tr>
																																<tr style="display:none" id="showData2">
																																	<td class="contenttab_row" valign="top" colspan="2">
																																		<div style="OVERFLOW: auto; height:530px; TEXT-ALIGN: justify;padding-right:10px">
																																			<div id="timeTableClassResults" ></div>
																																		</div>
																																	</td>
																																</tr>
																																<tr>
																																	<td height="10" colspan="2"></td>
																																</tr>
																																<tr  id = 'saveDiv3' style='display:none;'>
																																	<td align="right" width="55%">
																																		<input type="hidden" name="submitSubject" value="1">
																																		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateTimeTableClassForm();return false;" />
																																	</td>
																																</tr>
																															</table>
																														</form>
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
																	</td>
																</tr>
															</table>
														</div>
													</div>
												</div>
											</td>
										</tr>
									</table>
								<!-- div coding here -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>



<?php floatingDiv_Start('AddTimeTableLabel','Add Time Table Label'); ?>
<form name="AddTimeTableLabel" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>From Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;                                    
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('fromDate');
        ?></td>
    </tr>    
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>To Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('toDate');
        ?>
        </td>
    </tr>  
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Active<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding" align="left"><nobr>:&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
        </td>
    </tr>
   <tr>
     <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateTimeTableLabelForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTimeTableLabel');return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditTimeTableLabel','Edit Time Table Label'); ?>
<form name="EditTimeTableLabel" action="" method="post">  
    <input type="hidden" name="labelId" id="labelId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td width="79%" class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></nobr></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>From Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;                                    
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('fromDate1');
        ?></td>
     </tr>    
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><strong>To Date<?php echo REQUIRED_FIELD;?></strong>&nbsp;</td>
        <td width="79%" class="padding"><nobr>:&nbsp;
        <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
        echo HtmlFunctions::getInstance()->datePicker('toDate1');
        ?>
        </td>
    </tr>   
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>Active<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td  class="padding" align="left"><nobr>:</b>&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;</nobr>
        </td>
    </tr>
   <tr>
     <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateTimeTableLabelForm(this.form,'Edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditTimeTableLabel');return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<?php floatingDiv_Start('AddSubject','Add Subject'); ?> 
<form name="addSubject" action="" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="subjectName" name="subjectName" class="inputbox" maxlength="255" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectCode" name="subjectCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectAbbreviation" name="subjectAbbreviation" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" style="width:185px" onchange="getMarkAttendanceShow(this.value,'Add');" >
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getSubjectTypeData($REQUEST_DATA['subjectTypeId']==''? $subjectRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectTypeId'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Category<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="subjectCategoryId" id="subjectCategoryId" style="width:185px">
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getParentSubjectCategoryData($REQUEST_DATA['subjectCategoryId']==''? $subjectRecordArray[0]['subjectCategoryId'] : $REQUEST_DATA['subjectCategoryId']);
              ?>
        </select>
    </td>
</tr>                      
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Attendance<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasAttendance" id="hasAttendance" style="width:100px">
              <option value="1">Yes</option>  
              <option value="0">No</option>  
        </select>
    </td>
</tr>                      
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Marks<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasMarks" id="hasMarks" style="width:100px">
              <option value="1">Yes</option>  
              <option value="0">No</option>  
        </select>
    </td>
</tr>                   
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
    <textarea cols="35" rows="3" class="inputbox1" id="courseTopic" name="courseTopic" style="vertical-align:middle;"></textarea></tr>
<tr><td></td><td><b>&nbsp;&nbsp;<i>Please enter comma seprated(,) topics <br>&nbsp;&nbsp;for e.g: topic1,topic2,topic3</i></b></td>
</tr>

<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
	 <input type="text"  id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="30" /> </td>
</tr>
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return addEditSubject(this.form,'Add');return false;" />
      <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddSubject');return false;" /></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start edit Div-->
<?php floatingDiv_Start('EditSubject','Edit Subject '); ?>
<form name="editSubject" action="" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">

<input type="hidden" name="subjectId" id="subjectId" value="" />
<tr>
    <td width="19%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="81%" class="padding">:&nbsp;<input type="text" id="subjectName" name="subjectName" class="inputbox" maxlength="255" /></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectCode" name="subjectCode" class="inputbox" maxlength="20"/></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b></nobr></td>
    <td class="padding">:&nbsp;<input type="text" id="subjectAbbreviation" name="subjectAbbreviation" class="inputbox" maxlength="20"/></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:&nbsp;<select size="1" class="selectfield" name="subjectTypeId" id="subjectTypeId" style="width:185px" onchange="getMarkAttendanceShow(this.value,'Edit');" >
              <option value="">Select</option> 
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getSubjectTypeData($REQUEST_DATA['subjectTypeId']==''? $subjectRecordArray[0]['subjectTypeId'] : $REQUEST_DATA['subjectTypeId'] );
              ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Category<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="subjectCategoryId" id="subjectCategoryId" style="width:185px">
              <option value="">Select</option>  
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getParentSubjectCategoryData($REQUEST_DATA['subjectCategoryId']==''? $subjectRecordArray[0]['subjectCategoryId'] : $REQUEST_DATA['subjectCategoryId']);
              ?>
        </select>
    </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Attendance<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasAttendance" id="hasAttendance" style="width:100px">
            <option value="1">Yes</option>  
            <option value="0">No</option>  
        </select>
    </td>
</tr>                      
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Marks<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
        <select size="1" class="selectfield" name="hasMarks" id="hasMarks" style="width:100px">
            <option value="1">Yes</option>  
            <option value="0">No</option>  
        </select>
    </td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
    <textarea cols="35" rows="3" class="inputbox1" id="courseTopic" name="courseTopic" style="vertical-align:middle;"></textarea></tr>
<tr><td></td><td><b>&nbsp;&nbsp;<i>Please enter comma seprated(,) topics <br>&nbsp;&nbsp;for e.g: topic1,topic2,topic3</i></b></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td class="padding">:
	 <input type="text"  id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="30" /> </td>
</tr>

<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return addEditSubject(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditSubject');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>

</table></form>
<?php 
floatingDiv_End();
?>
<script type="text/javascript">	initTabs('dhtmlgoodies_tabView1',Array('Promotion Activities','Class-Subject Activities'),0,950,550,Array(false,false));   
			</script>
<?php 
//$History: listPromoteStudentsAdvanced.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:33p
//Created in $/LeapCC/Templates/PromoteStudentsAdvanced
//file added for new interface of session end activities




?>