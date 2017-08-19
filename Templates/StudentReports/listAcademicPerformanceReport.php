<?php 
//This file creates Html Form output for attendance report
//
// Author :Rajeev Aggarwal
// Created on : 12-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
	<tr>
		<td >
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<form name="testWiseMarksReportForm" id="testWiseMarksReportForm" action="" method="post" onSubmit="return false;">
						    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border1">
							    <tr>
								    <td valign="top" class="contenttab_border2">
										 <table width="100%" align="center"  border="0" cellspacing="4px" cellpadding="0">
                                            <tr>
                                                <td class="contenttab_internal_rows"><nobr><strong>Time Table</strong></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" width="15%">
                                                  <nobr>
                                                      <select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:180px" onChange="getLabelClass()">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                                        ?>
                                                      </select>&nbsp;
                                                   </nobr>    
                                                </td>
                                                <td class="contenttab_internal_rows"><nobr><strong>Degree</strong></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" width="15%">
                                                <nobr>
                                                    <select size="1" class="selectfield" name="degree" id="degree" style="width:280px" onchange="hideResult();">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getSessionClasses();?>
                                                    </select>&nbsp;
                                                </nobr>    
                                                </td>
                                                <td class="contenttab_internal_rows"><nobr><strong>Test Type</strong></nobr></td>
                                                <td class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" width="60%">
                                                   <nobr>
                                                      <select name="testCategoryId"  class="selectfield" id="testCategoryId" style="width:130px" onchange="hideResult();">
                                                        <option value="">Select</option>
                                                            <?php 
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->getTestTypeCategory(" WHERE examType='PC'");?>
                                                       </select>
                                                    </nobr>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="contenttab_internal_rows" ><nobr><strong>From Date</strong></nobr></td>
                                                <td class="contenttab_internal_rows" ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" >
                                                   <nobr>    
                                                    <?php
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                    ?>
                                                  </nobr>  
                                                </td>
                                                <td class="contenttab_internal_rows" ><nobr><strong>To Date</strong></nobr></td>
                                                <td class="contenttab_internal_rows" ><nobr><strong>:</strong></nobr></td>
                                                <td class="contenttab_internal_rows" colspan="4" valign="top" width="60%">
                                                  <?php
                                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                               echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                        ?>
                                                     </nobr> 
                                                </td>          
                                               </tr>
                                               <tr>
                                                <td class="contenttab_internal_rows" valign="top" colspan="10" width="60%">
                                                  <table cellpadding="0px" cellspacing="0px" border="0px">
                                                    <tr>
                                                         <td class="contenttab_internal_rows" > 
                                                            <nobr>    
                                                                                                                 
                                                         <td class="contenttab_internal_rows" >
                                                            <nobr><strong>Include</strong></nobr>
                                                         </td>
                                                         <td class="contenttab_internal_rows" ><nobr><strong>:</strong></nobr>&nbsp;</td>
                                                         <td class="contenttab_internal_rows" ><nobr><strong>
                                                              <input class="inputbox1"  onclick="setSignature();" type="checkbox" id="signature" name="signature" value="1">
                                                         </td>
                                                         <td class="contenttab_internal_rows" ><nobr>Signature</nobr></td>
                                                         <td class="contenttab_internal_rows" style='display:none;' id='signatureHide'>
                                                               <nobr><input type="text" id="signatureContents" name="signatureContents" class="inputbox" style="width:150px" maxlength="50"/></nobr>
                                                         </td>
                                                         <td class="contenttab_internal_rows" ><nobr><strong>
                                                            <input class="inputbox1"  type="checkbox" id="photo" name="photo" value="1">
                                                         </td>
                                                         <td class="contenttab_internal_rows" ><nobr>Photo</nobr></td>  
                                                         <td class="contenttab_internal_rows" style="padding-left:10px" ><nobr><strong>
                                                            <input class="inputbox1" onclick="setAddress();" type="checkbox" id="addressChk" name="addressChk" value="1">
                                                         </td>
                                                         <td class="contenttab_internal_rows" ><nobr>Address</nobr></td>
                                                         <td class="contenttab_internal_rows">
                                                            <div id='addressHide' style='display:none'>
                                                                <table cellpadding="0px" cellspacing="0px" border="0px">
                                                                     <tr>
                                                                        <td class="contenttab_internal_rows" ><nobr>
                                                                            <input class="inputbox1" checked="checked" type="radio" id="address" name="address" value="1">
                                                                            </nobr>
                                                                        </td>
                                                                        <td class="contenttab_internal_rows" ><nobr><B>Corr. Address</b></nobr></td>  
                                                                        <td class="contenttab_internal_rows" ><nobr><strong>
                                                                            <input class="inputbox1" type="radio" id="address" name="address" value="2">
                                                                        </td>
                                                                        <td class="contenttab_internal_rows" ><nobr><b>Perm. Address</b></nobr></td>  
                                                                     </tr>
                                                               </table>
                                                            </div>   
                                                         </td>
                                                         <td class="contenttab_internal_rows" valign="top" style="padding-left:15px"><nobr>
                                                         <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                         </nobr>
                                                         </td>
                                                     </tr>  
                                                  </table>  
                                                </td>    
                                            </tr>    
                                        </table>   
								    </td>
							    </tr>
						    </table>
						    <table width="100%" border="0" cellspacing="0" cellpadding="0">
							    <tr id='nameRow' style='display:none;'>
								    <td class="" height="20">
									    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										    <tr>
											    <td colspan="1" class="content_title">Student Performance Reports:</td>
											    <td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
										    </tr>
									    </table>
								    </td>
							    </tr>
							    <tr id='resultRow'>
								    <td  colspan="2" class='contenttab_row'><div id ='resultsDiv'></div></td>
							    </tr>
							    <tr>
								    <td height="10"></td>
							    </tr>
							    <tr id='resultRow2' style="display:none">
								    <td align="right" width="55%">
								    <input type="hidden" name="submitSubject" value="1">
								    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/generate.gif"   onclick="return validateAddForm1();return false;" /></td>
								     
							    </tr>
						    </table>
						<!-- form table ends -->
						</form>
					</td>
				</tr>
			</table>
       </td>
    </tr>           
</table>

<?php 
//$History: listAcademicPerformanceReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/04/09    Time: 10:38a
//Updated in $/LeapCC/Templates/StudentReports
//Removed print button which was added by mistake
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/02/09    Time: 10:48a
//Created in $/LeapCC/Templates/StudentReports
//Intial checkin
?>
