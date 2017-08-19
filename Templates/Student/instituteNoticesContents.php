<?php 
//it contain the template of Institute Notices on student activities 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
								</td></tr></table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" >
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<!--<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top">Student Info &nbsp;&raquo;&nbsp; Display Institute Notices </td>
					<td valign="top" align="right">   
					  <form action="" method="" name="searchForm">
						<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />&nbsp;
						<input type="image" name="submit" align="absbottom" src="<?php echo IMG_HTTP_PATH;?>/search.gif" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" />
					  </form>
				</tr> -->
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<!--<tr>
				 <td valign="top" class="content">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border" height="20">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title">Notice Detail :</td>
						 
						</tr>
						</table>
					</td>
				 </tr> -->
				 <tr>
					<td class="contenttab_row" valign="top" ><div id="results"></div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
<?php floatingDiv_Start('ViewNotices','Notice Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewNotices" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Subject: </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerNotice" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Department:</b></td>
</tr>

<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerDepartment" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
	<td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Description:</b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerText" style="overflow:auto; width:580px;height:200px" ></div>
	</td>
</tr>
<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
	
<?php
//$History: instituteNoticesContents.php $
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/04/09    Time: 5:19p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1036
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/27/09    Time: 7:21p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1328
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/27/09    Time: 1:15p
//Updated in $/LeapCC/Templates/Student
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:22a
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved alignment issue regarding issues 1226,1227
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates/Student
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/12/09    Time: 4:49p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0000969,0000965, 0000962, 0000963
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/11/09    Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 964
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/10/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0000966,0000970,0000967
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/31/09    Time: 1:25p
//Updated in $/LeapCC/Templates/Student
//fixed the bugs during self testing
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 11/04/08   Time: 6:21p
//Updated in $/Leap/Source/Templates/Student
//Put the attachment in ajax file also
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:38a
//Updated in $/Leap/Source/Templates/Student
//remove unsortable class from sr. no.
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/22/08   Time: 11:37a
//Updated in $/Leap/Source/Templates/Student
//remove the close button from notice contents
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 10/21/08   Time: 3:21p
//Updated in $/Leap/Source/Templates/Student
//modified in size of div
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//remove the html tags through strip_tags function
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/17/08   Time: 1:47p
//Updated in $/Leap/Source/Templates/Student
//modified for attachment
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/12/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Student
//bug fixed
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/Student
//modify for paging
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/10/08    Time: 7:53p
//Updated in $/Leap/Source/Templates/Student
//put paging
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:19p
//Updated in $/Leap/Source/Templates/Student
//modification in dimming div size
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:06p
//Updated in $/Leap/Source/Templates/Student
//modification for view detail
//
//*****************  Version 3  *****************
//User: Administrator Date: 9/01/08    Time: 1:27p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/02/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Student
//modification in template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:46p
//Created in $/Leap/Source/Templates/Student
//contain the template of institute notice for student
//
//


?>
