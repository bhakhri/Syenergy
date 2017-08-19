<?php 
//it contain the template of teacher comments on student activities 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	require_once(BL_PATH.'/HtmlFunctions.inc.php');
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
          
            <tr>
                <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right">   
             <form action="" method="" name="searchForm">
      
                  </form> 
            </tr>
        
        </td>
    </tr>
    <tr>
     <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
			  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr> <td class="contenttab_row" valign="top" ><div id="results"></div>           
             </td>
          </tr></table>
   </tr>
          </table>
        </td>
    </tr>
  
    </table></tr></table>

	<?php floatingDiv_Start('ViewTeacher','Teacher Comments Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewTeacher" action="" method="post"> 
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
		   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Teacher Comments Detail </b></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:10px">
			<br />
			<div id="innerNotice" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>
		
		<tr>
			<td height="5px"></td>
		</tr>

		</table>

<?php floatingDiv_End(); ?>

	
<?php
//$History: teacherCommentsContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates/Student
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/21/09    Time: 11:35a
//Updated in $/LeapCC/Templates/Student
//show pop up on dashboard in event, notices, admin messages, teacher
//messages
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
//*****************  Version 14  *****************
//User: Jaineesh     Date: 11/04/08   Time: 6:22p
//Updated in $/Leap/Source/Templates/Student
//make new function for attachment
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:36a
//Updated in $/Leap/Source/Templates/Student
//remove class unsortable in sr. no.
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 10/22/08   Time: 11:36a
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/21/08   Time: 6:16p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/12/08    Time: 7:16p
//Updated in $/Leap/Source/Templates/Student
//modification
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:35p
//Updated in $/Leap/Source/Templates/Student
//modify for paging
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:19a
//Updated in $/Leap/Source/Templates/Student
//modification for html error
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:12a
//Updated in $/Leap/Source/Templates/Student
//show teacher comments through view detail
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
