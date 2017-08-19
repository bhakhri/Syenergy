<?php 
//-------------------------------------------------------
//  This File contains html form for all Student Internal Reappear Contents
//
//
// Author :Harpreet Kaur
// Created on : 17-may-2013
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
 require_once(BL_PATH . "/UtilityManager.inc.php");
   global $sessionHandler; 
   
$hostelStudentName = $sessionHandler->getSessionVariable('StudentName');
$hostelRollNo = $sessionHandler->getSessionVariable('RollNo');
$hostelFatherName = $sessionHandler->getSessionVariable('FatherName');
$hostelClassName = $sessionHandler->getSessionVariable('ClassName');
					
							
?>
<form name="hostelRegForm" id="hostelRegForm" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
                        <td class="content_title"> Apply for New or Re-New Hostel Facilities </td>
                        <td class="contenttab_row" align="center"></td>
                    </tr>
                    </table>
                 </td>
                </tr>
              <tr>
                <td  class="contenttab_row" colspan="3" >
                	  
                      <table width="100%" border="0" cellspacing="2px" cellpadding="3px" class="border">
	  						<tr class="rowheading" align="left" style="height:24px;">  
							   <td class="padding_top" style="padding-left:10px;" align="left" colspan="6">
							   <nobr><font color="red"><b>Student Details</b></font></nobr>
                              </td>
							 </tr>    
                           </table>  
                           <table width="80%" border="0" cellspacing="2px" cellpadding="3px" align="center" class="border">                              
                              <tr>
                                  <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                    <nobr><b>Student Name</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <?php echo $hostelStudentName; ?></nobr></td> 
                                  <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                     <nobr><b>Father Name</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                   <?php echo $hostelFatherName; ?></nobr>
                                  </td> 
                              </tr>
                              <tr>
                              	   <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap><nobr><b>Roll No.</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <?php echo $hostelRollNo; ?></nobr></td> 
                                   <td  class="contenttab_internal_rows" style="padding-left:10px;" nowrap><nobr><b>Current Class</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap colspan="4"><nobr>&nbsp;
                                  <?php echo $hostelClassName; ?></nobr>
                                   </td>                                        
                              </tr> 
                             
                            </table>
                             <table width="100%" border="0" cellspacing="2px" cellpadding="3px" class="border">
	  						<tr class="rowheading" align="left" style="height:24px;display:none;" id="hostelD1" >  
							   <td class="padding_top" style="padding-left:10px;" align="left" colspan="6">
							   <nobr><font color="red"><b>Prev. Hostel Details</b></font></nobr>
                              </td>
							 </tr>    
                           </table>  
                      		<table width="80%" border="0" cellspacing="2px" cellpadding="3px" align="center" class="border">                              
                              <tr id="hostelD2" style="display:none;">
                                  <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                    <nobr><b>Hostel Name</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <span id="hostelName"></span></nobr></td> 
                                   <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                    <nobr><b>Room Type</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <span id="roomType"></span></nobr></td>
                                </tr> 
                                <tr id="hostelD3" style="display:none;">
                                  <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                    <nobr><b>Room No</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                    <span id="roomNo"></span></nobr>
                                   </td> 
                                   <td  class="contenttab_internal_rows" width="8%" style="padding-left:10px;" nowrap>
                                    <nobr><b>CheckOut Date</b></nobr>
                                  </td>
                                  <td  class="contenttab_internal_rows" nowrap width="2%"><nobr><b>:</b></nobr></td>
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <span id="checkOutDate"></span></nobr></td>
                                </tr> 
                                <tr id="hostelD4">
								  <td class="contenttab_internal_rows" height="2px;" colspan="3"></td>	
								</tr>
                               </table>
                                <table width="100%" border="0" cellspacing="2px" cellpadding="3px" class="border" >
		  						<tr class="rowheading" align="left" style="height:24px;" >  
								   <td class="padding_top" style="padding-left:10px;" align="left" colspan="6">
								   <nobr><font color="red"><b>Details</b></font></nobr>
	                              </td>
								 </tr>    
	                           </table>  
	                            <table width="60%" border="0" cellspacing="5px" cellpadding="3px" align="center" class="border" id="tblDetails" style="display:none;">
	                           <tr id="trDate1">                              	   
                                   <td  class="contenttab_internal_rows"  nowrap><nobr><b>Apply Date</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap ><nobr>&nbsp;
                                  <span id="applyDate">
                                  	<?php 	echo date('d-m-y');
                                  ?></span></nobr>
                                   </td>                                        
                              </tr>                              
                             <tr id="trDate2">
					           <td class="padding_top" width="100%" nowrap="nowrap"  style="padding-left:10px;font-size:12px;" colspan="3">
					             <nobr><b><font color="red"><u>Select Room Type (You have 3 choices)</u>&nbsp;:</font></b></nobr>                
					           </td>
					        </tr>
					         <tr id="trCancelRegister" style="display:none;">
					         	 <td class="padding_top" width="100%" nowrap="nowrap"  style="padding-left:10px;font-size:15px;" colspan="3">
					             <nobr><b><font color="red">Your Request for Hostel is set <span id="wardenStatus" name="wardenStatus"></span> by Warden</u></font></b></nobr>                
					           </td>
					        </tr>
					         <tr id="trCancelRegisterComments" style="display:none;">
					         	<td  class="contenttab_internal_rows"  nowrap><nobr><b>Warden comments</b></nobr></td>
                                   <td  class="contenttab_internal_rows" nowrap><nobr><b>:</b></nobr></td>					          
					           <td class="padding_top" width="100%" nowrap="nowrap"  style="padding-left:10px;font-size:12px;" >
					             <nobr><b><span id="wardenComments" name="wardenComments"></span></u></b></nobr>                
					           </td>
					        </tr>
					         <tr id="trDate3" style="display:none;">
					           <td class="padding_top" width="100%" nowrap="nowrap"  style="padding-left:10px;font-size:12px;" colspan="3">
					             <nobr><b><font color="red"><u>ROOM TYPE</u>&nbsp;:</font></b></nobr>                
					           </td>
					        </tr>
                               <tr id="applyPref1"> 
                              	<td  class="contenttab_internal_rows" width="8%"  nowrap >
                                     <nobr><b>Preference 1</b></nobr>
                                  </td>  
                                  <td  class="contenttab_internal_rows" width="2%" nowrap><nobr><b>:</b></nobr></td>                               
                                  <td  class="contenttab_internal_rows" width="90%" nowrap><nobr>&nbsp;
                                  <select id="registRoomTypeId" name="registRoomTypeId" class="selectfield" style="width:250px;">
									<option value="">Select</option>
									
								</select></nobr>
                                  </td> 
                              </tr>
                               <tr id="applyPref2"> 
                               	<td  class="contenttab_internal_rows" width="8%"  nowrap  >
                                     <nobr><b>Preference 2</b></nobr>
                                  </td> 
                                  <td  class="contenttab_internal_rows" nowrap><nobr><b>:</b></nobr></td>                                
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <select id="registRoomTypeId1" name="registRoomTypeId1" class="selectfield" style="width:250px;">
									<option value="">Select</option>
									
								</select></nobr>
                                  </td> 
                              </tr>
                              <tr id="applyPref3"> 
                               	<td  class="contenttab_internal_rows" width="8%"  nowrap >
                                     <nobr><b>Preference 3</b></nobr>
                                  </td> 
                                  <td  class="contenttab_internal_rows" nowrap><nobr><b>:</b></nobr></td>                                
                                  <td  class="contenttab_internal_rows" width="40%" nowrap><nobr>&nbsp;
                                  <select id="registRoomTypeId2" name="registRoomTypeId2" class="selectfield" style="width:250px;">
									<option value="">Select</option>
									
								</select></nobr>
                                  </td> 
                              </tr>
                              <tr >
								  <td class="contenttab_internal_rows" height="5px;" colspan="3"></td>	
								</tr> 
                              <tr >                              	
                               <td id="trhideApply"  nowrap="nowrap"  class="content_title" style="height:25px;padding-left:190px;" colspan="3">                                  
							   <input type="image"  src="<?php echo IMG_HTTP_PATH;?>/apply.png" onClick="return showInstructionsDetails();return false;" />    
							   </td>
							   <td  id="tdCancel" nowrap="nowrap"  class="content_title"  colspan="3" style="height:25px;padding-left:190px;display:none;">
                              
							   <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="return registerHostelDetails('1');return false;" />
							</td>
							 </tr>
                               <tr>
								  <td class="contenttab_internal_rows" height="5px;" colspan="3"></td>	
								</tr>                              
                            </table>  
                            <table width="100%" border="0" cellspacing="2px" cellpadding="2px" class="border" id="hostelInstructions" style="display:none;">        
                            <tr>
					           <td class="padding_top" width="100%" nowrap="nowrap" style="padding-left:10px;" >
					             <nobr><b><font color="red"><u>INSTRUCTIONS FOR APPLY/RENEW HOSTEL FACILITY:</u>&nbsp;:</font></b></nobr>                
					           </td>
					        </tr>
          					<tr>
          					  <td class="online_instrunctions" width="100%" style="padding-left:10px;">
							  <ul style=" font-size:13px;">						           
						            <li><b>&bull;</b>&nbsp;Student who are willing to avail hostel facility ,must have to apply online.</li>
						            <li><b>&bull;</b>&nbsp;Student have to deposit Hostel Fee within 7 days after the approval by warden.</li>
						            <li><b>&bull;</b>&nbsp;If any student fail to deposit fee within the given period then his/her alloted seat will be cancelled automatically.</li>
						             <li><b>&bull;</b>&nbsp;Cancelled seat will never be alloted again under any circumstances.</li>
						        
						        </ul>  
							   </td>   
          					</tr>                                  	    
							<td class="padding_top" align="left"  nowrap="nowrap"  style="height:25px;padding-left:20px;">
								<nobr><b>I agree &nbsp;<input type="checkBox" id="acceptHostel" name="acceptHostel"></b></nobr> 
							</td>
							</tr>
          					<tr>
                             <td nowrap="nowrap"  class="content_title" align="center" style="height:25px;padding-right:90px;">
                                    
							   <input type="image"  src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="return registerHostelDetails('0');return false;" />    
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