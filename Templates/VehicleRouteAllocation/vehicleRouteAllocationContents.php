<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Vehicle Route Allocation
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="searchForm" id="searchForm" action="" method="post">

<?php
    require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td height="15px"></td></tr>
                <tr>
                   <td class="contenttab_border1" colspan="3" valign="top" style="padding-left:10px" >  
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
                <tr id="showhideSeats" style="display:none"> 
                        <td class="contenttab_border1" colspan="3" valign="top" style="padding-left:10px" >
                                 
                                <table width="10%" border="0" cellspacing="0" cellpadding="0" height="20">
                                    <tr><td height="15px"></td></tr>
                                    <tr>
                                       <td class="contenttab_internal_rows" nowrap="nowrap" colspan="10">  
                                         <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
                                            * Pending Pass for those who paid the fees.
                                         </span>
                                       </td>
                                    </tr> 
                                    <tr><td height="15px"></td></tr>    
                                    <tr>
                                        <td class="contenttab_internal_rows" nowrap="nowrap">  
                                            <b>Include Classes</b> 
                                        </td>    
                                        <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                        <td class="contenttab_internal_rows"  nowrap>
                                            <input name="searchClassStatus" id="searchClassStatus1" value="1" onclick="resetForm();"  type="radio">Active&nbsp;
                                            <input name="searchClassStatus" id="searchClassStatus2" value="2" onclick="resetForm();" type="radio">Futrue
                                            <input name="searchClassStatus" id="searchClassStatus3" value="3" onclick="resetForm();" type="radio">Past
                                            <input name="searchClassStatus" id="searchClassStatus4" value="4" onclick="resetForm();" checked="checked" type="radio">All
                                        </td>
                                       
                                        <td class="contenttab_internal_rows" nowrap="nowrap" style="padding-left:15px">  
                                            <b>Stop City</b> 
                                        </td>    
                                        <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                        <td class="contenttab_internal_rows"  nowrap>
                                            <select size="1" class="selectfield" name="searchStopCity" id="searchStopCity" onChange="resetForm();"  style="width:240px">
                                            <option value="">All</option>
                                               <?php
                 								 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  								 echo HtmlFunctions::getInstance()->fetchBusStopCity();
              									?>
                                            </select>
                                        </td>
                                        <td class="contenttab_internal_rows" nowrap="nowrap" style="padding-left:5px">  
                                            <b>Route Name</b> 
                                        </td>   
                                        <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                        <td class="contenttab_internal_rows"  nowrap>
                                          <select size="1" class="selectfield" name="searchRouteName" id="searchRouteName" style="width:180px"  >
                                            <option value="">All</option>
                                          </select>
                                        </td>
                                       </tr>
                                          
                                     <tr><td height="8px"></td></tr>
                                     <tr>
                                        <td class="contenttab_internal_rows" nowrap="nowrap" >   
                                            <b>Roll No./Reg No./Uni. RNo.</b> 
                                         </td>    
                                         <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                         <td class="contenttab_internal_rows"  nowrap>
                                            <input type="text" name="searchRollNo" id="searchRollNo" class="inputbox"> 
                                            
                                       <td class="contenttab_internal_rows" align="left" nowrap>
                                           <b>Student Name</b>
                                       </td>
                                       <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>    
                                       <td class="contenttab_internal_rows"  nowrap>
                                          <input type="text" name="searchStudentName" id="searchStudentName" class="inputbox" style="width:235px"> 
                                       </td>
                                       <td class="contenttab_internal_rows" align="left" nowrap  style="padding-left:5px">
                                           <b>Father's Name</b>
                                       </td>
                                       <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>    
                                       <td class="contenttab_internal_rows"  nowrap>
                                          <input type="text" name="searchFatherName" id="searchFatherName" class="inputbox"> 
                                       </td>
                                     </tr>
                                     <tr><td height="8px"></td></tr>  
                                        <td class="contenttab_internal_rows" align="left" nowrap colspan="10">
                                           <table width="10%" border="0" cellspacing="0" cellpadding="0"> 
                                            <tr>
                                               <td class="contenttab_internal_rows" align="left" nowrap>
                                                   <b>Show For</b>
                                               </td>
                                               <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                               <td class="contenttab_internal_rows" align="left" nowrap>
                                                    <select size="1" class="selectfield" name="searchFor" id="searchFor" style="width:120px">
                                                        <option selected="selected" value="">All</option>
                                                        <option value="1">Pending Pass</option>
                                                        <option value="2">Generated Pass</option>
                                                        <option value="3">Total Paid</option>
                                                        <option value="4">Un Paid</option>
							<option value="5">Pending Fee</option>  
                                                    </select>   
                                               </td>  
                                               <td class="contenttab_internal_rows" align="left" nowrap style="padding-left:20px">
                                                   <b>Date Check</b>
                                               </td>
                                               <td class="contenttab_internal_rows" nowrap="nowrap"><b>&nbsp;:&nbsp;</b></td>        
                                               <td class="contenttab_internal_rows" align="left" nowrap>
                                                    <select size="1" class="selectfield" name="searchDate" id="searchDate" onChange="getShowSearch(this.value);" style="width:120px">
                                                       <option selected="selected" value="">Select</option>
                                                       <option value="1">Date Check</option>
                                                    </select>   
                                               </td>     
                                               <td class="contenttab_internal_rows" align="left" nowrap style="display:none;padding-left:10px" id='searchDt1'>  
                                                    <strong>From Date</strong>  
                                               </td>
                                               <td class="contenttab_internal_rows" align="left" style="display:none;" nowrap id='searchDt2'><strong>:</strong></td>
                                               <td class="contenttab_internal_rows" align="left" style="display:none;" nowrap id='searchDt3'>
                                                  <?php 
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                    echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                  ?>
                                               </td>    
                                               <td class="contenttab_internal_rows" align="left" nowrap style="display:none;padding-left:10px" id='searchDt4'>         
                                                 <strong>To Date</strong>
                                               </td>
                                               <td class="contenttab_internal_rows" align="left" style="display:none;" id='searchDt5' nowrap><strong>:</strong></td>
                                               <td class="contenttab_internal_rows" align="left" style="display:none;" nowrap id='searchDt6'>
                                                   <?php 
                                                      require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                      echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                   ?>
                                               </td>
                                               <td class="contenttab_internal_rows"  nowrap style="padding-left:40px">  
                                                   <input onClick="getShowList(); return false;" type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" >
                                               </td>
                                            </tr>
                                          </table>
                                        </td>       
                                     </tr>
                                     <tr><td height="15px"></td></tr>
                                </table> 
                         
                        </td>     
                    </tr> 
					<tr height="30">
						<td class="contenttab_border" height="20" style="border-right:0px;">
							<?php  //require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                        </td>
                       <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                          <!-- 
                          <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('EmployeeVechileRouteAllocation',315,250);blankValues(2);return false;"/>&nbsp;
                          -->
                          <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('VechileRouteAllocation',315,250);blankValues(1);return false;"/>&nbsp;
                        </td>
                     </tr>
                    <tr>
			<td class="contenttab_row"   colspan="2" valign="top" ><div  id="results"></div></td>
                    </tr>

				 <tr>
                            <td align="center" colspan="3" style="display:none" id='printRow2' class="contenttab_internal_rows">
                              <center> <b>Please click to Show List Button</b></center>
                            </td>
			
                         </tr>   

                         <tr>

				 <td class="contenttab_internal_rows" colspan="3" id='printRowNote' style="color:red">
               			<center><b>Click on Show List Button to see the details</b></center>
            			</td> 
				<td align="right" colspan="3" id='printRow'  style="display:none">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
				 <tr>
                     <td class="content_title" valign="middle" align="right" width="20%">
				              <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/generate_pass.gif"  onClick="generatePass(); return false;"  >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport(); return false;" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV(); return false;" >
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
</form>             
    <!--Start Add Div-->

<?php floatingDiv_Start('VechileRouteAllocation','Add Vehicle Route Allocation for Student'); ?>
    <form name="addVechileRouteAllocation" action="" method="post">
    <input type="hidden" name="studentId" id="studentId" value="" />
     
 <input type="hidden" name="assignTransportCharges" id="assignTransportCharges" value="" />    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Roll No. / Reg No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <input type="text" id="rollRegNo" name="rollRegNo" class="inputbox" maxlength="20" autocomplete='off' style="width:360px" onchange="getStudentData(this.value,'Add');" />
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Name </b></nobr></td>
        <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        &nbsp;<label id="studentName1" /></td>
    </tr>
    <tr id='showPrevious1' style='display:none'>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Previous Status</b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
           <a href="" onclick='getStatus(); return false;'>Please click to check previous status</a>
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Class<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
            <select size="1" class="selectfield" name="classId" id="classId" style="width:360px" onChange="getFeeCycle(this.value,'A');return false;">
               <option value="">Select</option>
            </select>
        </td>
    </tr>
    <tr>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Fee Cycle<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" style="width:360px" name="feeCycleId" id="feeCycleId" onChange="getFeeCycleDate(this.value,'A','Cycle'); return false;">
            <option value="">Select</option>
        </select>
    </td>
</tr> 
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Stop City<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" style="width:360px" name="stopCity" id="stopCity" onchange="getStopName(this.value,'Add', '');">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->fetchBusStopCity();
              ?>
        </select>
    </td>
   </tr>
   <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Stop Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" style="width:360px" name="stopName" id="stopName" onchange="getRouteName(this.value,'Add','');" >
        <option value="">Select</option>
        </select>
    </td>
   </tr>
   
   <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Route Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" style="width:360px" onChange="getFeeCycleDate(this.value,'A','Rent'); return false;" name="rootName" id="rootName">
            <option value="">Select</option>
        </select>
    </td>
  </tr>
 <tr>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Seat Number<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <input type="text" style="width:360px" id="seatNumber" name="seatNumber" class="inputbox" maxlength="20"  />
        </td>
</tr>
<tr>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Charges<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        &nbsp;<input type="text" style="width:360px" id="transportCharges" name="transportCharges" class="inputbox" maxlength="20"  />   
    </td>
</tr> 
<tr>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Valid From<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
     <td width="70%" class="padding">
       <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('validFrom',date('Y-m-d'));
       ?>
    </td>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Valid To<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="70%" class="padding">:
       <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->datePicker('validTo','');
       ?>
    </td>
</tr> 
<tr>
        <td width="30%" valign="top" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Comments</b></nobr></td>
         <td width="30%" valign="top"  class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <textarea type="text" id="comments" name="comments" maxlength="1000" class="inputbox1" rows="2" cols="10" style="width:357px"></textarea>
        </td>  
  </tr>   
  
<tr>
    <td height="9px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="6">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('VechileRouteAllocation');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>

</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->




<!--Start Edit Div-->
<?php floatingDiv_Start('EditVehicleRouteAllocationDiv','Edit Vehicle Route Allocation '); ?>
<form name="EditVehicleRouteAllocation" action="" method="post">
 <input type="hidden" name="busRouteStudentMappingId" id="busRouteStudentMappingId"/>
  
 <input type="hidden" name="assignTransportCharges" id="assignTransportCharges" value="" />    
 
  <input type="hidden" name="studentId" id="studentId" value="" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="15px"></td>
  </tr>
    <tr>
<td colspan="8">
<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 12px; color: red; font-weight: normal;  "> <b>
          &nbsp;&nbsp;NOTE:Changes in the Vehicle Charges can only be done by "ACCOUNTS DEPARTMENT"
</b>
        </span>
</td>
</tr>
<tr>
    <td height="15px"></td>
  </tr>
<tr>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Roll No. / Reg No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <input type="text" id="rollRegNo" name="rollRegNo" class="inputbox" readonly='true' maxlength="20" disabled="disabled" onblur="getStudentData(this.value,'Edit');" /></td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Name </b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4"><label id="studentName2" /></td>
    </tr>
      <tr id='showPrevious2' style='display:none'>    
       <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Previous Status</b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
           <a href="" onclick='getStatus(); return false;'>Please click to check previous status</a>
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Class </b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
            <select size="1" class="selectfield" name="classId" style="width:360px"  id="classId" disabled="disabled">
               <option value="">Select</option>
            </select>
        </td>
    </tr>
    <tr>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Fee Cycle<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" style="width:360px" name="feeCycleId" id="feeCycleId" onChange="getFeeCycleDate(this.value,'A','Cycle'); return false;">
            <option value="">Select</option>
        </select>
    </td>
</tr> 
        <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Stop City<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        <select size="1" class="selectfield" name="stopCity" id="stopCity" style="width:360px" onchange="getStopName(this.value,'Edit','');">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->fetchBusStopCity();
              ?>
        </select>
    </td>
   </tr>
   <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Stop Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4"><select size="1" class="selectfield" name="stopName" id="stopName" style="width:360px" onchange="getRouteName(this.value,'Edit','');" >
        <option value="">Select</option>
        </select>
    </td>
   </tr>
   
   <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Route Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4"><select size="1" class="selectfield" name="rootName" id="rootName" style="width:360px" onChange="getFeeCycleDate(this.value,'A','Rent'); return false;">
            <option value="">Select</option>
        </select>
    </td>
  </tr>
    <tr>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Seat Number<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4"><input type="text" id="seatNumber" name="seatNumber" class="inputbox" style="width:360px" maxlength="20"  />

</td>
    </tr>
    <tr>
    <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Charges<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        &nbsp;<input type="text" style="width:360px" id="transportCharges" name="transportCharges" class="inputbox" maxlength="20"  />   
    </td>
</tr> 

  <tr>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Valid From<?php echo REQUIRED_FIELD ?></b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding">
           <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('editValidFrom','');
           ?>
        </td>
        <td width="30%" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Valid To </b></nobr></td>
        <td width="70%" class="padding">:
           <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('editValidTo','');
           ?>
           <!-- <input type="checkbox" name="resetChk" onclick="document.EditRoomAllocation.chkOut2.value='';"><span class="contenttab_internal_rows">Reset</span>-->
        </td>
  </tr>
<tr>
        <td width="30%" valign="top" class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Comments</b></nobr></td>
         <td width="30%" class="contenttab_internal_rows"><b>&nbsp;:</b></td>
        <td width="70%" class="padding" colspan="4">
        &nbsp;<textarea type="text" id="comments" name="comments" maxlength="1000" class="inputbox1" rows="2" cols="10" style="width:357px"></textarea>
        </td>  
  </tr>   
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" colspan="5">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
        <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditVehicleRouteAllocationDiv');return false;" />
    </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<!--Start Update Div-->
<?php floatingDiv_Start('UpdateRoomAllocation','Room Occupants Detail'); ?>
    <form name="UpdateAllocation" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="studentId" id="studentId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td colspan="2">
         <div id="updateAllocationDiv" style="height:200px;overflow:auto;"></div>
        </td>
    </tr>
    <tr><td height="5px" colspan="2"></td></tr>
   <tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateOccupantForm();return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('UpdateRoomAllocation');return false;" />
    </td>
   </tr>
   <tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<!--End Update Div-->
<?php floatingDiv_Start('previousTransportFacility','Previous Detail'); ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border"> 
    <tr>
        <td> 
           <div style="overflow:auto; height:350px; vertical-align:top;">  
             <div id="previousTransportFacilityDiv"></div>
           </div> 
        </td>
    </tr>
  </table>
<?php floatingDiv_End(); ?>
