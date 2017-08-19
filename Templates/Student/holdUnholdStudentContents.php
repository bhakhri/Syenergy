<?php
//-------------------------------------------------------
// Purpose: to design the layout for Hold/Unhold Student Result.
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Student Setup&nbsp;&raquo;&nbsp;Hold/Unhold Student Result</td>
            </tr>
            </table>
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
							<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10" colspan="4"></td>
							</tr>
							<tr>	
								<td class="contenttab_internal_rows" rowspan="3"><nobr><b>Roll No(s)
								</b></nobr></td>
								<td class="padding" rowspan="3">:&nbsp;</td>
								<td rowspan="3" class="contenttab_internal_rows" style="width:70%;text-align:center" >&nbsp;<textarea name="rollNos" class="inputbox1" id="rollNos" cols="90" rows="7" style="vertical-align:middle;"></textarea><b><br>(Enter Roll Nos. in comma seperated)<b></td>
								<td  align="right" style="padding-right:5px">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/hold_result.gif" onClick="holdResult(1);return false;"/></td>
							</tr>

							<tr>
								<td  align="right" style="padding-right:5px">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/unhold_result.gif" onClick="unHoldResult(2); return false;"/></td>
							</tr>
							</table>
					    </td>
					</tr>
					<tr><td><div id="invalidData" style="display:inline;"></div></td></tr>
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