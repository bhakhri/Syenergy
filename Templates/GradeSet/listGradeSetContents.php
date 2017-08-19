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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGrade',315,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddGrade','Add Grade Set'); ?>
<form name="addGrade" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td width="27%" class="contenttab_internal_rows1">&nbsp;&nbsp;<nobr><b>Grade Set Name<?php echo REQUIRED_FIELD ?></b></nobr></td>   
            <td class="contenttab_internal_rows1" width="3%" align="left"><b>&nbsp;:&nbsp;</b></td>
			<td width="70%" class="padding">
			    <input type="text" maxlength="20" id="gradeSetName" name="gradeSetName" style="width:160px" />
			</td>
		</tr>
         <tr>
            <td class="contenttab_internal_rows1">&nbsp;&nbsp;<nobr><b>Active<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows1" align="left"><b>&nbsp;:&nbsp;</b></td>
            <td class="padding">
            <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
            <input type="radio" id="isActive" name="isActive1" value="0" />No&nbsp;
            </td>
        </tr>
		<tr>
			<td height="10px"></td></tr>
			<tr>
			<td align="center" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddGrade');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditGrade','Edit Grade Set'); ?>
<form name="editGrade" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <input type="hidden" name="gradeSetId" id="gradeSetId" value="" /> 
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td width="27%" class="contenttab_internal_rows1">&nbsp;&nbsp;<nobr><b>Grade Set Name<?php echo REQUIRED_FIELD ?></b></nobr></td>   
            <td class="contenttab_internal_rows1" width="3%" align="left"><b>&nbsp;:&nbsp;</b></td>
            <td width="70%" class="padding">
                <input type="text" maxlength="20" id="gradeSetName" name="gradeSetName" style="width:160px" />
            </td>
        </tr>
         <tr>
            <td class="contenttab_internal_rows1">&nbsp;&nbsp;<nobr><b>Active<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows1" align="left"><b>&nbsp;:&nbsp;</b></td>
            <td class="padding">
            <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
            <input type="radio" id="isActive" name="isActive1" value="0" />No&nbsp;
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
			<td align="center" colspan="3">    
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditGrade');return false;" />
			</td>
		</tr>
	</table>
</form>
    <?php floatingDiv_End(); 	?>
    <!--End: Div To Edit The Table-->
<?php
    // $History: listGradeSetContents.php $  
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/22/09   Time: 10:52a
//Updated in $/Leap/Source/Templates/GradeSet
//link name updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/22/09   Time: 10:32a
//Updated in $/Leap/Source/Templates/GradeSet
//search condition & formatting paramter updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/21/09   Time: 6:08p
//Created in $/Leap/Source/Templates/GradeSet
//file added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 3/02/09    Time: 11:29a
//Updated in $/Leap/Source/Templates/Grade
//grade in isDecimal update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/12/09    Time: 5:46p
//Updated in $/Leap/Source/Templates/Grade
//issue fix
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/13/08   Time: 11:21a
//Updated in $/Leap/Source/Templates/Grade
//bug fix
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/10/08   Time: 3:36p
//Updated in $/Leap/Source/Templates/Grade
//New column added Grade Points
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/23/08   Time: 9:53a
//Updated in $/Leap/Source/Templates/Grade
?>