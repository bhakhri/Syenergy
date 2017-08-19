<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to timetable to class.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
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
							<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows"><nobr><b>Select Time Table: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="labelId" id="labelId" onChange="clearText()">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
								?>
								</select></td>
								 
								<td  align="right" style="padding-right:5px">
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
								<td class="content_title">Associate Time Table To Class : </td>
								<td align="right" width="55%">
								<input type="hidden" name="submitSubject" value="1">
								<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" /></td> 
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
						<td align="right" width="55%">
						<input type="hidden" name="submitSubject" value="1">
						<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" /></td>
						 
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
// $History: timeTableToClassContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:51p
//Updated in $/LeapCC/Templates/TimeTable
//modified in breadcrumb
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:35p
//Updated in $/LeapCC/Templates/TimeTable
//change the breadcrumb
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/23/09    Time: 11:47a
//Updated in $/LeapCC/Templates/TimeTable
//fixed issues:  	 0000643: Associate Time Table to Class (Admin) >
//“TimeTable” text should be “Time Table” on “Associate Time Table to
//Class” page. 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
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