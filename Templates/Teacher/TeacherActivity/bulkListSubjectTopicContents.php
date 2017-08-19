<?php
//-------------------------------------------------------
// Purpose: to design the layout for subject topic
//
// Author : Rajeev Aggarwal
// Created on : 24-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
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
							
								</td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  
		<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddCourseTopicDiv',480,300);blankValues();return false;" />&nbsp;&nbsp;
                        </td>
           <!-- <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                <td valign="top">Marks & Attendance &nbsp;&raquo;&nbsp;Bulk Subject Topic Master</td>
                <td valign="bottom" align="right">
                  <form action="" method="" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');return false;">
                    <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                    <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');return false;"/>
                  </form>
                </td>
            </tr>
            </table> -->
   
    </tr>
  
             <tr>
                <td class="contenttab_row" valign="top" colspan=2><div id="results"> </div>           
             </td>
          </tr>
		  <tr>
           <td class="content_title" title="Print" align="right" colspan=2><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;<input type="image"  name="printSubjectTopicSubmit" id='generateCSV' onClick="printReportCSV();return false;" value="printSubjectTopicSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
		 </tr>
          </table>
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
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject</b><?php echo REQUIRED_FIELD; ?></nobr></td>
     <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td> 
     <td class="padding" align="left" nowrap> 
     <select size="1" name="studentCourse" id="studentCourse" class="inputbox1" style="width:155px" >
       <option value="">Select</option>
         <?php
           global $sessionHandler; 
           $employeeId   = $sessionHandler->getSessionVariable('EmployeeId');  
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getActiveClassSubjectData(""," AND tt.employeeId = $employeeId" );
         ?>
      </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;Topic</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td class="contenttab_internal_rows" align="left" valign="top" nowrap><b>&nbsp;:&nbsp;</b></td> 
    <td class="padding" align="left" nowrap> 
       <textarea cols="55" rows="7" class="inputbox1" id="courseTopic" name="courseTopic" ></textarea>
    </td>
</tr>
<tr>
	<td height="4"></td>
</tr>
<tr>    
    <td class="contenttab_internal_rows" align="left" nowrap><nobr><b>&nbsp;&nbsp;Topic Seprator</b></nobr></td>
    <td class="contenttab_internal_rows" align="left" nowrap><b>&nbsp;:&nbsp;</b></td>
    <td class="padding" align="left" nowrap> 
    &nbsp;<select name="topicSeprator" id="topicSeprator" class="inputbox1" onChange="changeText(this.value)"><option value=",">,</option><option value="~">~</option><option value=";">;</option></select>&nbsp;<div id="showText" style="display:inline"><i>For eg. topic1,topic2,topic3</i></div></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddCourseTopicDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
     </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Course Topic Add Div-->

<!--Course Topic Edit Div-->                    
<?php floatingDiv_Start('EditCourseTopicDiv','Edit Subject Topic '); ?>
<form name="editCourseTopic" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="subjectTopicId" id="subjectTopicId" value="" />
<tr>
     <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject</b><?php echo REQUIRED_FIELD; ?></nobr></td>
     <td ><b>:&nbsp;</b></td> 
     <td class="padding">
     <select size="1" name="studentSubject" id="studentSubject" class="inputbox1" style="width:200px">
      <option value="">Select</option>
       <?php
         //require_once(BL_PATH.'/HtmlFunctions.inc.php');
         //echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
          global $sessionHandler; 
          $employeeId   = $sessionHandler->getSessionVariable('EmployeeId');  
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getActiveClassSubjectData(""," AND tt.employeeId = $employeeId" );
       ?>
       </select>
     </td>  
</tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Topic</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding" valign="top"><textarea cols="55" rows="7" class="inputbox1" id="subjectTopic" name="subjectTopic" ></textarea><br><i>Please enter comma seprated(,) topics <br>for e.g: topic1,topic2,topic3</i></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Abbreviation</b><?php echo REQUIRED_FIELD; ?></nobr></td>
    <td><b>:&nbsp;</b></td>
    <td class="padding"><input type="text" style="width:200px" id="subjectAbbr" name="subjectAbbr" class="inputbox"  maxlength="100" /> </td>
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
<?php floatingDiv_Start('divTopic','Brief Description ','',''); ?>
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
//User: Parveen      Date: 4/13/10    Time: 2:22p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//look & feel udpated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/13/10    Time: 2:09p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//validation format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/10    Time: 1:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/13/10    Time: 9:26a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
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

    


