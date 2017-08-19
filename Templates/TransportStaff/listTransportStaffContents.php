<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR BUSSTOP LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddTransportStaff',360,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddTransportStaff','Add Transport Staff'); ?>
<form name="AddTransportStaff" id="AddTransportStaffForm" enctype="multipart/form-data" method="post" onsubmit="return false;" nowrap >  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
						<td width="30%" class="padding">:&nbsp;
						 <input type="text" name="staffName" id="staffName" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'staffName',event);" /></nobr>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows" valign="top" ><nobr>&nbsp;<strong>Address</strong></nobr></td>
						<td class="padding">:&nbsp;
							<textarea id="address" name="address" cols="22" rows="3" class="inputbox" style="vertical-align:top" ></textarea>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Date of Birth</b></nobr></td>
						<td class="padding">:&nbsp;
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->datePicker('dob','');
						   ?></nobr>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;
						  <input type="text" name="staffCode" id="staffCode" class="inputbox" onkeydown="return sendKeys(1,'staffCode',event);" />
						</nobr></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Joining Date</b></nobr></td>
						<td class="padding">:&nbsp;
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->datePicker('join1',date('Y-m-d'));
						   ?>
						</nobr></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Blood Group</b></nobr></td>
						<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="bloodGroup" id="bloodGroup">
						<option value="0">Select</option>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getBloodGroupData();
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;
						 <select name="staffType" id="staffType" class="selectfield">
						 <option value="">Select</option>
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->getTransportStaffTypeData();
						   ?>
						 </select></nobr>
						</td>
					</tr>
					</table>
				</td>
				<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0">
					   <tr>
							<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Leaving Date</b></nobr></td>
							<td width="30%" class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('leav1');
							   ?></nobr>
							</td>
						</tr>
						<tr> 
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Verification Done</b></nobr></td>
							<td class="padding" >:&nbsp;
							  <input type="radio" name="verificationDone" id="verificationDone1" checked="checked"/>Yes&nbsp;&nbsp;
							  <input type="radio" name="verificationDone" id="verificationDone1"/>No
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>License No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
							<td class="padding" >:&nbsp;
							<input type="text" name="dlNo" id="dlNo" class="inputbox" maxlength="15" onkeydown="return sendKeys(1,'dlNo',event);"/></nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Issue Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('issueDate',date('Y-m-d'));
							   ?>
							</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Licensing Authority<?php echo REQUIRED_FIELD; ?></b></nobr></td>
							<td class="padding">:&nbsp;
							   <input type="text" name="dlAuthority" id="dlAuthority" class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'dlAuthority',event);" />   
							</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Expiry Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('dlExp1',date('Y-m-d'));
							   ?>
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Medical Examination Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('medExaminationDate','');
							   ?>
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Image</b></nobr></td>
							<td class="padding"><b>:</b>&nbsp;&nbsp;<input type="file" id="staffPhoto" name="staffPhoto" class="inputbox"> </td>
						</tr>
						<tr>
						
							<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Driving License Image</b></nobr></td>
							<td class="padding"><b>:</b>&nbsp;&nbsp;<input type="file" id="drivingLicencePhoto" name="drivingLicencePhoto" class="inputbox"></td>
						</tr>
					</table>
				</td>
				<tr>
					<td height="5px" colspan="3"></td>
				</tr>
				<tr>
					<td align="center" style="padding-right:10px" colspan="5">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
					  <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTransportStaff');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;"/>
						</td>
				</tr>
				<tr>
					<td height="5px" colspan="3"></td>
				</tr>
			</tr>
	</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditTransportStaff','Edit Transport Staff'); ?>
<form name="EditTransportStaff" id="EditTransportStaffForm" enctype="multipart/form-data" method="post" onsubmit="return false;">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="staffId" id="staffId" value="" />  
    <tr>
        <td valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="20%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Name<?php echo REQUIRED_FIELD; ?> </b></nobr></td>
						<td width="30%" class="padding">:&nbsp;
						 <input type="text" name="staffName" id="staffName" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'staffName',event);" /></nobr>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows" valign="top" ><nobr>&nbsp;<strong>Address</strong></nobr></td>
						<td class="padding">:&nbsp;
							<textarea id="address" name="address" cols="22" rows="3" class="inputbox" style="vertical-align:top" ></textarea>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Date of Birth</b></nobr></td>
						<td class="padding">:&nbsp;
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->datePicker('dob1','');
						   ?></nobr>
						</td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Code<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;
						  <input type="text" name="staffCode" id="staffCode" class="inputbox" onkeydown="return sendKeys(1,'staffCode',event);" />
						</nobr></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Joining Date</b></nobr></td>
						<td class="padding">:&nbsp;
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->datePicker('join2',date('Y-m-d'));
						   ?>
						</nobr></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Blood Group</b></nobr></td>
						<td class="padding">:&nbsp;&nbsp;<select size="1" class="inputbox1" name="bloodGroup" id="bloodGroup">
						<option value="0">Select</option>
						<?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getBloodGroupData();
							?>
						</select></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<b>Staff Type<?php echo REQUIRED_FIELD; ?></b></nobr></td>
						<td class="padding">:&nbsp;
						 <select name="staffType" id="staffType" class="selectfield">
						 <option value="">Select</option>
						   <?php 
							 require_once(BL_PATH.'/HtmlFunctions.inc.php');
							 echo HtmlFunctions::getInstance()->getTransportStaffTypeData();
						   ?>
						 </select></nobr>
						</td>
					</tr>
					</table>
				</td>
				<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0">
					   <tr>
							<td class="contenttab_internal_rows"><nobr><b>Leaving Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('leav2');
							   ?></nobr>
							</td>
						</tr>
						<tr> 
							<td class="contenttab_internal_rows"><nobr><b>Verification Done</b></nobr></td>
							<td class="padding" >:&nbsp;
							  <input type="radio" name="verificationEditDone" id="verificationEditDone1" checked="checked"/>Yes&nbsp;&nbsp;
							  <input type="radio" name="verificationEditDone" id="verificationEditDone1"/>No
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>License No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
							<td class="padding" >:&nbsp;
							<input type="text" name="dlNo" id="dlNo" class="inputbox" maxlength="15" onkeydown="return sendKeys(1,'dlNo',event);"/></nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Issue Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('issueDate1',date('Y-m-d'));
							   ?>
							</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Licensing Authority<?php echo REQUIRED_FIELD; ?></b></nobr></td>
							<td class="padding">:&nbsp;
							   <input type="text" name="dlAuthority" id="dlAuthority" class="inputbox" maxlength="50" onkeydown="return sendKeys(1,'dlAuthority',event);" />   
							</nobr>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Expiry Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('dlExp2',date('Y-m-d'));
							   ?>
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Medical Examination Date</b></nobr></td>
							<td class="padding">:&nbsp;
							   <?php 
								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
								 echo HtmlFunctions::getInstance()->datePicker('medExaminationDate1',date('Y-m-d'));
							   ?>
							</nobr></td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><b>Staff Image</b></td>
							<td class="padding">:&nbsp;&nbsp;<input type="file" id="staffPhoto" name="staffPhoto" class="inputbox"></td> 
							<td class="padding" align="left" valign="middle" >&nbsp;
								<div id="imageDisplayDiv" style="display:inline"></div>
							</td>
						</tr>
						<tr>
							<td class="contenttab_internal_rows"><b>Driving License Image</b></td>
							<td class="padding">:&nbsp;&nbsp;<input type="file" id="drivingLicencePhoto" name="drivingLicencePhoto" class="inputbox"></td> 
							<td class="padding" align="left" valign="middle" >&nbsp;
								<div id="imageDLDisplayDiv" style="display:inline"></div>
							</td>
						</tr>
					</table>
				</td>
				<tr>
					<td height="5px" colspan="3"></td>
				</tr>
				<tr>
					<td align="center" style="padding-right:10px" colspan="6">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
					  <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditTransportStaff');return false;"/>
						</td>
				</tr>
				<tr>
					<td height="5px" colspan="3"></td>
				</tr>
			</tr>
	</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<?php
// $History: listTransportStaffContents.php $
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 1/21/10    Time: 4:07p
//Updated in $/Leap/Source/Templates/TransportStaff
//Add new field medical examination date
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug in Fleet management
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug in fleet management
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:33p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug nos. 0002370,0002369,0002365,0002363,0002362,0002361,0002368,
//0002366,0002360,0002359,0002372,0002358,0002357
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 12/24/09   Time: 7:05p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug nos.0002354,0002353,0002351,0002352,0002350,0002347,0002348,0
//002355,0002349
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/TransportStaff
//fixed bug during self testing
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Templates/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Templates/TransportStaff
//add new fields and upload image
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/10/09   Time: 11:43a
//Updated in $/Leap/Source/Templates/TransportStaff
//change in template
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Templates/TransportStaff
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/09/09   Time: 18:11
//Updated in $/Leap/Source/Templates/TransportStuff
//Done bug fixing found in self testing
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:30
//Updated in $/Leap/Source/Templates/TransportStuff
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/08/09    Time: 18:32
//Updated in $/Leap/Source/Templates/TransportStuff
//Done bug fixing.
//bug ids---0000848,0000846,0000849,0000851
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/06/09   Time: 11:15
//Updated in $/Leap/Source/Templates/TransportStuff
//Done bug fixing.
//bug ids---0000063,0000082,0000083,0000085,0000087,0000090,0000092,
//0000095
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/04/09   Time: 12:47
//Updated in $/Leap/Source/Templates/TransportStuff
//Modified bus repair modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:03
//Created in $/Leap/Source/Templates/TransportStuff
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:44
//Updated in $/SnS/Templates/TransportStuff
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:47
//Created in $/SnS/Templates/TransportStuff
//Created module Transport Stuff Master
?>