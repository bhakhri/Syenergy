<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
								</td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
          <!--  <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Notices &nbsp;&raquo;&nbsp;Display Teacher Comments</td>
                <td valign="top" align="right">
            
                <form action="" method="" name="searchForm">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
               <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="resetSendReqParam(1);sendReq(listURL,divResultName,searchFormName,'',false);hide_div('showList',1);return false;"/>&nbsp;
                  </form>
                </td>
            </tr>
            </table> -->
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
             <!--  <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Teacher Comments : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td> -->
             </tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm2">
               <table width="90%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                     <td align="left" class="contenttab_internal_rows"><b>Date</b></td>
                    <td align="left"  class="padding" colspan="6"><nobr>: 
                     <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     $thisDate=date('Y')."-".date('m')."-".date('d');
                     echo HtmlFunctions::getInstance()->datePicker('forDate','');
                    ?></nobr>
                    </td>
                    </tr>
                    <tr>    
                        <td width="9%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
                        <td width="23%" class="padding"><nobr>: 
                        <select size="1" class="selectfield" name="class" id="class" onchange="deleteRollNo();groupPopulate();" >
                        <option value="-1">All</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTeacherClassData();
                        ?>
                      </select></nobr>
                      </td>
                      <td width="7%" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Group</b></nobr></td>
                        <td width="14%" class="padding" align="left"><nobr>: 
                        <select size="1" class="selectfield" name="group" id="group" onchange="deleteRollNo();" >
                        <option value="-1">All</option>
                          <?php
                          // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          // echo HtmlFunctions::getInstance()->getTeacherGroupData();
                        ?>
                        </select></nobr>
                      </td>
                     <td width="10%" align="left" class="contenttab_internal_rows" style="padding-left:15px"><nobr><b>Roll No</b></nobr></td>
                     <td width="25%" align="left" class="padding"><nobr>: 
                     <input type="text" name="studentRollNo" id="studentRollNo" autocomplete='off' class="inputbox"></nobr>
                     <td  align="left" style="padding-left:5px" >
                      <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                    </td>
                    </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                <div id="showList" style="display:none">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                 <td>
                <form name="listFrm" id="listFrm">
                 <div id="results">
                </div>
                </form>           
                </td>
               </tr>
              <tr><td height="5px"></td></tr>
              </table> 
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

<?php floatingDiv_Start('CommentDiv','Comments Details','',' '); ?>
<form name="frmComment" action="" method="post"> 
    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="commenId" id="commentId" /> 
    <tr><td height="3px"></td></tr>
    <tr><td colspan="2"  align="left" style="padding-left:3px"><b>Dated:</b>
     &nbsp;&nbsp;&nbsp;<input type="text" id="msgDate" name="msgDate" class="inputbox" style="border:0px" readonly="readonly" />
    </td></tr>
    <tr><td colspan="2" height="3px"></td></tr>
    <tr><td colspan="2"  align="left" style="padding-left:3px"><b>Subject:</b>
     &nbsp;<input type="text" id="msgSubject" name="msgSubject" class="inputbox" style="width:600px" readonly="true" />
    </td></tr>
    <tr><td colspan="2" height="3px"></td></tr>
    <tr><td colspan="2"  align="left" style="padding-left:3px"><b>Comment:</b></td></tr>
    <tr>
     <td colspan="2"  align="left" style="padding-left:3px;">
      <!--<textarea id="msgBody" name="msgBody" cols="84" rows="5" readonly="true"></textarea>-->
      <div id="msgBody" name="msgBody" style="height:100px;width:675px;overflow:auto;border:1px solid black;"></div>
     </td> 
    </tr>
    <tr><td colspan="2" height="3px"></td></tr>
    <tr><td colspan="2" align="right" style="padding-right:3px">
    <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
     &nbsp;
    <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
    </td></tr>
    <tr><td colspan="2" height="3px"></td></tr>
    <tr>
    <td colspan="2" align="left" style="padding:3px">
     <div id="results2" style="height:150px;width:700px;display:none;overflow:auto"></div>
    </td>
   </tr> 
<tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('CommentDiv');return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->    

<?php
// $History: listTeacherCommentContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/02/10   Time: 12:20
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added the feature :
//Display Teacher Comments:By Default it should show the list of message
//sent by respective employee. after that search filter can be applied
//which are currently mandatory
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added code for "Time table adjustment"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/29/08    Time: 6:02p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:17p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:52p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Corrected Search image name
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/08    Time: 6:36p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//corrected breadcrumb and reset button height and width
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/13/08    Time: 2:38p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>