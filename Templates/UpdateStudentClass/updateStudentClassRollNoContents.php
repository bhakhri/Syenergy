<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to timetable to class.
//
// Author : Jaineesh
// Created on : (06.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>  
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
				<form action="" method="POST" name="updateStudentForm" id="updateStudentForm">
				 <table width="100%" border="0" cellspacing="5px" cellpadding="2px">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<table width="10%" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10" colspan="10"></td>
							</tr>
							<tr>
                              <td class="contenttab_internal_rows" colspan="10"> 
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" align="left">
                                    <tr>
                                        <td class="contenttab_internal_rows"><nobr><b>Show</b></nobr></td> 
                                        <td class="contenttab_internal_rows"><nobr>
                                    <input type='radio' checked="checked" value="1" id='chkClasses' name='chkClasses' class="inputbox1" /></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows"><nobr>Active Classes</nobr></td>
                                        <td class="contenttab_internal_rows"><nobr>
                                    <input type='radio' value="0" id='chkClasses' name='chkClasses' class="inputbox1" /></nobr>
                                        </td>
                                        <td class="contenttab_internal_rows">
                                            <nobr>Active Classes with matching subjects</nobr>
                                        </td>
                                    </tr>
                                  </table>  
                                </td>  
                             </tr>
                             <tr>   
                                <td class="contenttab_internal_rows"><nobr><b>Select Criteria</b></nobr></td>
								<td class="contenttab_internal_rows" align="left">
                                <select size="1" class="selectfield" name="criteria" id="criteria" style="width:160px;" onChange="getCriteria();">
                                    <option value="1">Roll No.</option> 
                                    <option value="2">University Roll No.</option>
                                    <option value="3">Registration No.&nbsp;</option>
									</select></td>
								<td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;<span id="criteriaHeading"></span></b></nobr></td>
								<td class="padding"><input type="text" id="rollNo" autocomplete='off' name="rollNo" class="inputbox" maxlength="20">
								<input type="hidden" id="classId" name="classId">
								<input type="hidden" id="studentId" name="studentId">
								<input type="hidden" id="userId" name="userId">
								</td>
                                <td  align="right" style="padding-left:15px">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateForm();return false;"/></td>
							</tr>
                            <tr>
                                <td height="10" colspan="10"></td>
                            </tr>
							</table>
					    </td>
					</tr>
					<tr style="display:none" id="showTitle">
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Update Student Class/Roll No. : </td>
							</tr>
							</table>
						</td>
					</tr>
					 <tr style="display:none" id="showData">
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
								<tr>
									<td class="contenttab_internal_rows1" colspan="2"><nobr><b>&nbsp;Update Student Class </b></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows1" width="10%"><nobr><b>&nbsp;Current Class : </b></nobr></td>
									<td width="90%" class="padding">
										<input type="text" id="currentClass" name="currentClass" disabled="disabled" size="40px" class="inputbox1"></label>
									</td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows1"><nobr><b>&nbsp;New Class : </b></nobr></td>
									<td class="padding"><select size="1" class="selectfield" name="newClass" id="newClass" style="width:260px;">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getClassData();
									?>
									</select></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows1" colspan="2"><nobr><b>&nbsp;Update Student Roll No.</b></nobr></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows1"><nobr><b>&nbsp;New Roll No. :</b></nobr></td>
									<td class="padding"><input type="text" id="newRollNo" name="newRollNo" class="inputbox" maxlength="30"></td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows1" colspan="2">&nbsp;Make new roll no. as username<input type="checkbox" id="userName" name="userName" checked="checked" value="1"></td>
								</tr>

								<tr>
									<td class="contenttab_internal_rows1"><nobr><b>&nbsp;Reason:</b></nobr></td>
									<td class="padding" colspan="2"><textarea name="reason" id="reason" cols="22" rows="3"></textarea></td>
								</tr>
								<tr>
									<td height="5px" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="2" >&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm();return false;" />
									</td>
								</tr>
								</table>
							</td>
					 </tr>
					 <!--
					 <tr>
						<td height="10" colspan="2"></td>
					 </tr>
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="right" width="55%">
						<input type="hidden" name="submitSubject" value="1">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" /></td>
					</tr>
					-->
				</table>
			</form>	
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<?php 
// $History: updateStudentClassRollNoContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/04/10    Time: 11:50
//Updated in $/LeapCC/Templates/UpdateStudentClass
//Done bug fixing.
//Fixed bugs---
//0003231,0003230,0003229,0003228,0003227,0003225,0003224,0003156
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/13/09   Time: 11:50a
//Updated in $/LeapCC/Templates/UpdateStudentClass
//fixed issue to show current and future classes
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/UpdateStudentClass
//added code for autosuggest functionality
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/14/09    Time: 6:37p
//Updated in $/LeapCC/Templates/UpdateStudentClass
//modified in queries, delete record student_groups,
//student_optional_subject
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/14/09    Time: 5:36p
//Created in $/LeapCC/Templates/UpdateStudentClass
//new file copy from sc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/25/09    Time: 7:37p
//Updated in $/Leap/Source/Templates/ScUpdateStudentClass
//put new field reason in update student class/roll no
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/25/09    Time: 6:35p
//Updated in $/Leap/Source/Templates/UpdateStudentClass
//increased roll no. length
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/25/09    Time: 3:37p
//Created in $/Leap/Source/Templates/UpdateStudentClass
//new template of update student class roll no
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 5/25/09    Time: 3:34p
//Updated in $/Leap/Source/Templates/UpdateStudentClass
//done changes in design to make it look more symmetrical.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/25/09    Time: 1:00p
//Updated in $/Leap/Source/Templates/UpdateStudentClass
//put messages related to update student class roll no module and
//modified in messages
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/25/09    Time: 12:27p
//Updated in $/Leap/Source/Templates/UpdateStudentClass
//to show outer border
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/25/09    Time: 12:20p
//Created in $/Leap/Source/Templates/UpdateStudentClass
//new template file for update student class/rollno
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/08/09    Time: 4:31p
//Updated in $/Leap/Source/Templates/TimeTable
//remove 1 from the note
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/07/09    Time: 3:05p
//Updated in $/Leap/Source/Templates/TimeTable
//modified in heading
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/07/09    Time: 3:04p
//Created in $/Leap/Source/Templates/TimeTable
//new template file for attendance marks
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/16/09    Time: 1:31p
//Updated in $/Leap/Source/Templates/TimeTable
//Updated formatting of template file
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/14/08   Time: 12:31p
//Updated in $/Leap/Source/Templates/TimeTable
//removed the condition of active from getTimeTableLabelData function to
//show all labels
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/30/08    Time: 6:13p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin
?>