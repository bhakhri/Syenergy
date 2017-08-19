<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
$queryString =  $_SERVER['QUERY_STRING'];
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
									<!--<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>-->
								</td>
         		   </tr>
           
             <tr>
                <td class="contenttab_row" valign="top"><div id="dhtmlgoodies_tabView1">
			
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
					<table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                            <td><div id="LeaveTypeResultDiv"></div></td>
                        </tr>
						 
                      </table>                 
                   </div>
				    
				   </td>
				   </tr>
          </table>
        </td>
    </tr>
    </table>
   
<!--Start Add/Edit Div-->
<?php floatingDiv_Start('StudentTeacherActionDiv',''); ?>

<form name="StudentTeacher" id="StudentTeacher" style="display:inline"> 
<input type="hidden" name="attachmentFile" id="attachmentFile" value="" />
<table width="100%" border="0" cellspacing="2" cellpadding="0" class="border">
	<tr id="replyMessage">
		<td class="contenttab_internal_rows" width="7%">&nbsp;<nobr><strong>Sent By&nbsp;</strong></nobr></td>
		<td  valign="middle" class="contenttab_internal_rows" width="1%"><nobr><strong>:</strong></nobr></td>
		<td><div id='senderName'></div></td>
	</tr>
	<tr id="replyMessage2">
		<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Sent On</strong></nobr></td>
		<td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
		<td><div id='senderDate'></div></td>
	</tr>
	<tr id="replyMessage2">
		<td class="contenttab_internal_rows">&nbsp;<nobr><strong>Subject</strong></nobr></td>
		<td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
		<td><div id='senderSubject'></div></td>
	</tr> 
	<tr id="replyMessage2">
		<td class="contenttab_internal_rows" valign="top">&nbsp;<nobr><strong>Suggestion</strong></nobr></td>
		<td class="contenttab_internal_rows" valign="top"><nobr><strong>:</strong></nobr></td>
		<td><div id="noticeSubject" style="overflow:auto; width:430px; height:100px"><div id='senderSuggestion'></div></div></td>
	</tr> 
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<!--End Add Div-->
<?php
// $History: suggestionContents.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Index
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:45a
//Created in $/LeapCC/Templates/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/02/09    Time: 1:04p
//Created in $/SnS/Templates/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/11/09    Time: 2:55p
//Created in $/Leap/Source/Templates/ScIndex
//Intial checkin
?>