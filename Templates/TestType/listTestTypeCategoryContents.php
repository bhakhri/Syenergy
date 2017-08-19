<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Test type category
//
//
// Author :Jaineesh
// Created on : (22.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
require_once(BL_PATH.'/helpMessage.inc.php');
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                  
                                    <?php 
                                      $specialSearchCondition="getTestTypeCategoryData()";
                                      require_once(TEMPLATES_PATH . "/searchForm.php"); 
                                    ?>
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('TestTypeCategoryActionDiv',390,250);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="TestTypeCategoryResultDiv"></div></td>
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('TestTypeCategoryActionDiv',''); ?>
<form name="TestTypeCategoryDetail" action="" method="post">  
<input type="hidden" name="testTypeCategoryId" id="testTypeCategoryId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 	<tr>
       	 <td width="100%" class="contenttab_internal_rows" style="padding-left:5px" colspan="3"><nobr>
         <div id="testCategoryDiv" style="display:inline;text-align:center;color:red">
         </div>
         </nobr>
	 </td>
    	</tr>
   <tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Test Type Category Name </strong><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Test Type Category Name',HELP_TEST_CAT_NAME);
     ?><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="testTypeCategoryName" name="testTypeCategoryName"  style="width:170px" class="inputbox" maxlength="20"/>
     </td>
   </tr>

   <tr> 
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Test Type Category Abbr. </strong><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Test Type Category Abbr.',HELP_TEST_CAT_ABB);
?> <?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:
      <input type="text" id="testTypeCategoryAbbr" name="testTypeCategoryAbbr"  style="width:170px" class="inputbox" maxlength="8"/>
     </td>
   </tr>

   <tr> 

      <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Exam Conducted By </strong><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Exam Type',HELP_EXAM_TYPE);
	
?></nobr></td>
      <td class="padding">:
      <select id="examType" name="examType" class="selectfield" size="1">
		<option value="PC" selected="selected">Internal</option>
		<option value="C">External</option>
	  </select
     </td>
   </tr>

   <tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Subject Type </strong><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Subject Type',HELP_SUB_TYPE);
?> </nobr></td>
      <td class="padding">:&nbsp;<select id="subjectType" name="subjectType" class="selectfield" size="1">
		<?php
			require_once(BL_PATH.'/HtmlFunctions.inc.php');
			echo HtmlFunctions::getInstance()->getSubjectTypeData2();
		?></select>
     </td>
   </tr>

   <tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Show Category in Test</strong> <?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Show Category in Test',HELP_TEST_CAT);
?></nobr></td>
      <td class="padding">:
      <select id="showName" name="showName" class="selectfield1" size="1">
		<option value="1" selected="selected">Yes</option>
		<option value="0">No</option>
	  </select
     </td>
   </tr>

   <tr> 
      <td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Whether Attendance Category</strong><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Whether Attendance Category',HELP_ATD_CAT);
?> </nobr></td>
      <td class="padding">:
      <select id="attendanceCategory" name="attendanceCategory" class="selectfield1" size="1">
		<option value="0" selected="selected">No</option>
		<option value="1" >Yes</option>
	  </select>
     </td>
   </tr>
<tr>    
    <td class="contenttab_internal_rows"><nobr><b>Color</b><?php
     require_once(BL_PATH.'/HtmlFunctions.inc.php');
     echo HtmlFunctions::getInstance()->getHelpLink('Color',HELP_COLOR);
?> </nobr></td>
    <td class="padding">:
    <input type="text" id="subjectColor1" name="subjectColor" class="inputbox" readonly maxlength="10"/>
    </td>
</tr>
<tr>    
    <td class="contenttab_internal_rows"></nobr></td>
    <td class="padding" style="padding-left:10px"><nobr><div id="color1" style="display:inline"></div></nobr>
    </td>
</tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('TestTypeCategoryActionDiv');if(flag==true){getTestTypeCategoryData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div>
            </td>
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>
<!--Daily Attendance Help  Details  End -->



<?php
// $History: listTestTypeCategoryContents.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 4/19/10    Time: 11:11a
//Updated in $/LeapCC/Templates/TestType
//fixed bug. FCNS No.1605
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 3/31/10    Time: 4:45p
//Updated in $/LeapCC/Templates/TestType
//added university wise subject types. FCNS No.1506
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Templates/TestType
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/25/09    Time: 3:21p
//Updated in $/LeapCC/Templates/TestType
//fixed bug no.0000690 & show print & export to excel button
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/12/09    Time: 2:54p
//Updated in $/LeapCC/Templates/TestType
//fixed bug nos.0000040,0000051,0000052,0000053
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/03/09    Time: 6:03p
//Updated in $/LeapCC/Templates/TestType
//add new filed test type category abbr.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/01/09    Time: 10:49a
//Updated in $/LeapCC/Templates/TestType
//fixed bug no.9 of LeapCC Bugs2.doc dated 30.05.09
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Templates/TestType
//modified for test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:29p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/25/09    Time: 12:39p
//Updated in $/SnS/Templates/TestType
//modified in test type show exam type & show category 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/27/09    Time: 4:06p
//Created in $/SnS/Templates/TestType
//new template for test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:33a
//Created in $/Leap/Source/Templates/TestType
//new template file for test type contents
//
 
?>
