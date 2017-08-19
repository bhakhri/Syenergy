<?php
//-------------------------------------------------------
// Purpose: to design the layout for SMS.
//
// Author : Parveen Sharma
//--------------------------------------------------------
?> 
<form name="listForm" id="listForm" action="" method="post" onSubmit="return false;"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                        <table align="left" border="0" cellpadding="2px" cellspacing="2px"  width="80%">
                                           <tr>
                                                <td class="contenttab_internal_rows"><nobr><b>Mentor Name</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
				                                <td class="padding">
                                                    <select size="1" class="inputbox1" style="width:350px;" name="mentorName" id="mentorName" onchange=hideResults(); >
				                                        <option value="">All</option>
                                                        <?php    
					                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
					                                        //echo HtmlFunctions::getInstance()->getStudentRegistrationData();
                                                            echo HtmlFunctions::getInstance()->getEmployeeData(); 
					                                    ?>
					                                </select>
                                                </td>
                                                <td colspan="1" class="contenttab_internal_rows" valign="middle" style="padding-left:10px;" align="left"><nobr><b>Roll No.</nobr></b></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                <td colspan="1" class="" valign="middle" align="left">
                                                    <input class="selectfield" name="rollNo" id="rollNo" style="width: 120px;" type="text">
                                                </td>
                                                <td colspan="1" class="contenttab_internal_rows" valign="middle" style="padding-left:10px;" align="left"><nobr><b>Student Name</b></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                <td colspan="1" class="" valign="middle" align="left">
                                                    <input class="selectfield" name="studentName" id="studentName" style="width: 120px;" type="text">
                                                </td>
                                             </tr>
                                             <tr>   
                                                <td colspan="1" class="contenttab_internal_rows" valign="middle"  align="left">
                                                  <nobr><b>Registration Status</b></nobr>
                                                </td>
                                                <td class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                                <td class="" valign="middle" align="left" ><nobr>
                                                  <input id="registered1" name="registered" value="1" type="radio"><nobr>&nbsp;Registered
                                                  <span style="padding-left: 5px;">
                                                  <input id="registered2" name="registered" value="2" type="radio"></span></nobr><nobr>&nbsp;<span style="color: red;">Pending</span>
                                                  <span style="padding-left: 5px;">
                                                  <input checked="checked"  id="registered3" name="registered" value="3" type="radio"></span></nobr><nobr>&nbsp;Both</nobr>
                                                </td>
                                                <td align="left" colspan="10" style="padding-left:20px" >
                                                    <input type="image" name="mSubmit" value="mSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateRegistrationForm();return false;" />
                                                </td>    
                                            </tr>
                                        </table>
                                    
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Student Details :</td>
                                            <td colspan="2" class="content_title" align="right">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:980px; height:350px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:100%; vertical-align:top;"></div>
                                     </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td width="80%" nowrap class="content_title" align="center" style="padding-left:160px;display:none">
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/approve.gif" onClick="printReport();return false;"/>&nbsp;&nbsp;
                                                <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hideResults(); return false;" />
                                            </td>
                                            <td width="20%" nowrap  class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>
</form>    
 
<!--Start Notice  Div-->
<?php floatingDiv_Start('divMentorship','Mentorship ','',' '); ?>
<form name="MentorshipForm" id="MentorshipForm" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
       <tr>
        <td height="390px" colspan='3'>
           <div id="mentorshipCommentDiv" style="overflow:auto; WIDTH:690px; HEIGHT:390px; vertical-align:top;"></div>
        </td>
       </tr>
       <tr style ="vertical-align:top;">    
         <td style="vertical-align:top;" nowrap><b>Comments</b></td>
         <td style="vertical-align:top;" nowrap><b>&nbsp;:&nbsp;</b></td>
         <td style="vertical-align:top;">
            <textarea name="mentorshipComments" id="mentorshipComments" rows="2" cols="83"></textarea>
         </td>      
       </tr>
       <tr>
         <td align="center" style="padding-right:10px" colspan="4">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm(this.form,'Add');return false;" />
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('divMentorship');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;"/>  
         </td>
       </tr>
    </table>
</form>        
<?php floatingDiv_End(); ?> 

			
        
<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Brief Description','',''); ?>
<form name="MessageForm" id="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:350px; height:200px; vertical-align:top;">
                <div id="message" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>

