<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Fine Category LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">				  
            <tr>
             <td valign="top" class="content">
             <form action="" method="POST" name="listForm" id="listForm" onsubmit="return false;">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border1" colspan="2">
						<table width="100%" border="0" cellspacing="0px" cellpadding="0" align="left">								
				            <tr>
				               <td class="contenttab_border1" colspan="6" valign="top" style="padding-left:10px" >  
				                 <span class="contenttab_internal_rows"> 
				                   <nobr>
				                     <b><a href="javascript:void(0);" style="cursor:pointer;" onclick="getShowDetail();" class="link">
				                        <label id="lblMsg">Please Click to Show Advance Search</label>
				                        </a>
				                     </b>
				                      <img id="showInfo" style="cursor:pointer;" src="<?php echo IMG_HTTP_PATH; ?>/arrow-down.gif" onclick="getShowDetail(); return false;">
				                   </nobr>
				                 </span>
				               </td>     
				            </tr>
							<tr><td height="5px"></td></tr>
							<tr id="showhideSeats" style="display:none">
								<td class="contenttab_border1" colspan="3" valign="top" style="padding-left:10px" >
									<table width="10%" border="0" cellspacing="2" cellpadding="0" height="20">
										<tr><td height="5px"></td></tr>
										<tr>
											<td class="contenttab_internal_rows" nowrap="nowrap">  
												<nobr><b>Include Classes&nbsp;:&nbsp;</b></nobr> 
											</td>      
											<td class="contenttab_internal_rows" nowrap>
												<input name="searchClassStatus" id="searchClassStatus1" value="1"  checked="checked" type="radio">Active&nbsp;
												<input name="searchClassStatus" id="searchClassStatus3" value="3"  type="radio">Past&nbsp;
												<input name="searchClassStatus" id="searchClassStatus4" value="4"  type="radio">All
											</td>
											<td class="contenttab_internal_rows" style="padding-left:5px"><nobr><strong>Roll No./Uni. RNo.&nbsp;:&nbsp;</strong></nobr></td>
											<td class="contenttab_internal_rows"><nobr>
											   <input type="text" id="rollNo" name="rollNo" class="inputbox" autocomplete='off'  maxlength="30" style="width:145px">
											   </nobr>
											</td>
											<td class="contenttab_internal_rows" style="padding-left:5px"><nobr><strong>Student Name&nbsp;:&nbsp;</strong></nobr></td>
											<td class="contenttab_internal_rows"><nobr><strong> 											
											   <input type="text" id="studentName" name="studentName" class="inputbox" autocomplete='off'  maxlength="30" style="width:145px">
											   </nobr>
											</td>											
										</tr>
										<tr><td height="5px"></td></tr>
										<tr>
                                            <td class="contenttab_internal_rows" ><nobr><strong>Approval Status&nbsp;:&nbsp;</strong></nobr></td>
                                            <td class="contenttab_internal_rows"><nobr><strong> 
                                                <select size="1" class="selectfield" name="status" id="status" style="width:135px;">
                                                    <?php 
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    echo HtmlFunctions::getInstance()->getStatus();
                                                    ?>
                                                </select>
                                            </td>
											<td class="contenttab_internal_rows" style="padding-left:5px"><nobr><strong>Fine Category&nbsp;:&nbsp;</strong></nobr></td>
											<td class="contenttab_internal_rows"><nobr><strong> 
												<select size="1" class="htmlElement" name="fineCategory" id="fineCategory" style="width:145px;">
											   	<option value="">All</option>
													<?php 
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getFineCategory();
													?>
												</select>
											</td>
                                            <td class="contenttab_internal_rows" style="padding-left:5px" align="left">
                                              <a href="" title="Click to Clear Date Check" alt="Click to Clear Date Check" onClick="getDateCheck(); return false;">
                                                <nobr><b>Fine Date&nbsp;:&nbsp;</b></nobr>
                                              </a>
                                            </td>
											<td class="contenttab_internal_rows" colspan="10">
                                              <table width="10%" border="0" cellspacing="0" cellpadding="0" height="20">
                                               <tr>  
                                                <td class="contenttab_internal_rows"> 
                                                    <nobr>From&nbsp;&nbsp;</nobr>
                                                </td>
											    <td class="contenttab_internal_rows"><nobr> 
												    <?php
												       require_once(BL_PATH.'/HtmlFunctions.inc.php');
												       echo HtmlFunctions::getInstance()->datePicker('startDate','');
												    ?>
                                                </td>
												<td class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;To&nbsp;&nbsp;</nobr></td>
                                                <td class="contenttab_internal_rows"><nobr> 
												    <?php
												       require_once(BL_PATH.'/HtmlFunctions.inc.php');
												       echo HtmlFunctions::getInstance()->datePicker('toDate','');
												    ?>
											    </td>
											    <td class="contenttab_internal_rows" style="padding-left:35px"><nobr>
												    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getFineStudent(); return false;"/>
												    </nobr> 
											     </td>
                                               </tr>
                                             </table>  
                                           </td>    
										</tr>
										<tr>
											<td colspan="14" height="5px"></td>
										</tr>	
									</table>
								</td>
							</tr>
					</table>
				  </td>     
				</tr>
	            <tr>
	               <td class="contenttab_border" height="20">
	                   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
	                   <tr>
	                      <td class="contenttab_border" height="20" style="border-right:0px;">
	                         
                           </td>
	                       <td title="Add" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;padding-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
	                       align="right" onClick="displayWindow('AddFineStudent',360,250);blankValues();return false;" />&nbsp;</td>
	                   </tr>
	                   </table>
	               </td>
	            </tr>
	            <tr>
	            	<td class="contenttab_row" valign="top" >
	                	<div id="results">
	               		</div>           
	            	</td>
	          	</tr>
          		<tr><td height="10px"></td></tr>
          		<tr>
           			<td align="right">
                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/delete.gif" onClick="return deleteAllFine();return false;" />&nbsp; 
             			<input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
          			</td>
          		</tr> 
          </table>
          </form>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>    

<!--Start Add Div-->
<?php floatingDiv_Start('AddFineStudent','Add Student Fine'); ?>
<form name="AddFineStudent" action="" method="post">  
<select size="1" class="selectfield" name="fineAllowClassId" id="fineAllowClassId" style="width:226px;display:none">
</select>

<input type="hidden" name="instituteId1" id="instituteId1" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr><td height="5px" colspan="3"></td></tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" /><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox1"  onchange="getStudent(this.value,'Add');" style="width:226px" />
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Student Name </b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="studentName1" id="studentName1" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="className1" id="className1" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Fine Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="fineCategoryId" id="fineCategoryId" style="width:226px">
         </select>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getRoleFineCategory();
              ?>      
    </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('fineDate1',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="fineAmount" name="fineAmount" class="inputbox1" maxlength="8" style="width:226px">
        </td>
    </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Reason</b></nobr></td>
        <td class="padding" valign="top"><b>:</b></td>
        <td width="79%" class="padding">
           <textarea name="remarksTxt" style="width:226px" id="remarksTxt" cols="32" rows="3" onkeyup="return ismaxlength(this)" maxlength="400"  class="inputbox1"></textarea>
        </td>
 </tr>
 <!--<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Add to "No Dues"</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="dueStatus" id="dueStatus" style="width:60px">
         <option value="1">Yes</option>
         <option value="0">No</option>
        </select></td>
 </tr> -->
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFineStudent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="2px" colspan="3"></td></tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFineStudent','Edit Student Fine'); ?>
<form name="EditFineStudent" action="" method="post">  
<select size="1" class="selectfield" name="fineAllowClassId" id="fineAllowClassId" style="width:226px;display:none">
</select>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="oldDueAmount" id="oldDueAmount" value="" />
    <input type="hidden" name="fineStudentId" id="fineStudentId" value="" />
    <input type="hidden" name="studentId" id="studentId" value="" />
    <input type="hidden" name="classId" id="classId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><input type="hidden" name="oldDueStatus" id="oldDueStatus" value="" /><nobr><b>Roll No.<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox1" maxlength="30" size="35" readonly/>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name </b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="studentName2" id="studentName2" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class</b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
          <div name="className2" id="className2" style="display:inline;padding:5px" />
        </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Fine Category<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <select size="1" class="selectfield" name="fineCategoryId" id="fineCategoryId" style="width:226px">
         <option value="">Select</option>
              <?php
                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  //echo HtmlFunctions::getInstance()->getRoleFineCategory();
              ?>
        </select>
    </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('fineDate2',date('Y-m-d'));
              ?>
      </td>
 </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Amount<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding">
         <input type="text" id="fineAmount" name="fineAmount" class="inputbox1" maxlength="8" size="9"/>
        </td>
    </tr>
 <tr>
        <td width="21%" class="contenttab_internal_rows" valign="top"><nobr><b>Reason</b></nobr></td>
        <td class="padding" valign="top"><b>:</b></td>
        <td width="79%" class="padding"><textarea name="remarksTxt" id="remarksTxt" cols="32" rows="3" onkeyup="return ismaxlength(this)" maxlength="400" class="inputbox1"></textarea></td>
 </tr>
 <!--<tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Add to "No Dues"<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
        <td width="79%" class="padding"><select size="1" class="selectfield" name="dueStatus" id="dueStatus" style="width:60px">
         <option value="1">Yes</option>
         <option value="0">No</option>
        </select></td>
 </tr>--> 
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFineStudent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->
 