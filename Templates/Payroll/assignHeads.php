<?php 
//it contain the template of Payroll assign heads 
//
// Author :Abhiraj Malhotra
// Created on : 19-04-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top">Setup &raquo; Payroll &raquo; Assign Heads  </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
                 <td valign="top" class="content">
                     <!-- form table starts -->
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                        <tr>
                            <td valign="top" class="contenttab_row1">
                                <form name="headsMapping" action="" method="post" onSubmit="return false;">
                                  <table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td class="contenttab_internal_rows"><nobr><b>Employee Name: </b></nobr></td>
                                      <td>
                                      <input type="text" style="width:182px" name="searchfield" id="searchfield" class="inputBox" />
                                      </td>
                                         <td align="center" colspan="4" >
                                           <span style="padding-right:10px" >
<input type="image" name="headsMappingSubmit" value="headsMappingSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return showData(this.form);return false;" />
                                      </td>
                                    </tr>
                                  </table>
                              </form>
                          </td>
                       </tr>
                   </table>
					    <br />
						
                        <table width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tr id='nameRow' style='display:none;'>
                              <td width="30%" valign="top" class="contenttab_border2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
								<td valign="top" class="contenttab_row1">
								<fieldset class="fieldset">
								<legend>Employee Details</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" >
								<!--tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Serial Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="mySerial">--</span><input type="hidden" id="serialNumber" name="serialNumber" class="inputbox" READONLY/>
									</td>
								</tr-->
								
								<tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Employee Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="empName">--</span></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><strong>Employee Code </strong></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
								  <td><span id="empCode">--</span></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Designation</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="empDesignation">--</span></td>
								</tr>
								<tr>
								  <td class="contenttab_internal_rows"><strong>Department</strong></td>
								  <td class="contenttab_internal_rows"><B>:</B></td>
								  <td><span id="empDepartment">--</span></td>
								  </tr>
								<tr>
								  <td class="contenttab_internal_rows"><strong>PF Account No. </strong></td>
								  <td class="contenttab_internal_rows"><strong>:</strong></td>
								  <td><span id="empPF">--</span></td>
								  </tr>
								<tr>
								  <td class="contenttab_internal_rows"><strong>PAN</strong></td>
								  <td class="contenttab_internal_rows"><strong>:</strong></td>
								  <td><span id="empPAN">--</span></td>
								  </tr>
								<tr>
								  <td class="contenttab_internal_rows"><strong>ESI No. </strong></td>
								  <td class="contenttab_internal_rows"><strong>:</strong></td>
								  <td><span id="empESI">--</span></td>
								  </tr>
								<!--tr>	
									<td class="contenttab_internal_rows"><nobr><B>Curr Study Period</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myStudyPeriod">--</span> 
									<input type="hidden" name="currStudyPeriod">
									</select>
									</td>
								</tr-->
								</table>
								</fieldset>
								</td>
							</tr>
						</table>
					</td>
                              <td width="25%" valign="top" class="contenttab_border2">
							  <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
								<td valign="top" class="contenttab_row1">
								<fieldset class="fieldset">
								<legend>Select Heads </legend>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" >
								<!--tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Serial Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="mySerial">--</span><input type="hidden" id="serialNumber" name="serialNumber" class="inputbox" READONLY/>
									</td>
								</tr-->
								
								<tr>	
									<td colspan="3" align="left" class="contenttab_internal_rows"><form id="form1" name="headsList" method="post" action="">
									  <label>
									  <select id="headMenu" name="headMenu" multiple="multiple" size="10" style="width:242px; padding:0px; margin:0px" onChange="refreshHeadList(this)">
									  <!--<option value="-1" selected="selected">-------Select--------</option>-->
									  <?php
                  							//This is to populate the list with all available heads
                                            global $FE;
											require_once($FE . "/Library/common.inc.php");
											require_once(BL_PATH.'/HtmlFunctions.inc.php');
										    require_once(MODEL_PATH.'/PayrollManager.inc.php');
											global $headArray;
											$headArray=PayrollManager::getInstance()->getHeadList('','','headName');
											global $sessionHandler;
											$sessionHandler->setSessionVariable('headArray',$headArray);
                  							echo HtmlFunctions::getInstance()->makeSelectBox($headArray,'headId','headName'); 
              						  ?>
									  </select>
								      </label>
								  </form></td>
								  </tr>
								<tr>
								  <td colspan="3" align="left" class="contenttab_internal_rows" style="padding-top:0px"><strong>Select : <a href="#" onclick="selectAllHeads()">All</a> | <a href="#" onclick="clearAllHeads()">None</a> </strong></td>
								  </tr>
								
								<!--tr>	
									<td class="contenttab_internal_rows"><nobr><B>Curr Study Period</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myStudyPeriod">--</span> 
									<input type="hidden" name="currStudyPeriod">
									</select>
									</td>
								</tr-->
								</table>
								</fieldset>
								</td>
							</tr>
						</table>
							  </td>
                              <td width="45%" valign="top" class="contenttab_border2">
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td colspan="3">
								<div id="scroll" class="scroll">
								<div id="results" class="contenttab_row1">								</div>
								</div>								</td>
							  </tr>
							  <tr>
							  	<td width="24%" align="right" style="padding-top:15px; font-family:Arial, Helvetica, sans-serif; font-size:12px; ">
									<span style="position:relative; bottom:5px; width:100%">With effect from:&nbsp;</span></td>
								<td width="34%" style="padding-top:15px; padding-left:2px; border:0px">
								<!--<input name="" type="text" style="width:80px" readonly="true" id="chkIn1"/>-->
                                 <select  style="width:90px" id="chkIn1" onChange=checkAlreadyAssigned();>
                                 <option value="<?php echo date('Y-m')."-01";?>" selected><?php echo date('Y-m')."-01";?></option>
                                 <?php
                                 $newdate=date('Y-m-d',strtotime('-1 Month' , strtotime (date('Y-m')."-01")));
                                 ?>

                                 <option value="<?php echo $newdate;?>"><?php echo $newdate;?></option>
                                 </select>
           						<?php
										  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  //echo HtmlFunctions::getInstance()->datePicker('chkIn1',date('Y-m-d'));
										  //echo "01-".date('M-Y');
								   ?><span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; font-weight:normal"><br />
           						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;yyyy-mm-dd</span></td>
							    <td width="42%" align="right" class="contenttab_row1" style="padding-top:15px; padding-right:10px">Total	Amount	:	<span id="totalAmount">--</span>&nbsp;&nbsp; Rs</td>
							  </tr>
							  <tr>
							    <td colspan="3" align="center" style="padding-top:18px;">
								<input type="image" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="saveHeadMapping();">
								&nbsp; 
								<input type="image" src="<?php echo IMG_HTTP_PATH;?>/reset.gif" onclick="checkAlreadyAssigned();"></td>
							    </tr>
							</table>

							  
							  </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
