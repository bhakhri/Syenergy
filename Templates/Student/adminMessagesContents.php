<?php 
//it contain the template of admin comments on student activities 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<?php require_once(BL_PATH.'/HtmlFunctions.inc.php'); ?>
   <?Php require_once(TEMPLATES_PATH . "/breadCrumb.php");
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
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           <!-- <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Student Info &nbsp;&raquo;&nbsp; Display Admin Messages </td>
                <td valign="top" align="right">   
               <form action="" method="" name="searchForm">
				<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />&nbsp;
				<input type="image" name="submit" align="absbottom" src="<?php echo IMG_HTTP_PATH;?>/search.gif" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" />
			  </form>
            </tr> -->
            </table>
        </td>
    </tr>
   <!--  <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Admin Messages Detail :</td>
                     
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

	<?php floatingDiv_Start('ViewAdmin','Admin Messages Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewEvents" action="" method="post"> 
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
		   <td width="100%"  align="left" class="rowheading">&nbsp;<b>Subject </b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerSubject" style="overflow:auto; width:580px;" ></div>
			</td>
		</tr>
		<tr>
		<td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
		</tr>
		<tr>
			<td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleToDate"></span></td>
		</tr>
		<tr>
		   <td width="100%"  align="left" class="rowheading">&nbsp;<b>Message Detail</b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerNotice" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>
	
		<tr>
			<td height="5px"></td>
		</tr>

		   </form>
		</table>

<?php floatingDiv_End(); ?>

	
<?php
//$History: adminMessagesContents.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/24/09   Time: 3:50p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001883, 0001877 and modification in query
//getStudentCourseResourceList() to get courses of current class and make
//searchable course
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Templates/Student
//change breadcrumb & put department in employee
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/01/09    Time: 7:23p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001374, 0001375, 0001376, 0001379, 0001373
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates/Student
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
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
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:39a
//Updated in $/Leap/Source/Templates/Student
//remove unsortable class from sr. no.
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//remove the html tags through strip_tags function
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/22/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Student
//used removePHPJS function
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:10p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:14p
//Updated in $/Leap/Source/Templates/Student
//modification in close button
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:07p
//Updated in $/Leap/Source/Templates/Student
//modify for close button
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/12/08    Time: 7:16p
//Updated in $/Leap/Source/Templates/Student
//modification
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/Student
//modify for paging
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/10/08    Time: 7:53p
//Updated in $/Leap/Source/Templates/Student
//put paging
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:09a
//Created in $/Leap/Source/Templates/Student
//show template of admin messages
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
