<?php 
//This file creates Html Form output in displayNotices in parent Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?><?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");
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
         <!--  <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
          <td valign="top" class="content"> 
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
            
             <tr>-->
                <td class="contenttab_row" valign="top" >
                    <div id="results">
                    </div>           
                </td> 
          </tr>
          
    </table>
     
    
<?php floatingDiv_Start('ViewNotices','Notice Description','',''); ?>
<form name="viewNotices" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
<tr>
    <td height="5px"></td></tr> 
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="subjectNotice" style="overflow:auto; width:630px; height:20px" ></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Department: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeDepartment" style="width:600px; height:20px"></div></td>
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
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Description: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" style="padding-left:3px" ><div id="innerNotice" style="overflow:auto; width:630px;height:200px" ></div></td>
</tr> 
 
<tr>
<td height="5px"></td></tr>
<tr>
</table>
</form>

<?php floatingDiv_End(); ?>
    

<?php //$History: displayNoticesContents.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/31/09    Time: 11:51a
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: resolved issue 1348,1349
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/26/09    Time: 6:44p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: resolved alignment issue regarding issues 1226, 1227
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Templates/Parent
//formating, validations & conditions updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 8/13/09    Time: 1:05p
//Updated in $/Leap/Source/Templates/Parent
//formatting, validations & Div Base Brief information show 
//
//*****************  Version 20  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/Leap/Source/Templates/Parent
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//*****************  Version 19  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Templates/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 18  *****************
//User: Parveen      Date: 6/26/09    Time: 6:47p
//Updated in $/Leap/Source/Templates/Parent
//format, condition, validation updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 4/03/09    Time: 5:18p
//Updated in $/Leap/Source/Templates/Parent
//alignment formatting 
//
//*****************  Version 16  *****************
//User: Arvind       Date: 10/17/08   Time: 4:41p
//Updated in $/Leap/Source/Templates/Parent
//modify the width of div
//
//*****************  Version 15  *****************
//User: Arvind       Date: 10/17/08   Time: 4:15p
//Updated in $/Leap/Source/Templates/Parent
//modify the size of div
//
//*****************  Version 14  *****************
//User: Arvind       Date: 10/07/08   Time: 10:22a
//Updated in $/Leap/Source/Templates/Parent
//modified the download option
//
//*****************  Version 13  *****************
//User: Arvind       Date: 10/06/08   Time: 6:27p
//Updated in $/Leap/Source/Templates/Parent
//added the download option in notice module
//
//*****************  Version 12  *****************
//User: Arvind       Date: 9/06/08    Time: 5:30p
//Updated in $/Leap/Source/Templates/Parent
//replaced images by new one send by gaurav
//
//*****************  Version 11  *****************
//User: Arvind       Date: 9/05/08    Time: 5:49p
//Updated in $/Leap/Source/Templates/Parent
//removed unsortable class
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/04/08    Time: 11:19a
//Updated in $/Leap/Source/Templates/Parent
//html validated
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/26/08    Time: 1:47p
//Updated in $/Leap/Source/Templates/Parent
//modify
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/23/08    Time: 4:51p
//Updated in $/Leap/Source/Templates/Parent
//modified
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/19/08    Time: 3:41p
//Updated in $/Leap/Source/Templates/Parent
//modify
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/02/08    Time: 1:30p
//Updated in $/Leap/Source/Templates/Parent
//added field action
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/30/08    Time: 12:17p
//Updated in $/Leap/Source/Templates/Parent
//removed action field frokm display
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/17/08    Time: 12:19p
//Updated in $/Leap/Source/Templates/Parent
//Modified the Breadcrum and Listing HEading
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/16/08    Time: 5:29p
//Updated in $/Leap/Source/Templates/Parent
//added a new div in viewNoticediv
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/16/08    Time: 12:31p
//Updated in $/Leap/Source/Templates/Parent
//Added comments in th files
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:04p
//Created in $/Leap/Source/Templates/Parent
//added new file for Parent Module

?>
