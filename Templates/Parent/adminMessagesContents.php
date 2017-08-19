<?php 
//it contain the template of admin comments on student activities 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<?php require_once(BL_PATH.'/HtmlFunctions.inc.php'); ?>
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
                <td class="contenttab_row" valign="top" >
                <div id="results">  </div>           
             </td>
          </tr> 
          </table> 
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
       <!-- <td valign="top" class="title"> -->
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <!--  <tr>
                <td height="10"></td>
            </tr> -->
            <!--<tr>
				<?php
                /* if (STUDENT_ICON == true) { 
					<td valign="top" width="5%">
					<img src="<?php echo IMG_HTTP_PATH ?>/student.gif" border="0" style="cursor:default" title="Student">
					<!--echo "<img src=\"".IMG_HTTP_PATH."/".student.gif."\" width=\"100\" height=\"100\" border=\"0\"/>"; 
					</td>
				 }  */
                 ?>
                <td valign="center">Parent Activities &nbsp;&raquo;&nbsp; Display Admin Messages </td>
                <td valign="top" align="right">   
                   <form action="" method="" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');return false;">
                    <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                    <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');
                    return false;"/> -->
                  </form>
            <!--    </td> -->
            </tr>
            </table>

<?php floatingDiv_Start('ViewAdmin','Admin Messages Description','',$wrapType=''); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
            <form name="viewAdmin" action="" method="post"> 
        <tr>
            <td height="5px"></td></tr>
        <tr>
           <td width="100%"  align="left" class="rowheading">&nbsp;<b>Subject </b></td>
        </tr>
        <tr>
            <td width="100%"  align="left" style="padding-left:1px">
            <div id="innerSubject" style="overflow:auto; width:580px;" ></div>
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
        </tr>
        <tr>
            <td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleMessageFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleMessageToDate"></span></td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
           <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Message Detail </b></td>
        </tr>
        <tr>
            <td width="100%"  align="left" style="padding-left:1px">
            <div id="innerAdmin" style="overflow:auto; width:580px; height:200px" ></div>
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
//*****************  Version 2  *****************
//User: Parveen      Date: 9/04/09    Time: 3:01p
//Updated in $/LeapCC/Templates/Parent
//div base berif information formating updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/27/09    Time: 5:10p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/13/09    Time: 4:34p
//Updated in $/Leap/Source/Templates/Parent
//alignment, Div base information updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/07/09    Time: 6:33p
//Created in $/Leap/Source/Templates/Parent
//initial checkin
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/Student
//use student, dashboard, sms, email icons
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/12/09    Time: 6:13p
//Updated in $/Leap/Source/Templates/Student
//make the cellpadding & cellspacing 1
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
