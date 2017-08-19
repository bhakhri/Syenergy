<?php 
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR EMPLOYEE LISTING 
//
//
// Author :Jaineesh
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddEmployee','',850,595,150,20);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddEmployee','Add Employee'); ?>
<form name="addEmployee" method="post" action="" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td width="18%" class="contenttab_internal_rows"><nobr><b>&nbsp;User Name</b></nobr></td>
						<td width="32%" class="padding">:&nbsp;<input type="text" id="userName" name="userName" class="inputbox" maxlength="50" ></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;User Password</b></nobr></td>
						<td class="padding">:&nbsp;<input type="password" id="userPassword" name="userPassword" class="inputbox" maxlength="50" value="********"></td>			
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Role Name</b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="roleName" id="roleName">
						<option value="">Select</option>
							<?php
								$a = 2;
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getRoleData(2, "WHERE roleId NOT IN (1,3,4)");
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="title" id="title">
						<option value="">Select</option>
							<?php
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getTitleData(1);
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Last Name</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="lastName" name="lastName" class="inputbox" maxlength="50"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;First Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeName" name="employeeName" class="inputbox" maxlength="50"></td>
					</tr>

					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Middle Name</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="middleName" name="middleName" class="inputbox" maxlength="50"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Employee Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeCode" name="employeeCode" class="inputbox" maxlength="10" value="1"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Employee Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeAbbreviation" name="employeeAbbreviation" class="inputbox" maxlength="10"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Email</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="email" name="email" class="inputbox" maxlength="80"></td>
					</tr>
					<!--added by abhiraj for payroll -->
				  <!--added by abhiraj for payroll ends -->
				</table>
			</td>

			<td valign="top">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="designation" id="designation">
							<option value="">Select</option>
							<?php
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getDesignationData($REQUEST_DATA['designation']==''? $employeeRecordArray[0]['designationId'] : $REQUEST_DATA['designation'] );
							?></select>						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Gender</b></nobr></td>
						<td class="padding">:&nbsp;<input type="radio" name="gender" value="M" checked="checked">Male
							<input type="radio" name="gender" value="F"/>Female						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Branch<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="branch" id="branch" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branch']==''? $employeeRecordArray[0]['branchId'] : $REQUEST_DATA['branch'] );
							?>
							</select>						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Teaching Employee </b></nobr></td>
						<td class="padding">:&nbsp;<input type="radio" name="isTeaching" value="1" checked="checked">Yes
						<input type="radio" name="isTeaching" value="0"/>No						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Department</b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="department" id="department" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getDepartmentData(6);
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Contact Number</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="contactNumber" name="contactNumber" class="inputbox" maxlength="15"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Mobile Number </b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="mobileNumber" name="mobileNumber" class="inputbox" maxlength="15"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="address1" name="address1" class="inputbox" maxlength="255"></td>			
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="address2" name="address2" class="inputbox" maxlength="255"></td>			
					</tr>
					
					<!--added by abhiraj for payroll -->
				  <!--added by abhiraj for payroll ends-->
				</table>
			</td>
		</tr>
			
			<!--
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;Qualification</b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="qualification" name="qualification" class="inputbox" value="" tabindex="14" maxlength="100"></td>-->

		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td colspan="2" width="100%">
				<table border="0" width="100%">
					<tr>
						<td class="contenttab_internal_rows" valign="top" width="15%" nowrap><b>Teaching Institutes :</b></td>
						<td width="100%">
							<div id="scroll1" style="overflow:auto;HEIGHT:90px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr class="field1_heading">
										<td height="25" class="padding">Institute Name</td>
										<td class="padding" align="center">Default </td>
									</tr>
									<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										global $sessionHandler;
										$instituteId = $sessionHandler->getSessionVariable('InstituteId');
										echo HtmlFunctions::getInstance()->getEmployeeDefaultInstitute($instituteId);
									?>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;">
			<input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddEmployee');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;">
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		</td>
	  </tr>
   </table>
   <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditEmployeeDiv','Edit Employee'); ?>
<form name="editEmployee" method="post" action="" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="employeeId" id="employeeId" />
		<input type="hidden" name="userId" id="userId" />
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td width="18%" class="contenttab_internal_rows"><nobr><b>&nbsp;User Name</b></nobr></td>
						<td width="32%" class="padding">:&nbsp;<input type="text" id="userName" name="userName" class="inputbox" maxlength="50" ></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;User Password</b></nobr></td>
						<td class="padding">:&nbsp;<input type="password" id="userPassword" name="userPassword" class="inputbox" maxlength="50" ></td>			
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Role Name</b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="roleName" id="roleName">
						<option value="">Select</option>
							<?php
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roleName']==''? $employeeRecordArray[0]['roleId'] : $REQUEST_DATA['roleName'], "WHERE roleId NOT IN (1,3,4)");
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="title" id="title">
						<option value="">Select</option>
							<?php
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getTitleData();
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Last Name</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="lastName" name="lastName" class="inputbox" maxlength="50"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;First Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeName" name="employeeName" class="inputbox" maxlength="50"></td>
					</tr>

					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Middle Name</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="middleName" name="middleName" class="inputbox" maxlength="50"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Employee Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeCode" name="employeeCode" class="inputbox" maxlength="10"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Employee Abbr.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="employeeAbbreviation" name="employeeAbbreviation" class="inputbox" maxlength="10"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Email</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="emailEdit" name="emailEdit" class="inputbox" maxlength="80"></td>
					</tr>
					<!--added by abhiraj for payroll -->
				  <!--added by abhiraj for payroll ends-->
				</table>
			</td>

			<td valign="top">
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Designation<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="designation" id="designation">
							<option value="">Select</option>
							<?php
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->getDesignationData($REQUEST_DATA['designation']==''? $employeeRecordArray[0]['designationId'] : $REQUEST_DATA['designation'] );
							?></select>						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Gender</b></nobr></td>
						<td class="padding">:&nbsp;<input type="radio" name="gender" value="M" checked="checked">Male
							<input type="radio" name="gender" value="F"/>Female
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Branch<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="branch" id="branch" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getBranchData($REQUEST_DATA['branch']==''? $employeeRecordArray[0]['branchId'] : $REQUEST_DATA['branch'] );
							?>
							</select>						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Teaching Employee </b></nobr></td>
						<td class="padding">:&nbsp;<input type="radio" name="isTeaching" value="1" checked="checked">Yes
						<input type="radio" name="isTeaching" value="0"/>No						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>&nbsp;Department</b></nobr></td>
						<td class="padding">:&nbsp;<select size="1" class="selectfield" name="department" id="department" >
						<option value="">Select</option>
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getDepartmentData();
							?>
							</select>						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Contact Number</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="editContactNumber" name="editContactNumber" class="inputbox" maxlength="15"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Mobile Number </b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="editMobileNumber" name="editMobileNumber" class="inputbox" maxlength="15"></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="address1" name="address1" class="inputbox" maxlength="255"></td>			
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
						<td class="padding">:&nbsp;<input type="text" id="address2" name="address2" class="inputbox" maxlength="255"></td>			
					</tr>
				</table>
			</td>
		</tr>
			
			<!--
			<td class="contenttab_internal_rows"><nobr><b>&nbsp;Qualification</b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" id="qualification" name="qualification" class="inputbox" value="" tabindex="14" maxlength="100"></td>-->

		<tr>
			<td colspan="2" width="100%">
				<table border="0" width="100%">
					<tr>
						<td class="contenttab_internal_rows" valign="top" width="15%" nowrap><b>Teaching Institutes :</b></td>
						<td width="100%">
							<div id="scroll2" style="overflow:auto;HEIGHT:90px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr class="field1_heading">
										<td height="25" class="padding">Institute Name</td>
										<td class="padding" align="center">Default </td>
									</tr>
									<?php
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										global $sessionHandler;
										$instituteId = $sessionHandler->getSessionVariable('InstituteId');
										echo HtmlFunctions::getInstance()->getEditEmployeeDefaultInstitute($instituteId);
									?>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;">
			<input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEmployeeDiv');return false;">
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
  </table>
  <iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>

    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<?php 
// $History: listEmployeeContents.php $
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 4/15/10    Time: 6:56p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0003247, 0003250, 0003174
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 4/09/10    Time: 10:30a
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos.0003243,0003248
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 4/06/10    Time: 2:31p
//Updated in $/LeapCC/Templates/Employee
//make the size of image small during populate of images
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 3/29/10    Time: 5:18p
//Updated in $/LeapCC/Templates/Employee
//modification in files according to add new fileds, show exp. &
//qualification
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Templates/Employee
//changes for gap analysis in employee master
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 2/17/10    Time: 12:35p
//Updated in $/LeapCC/Templates/Employee
//provide the facility to change institute of an employee
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 2/15/10    Time: 7:19p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0002869, 0002870, 0002868, 0002867, 0002865, 0002864,
//0002866, 0002871
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:30p
//Updated in $/LeapCC/Templates/Employee
//fixed bug no.0002326
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Templates/Employee
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 10/03/09   Time: 12:23p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos.0001664, 0001665, 0001666
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/Employee
//change breadcrumb & put department in employee
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 9/01/09    Time: 2:08p
//Updated in $/LeapCC/Templates/Employee
//Modification in code while saving & edit record in IE browser.
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/21/09    Time: 11:58a
//Updated in $/LeapCC/Templates/Employee
//not show administrator in edit
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates/Employee
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Templates/Employee
//give print & export to excel facility
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/01/09    Time: 4:06p
//Updated in $/LeapCC/Templates/Employee
//fixed the length of fields
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 6/30/09    Time: 5:29p
//Updated in $/LeapCC/Templates/Employee
//make some enhancement in employee and in room print show capacity also
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/30/09    Time: 2:20p
//Updated in $/LeapCC/Templates/Employee
//make select all & none teach in institutes and some correction 
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Templates/Employee
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Templates/Employee
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Templates/Employee
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Templates/Employee
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/13/09    Time: 12:48p
//Updated in $/LeapCC/Templates/Employee
//show new field in list emp. abbr.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:58p
//Updated in $/LeapCC/Templates/Employee
//modified for Teaching in institute field
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/19/08   Time: 3:30p
//Updated in $/LeapCC/Templates/Employee
//modified for employee can teach in 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Employee
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 11/19/08   Time: 5:30p
//Updated in $/Leap/Source/Templates/Employee
//add new field status (active or deactive)
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 11/05/08   Time: 3:20p
//Updated in $/Leap/Source/Templates/Employee
//modification in the length of fields
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:53p
//Updated in $/Leap/Source/Templates/Employee
//embedded print option
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 9/29/08    Time: 3:37p
//Updated in $/Leap/Source/Templates/Employee
//modified in query
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 9/25/08    Time: 6:08p
//Updated in $/Leap/Source/Templates/Employee
//fixed bug
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:39p
//Updated in $/Leap/Source/Templates/Employee
//fixed bug
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 9/01/08    Time: 6:40p
//Updated in $/Leap/Source/Templates/Employee
//modification in template
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:06p
//Updated in $/Leap/Source/Templates/Employee
//modified in indentation
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/27/08    Time: 11:33a
//Updated in $/Leap/Source/Templates/Employee
//modified in html
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/25/08    Time: 12:56p
//Updated in $/Leap/Source/Templates/Employee
//modified in edit window function for edit floating div
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 8/23/08    Time: 12:46p
//Updated in $/Leap/Source/Templates/Employee
//modified in validation for dates
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 8/22/08    Time: 11:49a
//Updated in $/Leap/Source/Templates/Employee
//modified in floating div
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/19/08    Time: 1:46p
//Updated in $/Leap/Source/Templates/Employee
//changed in search button
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/14/08    Time: 7:19p
//Updated in $/Leap/Source/Templates/Employee
//remove width & height of cancel button
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/11/08    Time: 7:51p
//Updated in $/Leap/Source/Templates/Employee
//modified in bread crump
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/09/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Employee
//modified in Employee - bug removed
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/01/08    Time: 2:27p
//Updated in $/Leap/Source/Templates/Employee
//modified in screen height & width
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/25/08    Time: 6:35p
//Updated in $/Leap/Source/Templates/Employee
//modify in role function
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/24/08    Time: 1:39p
//Updated in $/Leap/Source/Templates/Employee
//disable text box of user name
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/19/08    Time: 6:44p
//Updated in $/Leap/Source/Templates/Employee
//change alert with messagebox
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/16/08    Time: 4:41p
//Updated in $/Leap/Source/Templates/Employee
//modification in validation or check for insertion data
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:52p
//Updated in $/Leap/Source/Templates/Employee
//add four new date fields
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/12/08    Time: 3:24p
//Updated in $/Leap/Source/Templates/Employee
//modified in template & functionality
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:29p
//Updated in $/Leap/Source/Templates/Employee
//modification in employee in templates & functions
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/10/08    Time: 3:09p
//Updated in $/Leap/Source/Templates/Employee
//modified in edit and validation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:29p
//Updated in $/Leap/Source/Templates/Employee
//modification in coding
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:09a
//Updated in $/Leap/Source/Templates/Employee
//modified for role name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:57p
//Created in $/Leap/Source/Templates/Employee
//List of employee
?>