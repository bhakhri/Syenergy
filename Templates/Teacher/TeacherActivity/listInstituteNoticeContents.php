<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (22.07.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
							
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <!--  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">-->
           
			 <tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td></tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results"></div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table> 
    </td>
    </tr>
    </table> 


<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice Description ','',' '); ?>
<form name="NoticeForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'>
     <div id="noticeSubject" style="overflow:auto; width:630px; height:20px"></div>
</td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Department: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeDepartment" style="width:600px; height:20px"></div></td>
</tr>



<tr>
    <td valign="middle" class="rowheading">&nbsp;<b>Description: &nbsp&nbsp;</b></td>
	
	<td class="rowheading" align="right">
	<span id="editLogoPlace" class="cl" style="display:none;">
	 <img src="<?php echo IMG_HTTP_PATH;?>/download.gif" class="imgLinkRemove" onClick="download1(); return false;" />&nbsp;<b><a onclick="download1(); return false;" > Download Attachment &nbsp&nbsp;</b></a>
	 <input readonly type="hidden" id="downloadFileName" name="downloadFileName" class="inputbox"></td>
	</span>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeText" style="overflow:auto; width:400px; height:200px" ></div></td>
</tr> 

 
 
</table>
</form> 
<?php floatingDiv_End(); ?>    

<?php
// $History: listInstituteNoticeContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 1/09/09    Time: 11:21
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001351,00001353,00001354,00001355,
//00001369,00001370,00001371
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:54a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue regardind issue nos. 1226,1227
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 2:06p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Showing Department Name
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:07p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>