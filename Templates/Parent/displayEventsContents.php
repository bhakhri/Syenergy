<?php 
//This file creates Html Form output in "displayEvents in parent Module" 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td></tr>
           
             <tr>
                <td class="contenttab_row" valign="top">
                    <div id="results">
                    </div>           
             </td>
          </tr>
          
          </table>
  

<?php floatingDiv_Start('ViewEvents','Event Description','',''); ?>
<form name="viewNotices" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Event: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="titleEvents" style="overflow:auto; width:630px; height:20px" ></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2">&nbsp;<B>Visible From</B>: <span id="startDate" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="endDate" style="height:20px"></span></td>
</tr>
<tr>
<td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerShortDescription" style="overflow:auto; width:580px;" ></div>
    </td>
</tr>
<tr>
<td height="5px"></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Full Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" style="padding-left:3px" ><div id="innerEvents" style="overflow:auto; width:630px;height:200px" ></div></td>
</tr> 
 
<tr>
<td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
    
<?php //$History: displayEventsContents.php $
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/31/09    Time: 12:05p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: Resolved issue 1350
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/27/09    Time: 1:19p
//Updated in $/LeapCC/Templates/Parent
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/26/09    Time: 6:44p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: resolved alignment issue regarding issues 1226, 1227
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Templates/Parent
//formating, validations & conditions updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 8/13/09    Time: 1:05p
//Updated in $/Leap/Source/Templates/Parent
//formatting, validations & Div Base Brief information show 
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/Leap/Source/Templates/Parent
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Templates/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/26/09    Time: 6:47p
//Updated in $/Leap/Source/Templates/Parent
//format, condition, validation updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/03/09    Time: 5:18p
//Updated in $/Leap/Source/Templates/Parent
//alignment formatting 
//
//*****************  Version 6  *****************
//User: Arvind       Date: 10/17/08   Time: 4:51p
//Updated in $/Leap/Source/Templates/Parent
//increased the width of div
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/24/08    Time: 11:44a
//Updated in $/Leap/Source/Templates/Parent
//replaced table heading Events by Title
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/06/08    Time: 5:35p
//Updated in $/Leap/Source/Templates/Parent
//replaced old image by new one
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/05/08    Time: 5:50p
//Updated in $/Leap/Source/Templates/Parent
//removed unsortable class
//
//*****************  Version 2  *****************
//User: Arvind       Date: 9/04/08    Time: 4:03p
//Updated in $/Leap/Source/Templates/Parent
//added action field and removed long desciption from display
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/30/08    Time: 7:40p
//Created in $/Leap/Source/Templates/Parent
//initial chekin


?>
