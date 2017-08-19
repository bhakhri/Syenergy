<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (22.07.2008)
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
							
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <!--   <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0"> -->
			 <tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td></tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">  
                 </div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table> 
    </td>
    </tr>
    </table>
<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event Description','',' '); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <form name="EventForm" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Event: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerNotice" style="overflow:auto; width:580px; height:20px" ></div>
    </td>
</tr>
 <tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
    <td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerDescription" style="overflow:auto; width:580px; height:50px" ></div>
    </td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="longDescription" style="overflow:auto; width:580px; height:150px" ></div>
    </td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<?php
// $History: listInstituteEventContents.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 4/09/09    Time: 11:48
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//corrected div's height in short description
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/31/09    Time: 11:29a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue 1352
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:54a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue regardind issue nos. 1226,1227
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/26/09    Time: 4:01p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: fixed issue 1226
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/09    Time: 1:09p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved allignment issue
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/20/09    Time: 1:27p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue 1083
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/09    Time: 12:55p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Gurkeerat: resolved issue 1082,1078,1081,1077,1079,1080
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/30/08    Time: 12:26p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:14p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:55p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>