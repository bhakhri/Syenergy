<?php 

//
//This file creates Html Form output in "Grade" Module 
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGradeDescription',315,250);cleanUpTable();return false;" />&nbsp;</td></tr>
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
    <!--Start Add Div-->

<?php floatingDiv_Start('AddGrade','Add Grade'); ?>
<form name="addGrade" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr>
                <td class="contenttab_internal_rows" width="40%"><strong>Grade Set<?php echo REQUIRED_FIELD ?></strong></td>
                <td width="2%" class="padding"><b>:</b></td>
                <td width="58%" class="padding">
                    <select size="1" class="selectField" name="gradeSetId" id="gradeSetId" style="width:144px">
                    <option value="">Select</option>
                       <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getGradeSetData(); 
                       ?>
                    </select>
                </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" width="30%"><strong>Grade<?php echo REQUIRED_FIELD ?></strong></td>
            <td class="padding"><b>:</b></td>
            <td class="padding">
            <input type="text" maxlength="20" id="gradeLabel" name="gradeLabel" style="width:142px" />
            </td>
        </tr>
         <tr>
            <td class="contenttab_internal_rows" width="30%"><strong>Grade Points<?php echo REQUIRED_FIELD ?></strong></td>
            <td class="padding"><b>:</b></td>
            <td class="padding">
            <input type="text" maxlength="6" id="gradePoints" name="gradePoints" style="width:142px" />
            </td>
        </tr>
        <tr>
            <td height="5px"></td></tr>
            <tr>
            <td align="center" style="padding-right:10px" colspan="3">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
            <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddGrade');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditGrade','Edit Grade'); ?>
<form name="editGrade" id="editGrade" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
            <input type="hidden" name="gradeId" id="gradeId" value="" /> 
            <tr>
                <td class="contenttab_internal_rows" width="40%"><strong>Grade Set<?php echo REQUIRED_FIELD ?></strong></td>
                <td width="2%" class="padding"><b>:</b></td>
                <td width="58%" class="padding">
                    <select size="1" class="selectfield" name="gradeSetId" id="gradeSetId" style="width:144px">
                    <option value="">Select</option>
                       <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getGradeSetData(); 
                       ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows" width="30%"><strong>Grade<?php echo REQUIRED_FIELD ?></strong></td>
                <td class="padding"><b>:</b></td>
                <td class="padding">
                <input type="text" maxlength="20" id="gradeLabel" name="gradeLabel" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows" width="30%"><strong>Grade Points<?php echo REQUIRED_FIELD ?></strong></td>
                <td class="padding"><b>:</b></td>
                <td class="padding">
                <input type="text" maxlength="6" id="gradePoints" name="gradePoints" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows" width="30%"><strong>Fail Grade</strong></td>
                <td class="padding"><b>:</b></td>
                <td class="padding">
                <input type="text" maxlength="20" id="failGrade" name="failGrade" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows" width="30%"><strong>Grade Status</strong></td>
                <td class="padding"><b>:</b></td>
                <td class="padding">
                <input type="text" maxlength="30" id="gradeStatus" name="gradeStatus" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td height="5px"></td>
            </tr>
            <tr>
                <td align="center" style="padding-right:10px" colspan="10">
                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                onclick="javascript:hiddenFloatingDiv('EditGrade');return false;" />
                </td>
            </tr>
            <tr>
                <td height="5px"></td></tr>
            </tr>
    </table>
</form>
    <?php floatingDiv_End();     ?>
    <!--End: Div To Edit The Table-->

<?php
//-------------------------------------------------------
// ADD GRADE DESCRIPTION
// Author :Aditi Miglani
// Created on : (19.08.2011 )
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------
?>

 <!--Start Add Div-->

<?php floatingDiv_Start('AddGradeDescription','Add Grade'); ?>
<form name="addGradeDescription" action="" method="post" onsubmit="return false;">
    <table width="150%" border="0" cellspacing="0" cellpadding="0" class="border">

    <tr>
        <td height="3px;"></td>
    </tr>

    <tr>
        <td colspan="2" class="contenttab_internal_rows" style="padding-bottom:8px;"><nobr><b>&nbsp;Grade Set:<?php echo REQUIRED_FIELD ?></b></nobr><b>:</b>&nbsp;
              <select size="1" name="gradeSetId" id="gradeSetId" class="selectField"  style='width:155px'  onchange="populateGradeDescription(this.value);    return false;">
            <option value="">SELECT</option>
                    <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getGradeSetData();       
                    ?>
              </select>
             </td>
       </tr>
    
    <tr>
            <td width="100%" colspan="2" style="width:500px;" >
            <div id="tableDiv" style="height:250px;width:520px;overflow:auto;">
                <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
            <tbody id="anyidBody">
                    <tr class="rowheading">
                        <td width="5%" class="contenttab_internal_rows" ><b>#</b></td>
                        <td width="40%" class="contenttab_internal_rows"><b>Grade</b></td>
                        <td width="20%" class="contenttab_internal_rows"><b>Grade Points</b></td>
                        <td width="20%" class="contenttab_internal_rows"><b>Fail Grade</b></td>
                        <td width="20%" class="contenttab_internal_rows"><b>Grade Status</b></td>
                        <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>    
            </td>
        </tr> 
        <tr>
            <td colspan="2">
            <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
            <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
        </td>
    </tr> 
      
    <tr>
       <td height="5px" colspan="2"></td>
    </tr>

    <tr>
            <td align="center" colspan="10">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="validateDescription();return false;" />
        <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddGradeDescription');document.addGradeDescription.gradeSetId.value='';if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
              </td> 
    </tr>
    
    <tr>
        <td height="5px" colspan="2"></td>
    </tr> 

    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
