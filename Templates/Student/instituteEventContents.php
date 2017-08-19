<?php 
//it contain the template of event comments on student activities 
//
// Author :Jaineesh
// Created on : 02-09-2008
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
								</td></tr><tr>
<!--	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  
    <tr>
        <td valign="top"> -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title">Events Detail :</td>
						</tr>
                    </table>
                </td>
             </tr> 
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results"></div>           
             </td>
          </tr>
          
   <!--       </table> -->
        </td>
    </tr>
    
    </table>

<?php floatingDiv_Start('ViewEvents','Event Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewEvents" action="" method="post"> 
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
    <td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerDescription" style="overflow:auto; width:580px; height:20px" ></div>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="longDescription" style="overflow:auto; width:580px; height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
	
<?php
//$History: instituteEventContents.php $
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/04/09    Time: 5:18p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1036
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
//User: Dipanjan     Date: 8/21/09    Time: 1:14p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved allignment issue 
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates/Student
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/13/09    Time: 10:46a
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1037
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/12/09    Time: 5:18p
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: fixed issue 1036, 1030
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/09    Time: 11:17a
//Updated in $/LeapCC/Templates/Student
//Gurkeerat: resolved issue 1025,1028
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:38a
//Updated in $/Leap/Source/Templates/Student
//remove unsortable class from sr. no.
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/21/08   Time: 3:21p
//Updated in $/Leap/Source/Templates/Student
//modified in size of div
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:09p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//remove the html tags through strip_tags function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/18/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/Student
//modified for institute event
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/12/08    Time: 7:16p
//Updated in $/Leap/Source/Templates/Student
//modification
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/12/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Student
//bug fixed
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:31p
//Created in $/Leap/Source/Templates/Student
//to show the template of Institute Event
//
//*****************  Version 4  *****************
//User: Administrator Date: 9/01/08    Time: 1:27p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/12/08    Time: 7:37p
//Updated in $/Leap/Source/Templates/Student
//modified in template
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/23/08    Time: 7:41p
//Updated in $/Leap/Source/Templates/Student
//contain the teacher comments
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/23/08    Time: 10:14a
//Created in $/Leap/Source/Templates/Student
//contain header, footer, menu and templates
//


?>
