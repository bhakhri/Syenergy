<?php
//-------------------------------------------------------
// Purpose: to design the layout for Requisition Master.
//
// Author : Jaineesh
// Created on : (02 Aug 2010)
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
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?></td>
                            <!--    <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddItem',360,250);blankValues();return false;" />&nbsp;</td></tr>-->
							 <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							 </tr>
							 <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" title="Print">&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" title="Export To Excel">
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
<?php floatingDiv_Start('EditApprovedRequisition','Approved Requisition'); ?>
<form name="EditApprovedRequisition" action="" method="post">
	<input type="hidden" name="requisitionId" id="requisitionId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
	<tr>
        <td><div id="approvedRequisitionDiv" style="display:none;overflow:auto;height:200px;" ></div></td>
	</tr>
	<tr><td height="5px" colspan="3"></td></tr>
        <tr>
            <td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" title ="Approve" src="<?php echo IMG_HTTP_PATH;?>/approve.gif" onClick="approvedRequisition();return false;" />
			<input type="image" name="imageField" title ="Reject" src="<?php echo IMG_HTTP_PATH;?>/reject.gif" onclick="cancelledRequisition();return false;"  />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php
floatingDiv_End(); 
// $History: $
//
?>