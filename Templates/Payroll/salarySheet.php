<?php 
//it contain the template of employee salary report 
//
// Author :Abhiraj Malhotra
// Created on : 01-05-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<style type="text/css">
<!--
.style1 {color: #0033CC}
.style2 {color: #FFFFFF}
-->
</style>

<form name="heads" onsubmit="showSalarySheet();return false;" id="heads">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top">Payroll &nbsp;&raquo;&nbsp; Salary Sheet </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
                 <td align="center" valign="top" class="content">
                     <!-- form table starts -->
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                        <tr>
                            <td valign="top" class="contenttab_row1" align="left">
							<?php global $sessionHandler;
							global $FE;
							require_once($FE . "/Library/common.inc.php");
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							require_once(BL_PATH.'/UtilityManager.inc.php');
							require_once(MODEL_PATH.'/PayrollManager.inc.php'); 
							logError("xxxxxxxxxx".$sessionHandler->getSessionVariable('UserId'));
							
							?>
                                					<?php
									$monthArray=PayrollManager::getInstance()->getMonth();
									$yearArray=PayrollManager::getInstance()->getYear();	
									if(count($monthArray)>0 && count($yearArray)>0)
									{
									?>
                                  <table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td style="padding-left:10px"><strong>Month:</strong></td>
									  <td style="padding-left:0px"><label>
									  <select name="month" id="month">
									  <?php 
                  					  echo HtmlFunctions::getInstance()->makeSelectBox($monthArray,'month','month');
									  ?>
								      </select>
									  </label></td>
									  <td style="padding-left:10px"><strong>Year:</strong></td>
									  <td style="padding-left:0px">
									  <select name="year" id="year">
									  <?php
									  echo HtmlFunctions::getInstance()->makeSelectBox($yearArray,'year','year');
								      ?>
									  </select>									  </td>
                                         
                                    </tr>
                                  </table>
								  <?php
								  }
								  else
								  {
								  echo "<div align=center style=padding:7px><b>No Salary Records Present</b></div><br>";
								  }
								  ?>
								  
                             
                          </td>
                       </tr>
                   </table>
				   <?php
				   	$headsArray=PayrollManager::getInstance()->getHeadList();
					$cnt=count($headsArray);
				   if(count($monthArray)>0 && count($yearArray)>0 && $cnt>0)
				   {
				   ?>
					    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:10px;">
                          <tr>
                            <td class="contenttab_row1">
							<fieldset class="fieldset">
							<legend>
							Select Heads To Display In Report</legend>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							<?php
							$headsArray=PayrollManager::getInstance()->getHeadList();
							$cnt=count($headsArray);
							for($i=0;$i<$cnt;$i++)
							{
								if($i%5==0 && $i!=0)
								{
									echo"</tr><tr>";
								}	
							?>
								<td width="20"><input name="headsArray[]" type="checkbox" id="<?php echo $headsArray[$i]['headId'];?>" value="<?php echo $headsArray[$i]['headName'];?>" /></td>
								<td><?php echo $headsArray[$i]['headName'];?></td>
								<td width="7"></td>
							<?php
								
							}
							?>
							</tr>
</table>

							</fieldset>
							</td>
                          </tr>
                        </table>
					    <br />
						<?php
						}
						else
						{
						echo "<div align=center><b>No Heads Created</b></div>";
						}
						?>
                                          
						<input type="image" name="generateSalarySheet" value="generateSalarySheet" src="<?php echo IMG_HTTP_PATH;?>/generate.gif" 
						 />
						<table width="100%" border="0" cellspacing="2" cellpadding="3" style="margin-top:10px">

							<tr id='nameRow1' style="display:none" >
							<td height="20" valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
								<tr>
									<td class="contenttab_row1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="20" align="left"><img src="<?php echo IMG_HTTP_PATH;?>/red_ball.gif" width="16" height="16" /></td>
                                        <td width="62" align="left" >Deduction</td>
                                        <td width="5" align="left" >&nbsp;</td>
                                        <td width="20" align="left" ><img src="<?php echo IMG_HTTP_PATH;?>/black_ball.gif"  width="16" height="16"  /></td>
                                        <td width="60" align="left" >Earning</td>
                                        <td width="697" align="left" style="text-decoration:line-through" >On Hold </td>
                                        <td width="5" >&nbsp;</td>
                                        <td width="285" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printSalarySheet(document.getElementById('month').value,document.getElementById('year').value);" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="downloadSalarySheet(document.getElementById('month').value,document.getElementById('year').value);return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
                                      </tr>
                                     
                                    </table>								    </td>
								</tr>
							  </table>							</td>
						 </tr>
							<tr id='nameRow2' style="display:none">
                              <td valign="top" class="contenttab_border2" style="padding-top:0px">
							  <div id="results" style="height:500px; width:990px; overflow:auto">							  </div>							  </td>
                            </tr>
							<tr id='nameRow3' style="display:none">
							  <td align="right" valign="top" style="padding-top:0px"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printSalarySheet(document.getElementById('month').value,document.getElementById('year').value)" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="downloadSalarySheet(document.getElementById('month').value,document.getElementById('year').value);return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
						  </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
 </form>