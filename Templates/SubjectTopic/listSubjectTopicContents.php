<?php
//-------------------------------------------------------
// Purpose: to design the layout for subject topic
//
// Author : Parveen Sharma
// Created on : 15-01-2009
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
                                    <?php //require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSubjectTopicDiv',480,300);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
        <a style="cursor:pointer" class="fontTitle" onClick="displayWindow('AddSubjectTopicDiv',480,300);blankValues();return false;">Add Subject Topics</a>&nbsp;
        <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSubjectTopicDiv',480,300);blankValues();return false;" />
        </nobr>
    </td></tr>
    </table>
  </td>
</tr>      
<tr>
<td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
        <tr>
            <td valign="top" class="">
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
    
    
<!--Subject Topic Add Div-->
<?php floatingDiv_Start('AddSubjectTopicDiv','Add Subject Topic'); ?>
<form name="addSubjectTopic" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td><b>:&nbsp;</b></td> 
     <td class="padding">
     <select size="1" name="studentSubject" id="studentSubject"class="inputbox1">
      <option value="">Select</option>
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
       ?>
       </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding">
    <textarea cols="55" rows="3" class="inputbox1" id="subjectTopic" name="subjectTopic" maxlength="150" onkeyup="return ismaxlength(this)">
    </textarea> </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding"><input type="text"  id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="30" /> </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddSubjectTopicDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Subject Topic Add Div-->

<!--Subject Topic Edit Div-->                    
<?php floatingDiv_Start('EditSubjectTopicDiv','Edit Subject Topic '); ?>
<form name="editSubjectTopic" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="subjectTopicId" id="subjectTopicId" value="" />
<tr>
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject Code<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td><b>:&nbsp;</b></td> 
     <td class="padding">
     <select size="1" name="studentSubject" id="studentSubject"class="inputbox1">
      <option value="">Select</option>
       <?php
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
       ?>
       </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding">
    <textarea cols="55" rows="3" class="inputbox1" id="subjectTopic" name="subjectTopic" maxlength="150" onkeyup="return ismaxlength(this)">
    </textarea> </td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding"><input type="text"  id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="30" /> </td>
</tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditSubjectTopicDiv');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
</table>
</form>
<!--End Subject Topic Add Div-->
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
// $History: listSubjectTopicContents.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/20/09   Time: 10:33a
//Updated in $/LeapCC/Templates/SubjectTopic
//Mandatory sign added
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/28/09    Time: 12:45p
//Updated in $/LeapCC/Templates/SubjectTopic
//Gurkeerat: resolved issue 1319
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectTopic
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/13/09    Time: 3:44p
//Updated in $/LeapCC/Templates/SubjectTopic
//textarea width set
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/06/09    Time: 1:13p
//Updated in $/LeapCC/Templates/SubjectTopic
//limits set
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//print & csv function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/19/09    Time: 11:24a
//Updated in $/LeapCC/Templates/SubjectTopic
//bug fix
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:17p
//Created in $/LeapCC/Templates/SubjectTopic
//SubjectTopic file added
//

?>

    


