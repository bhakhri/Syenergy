<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="POST" name="listForm" id="listForm" onsubmit="return false;">
    <input type="hidden" value="" name="editStopRouteId" id="editStopRouteId">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
 				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border2" colspan="2">
							<table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" ><nobr><b>Time Table<?php echo REQUIRED_FIELD?></b></nobr></td>
								<td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
								<td class='contenttab_internal_rows' align='left'><nobr>
									<select size="1" class="inputbox1" name="labelId" id="labelId" class="inputbox1" style="width:120px" onChange="hideResults(); return false;">
									<option value="">Select</option>
									<?php
									  require_once(BL_PATH.'/HtmlFunctions.inc.php');
									  echo HtmlFunctions::getInstance()->getAllTimeTableLabelData();
									?>
									</select></nobr>
								</td>
								<td class="contenttab_internal_rows" style='padding-left:10px;'><nobr><b>Bus Route<?php echo REQUIRED_FIELD?>: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="routeId" id="routeId"  style="width:250px" onChange="hideResults(); return false;">
								<option value="">Select</option>
								<?php
									require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getBusRouteName();
								?>
								</select></td>
								<td  align="right" style="padding-left:15px">
								<input type="hidden" name="listRoute" value="1">
								<input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getStop(); return false;"/></td>
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>
							</table>
					    </td>
					</tr>
					<tr style="display:none" id="showTitle">
						<td class="contenttab_border" height="20" colspan="2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title" nowrap>Bus Route Stop Mapping : </td>
								<td align="right"  nowrap id = 'saveDiv2' style='display:none;'>
                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" />
                                </td>
							</tr>
							</table>
						</td>
					</tr>
					 <tr style="display:none" id="showData">
						<td class="contenttab_row" valign="top" colspan="2">
                           <div id="scroll2" style="overflow:auto; height:410px; vertical-align:top;">
                              <div id="resultsDiv"></div> 
                           </div>   
                        </td>
					 </tr>
					 <tr>
						<td height="10" colspan="2"></td>
					 </tr>
					 <tr  id = 'saveDiv1' style='display:none;'>
						<td align="right" width="55%">
						     <input type="hidden" name="submitRoute" value="1">
						     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                             <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCourseToClassCSV();return false;"/>
                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onclick="return validateAddForm();return false;" />
                         </td>
					</tr>
				</table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
</form>    

