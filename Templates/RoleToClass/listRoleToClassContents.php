<?php
//-------------------------------------------------------
// Purpose: to design the layout for role to Class.
//
// Author : Jaineesh
// Created on : (22.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
				<form action="" method="POST" name="listForm" id="listForm">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<table width="65%" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows"><nobr><b>Role Name</b></nobr></td>
								<td class="padding"><b>:</b>&nbsp;<select size="1" class="inputbox" name="roleId" id="roleId" onBlur="getTeacherData();clearText();">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getRoleData('',"WHERE roleId >5");
								?>
								</select></td>
								<td class="contenttab_internal_rows"><nobr><b>Users</b></nobr></td>
								<td class="padding"><b>:</b>&nbsp;<select size="1" class="inputbox" name="teacher" id="teacher" onChange="clearText();" >
								<option value="">Select</option>
								<?php
								  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  //echo HtmlFunctions::getInstance()->getEmployeeData(''," AND isTeaching=1 AND ");
								?>
								</select>
								</td>
								
								<td  align="right" style="padding-right:5px">
								<input type="hidden" name="listSubject" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/Assign_Privileges.gif" onClick="getClasses(); return false;"/></td>
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
								<td class="content_title">Assign Privileges to Classes : </td>
								<td align="right" id = 'saveDiv2' style='display:none;' width="20%" >
										<input type="image" valign="top" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm();return false;" />&nbsp;<input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/ViewPrivileges.gif" onClick="printReport();return false;" title="View Privileges" /><!--<a href="#" onClick="printReport();" title="Print" class="inputbox" style="vertical-align:top">View Privileges</a>-->

								</td>
							</tr>
							</table>
						</td>
					</tr>
					 <tr style="display:none" id="showData">
						<td class="contenttab_row" valign="top" colspan="2"><div id="scroll" style="OVERFLOW: auto; HEIGHT:294px; TEXT-ALIGN: justify;padding-right:10px" class="scroll"><div id="results">  
						 </div> </div></td>
					 </tr>
					 <tr>
						<td height="10" colspan="2"></td>
					 </tr>
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="right">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm();return false;" />&nbsp;<input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/ViewPrivileges.gif" onClick="printReport();return false;" title="View Privileges" /></td>
						<td colspan='1' align='right' id = 'saveDiv' style='display:none;'><!--<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCourseToClassCSV();return false;"/>--></td>

						<!--<td colspan='1' align='right' valign="middle"> 
						<a href="#" onclick="printReport();"><img src="<?php echo IMG_HTTP_PATH;?>/print.gif" name="print" value="Print" border="0"/></a>&nbsp; </td>-->
					</tr>
				</table>
			</form>	
        </td>
    </tr>
    </table>
    </td>
    </tr>
 </table>

<?php 
// $History: listRoleToClassContents.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/RoleToClass
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/03/09   Time: 10:09a
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug no.0001679
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/03/09   Time: 9:43a
//Updated in $/LeapCC/Templates/RoleToClass
//fixed bug nos.0001679, 0001678, 0001677, 0001676, 0001675
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:04p
//Created in $/LeapCC/Templates/RoleToClass
//new template file to assigen privilleage to role
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/13/09    Time: 10:36a
//Updated in $/LeapCC/Templates/SubjectToClass
//Gurkeerat: resolved issue 1059
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/11/09    Time: 11:52a
//Updated in $/LeapCC/Templates/SubjectToClass
//0001009: Associate Subject to Class (Admin) > Print window Caption
//should be “Subject to Class Report Print” as clicked on “Print” button
//0001010: Associate Subject to Class (Admin) > Provide Save button on
//the right top of the grid. 
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:13a
//Updated in $/LeapCC/Templates/SubjectToClass
//Fixed bug no 984,982
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 10:23a
//Updated in $/LeapCC/Templates/SubjectToClass
//added required field and centralized message
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Templates/SubjectToClass
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SubjectToClass
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated formatting and added comments
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/21/08    Time: 11:15a
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the formatting
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:34p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated formatting
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/18/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated with new buttons and print reports
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/14/08    Time: 2:10p
//Updated in $/Leap/Source/Templates/SubjectToClass
//added print report
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the formatting and other issues
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:02p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated the functionality to map subject with class.
//made ajax based and removed study period and batch from search
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 3:39p
//Updated in $/Leap/Source/Templates/SubjectToClass
//placed submit button outside the result div
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/08    Time: 12:41p
//Updated in $/Leap/Source/Templates/SubjectToClass
//optimize the query
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/24/08    Time: 3:18p
//Updated in $/Leap/Source/Templates/SubjectToClass
//updated file
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:55p
//Created in $/Leap/Source/Templates/SubjectToClass
//intial checkin
?>