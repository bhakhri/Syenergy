<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

//fetch title,tab and proof form information
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$tabString=HtmlFunctions::getInstance()->getAppraisalTab('','');
$titleString=HtmlFunctions::getInstance()->getAppraisalTitle('','');
$proofFormString=HtmlFunctions::getInstance()->getAppraisalProof('','');

?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Appraisal &nbsp;&raquo;&nbsp;Appraisal Question Master
				</td>
                <td valign="top" align="right">
                  <form action="" method="" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');return false;">
                    <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                    <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,'');
                    return false;"/>
                  </form>
                  </td>
            </tr>
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
                        <td class="content_title">Appraisal Question Detail : </td>
                         
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddAppraisalQuestion',315,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                   <div id="results">  </div>           
                </td>
             </tr>
             <!--<tr>
                <td>
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
             -->
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<!--Start Add Div-->
<?php floatingDiv_Start('AddAppraisalQuestion','Add Appraisal Question'); ?>
    <form name="AddAppraisalQuestion" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Question<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="78%" class="padding">
        <textarea id="question" name="question" class="inputbox" style="width:295px;" cols="50" rows="4" maxlength="500" onkeyup="return ismaxlength(this);"></textarea> 
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Weightage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding"><input type="text" id="weightage" name="weightage" class="inputbox" style="width:50px;" maxlength="5" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Active<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="contenttab_internal_rows">
         <input type="radio" name="isActive" id="isActive1" checked="checked" />Yes&nbsp;
         <input type="radio" name="isActive" id="isActive2" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Proof<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="contenttab_internal_rows">
         <input type="radio" name="isProof" id="isProo1" checked="checked" onclick="toggleProof(true,1);" />Yes&nbsp;
         <input type="radio" name="isProof" id="isProo2" onclick="toggleProof(false,1);" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Proof Form<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="proofId" id="proofId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $proofFormString;
          ?>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="titleId" id="titleId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $titleString;
          ?>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Tab<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="tabId" id="tabId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $tabString;
          ?>
         </select>
        </td>
    </tr>
  <tr><td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
     <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddAppraisalQuestion');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditAppraisalQuestion','Edit Appraisal Question '); ?>
<form name="EditAppraisalQuestion" action="" method="post">  
<input type="hidden" name="appraisalId" id="appraisalId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Question<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding" valign="top">:</td>
        <td width="78%" class="padding">
        <textarea id="question" name="question" class="inputbox" style="width:295px;" cols="50" rows="4" maxlength="500" onkeyup="return ismaxlength(this);"></textarea> 
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Weightage<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding"><input type="text" id="weightage" name="weightage" class="inputbox" maxlength="5" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Active<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="contenttab_internal_rows">
         <input type="radio" name="isActive" id="isActive3" />Yes&nbsp;
         <input type="radio" name="isActive" id="isActive4" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Proof<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="contenttab_internal_rows">
         <input type="radio" name="isProof" id="isProo1" onclick="toggleProof(true,2);" />Yes&nbsp;
         <input type="radio" name="isProof" id="isProo2" onclick="toggleProof(false,2);" />No
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Proof Form<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="proofId" id="proofId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $proofFormString;
          ?>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Title<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="titleId" id="titleId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $titleString;
          ?>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Tab<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select name="tabId" id="tabId" class="inputbox" style="width:300px;">
          <option value="">Select</option>
          <?php
            echo $tabString;
          ?>
         </select>
        </td>
    </tr>
  <tr><td height="5px"></td></tr>
  <tr>
    <td align="center" style="padding-right:10px" colspan="3">
     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm(this.form,'Edit');return false;" />
     <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditAppraisalQuestion');return false;" />
    </td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Questionle-->

<?php
// $History: listAppraisalQuestionContents.php $
?>