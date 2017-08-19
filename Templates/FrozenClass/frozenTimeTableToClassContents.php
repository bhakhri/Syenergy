<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to timetable to class.
//
// Author : Jainesh
// Created on : (30.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
    <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
							<table width="280" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows"><nobr><b>Select Time Table&nbsp;:&nbsp;</b></nobr></td>
								<td class="padding">
                                <select style="width:250px" size="1" class="inputbox1" name="labelId" id="labelId" onChange="clearText()">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
								?>
								</select></td>
								 
								<td  align="right" style="padding-left:15px">
								<input type="hidden" name="listSubject" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getClasses(); return false;"/></td>
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
								<td class="content_title">Freeze/Backup Data : </td>
							</tr>
							</table>
						</td>
					</tr>
					 <tr style="display:none" id="showData">
						<td class="contenttab_row" valign="top" colspan="2"><div id="results">  
						 </div><div id="txtReason" class="contenttab_internal_rows"><b>Reason</b><?php echo REQUIRED_FIELD;?><b>:&nbsp;</b><textarea class="inputbox" name="reason" id="reason" cols="40" rows="5" style="vertical-align:top;" maxlength="250" onkeyup="return ismaxlength(this)"></textarea></div></td>
					 </tr>
					 
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="center" width="55%">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/freeze.gif" value="freeze" onclick="return validateAddForm();return false;" />
						<input type="image" name="imageField1" src="<?php echo IMG_HTTP_PATH;?>/Unfreeze.gif" value="unfreeze" onclick="return validateUnfreezeForm();return false;" />
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
<?php 
// $History: frozenTimeTableToClassContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/21/10    Time: 1:59p
//Updated in $/LeapCC/Templates/FrozenClass
//fixed bug nos. 0002672, 0002660, 0002657, 0002656, 0002658, 0002659,
//0002661, 0002662
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FrozenClass
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/FrozenClass
//change breadcrumb & put department in employee
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:51p
//Created in $/LeapCC/Templates/FrozenClass
//get template of frozen class
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/09    Time: 12:11p
//Created in $/Leap/Source/Templates/FrozenClass
//new content file to show frozen class template
//
?>