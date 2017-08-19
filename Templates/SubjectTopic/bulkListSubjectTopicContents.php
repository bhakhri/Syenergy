<?php
//-------------------------------------------------------
// Purpose: to design the layout for subject topic
//
// Author : Rajeev Aggarwal
// Created on : 24-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<!--
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                    <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddCourseTopicDiv',480,300);blankValues();return false;" />&nbsp;
                                    </td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top" class="title">
      <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
</td>
</tr>
<tr>
<td valign="top" width='100%'>  
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr height="30">
    <td class="contenttab_border" height="20" style="border-right:0px;">
        <?php //require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
    </td>
     <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;padding-right:15px"><nobr>
        <a style="cursor:pointer" class="fontTitle" onClick="displayWindow('AddCourseTopicDiv',480,300);blankValues();return false;">Add Subject Topics</a>&nbsp;
        <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddCourseTopicDiv',480,300);blankValues();return false;" />&nbsp;
        </nobr>
    </td></tr>
    </table>
  </td>
</tr>      
<tr>
<td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
        <tr>
            <td valign="top" class="" width="100%" >
                <!-- form table starts -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                    <tr>
                        <td valign="top" class="contenttab_row1">
                            <form name="totalMarksReportForm" action="" method="post" onSubmit="return false;">
                                <table align="center" border="0" >
                                    <tr>
                                        <td class="contenttab_internal_rows"><nobr><b>Subject: </b></nobr></td>
                                        <td class="padding">
                                        <select size="1" class="htmlElement" name="tSubjectId" id="tSubjectId" style='width:450px' onchange="hideResults(); return false;">
                                        <option value="">Select</option>
                                        <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->getCourseList();
                                        ?>
                                        </select></td>
                                        <td align="center">
                                            <span style="padding-right:10px" >
                                            <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" onClick="return validateSearchForm();return false;" />
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr id='nameRow' style='display:none;'>
                        <td class="" height="20">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                <tr>
                                    <td colspan="1" class="content_title">Subject Topic Details</td>
                                    <td colspan="1" class="content_title" align="right">
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr id='resultRow' style='display:none;'>
                        <td colspan='1' class='contenttab_row'>
                            <div id = 'results'></div>
                            <div id = 'pagingDiv' align='right'></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" align="right">
                            
                        </td>
                    </tr>
                    <tr id='nameRow2' style='display:none;'>
                        <td class="" height="20">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                <tr>
                                    <td colspan="2" class="content_title" align="right">
                                        <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                        <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
     </td>
  </tr>    
</table>        
<!--Course Topic Add Div-->
<?php floatingDiv_Start('AddCourseTopicDiv','Add Bulk Subject Topic'); ?>
<form name="addCourseTopic" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code</b><?php echo REQUIRED_FIELD; ?></nobr></td>
     <td ><b>:&nbsp;</b></td> 
     <td class="padding">
     <select size="1" name="studentCourse" id="studentCourse"class="inputbox1">
      <option value="">Select</option>
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
       ?>
       </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding" valign="top"><textarea cols="55" rows="7" class="inputbox1" id="courseTopic" name="courseTopic" ></textarea></td>
</tr>
<tr>
	<td height="4" colspan="3"></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic Separator</b></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding"><select name="topicSeprator" id="topicSeprator" class="inputbox1" onChange="changeText(this.value)"><option value=",">,</option><option value="~">~</option><option value=";">;</option></select>&nbsp;<div id="showText" style="display:inline"><i>For eg. topic1,topic2,topic3</i></div></td>
</tr>
 
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddCourseTopicDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Course Topic Add Div-->

<!--Course Topic Edit Div-->                    
<?php floatingDiv_Start('EditCourseTopicDiv','Edit Bulk Subject Topic '); ?>
<form name="editCourseTopic" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="subjectTopicId" id="subjectTopicId" value="" />
<tr>
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code</b><?php echo REQUIRED_FIELD; ?></nobr></td>
     <td ><b>:&nbsp;</b></td> 
     <td class="padding">
     <select size="1" name="studentSubject" id="studentSubject" class="inputbox1">
      <option value="">Select</option>
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
       ?>
       </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding" valign="top"><textarea cols="55" rows="7" class="inputbox1" id="subjectTopic" name="subjectTopic" ></textarea><br><i>Please enter comma separated(,) topics <br>for e.g: topic1,topic2,topic3</i></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding"><input type="text"  id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="100" /> </td>
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditCourseTopicDiv');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
</table>
</form>
<!--End Course Topic Add Div-->
<?php floatingDiv_End(); ?>



<!--Start Topic  Div-->
<?php floatingDiv_Start('divTopic','Brief Description '); ?>
<form name="TopicForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="89%"><div id="topicInfo" style="overflow:auto; width:400px; height:200px" ></div></td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<?php
// $History: bulkListSubjectTopicContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Templates/SubjectTopic
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Templates/SubjectTopic
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectTopic
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/12/09    Time: 3:15p
//Created in $/LeapCC/Templates/SubjectTopic
//bulksubject topic file added
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 2/26/09    Time: 2:57p
//Updated in $/Leap/Source/Templates/ScCourseTopic
//changed topic abbreviation length to 100 as per database changes
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 2/11/09    Time: 12:27p
//Updated in $/Leap/Source/Templates/ScCourseTopic
//Updated validations and fixed bugs
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:41p
//Updated in $/Leap/Source/Templates/ScCourseTopic
//added comments in topic textarea
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/02/09    Time: 12:16p
//Updated in $/Leap/Source/Templates/ScCourseTopic
//added validations and removed bugs
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/24/09    Time: 2:42p
//Created in $/Leap/Source/Templates/ScCourseTopic
//Intial checkin
?>

    


