<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TRAINING
//
//
// Author :Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								</td></tr></table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
         <!--   <tr>
                <td height="10"></td>
            </tr>
				<form name='searchBox1' onSubmit="return false;">
            <tr>
                <td valign="top">Miscellaneous&nbsp;&raquo;&nbsp;Task Manager </td>
				<td valign="top" align="right">
                <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php //echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image" name="submit" src="<?php //echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="getTask();"/>&nbsp;
                  </td>
            </tr>
			</form> -->
            </table>
        </td>
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
                        <td class="content_title">Task Detail : </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" colspan="4">
                <div id="TaskResultDiv">
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

<!--Start Add/Edit Div-->

<!--End Add Div-->


<?php
// $History: listParentTaskContents.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/21/09    Time: 11:50a
//Created in $/LeapCC/Templates/Parent
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:47p
//Created in $/SnS/Templates/Parent
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:12p
//Created in $/SnS/Templates/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Templates/Task
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:41p
//Updated in $/SnS/Templates/Task
//modified in showing colon
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:35p
//Updated in $/SnS/Templates/Task
//modified in task template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:24p
//Created in $/SnS/Templates/Task
//new template for task
//
 
?>