<?php 
//This file creates Html Form output in "displayTeacherComments in parent Module" 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	<!--<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
           <td valign="top" class="content"> 
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
      <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Teacher Comments Detail :</td>
                    </tr> 
                    </table>
                </td> 
             </tr> -->
             <tr>
                <td class="contenttab_row" valign="top">
                    <div id="results">
                    </div>           
       </td> 
          </tr>
          
          </table>
     <!--   </td>
    </tr>
    
    </table> -->

<?php floatingDiv_Start('ViewComments','Teacher Comments Description','',''); ?>
<form name="viewComments" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Teacher Name: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="employeeNameComments" style="overflow:auto; width:630px;" ></div></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Visible Date: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2">&nbsp;<B>From</B>: <span id="startDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="endDate" style="height:20px"></span></td>
</tr>
<tr>
    <td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Comments: &nbsp;</b></nobr></td>
</tr>
<tr>
    <td valign="middle" colspan="2" style="padding-left:3px" ><div id="innerComments" style="overflow:auto; width:630px;height:200px" ></div></td>
</tr> 
 
<tr>
<td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>    
    
<?php 
//$History: displayTeacherCommentsContents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/24/09    Time: 10:46a
//Updated in $/LeapCC/Templates/Parent
// press “Enter” from keyboard in IE browse (search condition updated)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 5:44p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 8/17/09    Time: 11:31a
//Updated in $/Leap/Source/Templates/Parent
//validiation, formatting 
//1100, 1098, 1095, 1094, 1050, 1046 issue fix 
//
//*****************  Version 14  *****************
//User: Parveen      Date: 8/11/09    Time: 11:46a
//Updated in $/Leap/Source/Templates/Parent
//1002 bug fix (validation & formating updated)
//
//*****************  Version 13  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/Leap/Source/Templates/Parent
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Templates/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/03/09    Time: 5:18p
//Updated in $/Leap/Source/Templates/Parent
//alignment formatting 
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/20/08    Time: 5:43p
//Updated in $/Leap/Source/Templates/Parent
//modify
//
//*****************  Version 9  *****************
//User: Arvind       Date: 9/12/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Parent
//modify
//
//*****************  Version 8  *****************
//User: Arvind       Date: 9/11/08    Time: 6:24p
//Updated in $/Leap/Source/Templates/Parent
//added facility for display div
//
//*****************  Version 7  *****************
//User: Arvind       Date: 9/05/08    Time: 5:48p
//Updated in $/Leap/Source/Templates/Parent
//removed unsortable class
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/26/08    Time: 10:49a
//Updated in $/Leap/Source/Templates/Parent
// modiofied the display width of header
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/12/08    Time: 6:42p
//Updated in $/Leap/Source/Templates/Parent
//modified the display list
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/17/08    Time: 12:18p
//Updated in $/Leap/Source/Templates/Parent
//Modified the Breadcrum and Listing HEading
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/16/08    Time: 5:40p
//Updated in $/Leap/Source/Templates/Parent
//changed the width of the column headings in the table
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
