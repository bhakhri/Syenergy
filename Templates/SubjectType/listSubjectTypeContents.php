<?php 
//This file creates Html Form output in Subject Type Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSubjectType',315,250);blankValues();return false;" />&nbsp;</td></tr>
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
   <!--//Start Add Div-->
<?php floatingDiv_Start('AddSubjectType','Add Subject Type'); ?>
<form name="addSubjectType" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
          <tr>
          <td width="35%" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD;?> </strong></td>
          <td width="2%" class="contenttab_internal_rows"><b>:</b></td>
          <td width="63%" class="padding">
           <input type="text" id="subjectTypeName" class="inputbox"  maxlength="20"  name="subjectTypeName"  value=""  />
          </td>
        </tr>
        <tr>
          <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD;?> </strong></td>
          <td class="contenttab_internal_rows"><b>:</b></td>
          <td class="padding">
           <input type="text" id="subjectTypeCode" class="inputbox"  maxlength="10" name="subjectTypeCode" value=""  />
          </td>
        </tr>
        <tr>
         <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;University<?php echo REQUIRED_FIELD;?></strong></nobr></td>        
         <td class="contenttab_internal_rows"><b>:</b></td>
         <td class="padding">
          <select name="universityId" class="inputbox" id="universityId" style="width:100%">
            <option value="">Select</option>
            <?php    
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['universityId']==''? $subjectTypeRecordArray[0]['universityId'] : $REQUEST_DATA['universityId'] );
              ?>
            
          </select></td>
        </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                   <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" 
                    onclick="javascript:hiddenFloatingDiv('AddSubjectType');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
  
</table>
 </form>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('EditSubjectType','Edit Subject Type'); ?>
     <form name="editSubjectType" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
     
    <input type="hidden" name="subjectTypeId" id="subjectTypeId" value="" />
        <tr>
          <td width="35%" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Subject Type<?php echo REQUIRED_FIELD;?> </strong></td>
          <td width="2%" class="contenttab_internal_rows"><b>:</b></td>
          <td width="63%" class="padding">
           <input type="text" id="subjectTypeName"  maxlength="20"  name="subjectTypeName" class="inputbox" value=""  />
          </td>
        </tr>
        <tr>
          <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD;?> </strong></td>
          <td class="contenttab_internal_rows"><b>:</b></td>
          <td class="padding">
           <input type="text" id="subjectTypeCode"  maxlength="10" name="subjectTypeCode" class="inputbox" value=""  />
          </td>
        </tr>
        <tr>
         <td class="contenttab_internal_rows"><nobr><strong>&nbsp;&nbsp;University<?php echo REQUIRED_FIELD;?></strong></nobr></td>        
         <td class="contenttab_internal_rows"><b>:</b></td>
         <td class="padding">
          <select name="universityId" class="inputbox" id="universityId" style="width:100%">
            <option value="">Select</option>
            <?php    
                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                 echo HtmlFunctions::getInstance()->getUniversityData($REQUEST_DATA['universityId']==''? $subjectTypeRecordArray[0]['universityId'] : $REQUEST_DATA['universityId'] );
              ?>
            
          </select></td>
        </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="EditCancel"src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"
                    onclick="javascript:hiddenFloatingDiv('EditSubjectType');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>

    <?php floatingDiv_End();    // Div To Edit The Table 
   

//$History: listSubjectTypeContents.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Templates/SubjectType
//formatting & role permission added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectType
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Templates/SubjectType
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/12/09    Time: 4:20p
//Updated in $/LeapCC/Templates/SubjectType
//formatting & requird parameter updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/01/09    Time: 12:57p
//Updated in $/LeapCC/Templates/SubjectType
//required field validator added
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Updated in $/LeapCC/Templates/SubjectType
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SubjectType
//
//*****************  Version 12  *****************
//User: Arvind       Date: 9/05/08    Time: 5:43p
//Updated in $/Leap/Source/Templates/SubjectType
//removed unsortable class
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/27/08    Time: 12:41p
//Updated in $/Leap/Source/Templates/SubjectType
//html validated
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/19/08    Time: 3:11p
//Updated in $/Leap/Source/Templates/SubjectType
//replaced search button
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/14/08    Time: 7:18p
//Updated in $/Leap/Source/Templates/SubjectType
//modified the bread crum
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/14/08    Time: 7:00p
//Updated in $/Leap/Source/Templates/SubjectType
//modified the bread crum
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/05/08    Time: 5:03p
//Updated in $/Leap/Source/Templates/SubjectType
//added php tag at the end of the file
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/01/08    Time: 1:40p
//Updated in $/Leap/Source/Templates/SubjectType
//added a new field universityName in the table
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/01/08    Time: 12:36p
//Updated in $/Leap/Source/Templates/SubjectType
//remove select from select university label
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/01/08    Time: 12:32p
//Updated in $/Leap/Source/Templates/SubjectType
//added <nobr> tag in add and edit divs inSelect university field
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/30/08    Time: 7:30p
//Updated in $/Leap/Source/Templates/SubjectType
//modify image button cancel to input type image button
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 5:21p
//Updated in $/Leap/Source/Templates/SubjectType
//1) Added a new blankValues function on th click of add button.
//2) Added a new deleteSubjectType function on the click of delete button
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:27p
//Created in $/Leap/Source/Templates/SubjectType
//new files added

?>
