<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<form name="frmGraceMarks" id="frmGraceMarks" method="post" >
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
            </tr>
            </table>
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
                        <td class="content_title">Grace Marks : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td class="contenttab_border1" align="center" style="padding-left:10px;" >
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                   <tr>
                     <td colspan="9" height="5px"></td>
                   </tr>
                    <tr>    
                       <td class="contenttab_internal_rows" align="left" colspan="20"><nobr><b>Note: Only those classes, subjects, groups will be shown which are mapped to active time table and for which marks has been transferred. </b></nobr></td>
                    </tr>
                    <tr>
                     <td colspan="9" height="5px"></td>
                   </tr>
                    <tr>
						<td class="contenttab_internal_rows" align="left"><nobr><b>Time Table</b></nobr></td>
						<td class="contenttab_internal_rows" width="1%"><b>:</b></td>
					    <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="labelId" id="labelId">
						<?php
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							echo HtmlFunctions::getInstance()->getTimeTableLabelData();
						?>
						<td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="contenttab_internal_rows" align="left" colspan="10">
                         <table width="5%" border="0" cellspacing="0" cellpadding="0" >   
                          <tr>
                             <td class="contenttab_internal_rows" nowrap>
                                <select size="1" class="selectfield" name="class1" id="class1" style="width:250px" onFocus= "getClasses();" onchange="clearData();subjectPopulate(this.value);" >
                                <option value="">Select Class</option>
                                <!--<?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  require_once(MODEL_PATH.'/Teacher/TeacherManager.inc.php');
                                  $activeTimeTable=TeacherManager::getInstance()->getActiveTimeTable();
                                  if($activeTimeTable[0]['timeTableLabelId']!=''){
                                   echo HtmlFunctions::getInstance()->getTransferredClasses($activeTimeTable[0]['timeTableLabelId']);
                                  }
                                ?>-->
                              </select></td>
                                <td class="contenttab_internal_rows" style="padding-left:30px" align="left"><nobr><b>Subject</b></nobr></td>
                                <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                                <td  class="contenttab_internal_rows"  align="left" >
                                    <select size="1" class="selectfield" name="subject" id="subject" onchange="groupPopulate(this.value);" >
                                        <option value="">Select Subject</option>
                                          <?php
                                          //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          //echo HtmlFunctions::getInstance()->getAllTeacherSubjectData();
                                        ?>
                                    </select>
                                </td>    
                            </tr>
                         </table>
                       </td>             
                    </tr>
                    <tr> 
					    <td  class="contenttab_internal_rows"><nobr><b>Group</b></nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding" align="left">
                        <select size="1" class="selectfield" name="group" id="group" onchange="clearData();" >
                        <option value="">Select Group</option>
                        </select>
                        </td>
                        <td  class="contenttab_internal_rows"><nobr><b>Roll No.</b>&nbsp;(Optional)</nobr></td>
                        <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                        <td  class="padding"  align="left" colspan="10">
                        <input type="text" id="studentRollNo" name="studentRollNo" class="inputbox" style="width:246px" autocomplete='off' onchange="clearData();">
                        </td>
                        <!--<td colspan="2"></td>-->
                    </tr>
                    <tr>
                      <td  class="contenttab_internal_rows"><nobr><b>Grace Marks</b></nobr></td>
                      <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                      <td  class="contenttab_internal_rows"  align="left" nowrap>
                        <table width="5%" border="0" cellspacing="0" cellpadding="0" >   
                          <tr>
                             <td class="contenttab_internal_rows" nowrap>
                               <input type="text" id="graceMarksAll" name="graceMarksAll" class="inputbox" style="width:40px" onkeyup="setData(this.value);">
                               <span class="contenttab_internal_rows"><b>( for all )</b></span>
                             </td>  
                             <td class="contenttab_internal_rows" style='display:none'  nowrap>
                                 <input name="graceMarksFormat" id="graceMarksAbsolute" value="1" checked="checked" type="radio"> Absolute<br>
                                 <input name="graceMarksFormat" id="graceMarksPercentage" value="2" type="radio">%age
                             </td>
                          </tr>
                        </table>  
                      </td>
                      <td class="contenttab_internal_rows" nowrap>
                        <b>Grace Marks For</b>
                      <td class="contenttab_internal_rows" nowrap><b>:&nbsp;</b></td>
                      <td  class="contenttab_internal_rows"  align="left" colspan="10">
                          <table width="5%" align="left" border="0" cellspacing="0" cellpadding="0" >   
                              <tr>
                                 <td class="contenttab_internal_rows" nowrap>       
                                    <input name="graceMarksFor" id="internalMarks" value="1" type="radio"> Internal Marks
                                 </td>
                                 <td class="contenttab_internal_rows" nowrap>       
                                    <input name="graceMarksFor" id="externalMarks" value="2" type="radio"> External Marks
                                 </td>
                                 <td class="contenttab_internal_rows" nowrap>       
                                    <input name="graceMarksFor" id="totalMarks" value="3" checked="true" type="radio"> Total Marks
                                 </td>
                                 <td style='display:none'  class="contenttab_internal_rows" style="padding-left:20px" colspan="1"><nobr><b>Class Average (with Grace)</b><br><b>Class Average (without Grace)</b></nobr></td>
                                 <td style='display:none' class="contenttab_internal_rows" width="1%"><b>: <br>:</b></td>
                                 <td style='display:none'  class="contenttab_internal_rows" nowrap>
                                   <b><span id="classAverageSpan">0.00</span><br><span id="classAverageSpan3">0.00</span></b>
                                 </td>
                                 <td align="left"  style="padding-left:20px" >
                                    <input type="image" name="imageField" onClick="getData();return false" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                                 </td>
                              </tr>
                          </table> 
                       </td>
                    </tr>    
                    </table>
                </td>
             </tr>
             </form>  
             <tr>
                <td class="contenttab_row" valign="top" style="padding-right:0px" style="width:100%" >&nbsp;
                <div id="headingDivId" class="contenttab_border content_title" style="text-align:left;display:none;">
                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                 <tr>
                  <td align="left">Student List</td>
                  <td align="right">
                   <input type="image" name="imageField2" id="imageField2" onClick="saveData();return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" />
                   <input type="image" name="imageField3" id="imageField3" onClick="clearData();return false" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" />&nbsp;
                  </td>
                 </tr>
                 </table> 
                  </div>
                   <form name="listFrm" id="listFrm">  
                 <div id="scroll2" style="overflow:auto; height:410px; width:1000px; vertical-align:top;">
                   <div id="results" style="width:98%; vertical-align:top;"></div>
                 </div>
                  <input type="hidden"  name="student" id="student" value="1">
                  <input type="hidden"  name="student" id="student" value="1">
                  </frm>
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr style="display:none" id="buttonRow" valign="top">
            <td align="center" class="contenttab_internal_rows" width="100%">
					<table border='0' cellspacing='0' cellpadding='0' width="100%">
						<tr>
							<td valign='top' colspan='1' class='' align="right" width="50%">
							  <input type="image" name="imageField2" id="imageField2" onClick="saveData();return false" src="<?php echo IMG_HTTP_PATH;?>/save.gif" />
							  <input type="image" name="imageField3" id="imageField3" onClick="clearData();return false" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" />
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
